<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
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
     * @return string[] The validation rules
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'unique:users|required|email',
            'timeZone' => 'required|string',
            'password' => 'required|confirmed',
        ];
    }

    public function messages()
    {
        $messages = [
            'email.required' => 'The :attribute field is required.',
        ];

        return $messages;
    }
}
