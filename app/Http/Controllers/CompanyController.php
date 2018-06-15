<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Fetch all users companies.
     * @param $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($user)
    {
        $companies = Company::whereUserId($user->id)->first();
        return CompanyResource::collection($companies);
    }

}
