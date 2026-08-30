<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\SslCommerzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function __construct(private SslCommerzService $sslCommerz) {}

    public function index()
    {
        $user    = Auth::user();
        $pricing = Subscription::PRICING;
        $history = $user->subscriptions()->latest('subscription_id')->take(5)->get();

        return view('student.subscription.index', compact('user', 'pricing', 'history'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'duration_months' => ['required', 'in:1,3,6,12'],
            'payment_method'  => ['required', 'in:sslcommerz,bkash,nagad'],
        ]);

        $months  = (int) $validated['duration_months'];
        $pricing = Subscription::PRICING[$months];
        $user    = Auth::user();

        $sub = Subscription::create([
            'user_id'         => $user->user_id,
            'plan'            => 'premium',
            'duration_months' => $months,
            'amount_bdt'      => $pricing['bdt'],
            'payment_method'  => $validated['payment_method'],
            'transaction_id'  => null,
            'status'          => 'pending',
        ]);

        if ($validated['payment_method'] !== 'sslcommerz') {
            return redirect()->route('student.subscription.pay', $sub)
                ->with('info', 'This gateway is in fallback demo mode. You can still complete the payment flow to activate premium.');
        }

        $gateway = $this->sslCommerz->initiatePayment($sub);

        if (! empty($gateway['error'])) {
            $sub->update(['status' => 'failed', 'payment_response' => ['error' => $gateway['error']]]);

            return redirect()->route('student.subscription.index')
                ->with('error', 'SSLCommerz could not start the payment session. Please try again or contact support.');
        }

        $sub->update(['payment_response' => $gateway]);

        if (! empty($gateway['gateway_url'])) {
            return redirect()->away($gateway['gateway_url']);
        }

        return redirect()->route('student.subscription.pay', $sub);
    }

    public function pay(Subscription $subscription)
    {
        abort_if($subscription->user_id !== Auth::id(), 403);

        return view('student.subscription.pay', compact('subscription'));
    }

    public function success(Request $request, Subscription $subscription)
    {
        abort_if($subscription->user_id !== Auth::id(), 403);

        $transactionId = $request->query('tran_id', $request->input('tran_id', $subscription->transaction_id));

        if ($transactionId && $subscription->transaction_id && $subscription->transaction_id !== $transactionId) {
            $subscription->update(['status' => 'failed']);

            return redirect()->route('student.subscription.index')
                ->with('error', 'This payment transaction does not match the active subscription.');
        }

        if ($subscription->status === 'completed') {
            return redirect()->route('student.subscription.index')
                ->with('success', 'Your Premium plan is already active.');
        }

        $durationMonths = (int) $subscription->duration_months;

        $subscription->update([
            'status'         => 'completed',
            'transaction_id' => $transactionId ?: $subscription->transaction_id ?: 'TXN-' . strtoupper(Str::random(10)),
            'starts_at'      => now(),
            'expires_at'     => now()->addMonths($durationMonths),
            'payment_response' => array_merge((array) $subscription->payment_response, [
                'callback' => [
                    'status' => 'success',
                    'at' => now()->toDateTimeString(),
                    'gateway' => 'sslcommerz',
                ],
            ]),
        ]);

        $subscription->user->update([
            'plan'           => 'premium',
            'plan_expires_at'=> $subscription->expires_at,
        ]);

        return redirect()->route('student.subscription.index')
            ->with('success', "🎉 You're now a Premium member! Enjoy unlimited recommendations and all filters.");
    }

    public function fail(Request $request, Subscription $subscription)
    {
        abort_if($subscription->user_id !== Auth::id(), 403);

        $subscription->update([
            'status' => 'failed',
            'payment_response' => array_merge((array) $subscription->payment_response, [
                'callback' => [
                    'status' => 'failed',
                    'message' => $request->query('status', 'Payment failed'),
                    'at' => now()->toDateTimeString(),
                ],
            ]),
        ]);

        return redirect()->route('student.subscription.index')
            ->with('error', 'The payment was not completed. Please try again.');
    }

    public function cancel(Subscription $subscription)
    {
        abort_if($subscription->user_id !== Auth::id(), 403);
        $subscription->update([
            'status' => 'cancelled',
            'payment_response' => array_merge((array) $subscription->payment_response, [
                'callback' => [
                    'status' => 'cancelled',
                    'at' => now()->toDateTimeString(),
                ],
            ]),
        ]);

        return redirect()->route('student.subscription.index')
            ->with('error', 'Payment was cancelled. You can try again anytime.');
    }
}
