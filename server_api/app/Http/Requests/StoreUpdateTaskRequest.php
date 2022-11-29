<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTaskRequest extends FormRequest
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
            'owner_id' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'completed' => 'required|boolean',
            'description' => 'required|max:50|min:3',
            'notes' => 'nullable',
            'total_hours' => 'nullable|integer|min:0|max:100'
        ];
    }
}
