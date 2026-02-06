@extends('layouts.backoffice')

@section('content')
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1">Utilisateurs</h4>
            <div class="bo-muted">Gestion des comptes et des rôles.</div>
        </div>
        <div class="gap-2 d-flex">
            <a class="btn btn-bo" href="{{ route('backoffice.users.create') }}">Nouvel utilisateur</a>
            <form class="gap-2 d-flex" method="GET" action="{{ route('backoffice.users.index') }}">
                <input class="form-control" type="search" name="q" value="{{ $search }}"
                    placeholder="Nom, matricule, email">
                <button class="btn btn-outline-dark" type="submit">Rechercher</button>
            </form>
        </div>
    </div>

    <div class="bo-card">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($users->isEmpty())
            <div class="bo-muted">Aucun utilisateur.</div>
        @else
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Nom Complet</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Doctor</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name ?? '' }} </td>
                                <td>{{ $user->email ?? '—' }}</td>
                                <td>{{ $user->role ?? '—' }}</td>
                                <td>{{ $user->is_doctor ? 'Oui' : 'Non' }}</td>
                                <td class="text-end">
                                    <a class="btn btn-outline-dark btn-sm"
                                        href="{{ route('backoffice.users.edit', $user) }}">Modifier</a>
                                    <form class="d-inline" method="POST"
                                        action="{{ route('backoffice.users.destroy', $user) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Supprimer cet utilisateur ?')">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
