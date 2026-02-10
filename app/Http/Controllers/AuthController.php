<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\OrangeSmsService;
use Log;

class AuthController extends Controller
{
    protected OrangeSmsService $sms;

    public function __construct(OrangeSmsService $sms)
    {
        $this->sms = $sms;
    }


    public function showLogin()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email',
            'password'  => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        //dd('user', $user);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect'
            ])->withInput();
        }

        // Générer le code OTP
        $otpCode = rand(100000, 999999);

        // Stocker OTP et info utilisateur dans session
        session([
            'otp' => $otpCode,
            'user_id' => $user->id,
            'phone' => $user->telephone,
        ]);

        // Envoyer le SMS
        try {
            $this->sms->sendSms(
                $user->telephone,
                "Votre code de vérification est : $otpCode",
                "Code de vérification"
            );
        } catch (\Exception $e) {
            Log::info($e);
        }

        return redirect()->route('otp.form');
    }

    /* ======================
        FORM OTP
    ======================= */
    public function showOtp()
{
    if (!session('user_id')) {
        return redirect()->route('login');
    }

    $user = User::find(session('user_id'));

    return view('auth.otp', compact('user'));
}


    /* ======================
        VÉRIFICATION OTP
    ======================= */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otpSession = session('otp');
        $userId = session('user_id');

        if (!$otpSession || !$userId) {
            return redirect()->route('login')->withErrors([
                'session' => 'Session expirée, veuillez vous reconnecter'
            ]);
        }

        if ($request->otp != $otpSession) {
            return back()->withErrors([
                'otp' => 'Code incorrect'
            ]);
        }

        // Connexion de l'utilisateur
        Auth::loginUsingId($userId);

        // Nettoyer la session
        session()->forget(['otp', 'user_id', 'phone']);

        $user = Auth::user();

        if ($user?->isAdmin() || $user?->isMedecin() || $user?->isCh()) {
            return redirect()->route('backoffice.dashboard')->with('success', 'Connexion réussie');
        }

        if ($user?->isRh()) {
            return redirect()->route('medical-visits.qhse.index')->with('success', 'Connexion réussie');
        }

        if ($user?->isDoctor()) {
            return redirect()->route('home')->with('success', 'Connexion réussie');
        }

        return redirect()->route('home')->with('success', 'Connexion réussie');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /* ======================
        RENVOI OTP
    ======================= */
    public function resendOtp()
    {
        $userId = session('user_id');
        $phone = session('phone');

        if (!$userId || !$phone) {
            return redirect()->route('login')->withErrors([
                'session' => 'Session expirée, veuillez vous reconnecter'
            ]);
        }

        $cooldownSeconds = 60;
        $lastSentAt = session('otp_resent_at');
        if ($lastSentAt && now()->diffInSeconds($lastSentAt) < $cooldownSeconds) {
            $remaining = $cooldownSeconds - now()->diffInSeconds($lastSentAt);
            return back()->with('error', "Veuillez patienter {$remaining}s avant de renvoyer un code.");
        }

        $user = User::find($userId);
        if (!$user) {
            session()->forget(['otp', 'user_id', 'phone']);
            return redirect()->route('login')->withErrors([
                'session' => 'Utilisateur introuvable, veuillez vous reconnecter'
            ]);
        }

        // Supprimer l'ancien code et générer un nouveau
        session()->forget('otp');
        $otpCode = rand(100000, 999999);
        session(['otp' => $otpCode]);

        try {
            $this->sms->sendSms(
                $user->telephone,
                "Votre code de vérification est : $otpCode",
                "Code de vérification"
            );
        } catch (\Exception $e) {
            Log::info($e);
            return back()->with('error', 'Erreur lors de l\'envoi du code. Veuillez réessayer.');
        }

        session(['otp_resent_at' => now()]);
        return back()->with('success', 'Un nouveau code vous a été envoyé par SMS.');
    }
}
