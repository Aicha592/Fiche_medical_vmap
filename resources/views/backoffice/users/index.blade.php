@extends('layouts.backoffice')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">Utilisateurs</h4>
            <div class="bo-muted">Gestion des comptes et des rôles.</div>
        </div>
        <form class="d-flex gap-2" method="GET" action="{{ route('backoffice.users.index') }}">
            <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Nom, matricule, email">
            <button class="btn btn-bo" type="submit">Rechercher</button>
        </form>
    </div>

    <div class="bo-card">
        @if($users->isEmpty())
            <div class="bo-muted">Aucun utilisateur.</div>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Matricule</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Doctor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->nom ?? '' }} {{ $user->prenom ?? '' }}</td>
                                <td>{{ $user->matricule ?? '—' }}</td>
                                <td>{{ $user->email ?? '—' }}</td>
                                <td>{{ $user->role ?? '—' }}</td>
                                <td>{{ $user->is_doctor ? 'Oui' : 'Non' }}</td>
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
