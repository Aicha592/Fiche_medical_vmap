@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h3>Liste consultée</h3>
        <p class="text-muted">Les agents que vous avez consultés — {{ $employees->total() }} agent(s).</p>
    </div>

    <form method="GET" action="{{ route('medical-visits.consulted') }}" class="gap-2 mb-4 d-flex">
        <label for="consulted-search" class="visually-hidden">Rechercher un agent</label>
        <input id="consulted-search" type="search" name="q" value="{{ $search }}" class="form-control"
            placeholder="Nom, prénom ou matricule">
        <button class="btn btn-outline-success" type="submit">Rechercher</button>
    </form>

    <div class="table-responsive">
        <table class="table align-middle table-striped">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Sexe</th>
                    <th>Emploi occupé</th>
                    <th>Direction</th>
                    <th>Date visite</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr>
                        <td>{{ $employee->matricule }}</td>
                        <td>{{ $employee->prenom }}</td>
                        <td>{{ $employee->nom }}</td>
                        <td>{{ $employee->sexe }}</td>
                        <td>{{ $employee->emploi_occupe }}</td>
                        <td>{{ $employee->direction }}</td>
                        <td>{{ $employee->last_consultation_at ? \Illuminate\Support\Carbon::parse($employee->last_consultation_at)->format('d/m/Y H:i') : '' }}</td>
                        <td>
                            <a class="btn btn-sm btn-outline-success" href="{{ route('medical-visits.consulted.show', $employee) }}">Détail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Aucun agent consulté{{ $search !== '' ? ' ne correspond à votre recherche' : '' }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $employees->links() }}
@endsection
