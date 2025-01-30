<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    use AuthorizesRequests;

    public function download(Transaction $transaction)
    {
        $amount = '$' . number_format(abs($transaction->amount), 2);
        $type = $transaction->amount < 0 ? 'Expense' : 'Income';

        // Format the date
        $date = date('F j, Y g:i A', strtotime($transaction->created_at));

        $data = [
            'transaction' => $transaction,
            'amount' => $amount,
            'type' => $type,
            'date' => $date
        ];

        $pdf = PDF::loadView('pdfs.receipt', $data);

        return $pdf->download("receipt-{$transaction->transaction_id}.pdf");
    }
}
