@extends('layouts.backoffice')

@section('content')
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1">Tableau de bord</h4>
            <div class="bo-muted">Vue d’ensemble des visites et des accès.</div>
        </div>
        @if ($user->isMedecin())
            <a class="btn btn-bo" href="{{ route('backoffice.medical-records.index') }}">Consulter les fiches</a>
        @endif
    </div>

    <div class="mb-4 row g-3">
        @if ($user->isMedecin() || $user->isCh())
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
        @if ($user->isCh())
            <div class="col-md-4">
                <div class="bo-card bo-kpi">
                    <div class="bo-muted">Fiches avec QHSE</div>
                    <div class="fs-3 fw-semibold">{{ $stats['qhse_filled'] }}</div>
                </div>
            </div>
        @endif
        @if ($user->isAdmin() || $user->isCh() || $user->isMedecin())
            <div class="col-md-4">
                <div class="bo-card bo-kpi">
                    <div class="bo-muted">Employés</div>
                    <div class="fs-3 fw-semibold">{{ $stats['employees_count'] }}</div>
                    <div class="mt-2 bo-muted">Visites effectuées / employés</div>
                    <div class="fw-semibold">{{ $stats['total_visits'] }} / {{ $stats['employees_count'] }}</div>
                </div>
            </div>
        @endif
    </div>

    @if ($user->isAdmin() || $user->isCh() || $user->isMedecin())
        <div class="mb-2 row g-3">
            <div class="col-md-3">
                <div class="bo-card bo-kpi" style="border-left-color: #356a45;">
                    <div class="bo-muted">Couverture médicale</div>
                    <div class="fs-3 fw-semibold" style="color: #356a45;">{{ $stats['coverage_pct'] }}%</div>
                    <div class="mt-2 bo-muted">{{ $stats['employees_with_visit'] }} / {{ $stats['employees_count'] }}
                        employés</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bo-card bo-kpi" style="border-left-color: #356a45;">
                    <div class="bo-muted">Aptes sans restriction</div>
                    <div class="fs-3 fw-semibold" style="color: #356a45;">{{ $stats['aptes_sans_restriction'] }}</div>
                    <div class="mt-2 bo-muted">Périmètre: dernière fiche / employé</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bo-card bo-kpi" style="border-left-color: #4a7e59;">
                    <div class="bo-muted">Aptes avec aménagement</div>
                    <div class="fs-3 fw-semibold" style="color: #4a7e59;">{{ $stats['aptes_avec_amenagement'] }}</div>
                    <div class="mt-2 bo-muted">Périmètre: dernière fiche / employé</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bo-card bo-kpi" style="border-left-color: #9f3f3f;">
                    <div class="bo-muted">Inaptes (temporaire + définitif)</div>
                    <div class="fs-3 fw-semibold" style="color: #9f3f3f;">{{ $stats['inaptes'] }}</div>
                    <div class="mt-2 bo-muted">Périmètre: dernière fiche / employé</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bo-card bo-kpi" style="border-left-color: #9b7d34;">
                    <div class="bo-muted">Postes à risque</div>
                    <div class="fs-3 fw-semibold" style="color: #9b7d34;">{{ $stats['postes_risque'] }}</div>
                    <div class="mt-2 bo-muted">QHSE sur dernière fiche / employé</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bo-card bo-kpi" style="border-left-color: #9b7d34;">
                    <div class="bo-muted">Risque élevé ou très élevé</div>
                    <div class="fs-3 fw-semibold" style="color: #9b7d34;">{{ $stats['risque_eleve'] }}</div>
                    <div class="mt-2 bo-muted">QHSE sur dernière fiche / employé</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bo-card bo-kpi" style="border-left-color: #9b7d34;">
                    <div class="bo-muted">EPI insuffisants</div>
                    <div class="fs-3 fw-semibold" style="color: #9b7d34;">{{ $stats['epi_insuffisant'] }}</div>
                    <div class="mt-2 bo-muted">QHSE sur dernière fiche / employé</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bo-card bo-kpi" style="border-left-color: #9f3f3f;">
                    <div class="bo-muted">Alertes RPS (score ≥ 2)</div>
                    <div class="fs-3 fw-semibold" style="color: #9f3f3f;">{{ $stats['rps_alerts'] }}</div>
                    <div class="mt-2 bo-muted">Périmètre: dernière fiche / employé</div>
                </div>
            </div>
        </div>

        <div class="mb-4 row g-3">
            <div class="col-lg-6">
                <div class="bo-card">
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <div class="fw-semibold">Répartition des avis médicaux</div>
                        <div class="bo-muted">Base: {{ $latestVisitCount }} employés</div>
                    </div>
                    @forelse ($avisChart as $item)
                        <div class="mb-3">
                            <div class="mb-1 d-flex justify-content-between">
                                <span>{{ $item['label'] }}</span>
                                <span>{{ $item['count'] }} ({{ $item['percent'] }}%)</span>
                            </div>
                            <div class="progress" role="progressbar" aria-label="{{ $item['label'] }}">
                                <div class="progress-bar"
                                    style="width: {{ $item['percent'] }}%; background: {{ $item['color'] }};"></div>
                            </div>
                        </div>
                    @empty
                        <div class="bo-muted">Aucune donnée disponible.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bo-card">
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <div class="fw-semibold">Top facteurs de risque QHSE</div>
                        <div class="bo-muted">Top 8</div>
                    </div>
                    @forelse ($facteursChart as $item)
                        <div class="mb-3">
                            <div class="mb-1 d-flex justify-content-between">
                                <span>{{ $item['label'] }}</span>
                                <span>{{ $item['count'] }}</span>
                            </div>
                            <div class="progress" role="progressbar" aria-label="{{ $item['label'] }}">
                                <div class="progress-bar"
                                    style="width: {{ $item['percent'] }}%; background: {{ $item['color'] }};"></div>
                            </div>
                        </div>
                    @empty
                        <div class="bo-muted">Aucun facteur renseigné.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    @if ($user->isAdmin() || $user->isCh() || $user->isMedecin())
        <div class="mt-4 bo-card">
            <div class="mb-3 d-flex align-items-center justify-content-between" style="flex-wrap: wrap; gap: 1rem;">
                <div class="fw-semibold">Visites médicales effectuées (par région)</div>
                <form class="gap-2 d-flex flex-nowrap align-items-center" method="GET"
                    action="{{ route('backoffice.dashboard') }}">
                    <input type="date" name="date_passage" class="w-auto form-control" value="{{ $selectedDate }}">
                    <select name="delegation_r_filter" class="w-auto form-control">
                        <option value="">Toutes les régions</option>
                        @foreach ($allDelegations as $delegation)
                            <option value="{{ $delegation }}"
                                {{ $selectedDelegation === $delegation ? 'selected' : '' }}>
                                {{ $delegation }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-dark btn-sm" type="submit">Filtrer</button>
                    <button class="btn btn-outline-dark btn-sm" type="submit" name="today"
                        value="1">Aujourd’hui</button>
                    <a class="btn btn-outline-secondary btn-sm text-nowrap"
                        href="{{ route('backoffice.dashboard') }}">Effacer les filtres</a>
                </form>
            </div>
            @if ($selectedDate)
                <div class="py-2 mb-3 alert alert-info">
                    {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }} :
                    {{ $visitsByRegionDay->done_total ?? 0 }} visite(s) médicale(s) enregistrée(s),
                    {{ $visitsByRegionDay->qhse_total ?? 0 }} évaluation(s) QHSE enregistrée(s)
                </div>
            @endif
            @if ($visitsByRegion->isEmpty())
                <div class="bo-muted">Aucune région renseignée.</div>
            @else
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Région</th>
                                <th>Effectif total</th>
                                <th>Nombre de visites médicales effectuées</th>
                                <th>Nombre de visites QHSE effectuées</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($visitsByRegion as $row)
                                <tr>
                                    <td>{{ $row->region }}</td>
                                    <td>{{ $row->region_total }}</td>
                                    <td>{{ $row->done_total }}</td>
                                    <td>{{ $row->qhse_total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-semibold">
                                <td>Total</td>
                                <td>{{ $visitsByRegion->sum('region_total') }}</td>
                                <td>{{ $visitsByRegion->sum('done_total') }}</td>
                                <td>{{ $visitsByRegion->sum('qhse_total') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    @endif

    @if ($user->isAdmin() || $user->isCh() || $user->isMedecin())
        <div class="mt-4 bo-card">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <div class="fw-semibold">Visites médicales effectuées (par date de passage)</div>
                <form class="gap-2 d-flex" method="GET" action="{{ route('backoffice.dashboard') }}">
                    <input type="date" name="date_passage" class="form-control" value="{{ $selectedDate }}">
                    <button class="btn btn-outline-dark btn-sm" type="submit">Filtrer</button>
                    <button class="btn btn-outline-dark btn-sm" type="submit" name="today"
                        value="1">Aujourd’hui</button>
                </form>
            </div>
            @if ($selectedDate)
                <div class="py-2 mb-3 alert alert-info">
                    {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }} :
                    {{ $visitsByPassageDay->planned_total ?? 0 }} prévue(s),
                    {{ $visitsByPassageDay->done_total ?? 0 }} médicale(s) effectuée(s),
                    {{ $visitsByPassageDay->qhse_total ?? 0 }} QHSE effectuée(s)
                </div>
            @endif
            @if ($visitsByPassage->isEmpty())
                <div class="bo-muted">Aucune date de passage renseignée.</div>
            @else
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Date de passage</th>
                                <th>Nombre de visites prévues</th>
                                <th>Nombre de visites médicales effectuées</th>
                                <th>Nombre de visites QHSE effectuées</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($visitsByPassage as $row)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($row->date_passage)->format('d/m/Y') }}</td>
                                    <td>{{ $row->planned_total }}</td>
                                    <td>{{ $row->done_total }}</td>
                                    <td>{{ $row->qhse_total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    @if ($user->isAdmin())
        <div class="mt-4 bo-card">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <div class="fw-semibold">Dernières visites</div>
                <a class="btn btn-outline-dark btn-sm" href="{{ route('backoffice.medical-records.index') }}">Voir
                    tout</a>
            </div>
            @if ($recentVisits->isEmpty())
                <div class="bo-muted">Aucune visite enregistrée.</div>
            @else
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Agent</th>
                                <th>Matricule</th>
                                <th>Avis</th>
                                <th>QHSE (employé)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentVisits as $visit)
                                <tr>
                                    <td>{{ $visit->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $visit->employee->nom ?? '' }} {{ $visit->employee->prenom ?? '' }}</td>
                                    <td>{{ $visit->employee->matricule ?? '—' }}</td>
                                    <td>{{ $visit->avis ?? '—' }}</td>
                                    @php
                                        $qhse = $visit->employee?->qhse;
                                        $qhseFields = [
                                            $qhse?->contrainte_manutention,
                                            $qhse?->contrainte_postures,
                                            $qhse?->nuisances_physiques,
                                            $qhse?->nuisances_chimiques,
                                            $qhse?->risques_mecaniques,
                                            $qhse?->organisation_travail,
                                            $qhse?->epi_disponibilite,
                                            $qhse?->epi_utilisation,
                                            $qhse?->epi_difficultes,
                                            $qhse?->formation_sst,
                                            $qhse?->appreciation_poste,
                                            $qhse?->observations_qhse,
                                            $qhse?->synthese_risque,
                                            $qhse?->synthese_facteurs,
                                            $qhse?->synthese_actions,
                                        ];
                                        $hasQhse = collect($qhseFields)
                                            ->filter(function ($value) {
                                                return !empty($value);
                                            })
                                            ->isNotEmpty();
                                    @endphp
                                    <td>
                                        <span
                                            class="bo-pill">{{ $hasQhse ? 'Complété (employé)' : 'En attente (employé)' }}</span>
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
