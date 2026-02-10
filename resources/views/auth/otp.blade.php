<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification OTP – VMAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --green-dark: #467049;
            --green-light: #aeca5f;
            --ink: #000000;
            --ink-soft: #626160;
            --paper: #ffffff;
            --radius-lg: 22px;
            --radius-md: 14px;
            --shadow: 0 18px 40px rgba(0, 0, 0, 0.15);
            --font-title: "LOEW HEAVY", "LOEW Heavy", "Times New Roman", serif;
            --font-strong: "ALLER BOLD", "Aller Bold", "Arial Black", sans-serif;
            --font-body: "ALLER REGULAR", "Aller Regular", "Arial", sans-serif;
            --font-light: "ALLER LIGHT", "Aller Light", "Arial", sans-serif;
            --font-accent: "HAND OF SEAN", "Hand of Sean", "Comic Sans MS", cursive;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: radial-gradient(900px 500px at 10% -20%, rgba(174, 202, 95, 0.45), transparent),
                radial-gradient(900px 420px at 110% 0%, rgba(70, 112, 73, 0.35), transparent),
                #f6f7f1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-body);
            color: var(--ink);
        }

        .otp-shell {
            width: min(900px, 92vw);
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            background: var(--paper);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid rgba(70, 112, 73, 0.15);
        }

        .otp-aside {
            background: linear-gradient(135deg, var(--green-dark), #36583a);
            color: #fff;
            padding: 40px 36px;
            position: relative;
        }

        .otp-aside::after {
            content: "";
            position: absolute;
            inset: 22% -20% 22% 35%;
            background: radial-gradient(circle, rgba(174, 202, 95, 0.45), transparent 70%);
            opacity: 0.9;
            pointer-events: none;
        }

        .otp-aside h1 {
            font-family: var(--font-title);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.3rem;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .otp-aside p {
            font-family: var(--font-light);
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .otp-aside .accent {
            font-family: var(--font-accent);
            color: var(--green-light);
            font-size: 1rem;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }

        .otp-card {
            padding: 38px 34px;
        }

        .otp-card h3 {
            font-family: var(--font-title);
            color: var(--green-dark);
            letter-spacing: 0.6px;
            /*text-transform: uppercase;*/
        }

        .form-label {
            font-family: var(--font-strong);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-size: 0.8rem;
            color: var(--ink);
        }

        .form-control {
            border-radius: var(--radius-md);
            border-color: rgba(70, 112, 73, 0.25);
            background-color: #fbfcf6;
            padding: 11px 12px;
        }

        .form-control:focus {
            border-color: var(--green-dark);
            box-shadow: 0 0 0 0.2rem rgba(70, 112, 73, 0.2);
        }

        .btn-custom {
            background-color: var(--green-dark);
            border-color: var(--green-dark);
            color: #fff;
            font-family: var(--font-strong);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-radius: 999px;
        }

        .btn-custom:hover {
            background-color: #355c39;
            border-color: #355c39;
            color: #fff;
        }

        .btn-resend {
            background-color: transparent;
            border: 1px solid rgba(70, 112, 73, 0.5);
            color: var(--green-dark);
            font-family: var(--font-strong);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 16px;
            border-radius: 999px;
        }

        .btn-resend:hover {
            background-color: rgba(70, 112, 73, 0.08);
            border-color: var(--green-dark);
            color: var(--green-dark);
        }

        .btn-resend[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .resend-hint {
            color: var(--ink-soft);
            font-size: 0.85rem;
            text-align: center;
            margin-top: 8px;
        }

        .alert {
            border-radius: var(--radius-md);
            font-size: 0.9rem;
        }

        .brand-logo {
            width: 120px;
            height: auto;
            margin-bottom: 16px;
            filter: drop-shadow(0 6px 14px rgba(0, 0, 0, 0.2));
        }

        .brand-logo.aside {
            width: 150px;
            margin: 0 auto 22px auto;
            display: block;
        }

        .brand-logo.small {
            width: 100px;
            margin: 0 auto 18px auto;
            display: block;
            filter: none;
        }

        @media (max-width: 900px) {
            .otp-shell {
                grid-template-columns: 1fr;
            }

            .otp-aside {
                padding: 28px 26px;
            }
        }
    </style>
</head>

<body>

    <div class="otp-shell">
        <aside class="otp-aside">
            <img class="brand-logo aside" src="{{ asset('images/sonaged-logo.png') }}" alt="SONAGED">
            <h1>VMap Sécurité</h1>
            <p>Une étape de vérification pour protéger les données médicales et garantir la confidentialité.</p>
            <p class="accent">Sécurité avant tout.</p>
        </aside>

        <div class="otp-card">
            <img class="brand-logo small" src="{{ asset('images/sonaged-logo.png') }}" alt="SONAGED">
            <h3 class="mb-4 text-center">Un code de vérification a été envoyé par sms au numéro
                {{ substr($user->telephone, 0, 4) . '*****' . substr($user->telephone, -2) }}</h3>

            @if (session('error'))
                <div class="text-center alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="text-center alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->has('session'))
                <div class="text-center alert alert-danger">
                    {{ $errors->first('session') }}
                </div>
            @endif
            @if ($errors->has('otp'))
                <div class="text-center alert alert-danger">
                    {{ $errors->first('otp') }}
                </div>
            @endif

            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <div class="mb-3">
                    <label for="otp" class="form-label">Code OTP</label>
                    <input type="text" name="otp" id="otp" class="form-control" required>
                </div>
                <div class="mt-4 d-grid">
                    <button type="submit" class="btn btn-custom">Vérifier</button>
                </div>
            </form>
            <form method="POST" action="{{ route('otp.resend') }}" class="mt-3 d-grid">
                @csrf
                @php
                    $cooldownSeconds = 60;
                    $lastSentAt = session('otp_resent_at');
                    $remainingSeconds = 0;
                    if ($lastSentAt) {
                        $elapsed = now()->diffInSeconds($lastSentAt);
                        $remainingSeconds = max(0, $cooldownSeconds - $elapsed);
                    }
                @endphp
                <button type="submit" class="btn btn-resend" id="resendBtn" {{ $remainingSeconds > 0 ? 'disabled' : '' }}>
                    Renvoyer le code
                </button>
                <div class="resend-hint" id="resendHint" data-remaining="{{ $remainingSeconds }}">
                    @if ($remainingSeconds > 0)
                        Vous pourrez renvoyer un code dans {{ sprintf('%02d:%02d', intdiv($remainingSeconds, 60), $remainingSeconds % 60) }}.
                    @else
                        Vous n'avez pas reçu de code ? Vous pouvez le renvoyer.
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const hint = document.getElementById('resendHint');
            const btn = document.getElementById('resendBtn');
            if (!hint || !btn) return;
            let remaining = parseInt(hint.dataset.remaining || '0', 10);
            if (!Number.isFinite(remaining) || remaining <= 0) return;

            const fmt = (seconds) => {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            };

            const tick = () => {
                remaining -= 1;
                if (remaining <= 0) {
                    btn.disabled = false;
                    hint.textContent = "Vous n'avez pas reçu de code ? Vous pouvez le renvoyer.";
                    return;
                }
                hint.textContent = `Vous pourrez renvoyer un code dans ${fmt(remaining)}.`;
                setTimeout(tick, 1000);
            };

            setTimeout(tick, 1000);
        })();
    </script>
</body>

</html>
