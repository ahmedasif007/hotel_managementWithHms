<?php

namespace App\Services;

use App\Models\Reservation;
use Twilio\Rest\Client;
use Exception;

class TwilioSmsService
{
    protected $client;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->client = new Client($sid, $token);
    }

    /**
     * Send booking confirmation SMS
     */
    public function sendBookingConfirmation(Reservation $reservation): bool
    {
        $guest = $reservation->guest;
        $room = $reservation->room;

        $message = "Hi {$guest->first_name}, your reservation at Hotel Management System has been confirmed! Room: {$room->room_number}, Check-in: {$reservation->check_in_date->format('M d, Y')}. Thank you!";

        return $this->sendSms($guest->phone_number, $message);
    }

    /**
     * Send check-in reminder SMS
     */
    public function sendCheckInReminder(Reservation $reservation): bool
    {
        $guest = $reservation->guest;
        $room = $reservation->room;

        $message = "Hi {$guest->first_name}, reminder: You have a check-in today at {$reservation->check_in_date->format('g:i A')}. Room {$room->room_number} is ready for you. Safe travels!";

        return $this->sendSms($guest->phone_number, $message);
    }

    /**
     * Send payment confirmation SMS
     */
    public function sendPaymentConfirmation(Reservation $reservation): bool
    {
        $guest = $reservation->guest;
        $invoice = $reservation->invoice;

        $message = "Hi {$guest->first_name}, payment of \${$invoice->total} has been received. Thank you for staying with us!";

        return $this->sendSms($guest->phone_number, $message);
    }

    /**
     * Send generic SMS
     */
    public function sendSms(string $phoneNumber, string $message): bool
    {
        try {
            $this->client->messages->create(
                $phoneNumber,
                [
                    'from' => config('services.twilio.from'),
                    'body' => $message,
                ]
            );

            return true;
        } catch (Exception $e) {
            \Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk SMS to multiple numbers
     */
    public function sendBulkSms(array $phoneNumbers, string $message): int
    {
        $sent = 0;
        foreach ($phoneNumbers as $phoneNumber) {
            if ($this->sendSms($phoneNumber, $message)) {
                $sent++;
            }
        }
        return $sent;
    }
}
