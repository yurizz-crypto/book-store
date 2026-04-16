<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $redactPII;

    public function __construct($redactPII = true)
    {
        $this->redactPII = $redactPII;
    }

    public function query()
    {
        return User::query();
    }

    public function headings(): array
    {
        return ['User ID', 'First Name', 'Last Name', 'Email', 'Role', 'Account Created'];
    }

    public function map($user): array
    {
        $email = $user->email;
        $lastName = $user->last_name;

        if ($this->redactPII) {
            $parts = explode('@', $email);
            if (count($parts) == 2) {
                $email = substr($parts[0], 0, 1) . '***@' . $parts[1];
            }
            $lastName = substr($lastName, 0, 1) . '***';
        }

        return [
            $user->id,
            $user->first_name,
            $lastName,
            $email,
            strtoupper($user->role),
            $user->created_at->format('Y-m-d'),
        ];
    }
}