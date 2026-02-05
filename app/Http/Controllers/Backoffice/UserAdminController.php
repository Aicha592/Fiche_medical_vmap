<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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

        $query = User::query()->orderBy('nom');

        if ($search !== '') {
            $query->where('nom', 'like', "%{$search}%")
                ->orWhere('prenom', 'like', "%{$search}%")
                ->orWhere('matricule', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->paginate(15)->withQueryString();

        return view('backoffice.users.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }
}
