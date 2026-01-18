<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasPermission('record_payment');
    }

    public function rules()
    {
        return [
            'reservation_id' => 'required|exists:reservations,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,online,bank_transfer',
            'reference_number' => 'nullable|string|max:100',
        ];
    }
}
