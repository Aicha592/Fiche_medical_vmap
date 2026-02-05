@extends('layouts.backoffice')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">Fiche médicale</h4>
            <div class="bo-muted">Consultation en lecture seule.</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-dark" href="{{ route('backoffice.medical-records.index') }}">Retour</a>
            @if($user->isDoctor())
                <a class="btn btn-bo" href="{{ route('medical-visits.pdf', $visit) }}" target="_blank">Télécharger PDF</a>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-5">
            <div class="bo-card h-100">
                <div class="fw-semibold mb-2">Identification</div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="bo-muted">Nom</div>
                        <div>{{ $visit->user->nom ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Prénom</div>
                        <div>{{ $visit->user->prenom ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Matricule</div>
                        <div>{{ $visit->user->matricule ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Téléphone</div>
                        <div>{{ $visit->user->telephone ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Direction</div>
                        <div>{{ $visit->user->direction ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Poste</div>
                        <div>{{ $visit->user->poste ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Site</div>
                        <div>{{ $visit->user->site ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Ancienneté</div>
                        <div>{{ $visit->user->anciennete ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="bo-muted">Date visite</div>
                        <div>{{ $visit->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            @if($user->isDoctor())
                <div class="bo-card h-100">
                    <div class="fw-semibold mb-3">Résumé médical</div>
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="bo-muted">Taille</div>
                            <div>{{ $visit->taille ?? '—' }}</div>
                        </div>
                        <div class="col-4">
                            <div class="bo-muted">Poids</div>
                            <div>{{ $visit->poids ?? '—' }}</div>
                        </div>
                        <div class="col-4">
                            <div class="bo-muted">IMC</div>
                            <div>{{ $visit->imc ?? '—' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="bo-muted">Tension</div>
                            <div>{{ $visit->tension ?? '—' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="bo-muted">Avis médical</div>
                            <div>{{ $visit->avis ?? '—' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="bo-muted">Stress</div>
                            <div>{{ $visit->stress ?? '—' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="bo-muted">Sommeil</div>
                            <div>{{ $visit->sommeil ?? '—' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="bo-muted">Charge de travail</div>
                            <div>{{ $visit->charge_travail ?? '—' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="bo-muted">Soutien</div>
                            <div>{{ $visit->soutien ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bo-card h-100">
                    <div class="fw-semibold mb-2">Synthèse QHSE</div>
                    <div class="bo-muted mb-2">Informations QHSE et identification uniquement.</div>
                    <div><strong>Risque global :</strong> {{ $visit->synthese_risque ?? '—' }}</div>
                    <div class="mt-2"><strong>Observations :</strong> {{ $visit->observations_qhse ?? '—' }}</div>
                </div>
            @endif
        </div>
    </div>

    @if($user->isDoctor())
        <div class="bo-card mb-4">
            <div class="fw-semibold mb-2">Antécédents</div>
            @if(is_array($visit->antecedents) && count($visit->antecedents))
                <div class="d-flex flex-wrap gap-2">
                    @foreach($visit->antecedents as $item)
                        <span class="bo-pill">{{ $item }}</span>
                    @endforeach
                </div>
            @else
                <div class="bo-muted">—</div>
            @endif
            @if($visit->antecedents_precisions)
                <div class="mt-2"><strong>Précisions :</strong> {{ $visit->antecedents_precisions }}</div>
            @endif
        </div>

        <div class="bo-card mb-4">
            <div class="fw-semibold mb-2">Observations</div>
            <div>{{ $visit->observations ?? '—' }}</div>
        </div>
    @endif

    @if($user->isRh())
        <div class="bo-card mb-4">
            <div class="fw-semibold mb-3">QHSE / SST</div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="bo-muted">Contraintes manutention</div>
                    @if(is_array($visit->contrainte_manutention) && count($visit->contrainte_manutention))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($visit->contrainte_manutention as $item)
                                <span class="bo-pill">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="bo-muted">—</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="bo-muted">Contraintes postures</div>
                    @if(is_array($visit->contrainte_postures) && count($visit->contrainte_postures))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($visit->contrainte_postures as $item)
                                <span class="bo-pill">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="bo-muted">—</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="bo-muted">Nuisances physiques</div>
                    @if(is_array($visit->nuisances_physiques) && count($visit->nuisances_physiques))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($visit->nuisances_physiques as $item)
                                <span class="bo-pill">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="bo-muted">—</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="bo-muted">Nuisances chimiques</div>
                    @if(is_array($visit->nuisances_chimiques) && count($visit->nuisances_chimiques))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($visit->nuisances_chimiques as $item)
                                <span class="bo-pill">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="bo-muted">—</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="bo-muted">Risques mécaniques</div>
                    @if(is_array($visit->risques_mecaniques) && count($visit->risques_mecaniques))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($visit->risques_mecaniques as $item)
                                <span class="bo-pill">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="bo-muted">—</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="bo-muted">Organisation travail</div>
                    @if(is_array($visit->organisation_travail) && count($visit->organisation_travail))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($visit->organisation_travail as $item)
                                <span class="bo-pill">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="bo-muted">—</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="bo-muted">EPI disponibilité</div>
                    @if(is_array($visit->epi_disponibilite) && count($visit->epi_disponibilite))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($visit->epi_disponibilite as $item)
                                <span class="bo-pill">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="bo-muted">—</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="bo-muted">EPI utilisation</div>
                    <div>{{ $visit->epi_utilisation ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="bo-muted">EPI difficultés</div>
                    @if(is_array($visit->epi_difficultes) && count($visit->epi_difficultes))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($visit->epi_difficultes as $item)
                                <span class="bo-pill">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="bo-muted">—</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="bo-muted">Formation SST</div>
                    @if(is_array($visit->formation_sst) && count($visit->formation_sst))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($visit->formation_sst as $item)
                                <span class="bo-pill">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="bo-muted">—</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="bo-muted">Appréciation poste</div>
                    <div>{{ $visit->appreciation_poste ?? '—' }}</div>
                </div>
                <div class="col-md-12">
                    <div class="bo-muted">Synthèse facteurs</div>
                    @if(is_array($visit->synthese_facteurs) && count($visit->synthese_facteurs))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($visit->synthese_facteurs as $item)
                                <span class="bo-pill">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="bo-muted">—</div>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="bo-muted">Synthèse actions</div>
                    @if(is_array($visit->synthese_actions) && count($visit->synthese_actions))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($visit->synthese_actions as $item)
                                <span class="bo-pill">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="bo-muted">—</div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection
