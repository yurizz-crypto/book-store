<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FastUsersImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 900;
    
    protected $filePath;
    public $defaultRole;

    /**
     * Create a new job instance.
     * Must accept BOTH the file path and the role from the controller.
     */
    public function __construct($filePath, $defaultRole = 'customer')
    {
        $this->filePath = $filePath;
        $this->defaultRole = $defaultRole;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Resolve the absolute path to the stored file
        $fullPath = Storage::disk('local')->path($this->filePath);

        if (!file_exists($fullPath)) {
            return; // Exit if the file vanished before the queue picked it up
        }

        $handle = fopen($fullPath, 'r');
        
        // Read and discard the first row (the Headers)
        fgetcsv($handle);

        $chunkSize = 500;
        $records = [];
        $now = now()->format('Y-m-d H:i:s');
        
        // Hash the password once outside the loop for massive performance gains
        $defaultPassword = Hash::make('password'); 

        while (($row = fgetcsv($handle)) !== false) {
            // Safety check: skip blank rows or rows with missing data
            if (count($row) < 4) {
                continue; 
            }

            $records[] = [
                // $row[0] is skipped because it is the ID column in your CSV!
                'first_name' => $row[1],
                'last_name'  => $row[2],
                'email'      => $row[3],
                'role'       => $this->defaultRole ?? 'customer',
                'password'   => $defaultPassword,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Insert into DB in chunks to prevent server memory crashes
            if (count($records) >= $chunkSize) {
                DB::table('users')->insertOrIgnore($records);
                $records = []; // Clear the array for the next batch
            }
        }

        // Insert any remaining records that didn't perfectly hit the chunk limit
        if (!empty($records)) {
            DB::table('users')->insertOrIgnore($records);
        }

        fclose($handle);

        // Clean up the temporary CSV file from storage so your disk doesn't fill up
        Storage::disk('local')->delete($this->filePath);
    }
}