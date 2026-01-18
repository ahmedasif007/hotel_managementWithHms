<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class StripePaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for an invoice
     */
    public function createPaymentIntent(Invoice $invoice): array
    {
        try {
            $intent = PaymentIntent::create([
                'amount' => (int) ($invoice->total * 100), // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'invoice_id' => $invoice->id,
                    'reservation_id' => $invoice->reservation_id,
                    'guest_name' => $invoice->reservation->guest->first_name . ' ' . $invoice->reservation->guest->last_name,
                ],
                'description' => "Invoice #{$invoice->invoice_number}",
            ]);

            return [
                'success' => true,
                'client_secret' => $intent->client_secret,
                'intent_id' => $intent->id,
            ];
        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirm payment and record it
     */
    public function confirmPayment(Invoice $invoice, string $paymentIntentId): array
    {
        try {
            $intent = PaymentIntent::retrieve($paymentIntentId);

            if ($intent->status === 'succeeded') {
                // Record payment
                $payment = Payment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $invoice->total,
                    'payment_method' => 'card',
                    'reference_number' => $intent->id,
                    'payment_date' => now(),
                ]);

                // Update invoice status
                $invoice->update(['status' => 'paid']);

                return [
                    'success' => true,
                    'message' => 'Payment successful',
                    'payment' => $payment,
                ];
            }

            return [
                'success' => false,
                'error' => 'Payment intent not successful',
            ];
        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle webhook from Stripe
     */
    public function handleWebhook(array $data): void
    {
        $type = $data['type'];

        match ($type) {
            'payment_intent.succeeded' => $this->handlePaymentSucceeded($data['data']['object']),
            'payment_intent.payment_failed' => $this->handlePaymentFailed($data['data']['object']),
            'charge.refunded' => $this->handleRefund($data['data']['object']),
            default => null,
        };
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentSucceeded(object $intent): void
    {
        $invoiceId = $intent->metadata->invoice_id ?? null;
        if ($invoiceId) {
            $invoice = Invoice::find($invoiceId);
            if ($invoice) {
                $invoice->update(['status' => 'paid']);
            }
        }
    }

    /**
     * Handle failed payment
     */
    private function handlePaymentFailed(object $intent): void
    {
        $invoiceId = $intent->metadata->invoice_id ?? null;
        if ($invoiceId) {
            $invoice = Invoice::find($invoiceId);
            if ($invoice) {
                $invoice->update(['status' => 'pending']);
            }
        }
    }

    /**
     * Handle refund
     */
    private function handleRefund(object $charge): void
    {
        $paymentIntentId = $charge->payment_intent;
        $payment = Payment::where('reference_number', $paymentIntentId)->first();
        if ($payment) {
            $payment->delete();
            $payment->invoice->update(['status' => 'pending']);
        }
    }
}
