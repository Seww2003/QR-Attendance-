<?php
// app/Http/Controllers/TestQRController.php

//namespace App\Http\Controllers;

//use App\Models\ClassSession;
//use Illuminate\Http\Request;
//use SimpleSoftwareIO\QrCode\Facades\QrCode;

// class TestQRController extends Controller
// {
//     public function generateTestQR()
//     {
//         // Find any session
//         $session = ClassSession::first();
        
//         if (!$session) {
//             return "No sessions found. Create a session first.";
//         }

//         // Generate token
//         $token = \Illuminate\Support\Str::uuid();
        
//         $session->update([
//             'qr_token' => $token,
//             'qr_generated_at' => now(),
//         ]);

//         // Test QR Data
//         $qrData = json_encode([
//             'session_id' => $session->id,
//             'token' => $token,
//             'expires' => now()->addHours(2)->format('Y-m-d H:i:s'),
//             'timestamp' => now()->timestamp
//         ]);

//         $qrCode = QrCode::size(300)->generate($qrData);

//         return view('test-qr', [
//             'session' => $session,
//             'qrCode' => $qrCode,
//             'qrData' => $qrData
//         ]);
//     }
// }