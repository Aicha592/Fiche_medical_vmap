@php
    $editing = isset($user);
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nom Complet</label>
        <input class="form-control" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Téléphone</label>
        <input class="form-control" type="text" name="telephone"
            value="{{ old('telephone', $user->telephone ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" value="{{ old('email', $user->email ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Rôle</label>
        <select class="form-select" name="role" required>
            @foreach ($roles as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user->role ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Mot de passe {{ $editing ? '(laisser vide pour ne pas changer)' : '' }}</label>
        <input class="form-control" type="password" name="password">
    </div>
</div>
