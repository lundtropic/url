<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateURLRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(\Auth::check()){
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'auth' => 'required|exists:google_auths,google_id',
            'urls' => 'required|array',
            'name' => 'string|nullable'
        ];
    }
}
