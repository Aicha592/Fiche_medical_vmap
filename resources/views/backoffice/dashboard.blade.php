@extends('layouts.backoffice')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">Tableau de bord</h4>
            <div class="bo-muted">Vue d’ensemble des visites et des accès.</div>
        </div>
        @if($user->isDoctor() || $user->isRh())
            <a class="btn btn-bo" href="{{ route('backoffice.medical-records.index') }}">Consulter les fiches</a>
        @endif
    </div>

    <div class="row g-3 mb-4">
        @if($user->isDoctor() || $user->isRh())
            <div class="col-md-4">
                <div class="bo-card bo-kpi">
                    <div class="bo-muted">Total visites</div>
                    <div class="fs-3 fw-semibold">{{ $stats['total_visits'] }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bo-card bo-kpi">
                    <div class="bo-muted">7 derniers jours</div>
                    <div class="fs-3 fw-semibold">{{ $stats['visits_last_7_days'] }}</div>
                </div>
            </div>
        @endif
        @if($user->isRh())
            <div class="col-md-4">
                <div class="bo-card bo-kpi">
                    <div class="bo-muted">Fiches avec QHSE</div>
                    <div class="fs-3 fw-semibold">{{ $stats['qhse_filled'] }}</div>
                </div>
            </div>
        @endif
        @if($user->isAdmin())
            <div class="col-md-4">
                <div class="bo-card bo-kpi">
                    <div class="bo-muted">Employés</div>
                    <div class="fs-3 fw-semibold">{{ $stats['employees_count'] }}</div>
                </div>
            </div>
        @endif
    </div>

    @if($user->isAdmin())
        <div class="bo-card mt-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="fw-semibold">Visites médicales effectuées (par date de passage)</div>
                <form class="d-flex gap-2" method="GET" action="{{ route('backoffice.dashboard') }}">
                    <input type="date" name="date_passage" class="form-control" value="{{ $selectedDate }}">
                    <button class="btn btn-outline-dark btn-sm" type="submit">Filtrer</button>
                    <button class="btn btn-outline-dark btn-sm" type="submit" name="today" value="1">Aujourd’hui</button>
                </form>
            </div>
            @if($selectedDate)
                <div class="alert alert-info py-2 mb-3">
                    {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }} :
                    {{ $visitsByPassageDay->planned_total ?? 0 }} prévue(s),
                    {{ $visitsByPassageDay->done_total ?? 0 }} effectuée(s)
                </div>
            @endif
            @if($visitsByPassage->isEmpty())
                <div class="bo-muted">Aucune date de passage renseignée.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date de passage</th>
                                <th>Nombre de visites prévues</th>
                                <th>Nombre de visites effectuées</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visitsByPassage as $row)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($row->date_passage)->format('d/m/Y') }}</td>
                                    <td>{{ $row->planned_total }}</td>
                                    <td>{{ $row->done_total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    @if($user->isAdmin())
        <div class="bo-card mt-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="fw-semibold">Dernières visites</div>
                <a class="btn btn-outline-dark btn-sm" href="{{ route('backoffice.medical-records.index') }}">Voir tout</a>
            </div>
            @if($recentVisits->isEmpty())
                <div class="bo-muted">Aucune visite enregistrée.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Agent</th>
                                <th>Matricule</th>
                                <th>Avis</th>
                                <th>QHSE</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentVisits as $visit)
                                <tr>
                                    <td>{{ $visit->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $visit->employee->nom ?? '' }} {{ $visit->employee->prenom ?? '' }}</td>
                                    <td>{{ $visit->employee->matricule ?? '—' }}</td>
                                    <td>{{ $visit->avis ?? '—' }}</td>
                                    @php
                                        $qhseFields = [
                                            $visit->contrainte_manutention,
                                            $visit->contrainte_postures,
                                            $visit->nuisances_physiques,
                                            $visit->nuisances_chimiques,
                                            $visit->risques_mecaniques,
                                            $visit->organisation_travail,
                                            $visit->epi_disponibilite,
                                            $visit->epi_utilisation,
                                            $visit->epi_difficultes,
                                            $visit->formation_sst,
                                            $visit->appreciation_poste,
                                            $visit->observations_qhse,
                                            $visit->synthese_risque,
                                            $visit->synthese_facteurs,
                                            $visit->synthese_actions,
                                        ];
                                        $hasQhse = collect($qhseFields)->filter(function ($value) {
                                            return !empty($value);
                                        })->isNotEmpty();
                                    @endphp
                                    <td>
                                        <span class="bo-pill">{{ $hasQhse ? 'Complété' : 'En attente' }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-outline-dark btn-sm"
                                           href="{{ route('backoffice.medical-records.show', $visit) }}">
                                            Ouvrir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
@endsection
