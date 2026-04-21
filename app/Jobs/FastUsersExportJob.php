<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FastUsersExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $redactPII;
    protected $filename;
    protected $user;

    public function __construct($redactPII, $filename, $user)
    {
        $this->redactPII = $redactPII;
        $this->filename = $filename;
        $this->user = $user;
    }

    public function handle()
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'users_');
        $handle = fopen($tempPath, 'w');
        fputcsv($handle, ['User ID', 'First Name', 'Last Name', 'Email', 'Role', 'Account Created']);

        $query = DB::table('users')->select(['id', 'first_name', 'last_name', 'email', 'role', 'created_at']);

        foreach ($query->orderBy('id')->cursor() as $u) {
            $email = $u->email;
            $lastName = $u->last_name;

            if ($this->redactPII) {
                $parts = explode('@', $email);
                $email = (count($parts) == 2) ? substr($parts[0], 0, 1) . '***@' . $parts[1] : '***';
                $lastName = substr($lastName, 0, 1) . '***';
            }

            fputcsv($handle, [
                $u->id,
                $u->first_name,
                $lastName,
                $email,
                strtoupper($u->role),
                date('Y-m-d', strtotime($u->created_at))
            ]);
        }

        fclose($handle);
        Storage::disk('public')->put($this->filename, fopen($tempPath, 'r+'));
        unlink($tempPath);

        dispatch(new \App\Jobs\NotifyExportCompleted($this->user, $this->filename));
    }
}