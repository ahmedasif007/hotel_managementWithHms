<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasPermission('create_room');
    }

    public function rules()
    {
        return [
            'room_number' => 'required|string|unique:rooms',
            'room_type_id' => 'required|exists:room_types,id',
            'price_per_night' => 'required|numeric|min:0',
            'floor' => 'nullable|integer|min:1',
            'status' => 'in:available,occupied,maintenance,reserved',
            'notes' => 'nullable|string|max:500',
        ];
    }
}
