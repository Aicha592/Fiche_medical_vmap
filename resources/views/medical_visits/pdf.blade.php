<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche médicale</title>
    <style>
        :root {
            --green-dark: #467049;
            --green-light: #aeca5f;
            --ink: #000000;
            --ink-soft: #626160;
            --paper: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            color: var(--ink);
            background: var(--paper);
            margin: 0;
            padding: 28px;
        }

        .header {
            border: 2px solid var(--green-dark);
            padding: 16px 18px;
            border-radius: 12px;
            background: #f5f7ef;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0 0 6px 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--green-dark);
        }

        .header p {
            margin: 0;
            font-size: 12px;
            color: var(--ink-soft);
        }

        .section {
            border: 1px solid rgba(70, 112, 73, 0.2);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 14px;
        }

        .section-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--green-dark);
            margin: 0 0 10px 0;
            font-weight: 700;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
        }

        .grid td {
            padding: 6px 0;
            vertical-align: top;
            font-size: 12px;
        }

        .label {
            width: 38%;
            color: var(--ink-soft);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-size: 10px;
        }

        .value {
            width: 62%;
        }

        .pill {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            background: rgba(174, 202, 95, 0.35);
            border: 1px solid rgba(70, 112, 73, 0.25);
            font-size: 10px;
            font-weight: 700;
        }

        .muted {
            color: var(--ink-soft);
        }
    </style>
