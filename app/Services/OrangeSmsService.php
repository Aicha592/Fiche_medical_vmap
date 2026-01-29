<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception; 

class OrangeSmsService
{
    protected $token;
    protected $accessKey;
    protected $baseUri;
    protected $signature;

    public function __construct()
    {
        $this->token = env('ORANGE_SMS_TOKEN');
        $this->accessKey = env('ORANGE_SMS_ACCESS_KEY');
        $this->baseUri = env('ORANGE_SMS_BASE_URI');
        $this->signature = env('ORANGE_SMS_SIGNATURE');
    }

    public function sendSms($phone, $message, $subject)
    {
        $timestamp = time();
        $flux = json_encode([
            "messages" => [
                [
                    "signature"  => $this->signature,
                    "subject"    => $subject,
                    "content"    => $message,
                    "recipients" => [
                        [
                            "id"    => time(),
                            "value" => str_replace('+', '', $phone)
                        ]
                    ]
                ]
            ]
        ]);

        $msgToEncrypt = $this->token . $flux . $timestamp;
        $key = hash_hmac('sha1', $msgToEncrypt, $this->accessKey);
        $uri = $this->baseUri . "?token={$this->token}&key={$key}&timestamp={$timestamp}";

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode("sonaged:{$this->token}"),
            'Content-Type'  => 'application/json',
            
        ])->post($uri, json_decode($flux, true));

        if ($response->successful()) {
            return $response->json();
        }

        throw new Exception('Erreur d\'envoi du SMS : ' . $response->body());
    }
}