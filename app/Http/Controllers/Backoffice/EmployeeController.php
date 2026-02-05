<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Imports\EmployeesImport;
use App\Models\Employee;
use App\Models\MedicalVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user || !$user->isAdmin()) {
                abort(403, 'Accès réservé à l’administrateur');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->string('q')->trim()->value();

        $medicalVisitsCount = MedicalVisit::selectRaw('count(*)')
            ->whereColumn('medical_visits.employee_id', 'employees.id');

        $latestMedicalVisitId = MedicalVisit::select('medical_visits.id')
            ->whereColumn('medical_visits.employee_id', 'employees.id')
            ->latest('medical_visits.created_at')
            ->limit(1);

        $qhseVisitsCount = MedicalVisit::selectRaw('count(*)')
            ->whereColumn('medical_visits.employee_id', 'employees.id')
            ->where(function ($builder) {
                $builder->whereNotNull('synthese_risque')
                    ->orWhereNotNull('observations_qhse')
                    ->orWhereNotNull('synthese_actions');
            });

        $query = Employee::query()
            ->select('employees.*')
            ->selectSub($medicalVisitsCount, 'medical_visits_count')
            ->selectSub($latestMedicalVisitId, 'latest_medical_visit_id')
            ->selectSub($qhseVisitsCount, 'qhse_visits_count')
            ->orderBy('nom');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%")
                    ->orWhere('direction', 'like', "%{$search}%")
                    ->orWhere('service', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(15)->withQueryString();

        return view('backoffice.employees.index', [
            'employees' => $employees,
            'search' => $search,
        ]);
    }

    public function importForm()
    {
        return view('backoffice.employees.import');
    }

    public function importTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="employees_template.csv"',
        ];

        $columns = [
            'matricule',
            'sexe',
            'prenom',
            'nom',
            'date_naissance',
            'date_embauche',
            'emploi_occupe',
            'direction',
            'delegation_r',
            'service',
            'unite_communale',
            'telephone',
            'date_passage',
        ];

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $columns, ';');
        rewind($handle);

        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, $headers);
    }

    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt'],
        ]);

        $import = new EmployeesImport();
        Excel::import($import, $request->file('file'));

        return redirect()
            ->route('backoffice.employees.index')
            ->with('success', "Import terminé. Créés: {$import->created}, mis à jour: {$import->updated}, ignorés: {$import->skipped}.")
            ->with('import_errors', $import->errors);
    }

    public function create()
    {
        return view('backoffice.employees.create', [
            'employee' => new Employee(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateEmployee($request);

        Employee::create($data);

        return redirect()
            ->route('backoffice.employees.index')
            ->with('success', 'Employé créé avec succès.');
    }

    public function edit(Employee $employee)
    {
        return view('backoffice.employees.edit', [
            'employee' => $employee,
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $this->validateEmployee($request, $employee->id);

        $employee->update($data);

        return redirect()
            ->route('backoffice.employees.index')
            ->with('success', 'Employé mis à jour avec succès.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()
            ->route('backoffice.employees.index')
            ->with('success', 'Employé supprimé avec succès.');
    }

    private function validateEmployee(Request $request, ?int $employeeId = null): array
    {
        $uniqueMatricule = 'unique:employees,matricule';
        if ($employeeId) {
            $uniqueMatricule .= ',' . $employeeId;
        }

        return $request->validate([
            'matricule' => ['required', 'string', 'max:255', $uniqueMatricule],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'sexe' => ['nullable', 'in:M,F'],
            'age' => ['nullable', 'integer', 'min:0'],
            'date_naissance' => ['nullable', 'date'],
            'date_embauche' => ['nullable', 'date'],
            'emploi_occupe' => ['nullable', 'string', 'max:255'],
            'direction' => ['nullable', 'string', 'max:255'],
            'delegation_r' => ['nullable', 'string', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
            'unite_communale' => ['nullable', 'string', 'max:255'],
            'anciennete' => ['nullable', 'string', 'max:255'],
            'site' => ['nullable', 'in:R,D,C'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'date_passage' => ['nullable', 'date'],
        ]);
    }
}
