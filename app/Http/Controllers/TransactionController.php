<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Models\TransactionAttachment;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Storage;

class TransactionController extends Controller {
    public function index(Request $request) {
        $startDate = Carbon::parse($request->get('startDate', '- 1 month'))->startOfDay();
        $endDate = Carbon::parse($request->get('endDate', 'today'))->endOfDay();

        $transactions = Transaction::select('transactions.id', 'date', 'amount', 'currency', 'rate', 'entity_id as entity', 'reason', 'comment', DB::raw('CAST(CONCAT("[",GROUP_CONCAT(CONCAT("{\"id\": ",transaction_attachments.id, ",\"name\":\"", transaction_attachments.name,"\"}")),"]") AS JSON) as attachments'))
            ->whereBetween('date', [$startDate, $endDate])
            ->leftJoin('transaction_attachments', 'transaction_attachments.transaction_id', 'transactions.id')
            ->orderByDesc('date')
            ->groupBy('transactions.id')
            ->get();

        $sumBefore = Transaction::where('date', '<', $startDate)->sum(DB::raw('amount * rate'));
        $total = $sumBefore + Transaction::where('date', '>=', $startDate)->sum(DB::raw('amount * rate'));

        return compact('transactions', 'total', 'sumBefore');
    }

    public function total() {
        return [
            'total' => Transaction::sum(DB::raw('amount * rate'))
        ];
    }

    public function store(StoreTransactionRequest $request) {
        return $request->commit();
    }

    public function update(StoreTransactionRequest $request, Transaction $transaction) {
        return $request->commit();
    }

    public function destroy(DestroyTransactionRequest $request, Transaction $transaction) {
        return [
            'success' => $request->commit()
        ];
    }

    public function showAttachment(TransactionAttachment $attachment) {
        return Storage::download("transactionAttachments/{$attachment->path}",$attachment->name);
    }
}
