<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class TransactionController extends Controller {
    public function index(Request $request) {
        $startDate = Carbon::parse($request->get('startDate', '- 1 month'))->startOfDay();
        $endDate = Carbon::parse($request->get('endDate', 'today'))->endOfDay();
        
        $transactions = Transaction::select('transactions.id', 'date', 'amount', 'currency', 'rate', DB::raw('entities.id as entity'), 'reason', 'comment')->whereBetween('date', [$startDate, $endDate])->join('entities','entities.id','transactions.entity_id')->orderByDesc('date')->get();
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
}
