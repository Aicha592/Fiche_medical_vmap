<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'Jean Dupont',
                'jean.dupont@example.com',
                '+33612345678',
                'admin',
                'Password123!',
            ],
            [
                'Marie Martin',
                'marie.martin@example.com',
                '+33687654321',
                'med-taf',
                'Password123!',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Email',
            'Téléphone',
            'Rôle',
            'Mot de passe',
        ];
    }
}
