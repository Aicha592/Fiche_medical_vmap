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

        $query = User::query()
            ->select('users.*')
            ->orderBy('email');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('users.email', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('backoffice.users.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }
}
