<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\Client\DestroyRequest;
use App\Http\Requests\Client\IndexRequest;
use App\Http\Requests\Client\ShowRequest;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;

class ClientController extends BaseController
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
        $clients =  new Client();

        if(!$request->has('limit')){
            return $this->respondWithData($this->transformer->transformCollection($clients::all()));
        }
        
        $this->setPagination($request->get('limit'));
        $pagination = $clients->paginate($this->getPagination());

        $clients = $this->transformer->transformCollection($pagination->items());

        return $this->respondWithPagination($pagination, $clients);
    }

    /**
     * The action to get a single user and return it in a JSON response.
     *
     * @param ShowRequest $request The incoming request.
     * @param Client $client The client.
     *
     * @return JsonResponse The JSON response with the client inside it.
     */
    public function show(ShowRequest $request, Client $client): JsonResponse
    {
        return $this->respond(
           // $this->transformer->transform($client->where('id', $user->id)->first())
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
        $client = new Client($request->only(
            [
                'first_name',
                'last_name',
                'phone',
                'email',
                'password',
            ]
        ));
        $client->password = bcrypt($client->password);

        $client->token = str_random(30);

        $client->save();
        return $this->respondCreated('The client has been created');
    }

    /**
     * The action to update a single client.
     *
     * @param UpdateRequest $request The incoming request.
     * @param Client $client The client to be updated.
     *
     * @return JsonResponse The JSON response if the client has been updated.
     */
    public function update(UpdateRequest $request, Client $client): JsonResponse
    {
        $client->first_name = $request->input('first_name');
        $client->last_name = $request->input('last_name');
        $client->phone = $request->input('phone');
        $client->email = $request->input('email');
        $client->save();

        return $this->respond($this->transformer->transform($client->where('id', $client->id)->first()));
    }

    /**
     * The action to delete a single client.
     *
     * @param DestroyRequest $request The incoming request.
     * @param Client $client The client to be deleted.
     *
     * @return JsonResponse The JSON response if the client has been deleted.
     */
    public function destroy(DestroyRequest $request, Client $client): JsonResponse
    {
        $client->delete();

        return $this->respondWithSuccess('The client has been deleted');
    }
}
