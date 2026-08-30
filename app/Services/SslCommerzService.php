<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SslCommerzService
{
    public function isSandboxMode(): bool
    {
        return (bool) config('services.sslcommerz.sandbox', true)
            || empty(config('services.sslcommerz.store_id'))
            || empty(config('services.sslcommerz.store_password'));
    }

    public function initiatePayment(Subscription $subscription): array
    {
        $transactionId = $subscription->transaction_id ?: 'SC-' . strtoupper(Str::random(12));

        $subscription->update([
            'transaction_id' => $transactionId,
            'payment_response' => ['mode' => 'pending'],
        ]);

        $payload = [
            'store_id' => config('services.sslcommerz.store_id'),
            'store_passwd' => config('services.sslcommerz.store_password'),
            'total_amount' => (string) $subscription->amount_bdt,
            'currency' => config('services.sslcommerz.currency', 'BDT'),
            'tran_id' => $transactionId,
            'success_url' => route('student.subscription.success', $subscription),
            'fail_url' => route('student.subscription.fail', $subscription),
            'cancel_url' => route('student.subscription.cancel', $subscription),
            'shipping_method' => 'NO',
            'product_name' => 'Scholarify Premium',
            'product_category' => 'Subscription',
            'product_profile' => 'general',
            'cus_name' => $subscription->user->full_name,
            'cus_email' => $subscription->user->email,
            'cus_add1' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1000',
            'cus_country' => 'Bangladesh',
            'cus_phone' => '01700000000',
            'value_a' => (string) $subscription->subscription_id,
            'value_b' => $subscription->user_id,
            'value_c' => $subscription->duration_months,
            'value_d' => 'scholarify_premium',
        ];

        if ($this->isSandboxMode()) {
            return [
                'mode' => 'sandbox',
                'gateway_url' => null,
                'redirect_url' => route('student.subscription.pay', $subscription),
                'transaction_id' => $transactionId,
                'payload' => $payload,
            ];
        }

        $response = Http::asForm()
            ->withOptions([
                'verify' => (bool) config('services.sslcommerz.verify_ssl', true),
                'timeout' => 30,
            ])
            ->post(config('services.sslcommerz.api_url'), $payload);

        $body = $response->json();

        if (! $response->successful() || ! is_array($body) || ($body['status'] ?? null) !== 'SUCCESS') {
            return [
                'mode' => 'sandbox',
                'gateway_url' => null,
                'redirect_url' => route('student.subscription.pay', $subscription),
                'transaction_id' => $transactionId,
                'payload' => $payload,
                'error' => $body['failedreason'] ?? 'SSLCommerz request failed.',
            ];
        }

        $gatewayUrl = $body['GatewayPageURL'] ?? $body['gateway_url'] ?? null;

        return [
            'mode' => 'live',
            'gateway_url' => $gatewayUrl,
            'redirect_url' => $gatewayUrl ?: route('student.subscription.pay', $subscription),
            'transaction_id' => $transactionId,
            'payload' => $payload,
        ];
    }
}
