<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Models\TransactionFilter;
use App\Http\Requests\v1\TransactionRequest;
use App\Http\Resources\v1\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function index(TransactionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        $filter = app()->make(TransactionFilter::class, ['queryParams' => array_filter($data)]);

        $transactions = TransactionResource::collection($user->transactions()->filter($filter)->paginate(12))
            ->response()
            ->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('All transactions'), (array)$transactions);
    }

    public function getRefills(Request $request): JsonResponse
    {
        $user = $request->user();

        $amounts = $user->transactions()->whereIn('type', Transaction::getRefillsTypes())->where('status', 'CONFIRMED')->pluck('amount_deposit');
        return $this->wrapResponse(Response::HTTP_OK, __('Ok'), ['amount' => kopToRub((int)array_sum($amounts->toArray()))]);
    }

    public function getRemovals(Request $request): JsonResponse
    {
        $user = $request->user();

        $amounts = $user->transactions()->whereIn('type', Transaction::getRemovalTypes())->where('status', 'CONFIRMED')->pluck('amount');
        return $this->wrapResponse(Response::HTTP_OK, __('Ok'), ['amount' => kopToRub((int)array_sum($amounts->toArray()))]);
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
