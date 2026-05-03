<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PpdbRegistrant;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function callback(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        if (!$notification) {
            return response()->json(['message' => 'Invalid JSON payload'], 400);
        }

        $setting = Setting::first();
        $serverKey = $setting->midtrans_server_key ?? env('MIDTRANS_SERVER_KEY');

        $orderId = $notification->order_id;
        $statusCode = $notification->status_code;
        $grossAmount = $notification->gross_amount;
        $signatureKey = $notification->signature_key;

        $calculatedSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($calculatedSignature !== $signatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $fraudStatus = $notification->fraud_status;

        $registrant = PpdbRegistrant::where('midtrans_order_id', $orderId)->first();

        if (!$registrant) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $registrant->payment_status = 'pending';
            } else if ($fraudStatus == 'accept') {
                $registrant->payment_status = 'paid';
                $registrant->status = 'daftar_ulang_terverifikasi';
                $registrant->confirmed_at = now();
            }
        } else if ($transactionStatus == 'settlement') {
            $registrant->payment_status = 'paid';
            $registrant->status = 'daftar_ulang_terverifikasi';
            $registrant->confirmed_at = now();
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $registrant->payment_status = 'failed';
        } else if ($transactionStatus == 'pending') {
            $registrant->payment_status = 'pending';
        }

        $registrant->save();

        return response()->json(['message' => 'Notification handled successfully'], 200);
    }
}
