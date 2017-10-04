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
     * The constructor for Account.
     *
     * @param AccountTransformer $transformer Used to transform the account to a more suitable format.
     */
    public function __construct(AccountTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display the current user account.
     *
     * @return JsonResponse
     */
    public function show(ShowRequest $request): JsonResponse
    {
        return $this->respond($this->transformer->transform(Auth::user()));
    }
}