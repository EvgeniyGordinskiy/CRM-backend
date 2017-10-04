<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;

class DestroyRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool If the user is authorized to make this request.
     */
    public function authorize() : bool
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return string[] The validation rules
     */
    public function rules() : array
    {
        return [
            //
        ];
    }
}
