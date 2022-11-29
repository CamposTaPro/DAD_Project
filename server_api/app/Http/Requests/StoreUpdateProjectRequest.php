<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name' => 'required|string|max:50',
            'responsible_id' => 'required|exists:users,id',
            'status' => 'required|in:P,W,C,I,D',
            'preview_start_date' => 'nullable|date',
            'preview_end_date' => 'nullable|date|after:preview_start_date',
            'real_start_date' => 'nullable|date',
            'real_end_date' => 'nullable|date|after:real_start_date',
            'billed' => 'required|boolean',
            'total_hours' => 'nullable|integer|min:0',
            'total_price' => 'nullable|numeric|min:0'
        ];
    }
}
