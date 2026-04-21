<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, ShouldQueue, WithBatchInserts, WithChunkReading
{
    protected $defaultRole;
    
    public function __construct($defaultRole = 'customer')
    {
        $this->defaultRole = $defaultRole;
    }

    public function model(array $row)
    {
        $csvRole = strtolower($row['role_id'] ?? $row['role'] ?? $this->defaultRole);

        if ($csvRole === 'user') {
            $csvRole = 'customer';
        }

        return new User([
            'first_name' => $row['first_name'],
            'last_name'  => $row['last_name'],
            'email'      => $row['email'],
            'password'   => Hash::make('password'), 
            'role'       => $csvRole, 
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'role_id'    => 'nullable|in:admin,user,ADMIN,USER,Admin,User,customer,CUSTOMER', 
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}