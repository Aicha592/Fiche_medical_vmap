@php
    $isEdit = $employee && $employee->exists;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Matricule</label>
        <input type="text" name="matricule" class="form-control" value="{{ old('matricule', $employee->matricule) }}" required>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="nom" class="form-control" value="{{ old('nom', $employee->nom) }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Prénom</label>
        <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $employee->prenom) }}" required>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">Sexe</label>
        <select name="sexe" class="form-select">
            <option value="">—</option>
            <option value="M" {{ old('sexe', $employee->sexe) === 'M' ? 'selected' : '' }}>M</option>
            <option value="F" {{ old('sexe', $employee->sexe) === 'F' ? 'selected' : '' }}>F</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Âge</label>
        <input type="number" class="form-control" value="{{ old('age', $employee->age) }}" readonly>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Date de naissance</label>
        <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', optional($employee->date_naissance)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Date d'embauche</label>
        <input type="date" name="date_embauche" class="form-control" value="{{ old('date_embauche', optional($employee->date_embauche)->format('Y-m-d')) }}">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Emploi occupé</label>
        <input type="text" name="emploi_occupe" class="form-control" value="{{ old('emploi_occupe', $employee->emploi_occupe) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Direction</label>
        <input type="text" name="direction" class="form-control" value="{{ old('direction', $employee->direction) }}">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Délégation / Région</label>
        <input type="text" name="delegation_r" class="form-control" value="{{ old('delegation_r', $employee->delegation_r) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Service</label>
        <input type="text" name="service" class="form-control" value="{{ old('service', $employee->service) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Unité communale</label>
        <input type="text" name="unite_communale" class="form-control" value="{{ old('unite_communale', $employee->unite_communale) }}">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Ancienneté</label>
        <input type="text" class="form-control" value="{{ old('anciennete', $employee->anciennete) }}" readonly>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Site</label>
        <select name="site" class="form-select">
            <option value="">—</option>
            <option value="R" {{ old('site', $employee->site) === 'R' ? 'selected' : '' }}>R</option>
            <option value="D" {{ old('site', $employee->site) === 'D' ? 'selected' : '' }}>D</option>
            <option value="C" {{ old('site', $employee->site) === 'C' ? 'selected' : '' }}>C</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Téléphone</label>
        <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $employee->telephone) }}">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Date de passage</label>
        <input type="date" name="date_passage" class="form-control" value="{{ old('date_passage', optional($employee->date_passage)->format('Y-m-d')) }}">
    </div>
</div>
