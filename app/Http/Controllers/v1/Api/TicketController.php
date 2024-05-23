<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TicketRequest;
use App\Http\Resources\v1\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $tickets = $user->tickets()->paginate(1);
        $tickets = TicketResource::collection($tickets)->response()->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('All tickets.'), (array)$tickets);
    }

    public function store(TicketRequest $request): JsonResponse
    {
        $user = $request->user();
        $message = $request['message'];
        $ticket = null;

        DB::transaction(function () use ($user, $request, $message, &$ticket) {
            $ticket = $user->tickets()->create($request->storeValidatedData());
            $message = $user->messages()->create($request->storeMessage($ticket, $message));

            $message->uploadFiles($request, 'files');
        });

        $ticket = (new TicketResource($ticket))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_CREATED, __('Ticket created successfully.'), $ticket);
    }

    public function show(Ticket $ticket): JsonResponse
    {
        Gate::authorize('view', $ticket);

        $ticketResource = (new TicketResource($ticket))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('Detail ticket'), $ticketResource);
    }

    /**
     * @throws \ErrorException
     */
    public function changeStatus(TicketRequest $request, Ticket $ticket): JsonResponse
    {
        Gate::authorize('changeStatus', $ticket);

        if ($ticket->update($request->validated())) {
            $ticket = (new TicketResource($ticket))
                ->response()
                ->getData(true);

            return $this->wrapResponse(Response::HTTP_OK, __('The ticket updated successfully.'), $ticket);
        }

        throw new \ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @throws \ErrorException
     */
    public function assignedTo(TicketRequest $request, Ticket $ticket): JsonResponse
    {
        if ($ticket->update($request->validated())) {
            $ticket = (new TicketResource($ticket))
                ->response()
                ->getData(true);

            return $this->wrapResponse(Response::HTTP_OK, __('The ticket updated successfully.'), $ticket);
        }

        throw new \ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @throws \ErrorException
     */
    public function delete(Ticket $ticket): JsonResponse
    {
        Gate::authorize('delete', $ticket);

        if($ticket->delete()) return $this->wrapResponse(Response::HTTP_OK, __('The ticket deleted successfully.'));

        throw new \ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function wrapResponse(int $code, string $message, ?array $resource = []): JsonResponse
    {
        $result = [
            'code' => $code,
            'message' => $message
        ];

        if (count($resource)) $result = array_merge($result, ['resource' => $resource]);

        return response()->json($result, $code);
    }
}
