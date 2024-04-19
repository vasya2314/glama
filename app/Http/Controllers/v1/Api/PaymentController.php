<?php

namespace App\Http\Controllers\v1\Api;

use App\Classes\Tinkoff;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\PaymentRequest;
use App\Models\Contract;
use App\Models\NaturalPerson;
use App\Models\Operation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function deposit(PaymentRequest $request): false|Tinkoff
    {
        $user = $request->user();
        $contract = Contract::findOrFail($request->get('contract_id'));

        if($operation = $user->operations()->create($request->storeOperation('deposit')))
        {
            if($contract->contractable_type == NaturalPerson::class)
            {
                return $this->depositForNaturalPerson($operation, $request);
            }
        }

        return false;

    }

    private function depositForNaturalPerson(Operation $operation, PaymentRequest $request): false|Tinkoff
    {
        $user = $request->user();

        $tinkoff = new Tinkoff(config('tinkoff')['api_url'], config('tinkoff')['terminal'], config('tinkoff')['secret_key']);

        $payment = [
            'OrderId' => $operation->id,
            'Amount' => $request->get('amount'),
            'Language' => 'ru',
            'Description' => 'Покупка GCoins',
            'Email' => $user->email,
            'Phone' => $user->phone,
            'Name' => $user->name,
            'Taxation' => 'usn_income_outcome',
        ];

        $items[] = [
            'Name' => 'Покупка GCoins',
            'Price' => $request->get('amount'),
            'Quantity' => 1,
            'NDS' => 'none',
        ];

        $paymentURL = $tinkoff->paymentURL($payment, $items);

        if(!$paymentURL){
            return $tinkoff;
        }

        if($operation->transaction()->create($request->storeTransaction($tinkoff)))
        {
            return $tinkoff;
        }

        return false;

    }
}
