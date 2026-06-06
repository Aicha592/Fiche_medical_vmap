<?php

namespace App\Http\Controllers\Backoffice;

use App\Exports\UsersTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !$user->isAdmin()) {
                abort(403, 'Accès réservé à l’administrateur');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->string('q')->trim()->value();

        $query = User::query()->orderBy('name');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('backoffice.users.index', [
            'users' => $users,
            'search' => $search,
            'roles' => $this->roles(),
        ]);
    }

    public function create()
    {
        return view('backoffice.users.create', [
            'roles' => $this->roles(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:190|unique:users,email',
            'telephone' => 'nullable|string|max:20|unique:users,telephone',
            'role' => 'required|in:admin,med-taf,rh,ch,doctor',
            'password' => 'nullable|string|min:6',
        ]);

        $payload = [
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'telephone' => $data['telephone'] ?? null,
            'role' => $data['role'],
            'is_doctor' => $data['role'] === 'doctor',
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        User::create($payload);

        return redirect()
            ->route('backoffice.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        return view('backoffice.users.edit', [
            'user' => $user,
            'roles' => $this->roles(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:190|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20|unique:users,telephone,' . $user->id,
            'role' => 'required|in:admin,med-taf,rh,ch,doctor',
            'password' => 'nullable|string|min:6',
        ]);

        $payload = [
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'telephone' => $data['telephone'] ?? null,
            'role' => $data['role'],
            'is_doctor' => $data['role'] === 'doctor',
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return redirect()
            ->route('backoffice.users.index')
            ->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return redirect()
                ->route('backoffice.users.index')
                ->with('error', 'Impossible de supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()
            ->route('backoffice.users.index')
            ->with('success', 'Utilisateur supprimé.');
    }

    private function roles(): array
    {
        return [
            'admin' => 'Administrateur',
            'med-taf' => 'Médecin Entreprise',
            'rh' => 'ATRH',
            'ch' => 'Capital Humain',
            'doctor' => 'Docteur',
        ];
    }

    public function downloadTemplate()
    {
        $fileName = 'modele_import_utilisateurs_' . now()->format('Y-m-d_His') . '.xlsx';
        return Excel::download(new UsersTemplateExport(), $fileName);
    }

    public function showImportForm()
    {
        return view('backoffice.users.import', [
            'roles' => $this->roles(),
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Le fichier est requis.',
            'file.mimes' => 'Le fichier doit être un fichier Excel ou CSV.',
            'file.max' => 'Le fichier ne doit pas dépasser 5 MB.',
        ]);

        try {
            $import = new UsersImport();
            Excel::import($import, $request->file('file'));

            $message = "Import terminé. ";
            $message .= "Créés: {$import->created}, ";
            $message .= "Mis à jour: {$import->updated}, ";
            $message .= "Ignorés: {$import->skipped}";

            $warning = null;
            if (!empty($import->errors)) {
                $warning = implode("\n", array_slice($import->errors, 0, 10));
                if (count($import->errors) > 10) {
                    $warning .= "\n... et " . (count($import->errors) - 10) . " autre(s) erreur(s)";
                }
            }

            return redirect()
                ->route('backoffice.users.index')
                ->with('success', $message)
                ->with('warning', $warning);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de l\'import: ' . $e->getMessage())
                ->withInput();
        }
    }
}
