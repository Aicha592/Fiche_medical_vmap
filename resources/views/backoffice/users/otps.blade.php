@extends('layouts.backoffice')

@section('content')
    <div class="mb-4 gap-3 d-flex align-items-center justify-content-between flex-wrap">
        <div>
            <h4 class="mb-1">Codes OTP</h4>
            <div class="bo-muted">Codes en attente de validation. Les codes utilisés ou expirés ne sont plus affichés.</div>
        </div>
        <a class="btn btn-outline-dark" href="{{ route('backoffice.users.index') }}">Retour aux utilisateurs</a>
    </div>

    <div class="bo-card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th scope="col">Nom utilisateur</th>
                        <th scope="col">Email</th>
                        <th scope="col">Code OTP</th>
                        <th scope="col">Date et heure d’expiration (Africa/Dakar)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($otps as $otp)
                        <tr>
                            <td>{{ $otp->user?->name ?? '—' }}</td>
                            <td>{{ $otp->user?->email ?? '—' }}</td>
                            <td><span class="font-monospace">{{ $otp->code }}</span></td>
                            <td>{{ $otp->expires_at->timezone('Africa/Dakar')->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center bo-muted">Aucun code OTP actif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $otps->links() }}</div>
    </div>
@endsection
