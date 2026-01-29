<?php

namespace App\Http\Controllers;

use App\Services\OrangeSmsService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function envoyerSms(Request $request, OrangeSmsService $smsService)
    {
        try {
            // Test SMS vers un numéro fixe
            $smsService->sendSms('+221781951113', 'Bonjour depuis Laravel', 'Test SMS');

            return response()->json(['message' => 'SMS envoyé']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
