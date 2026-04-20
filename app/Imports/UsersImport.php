<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{

    protected $defaultRole;
    
    public function __construct($defaultRole = 'customer')
    {
        $this->defaultRole = $defaultRole;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
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
            'password'   => \Illuminate\Support\Facades\Hash::make('password'), 
            'role'       => $csvRole, 
        ]);
    }

    /**
     * Validation rules for the CSV data.
     * * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'role_id'    => 'nullable|in:admin,user,ADMIN,USER,Admin,User,customer,CUSTOMER', 
        ];
    }
}