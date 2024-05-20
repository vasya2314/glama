<?php

namespace App\Http\Controllers\v1\Api;

use App\Facades\YandexDirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ClientRequest;
use App\Http\Resources\v1\ClientResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $user = $request->user();

        if ($user->clients->isNotEmpty())
        {
            $selectionCriteria = [];

            foreach ($user->clients as $client)
            {
                $selectionCriteria['Logins'][] = $client->login;
            }

            $data['SelectionCriteria'] = (object)$selectionCriteria;

            $object = YandexDirect::getAllClients($data);

            if(isset($object->error)) return $this->wrapResponse(Response::HTTP_OK, __('All clients'), (array)$object);

            return $this->wrapResponse(Response::HTTP_OK, __('All clients'), (array)$object->result);
        }

        return $this->wrapResponse(Response::HTTP_OK, __('All clients'), []);
    }

    public function store(ClientRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        $object = YandexDirect::storeClient($data['params']);

        if (isset($object->error)) return $this->wrapResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Error'), (array)$object->error);

        $client = $user->clients()->create($request->storeClient(
            [
                'contract_id' => $data['contract_id'],
                'account_name' => $data['account_name'],
                'login' => $object->result->Login,
                'password' => $object->result->Password,
                'client_id' => $object->result->ClientId,
            ]
        ));

        $clientResource = (new ClientResource($client))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('Client was created successfully.'), $clientResource);
    }

    public function show(ClientRequest $request, Client $client): JsonResponse
    {
        Gate::authorize('view', $client);

        $clientResource = (new ClientResource($client))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('Detail client'), $clientResource);
    }

    public function update(ClientRequest $request, Client $client): JsonResponse
    {
        Gate::authorize('update', $client);

        DB::transaction(function () use ($request, &$client) {
            $client->update($request->updateClient());
        });

        $client = (new ClientResource($client))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('Client updated successfully.'), $client);
    }

    public function updateCampaignsQty(ClientRequest $request, Client $client): JsonResponse
    {
        $result = YandexDirect::getClientCampaignsQty($client->login);

        if(!is_numeric($result))
        {
            return $this->wrapResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Error'), (array)$result);
        }

        $client->qty_campaigns = $result;
        $client->save();

        return $this->wrapResponse(Response::HTTP_OK, __('The company counter has been successfully updated'), ['count' => $result]);

    }

    private function wrapResponse(int $code, string $message, ?array $resource = []): JsonResponse
    {
        $result = [
            'code' => $code,
            'message' => $message
        ];

        $result = array_merge($result, ['resource' => $resource]);

        return response()->json($result, $code);
    }

}
