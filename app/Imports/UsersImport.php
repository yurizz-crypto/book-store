<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new User([
            'first_name' => $row['first_name'],
            'last_name'  => $row['last_name'],
            'email'      => $row['email'],
            // Auto-generate a secure 16-character password if none is provided
            'password'   => Hash::make($row['password'] ?? Str::random(16)), 
            // Default to 'user' role, but allow corporate bulk overrides to 'admin'
            'role'       => $row['role'] ?? 'user', 
            // Assume corporate imports are pre-verified to save time
            'email_verified_at' => now(), 
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'role'       => 'nullable|in:admin,user',
        ];
    }
}