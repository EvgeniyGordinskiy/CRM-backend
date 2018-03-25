<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DestroyRequest;
use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\ShowRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Permission;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{

    /**
     * The action to get all the clients and paginate the request.
     *
     * @param IndexRequest $request The incoming request.
     *
     * @return JsonResponse The paginated clients in a JSON response.
     */
    public function index(Request $request)
    {
        $users =  new User;

        if(!$request->has('limit')){
            return $this->respondWithData($this->transformer->transformCollection($users::all()));
        }
        $this->setPagination($request->get('limit'));
        $pagination = $users->paginate($this->getPagination());

        $users = $this->transformer->transformCollection($pagination->items());

        return $this->respondWithPagination($pagination, $users);
    }

    /**
     * The action to get a single user and return it in a JSON response.
     *
     * @param ShowRequest $request The incoming request.
     * @param User $user The client.
     *
     * @return JsonResponse The JSON response with the client inside it.
     */
    public function show(ShowRequest $request, User $user): JsonResponse
    {
        return $this->respond(
            $this->transformer->transform($user->where('id', $user->id)->first())
        );
    }

    /**
     * The action to store a single client.
     *
     * @param StoreRequest $request The incoming request with data.
     *
     * @return JsonResponse The JSON response if the client has been created.
     */
    public function store(StoreRequest $request): JsonResponse
    {
       $user = new User($request->only(
            [
                'first_name',
                'last_name',
                'timeZone',
                'email',
                'password',
            ]
        ));
        $user->password = bcrypt($user->password);

        $user->token = str_random(30);

        $user->save();
        return $this->respondCreated('The client has been created');
    }

    /**
     * The action to update a single client.
     *
     * @param UpdateRequest $request The incoming request.
     * @param User $user The client to be updated.
     *
     * @return JsonResponse The JSON response if the client has been updated.
     */
    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->timeZone = $request->input('timeZone');
        $user->email = $request->input('email');
        $user->save();

        return $this->respond($this->transformer->transform($user->where('id', $user->id)->first()));
    }

    /**
     * The action to delete a single client.
     *
     * @param DestroyRequest $request The incoming request.
     * @param User $client The client to be deleted.
     *
     * @return JsonResponse The JSON response if the client has been deleted.
     */
    public function destroy(DestroyRequest $request, User $user): JsonResponse
    {
        $user->delete();

        return $this->respondWithSuccess('The client has been deleted');
    }
}
