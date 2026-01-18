<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasPermission('edit_room');
    }

    public function rules()
    {
        return [
            'status' => 'in:available,occupied,maintenance,reserved',
            'price_per_night' => 'numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ];
    }
}
