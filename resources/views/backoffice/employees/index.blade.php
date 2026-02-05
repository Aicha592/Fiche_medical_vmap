@extends('layouts.backoffice')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">Employés</h4>
            <div class="bo-muted">Gestion des fiches employés.</div>
        </div>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="GET" action="{{ route('backoffice.employees.index') }}">
                <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Nom, matricule, direction">
                <button class="btn btn-bo" type="submit">Rechercher</button>
            </form>
            <a class="btn btn-outline-dark" href="{{ route('backoffice.employees.import') }}">Importer CSV</a>
            <a class="btn btn-outline-dark" href="{{ route('backoffice.employees.create') }}">Nouvel employé</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('import_errors'))
        <div class="alert alert-warning">
            <div class="fw-semibold mb-2">Erreurs d'import</div>
            <ul class="mb-0">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bo-card">
        @if($employees->isEmpty())
            <div class="bo-muted">Aucun employé.</div>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Matricule</th>
                            <th>Direction</th>
                            <th>Délégation / Région</th>
                            <th>Service</th>
                            <th>Unité communale</th>
                            <th>Visite médicale</th>
                            <th>Fiche QHSE</th>
                            @if(auth()->user()->role === 'med-taf')
                                <th>Fiche médicale</th>
                            @endif
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->nom }} {{ $employee->prenom }}</td>
                                <td>{{ $employee->matricule }}</td>
                                <td>{{ $employee->direction ?? '—' }}</td>
                                <td>{{ $employee->delegation_r ?? '—' }}</td>
                                <td>{{ $employee->service ?? '—' }}</td>
                                <td>{{ $employee->unite_communale ?? '—' }}</td>
                                <td>
                                    <span class="bo-pill {{ ($employee->medical_visits_count ?? 0) > 0 ? 'bg-success text-white' : 'bg-light text-dark border' }}">
                                        {{ ($employee->medical_visits_count ?? 0) > 0 ? 'Effectuée' : 'Non' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="bo-pill {{ ($employee->qhse_visits_count ?? 0) > 0 ? 'bg-success text-white' : 'bg-light text-dark border' }}">
                                        {{ ($employee->qhse_visits_count ?? 0) > 0 ? 'Effectuée' : 'Non' }}
                                    </span>
                                </td>
                                @if(auth()->user()->role === 'med-taf')
                                    <td>
                                        @if($employee->latest_medical_visit_id)
                                            <a class="btn btn-sm btn-outline-dark"
                                               href="{{ route('backoffice.medical-records.show', $employee->latest_medical_visit_id) }}">
                                                Voir fiche
                                            </a>
                                        @else
                                            <span class="bo-muted">—</span>
                                        @endif
                                    </td>
                                @endif
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-dark" href="{{ route('backoffice.employees.edit', $employee) }}">Modifier</a>
                                    <form method="POST" action="{{ route('backoffice.employees.destroy', $employee) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Supprimer cet employé ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
@endsection
