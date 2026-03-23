<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Payment;
use App\Services\SSLCommerzService;
use App\Services\SubscriptionService;
use Illuminate\Support\Str;

class BillingController extends Controller
{
    public function checkout(Request $request, Plan $plan, SSLCommerzService $sslService)
    {
        $store = auth()->user()->store; // Assuming owner
        
        $paymentData = [
            'amount' => $plan->price,
            'currency' => 'BDT',
            'transaction_id' => Str::uuid()->toString(),
            'customer_name' => auth()->user()->name,
            'customer_email' => auth()->user()->email,
            'customer_phone' => auth()->user()->phone ?? '01711111111',
            'plan_name' => $plan->name,
            'store_db_id' => $store->id,
            'plan_db_id' => $plan->id,
        ];

        // Create pending payment record
        Payment::create([
            'store_id' => $store->id,
            'amount' => $plan->price,
            'transaction_id' => $paymentData['transaction_id'],
            'status' => 'pending'
        ]);

        $url = $sslService->initiatePayment($paymentData);

        if ($url) {
            return redirect($url);
        }

        return back()->with('error', 'Could not initiate payment gateway.');
    }

    public function success(Request $request, SSLCommerzService $sslService, SubscriptionService $subService)
    {
        $valId = $request->input('val_id');
        $transId = $request->input('tran_id');

        $validation = $sslService->validateSession($valId);

        if ($validation && $validation['status'] === 'VALID') {
            $payment = Payment::where('transaction_id', $transId)->firstOrFail();
            
            if ($payment->status === 'pending') {
                $payment->update([
                    'status' => 'completed',
                    'payment_method' => $validation['card_type'] ?? 'Unknown',
                    'details' => json_encode($validation),
                ]);

                // Grant subscription
                $store = \App\Models\Store::find($validation['value_a']);
                $plan = Plan::find($validation['value_b']);
                
                $subscription = $subService->changePlan($store, $plan);

                $payment->update(['subscription_id' => $subscription->id]);
            }

            return redirect()->route('admin.dashboard')->with('success', 'Plan upgraded successfully!');
        }

        return redirect()->route('admin.dashboard')->with('error', 'Payment validation failed.');
    }

    public function fail(Request $request)
    {
        return redirect()->route('admin.dashboard')->with('error', 'Payment failed or cancelled.');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('admin.dashboard')->with('warning', 'Payment cancelled.');
    }

    public function ipn(Request $request)
    {
        // Handle server-to-server callback silently
        return response()->json(['status' => 'received']);
    }
}
