<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Account\ShowRequest;
use App\Transformers\AccountTransformer;
use Auth;
use Illuminate\Http\JsonResponse;

class AccountController extends BaseController
{

    /**
     * @param ShowRequest $request
     * @return JsonResponse
     */
    public function show(ShowRequest $request): JsonResponse
    {
        return $this->respond($this->transformer->transform(Auth::user()));
    }
}