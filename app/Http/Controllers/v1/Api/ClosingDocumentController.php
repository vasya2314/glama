<?php

namespace App\Http\Controllers\v1\Api;

use App\Http\Controllers\Controller;
use App\Models\ClosingAct;
use App\Models\ClosingDocument;
use App\Models\ClosingInvoice;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClosingDocumentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        dd($user->closingDocuments);
    }
}
