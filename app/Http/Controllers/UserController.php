<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');

        $employees = Employee::query()
            ->select([
                'employees.id',
                'employees.nom',
                'employees.prenom',
                'employees.matricule',
                'employees.sexe',
                'employees.date_naissance',
                'employees.date_embauche',
                'employees.direction',
                'employees.delegation_r',
                'employees.service',
                'employees.unite_communale',
                'employees.emploi_occupe',
                'employees.telephone',
                'employees.date_passage',
            ])
            ->where(function ($builder) use ($query) {
                $builder->where('employees.nom', 'like', "%{$query}%")
                    ->orWhere('employees.prenom', 'like', "%{$query}%")
                    ->orWhere('employees.matricule', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return $employees->map(function ($employee) {
            $fullName = trim(($employee->nom ?? '') . ' ' . ($employee->prenom ?? ''));
            return [
                'id' => $employee->id,
                'employee_id' => $employee->id,
                'nom' => $employee->nom,
                'prenom' => $employee->prenom,
                'name' => $fullName === '' ? '—' : $fullName,
                'matricule' => $employee->matricule,
                'sexe' => $employee->sexe,
                'age' => $employee->age,
                'date_naissance' => optional($employee->date_naissance)->format('Y-m-d'),
                'date_embauche' => optional($employee->date_embauche)->format('Y-m-d'),
                'direction' => $employee->direction,
                'delegation_r' => $employee->delegation_r,
                'service' => $employee->service,
                'unite_communale' => $employee->unite_communale,
                'poste' => $employee->emploi_occupe,
                'anciennete' => $employee->anciennete,
                'telephone' => $employee->telephone,
                'date_passage' => optional($employee->date_passage)->format('Y-m-d'),
            ];
        });
    }
}
