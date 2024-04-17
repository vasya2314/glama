<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\MessageRequest;
use App\Http\Resources\v1\MessageResource;
use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    public function index(Request $request, Ticket $ticket): JsonResponse
    {
        Gate::authorize('viewAny', [Message::class, $ticket]);
        $messages = $ticket->messages()->latest()->paginate(100);
        $messages = MessageResource::collection($messages)->response()->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('All messages'), (array)$messages);
    }

    /**
     * @throws \ErrorException
     */
    public function store(MessageRequest $request, Ticket $ticket): JsonResponse
    {
        Gate::authorize('create', [Message::class, $ticket]);
        $user = $request->user();

        if ($message = $user->messages()->create($request->storeValidatedData())) {
            $message = (new MessageResource($message))
                ->response()
                ->getData(true);

            $message->uploadFiles($request, 'files');

            return $this->wrapResponse(Response::HTTP_CREATED, __('Message created successfully.'), $message);
        }

        throw new \ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @throws \ErrorException
     */
    public function update(MessageRequest $request, Message $message): JsonResponse
    {
        Gate::authorize('update', $message);

        if ($message->update($request->validated())) {
            $message = (new MessageResource($message))
                ->response()
                ->getData(true);

            return $this->wrapResponse(Response::HTTP_OK, __('Message updated successfully.'), $message);
        }

        throw new \ErrorException(__('Failed to get service, please try again.'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @throws \ErrorException
     */
    public function delete(Message $message): JsonResponse
    {
        Gate::authorize('delete', $message);

        if($message->delete()) return $this->wrapResponse(Response::HTTP_OK, __('Message deleted successfully.'));

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
