<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller {
    public function index(Request $request) {
        $startDate = Carbon::parse($request->get('startDate', '- 1 month'))->startOfDay();
        $endDate = Carbon::parse($request->get('endDate', 'today'))->endOfDay();
        
        $transactions = Transaction::select('id','date','amount','payer','reason','comment')->whereBetween('date', [$startDate, $endDate])->orderByDesc('date')->get();
        $sumBefore = Transaction::where('date', '<', $startDate)->sum('amount');
        $total = $sumBefore + Transaction::where('date', '>=', $startDate)->sum('amount');
        
        return compact('transactions', 'total', 'sumBefore');
    }
    
    public function total() {
        return [
            'total' => Transaction::sum('amount')
        ];
    }
    
    public function store(StoreTransactionRequest $request) {
        return $request->commit();
    }
    
    public function update(UpdateTransactionRequest $request, Transaction $transaction) {
        return $request->commit();
    }
    
    public function destroy(DestroyTransactionRequest $request, Transaction $transaction) {
        return [
            'success' => $request->commit()
        ];
    }
}
