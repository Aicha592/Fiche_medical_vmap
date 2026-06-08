<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $created = 0;
    public int $updated = 0;
    public int $skipped = 0;
    public array $errors = [];
    private array $validRoles = ['admin', 'med-taf', 'rh', 'ch', 'doctor', 'bio'];

    public function collection(Collection $rows): void
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;
                $normalized = $this->normalizeRow($row->toArray());

                $email = $normalized['email'] ?? null;
                $name = $normalized['nom'] ?? null;
                $role = $normalized['role'] ?? null;

                if (!$email || !$name || !$role) {
                    $this->skipped++;
                    $this->errors[] = "Ligne {$rowNumber}: email/nom/rôle manquant.";
                    continue;
                }

                if (!in_array($role, $this->validRoles)) {
                    $this->skipped++;
                    $this->errors[] = "Ligne {$rowNumber}: rôle invalide '{$role}'. Rôles valides: " . implode(', ', $this->validRoles);
                    continue;
                }

                try {
                    $user = User::where('email', $email)->first();

                    $payload = [
                        'name' => $name,
                        'email' => $email,
                        'telephone' => $normalized['telephone'] ?? null,
                        'role' => $role,
                        'is_doctor' => $role === 'doctor',
                    ];

                    if ($user) {
                        $user->update($payload);
                        $this->updated++;
                    } else {
                        // Générer un mot de passe par défaut
                        $password = $normalized['password'] ?? 'Password123!';
                        $payload['password'] = Hash::make($password);
                        User::create($payload);
                        $this->created++;
                    }
                } catch (\Exception $e) {
                    $this->skipped++;
                    $this->errors[] = "Ligne {$rowNumber}: Erreur lors de l'import - {$e->getMessage()}";
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function normalizeRow(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $cleanKey = str_replace([' ', '-', '_'], '', strtolower(trim((string) $key)));
            $normalized[$cleanKey] = is_string($value) ? trim($value) : $value;
        }
        return $normalized;
    }
}