</head>
<body>
    @php
        $fullName = trim(($employee?->nom ?? '') . ' ' . ($employee?->prenom ?? ''));
        if ($fullName === '') {
            $fullName = '—';
        }
    @endphp

    <div class="header">
        <h1>Fiche médicale – VMAP 2026</h1>
        @php
            $createdByName = trim(($visit->createdBy?->nom ?? '') . ' ' . ($visit->createdBy?->prenom ?? ''));
            if ($createdByName === '') {
                $createdByName = $visit->createdBy?->name ?? '—';
            }
        @endphp
        <p>Agent : {{ $fullName }} | Matricule : {{ $employee?->matricule ?? '—' }}</p>
        <p>Enregistré par : {{ $createdByName }}</p>
    </div>

    @php
        $join = function ($value) {
            if (is_array($value)) {
                return count($value) ? implode(', ', $value) : '—';
            }
            return $value ?: '—';
        };
    @endphp

    <div class="section">
        <div class="section-title">Identification</div>
        <table class="grid">
            <tr><td class="label">Nom et prénom</td><td class="value">{{ $fullName }}</td></tr>
            <tr><td class="label">Matricule</td><td class="value">{{ $employee?->matricule ?? '—' }}</td></tr>
            <tr><td class="label">Sexe</td><td class="value">{{ $employee?->sexe ?? '—' }}</td></tr>
            <tr><td class="label">Âge</td><td class="value">{{ $employee?->age ?? '—' }}</td></tr>
            <tr><td class="label">Date de naissance</td><td class="value">{{ $employee?->date_naissance?->format('d/m/Y') ?? '—' }}</td></tr>
            <tr><td class="label">Date d'embauche</td><td class="value">{{ $employee?->date_embauche?->format('d/m/Y') ?? '—' }}</td></tr>
            <tr><td class="label">Direction</td><td class="value">{{ $employee?->direction ?? '—' }}</td></tr>
            <tr><td class="label">Délégation / Région</td><td class="value">{{ $employee?->delegation_r ?? '—' }}</td></tr>
            <tr><td class="label">Service</td><td class="value">{{ $employee?->service ?? '—' }}</td></tr>
            <tr><td class="label">Unité communale</td><td class="value">{{ $employee?->unite_communale ?? '—' }}</td></tr>
            <tr><td class="label">Poste</td><td class="value">{{ $employee?->emploi_occupe ?? '—' }}</td></tr>
            <tr><td class="label">Ancienneté</td><td class="value">{{ $employee?->anciennete ?? '—' }}</td></tr>
            <tr><td class="label">Téléphone</td><td class="value">{{ $employee?->telephone ?? '—' }}</td></tr>
            <tr><td class="label">Date de passage</td><td class="value">{{ $employee?->date_passage?->format('d/m/Y') ?? '—' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Antécédents médicaux</div>
        <table class="grid">
            <tr><td class="label">Antécédents</td><td class="value">{{ $join($visit->antecedents) }}</td></tr>
            <tr><td class="label">Précisions</td><td class="value">{{ $visit->antecedents_precisions ?: '—' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Examen clinique</div>
        <table class="grid">
            <tr><td class="label">Taille</td><td class="value">{{ $visit->taille ? $visit->taille.' cm' : '—' }}</td></tr>
            <tr><td class="label">Poids</td><td class="value">{{ $visit->poids ? $visit->poids.' kg' : '—' }}</td></tr>
            <tr><td class="label">IMC</td><td class="value">{{ $visit->imc ?? '—' }}</td></tr>
            <tr><td class="label">Tension artérielle</td><td class="value">{{ $visit->tension ?? '—' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Dépistage RPS</div>
        <table class="grid">
            <tr><td class="label">Stress lié au travail</td><td class="value">{{ $visit->stress ?? '—' }}</td></tr>
            <tr><td class="label">Troubles du sommeil</td><td class="value">{{ $visit->sommeil ?? '—' }}</td></tr>
            <tr><td class="label">Charge de travail</td><td class="value">{{ $visit->charge_travail ?? '—' }}</td></tr>
            <tr><td class="label">Soutien hiérarchique</td><td class="value">{{ $visit->soutien ?? '—' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Avis médical</div>
        <table class="grid">
            <tr><td class="label">Avis</td><td class="value"><span class="pill">{{ $visit->avis ?? '—' }}</span></td></tr>
            <tr><td class="label">Observations</td><td class="value">{!! $visit->observations ? nl2br(e($visit->observations)) : '—' !!}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">QHSE / SST</div>
        <table class="grid">
            <tr><td class="label">Manutention</td><td class="value">{{ $join($visit->contrainte_manutention) }}</td></tr>
            <tr><td class="label">Postures</td><td class="value">{{ $join($visit->contrainte_postures) }}</td></tr>
            <tr><td class="label">Nuisances physiques</td><td class="value">{{ $join($visit->nuisances_physiques) }}</td></tr>
            <tr><td class="label">Nuisances chimiques</td><td class="value">{{ $join($visit->nuisances_chimiques) }}</td></tr>
            <tr><td class="label">Risques mécaniques</td><td class="value">{{ $join($visit->risques_mecaniques) }}</td></tr>
            <tr><td class="label">Organisation du travail</td><td class="value">{{ $join($visit->organisation_travail) }}</td></tr>
            <tr><td class="label">EPI disponibles</td><td class="value">{{ $join($visit->epi_disponibilite) }}</td></tr>
            <tr><td class="label">EPI utilisation</td><td class="value">{{ $visit->epi_utilisation ?? '—' }}</td></tr>
            <tr><td class="label">Difficultés EPI</td><td class="value">{{ $join($visit->epi_difficultes) }}</td></tr>
            <tr><td class="label">Formations SST</td><td class="value">{{ $join($visit->formation_sst) }}</td></tr>
            <tr><td class="label">Appréciation globale</td><td class="value"><span class="pill">{{ $visit->appreciation_poste ?? '—' }}</span></td></tr>
            <tr><td class="label">Observations QHSE</td><td class="value">{!! $visit->observations_qhse ? nl2br(e($visit->observations_qhse)) : '—' !!}</td></tr>
            <tr><td class="label">Poste à risque</td><td class="value"><span class="pill">{{ $visit->synthese_risque ?? '—' }}</span></td></tr>
            <tr><td class="label">Facteurs dominants</td><td class="value">{{ $join($visit->synthese_facteurs) }}</td></tr>
            <tr><td class="label">Actions recommandées</td><td class="value">{{ $join($visit->synthese_actions) }}</td></tr>
        </table>
    </div>

    <p class="muted" style="font-size: 10px;">Document généré le {{ now()->format('d/m/Y') }}</p>
</body>
</html>
