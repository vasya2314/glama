<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ClosingDocumentRequest;
use App\Http\Requests\v1\UserRequest;
use App\Http\Resources\v1\ClosingDocumentResource;
use App\Models\ClosingAct;
use App\Models\ClosingDocument;
use App\Models\ClosingInvoice;
use App\Models\Contract;
use App\Models\PaymentClosingInvoice;
use App\Models\Transaction;
use http\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ClosingDocumentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $closingDocuments = $user->closingDocuments()->latest()->paginate(20);
        $closingDocuments = ClosingDocumentResource::collection($closingDocuments)->response()->getData(true);

        return $this->wrapResponse(Response::HTTP_OK, __('All closing documents.'), (array)$closingDocuments);
    }

    public function getClosingAct(ClosingDocumentRequest $request): BinaryFileResponse
    {
        $data = $request->validated();
        $closingAct = ClosingAct::findOrFail($data['closing_act_id']);

        return response()->file($closingAct->path);
    }

    public function getClosingInvoice(ClosingDocumentRequest $request): BinaryFileResponse
    {
        $data = $request->validated();
        $closingInvoice = ClosingInvoice::findOrFail($data['closing_invoice_id']);

        return response()->file($closingInvoice->path);
    }

    public static function generateClosingActPdf(array $params): void
    {
        $contract = $params['contract'];
        $closingInvoice = $params['closingInvoice'];
        $closingAct = $params['closingAct'];
        $closingDocument = $params['closingDocument'];

        $logo = base64_encode(file_get_contents(public_path('storage/static/glama_logo.png')));
        $template = view('pdf.closing-act', compact('closingAct', 'logo', 'closingDocument', 'closingInvoice', 'contract'))->render();

        $output = self::initDomPdf($template);
        $fileName = str_replace('/', '_', $closingAct->act_number) . '_Act.pdf';
        $path = resource_path('closing-documents/closing-acts/' . $fileName);

        if(file_put_contents($path, $output))
        {
            $closingAct->update(
                [
                    'path' => $path,
                ]
            );
        }
    }

    public static function generateClosingInvoicePdf(array $params): void
    {
        $contract = $params['contract'];
        $closingInvoice = $params['closingInvoice'];
        $closingAct = $params['closingAct'];
        $closingDocument = $params['closingDocument'];

        $paymentClosingInvoices = PaymentClosingInvoice::where('contract_id', $contract->id)->where('amount', '>', 0)->orderBy('created_at', 'ASC')->get();
        $needTransactionIds = self::getNeedTransactions($paymentClosingInvoices, $closingInvoice->amount);
        $transactions = Transaction::select(['id', 'created_at'])->whereIn('id', $needTransactionIds)->get();

        $template = view('pdf.closing-invoice', compact('closingInvoice','closingDocument', 'closingAct', 'contract', 'transactions'))->render();

        $output = self::initDomPdf($template);
        $fileName = $closingInvoice->id . '_Invoice.pdf';
        $path = resource_path('closing-documents/closing-invoices/' . $fileName);

        if(file_put_contents($path, $output))
        {
            $closingInvoice->update(
                [
                    'path' => $path,
                ]
            );
        }
    }

    public static function initDomPdf(string $template)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option('enable_php', true);
        $pdf->loadHTML($template);
        $pdf->render();

        return $pdf->output();
    }

    /**
     * @throws \Exception
     */
    public static function getNeedTransactions(Collection $paymentClosingInvoices, int $amount): array
    {
        $list = [];
        $number = $amount;

        if($paymentClosingInvoices->isNotEmpty())
        {
            $paymentClosingInvoices->each(function ($item) use (&$number, &$list)
            {
                $res = (int)$item->amount - $number;

                if($res <= 0)
                {
                    $item->amount = 0;
                    $list[] = $item->transaction_id;
                    $number = abs($res);
                    $item->save();
                } else {
                    $list[] = $item->transaction_id;
                    $item->amount = $res;
                    $item->save();

                    return false;
                }
            });

            return $list;
        }

        throw new \Exception('Нет свободных транзакции на которых есть баланс');

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
