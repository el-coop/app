<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller {
    public function index() {
        $transactions = Transaction::where('date', '>', Carbon::parse('30 days ago'))->orderByDesc('date')->get();
        $total = Transaction::sum('amount');
        
        return compact('transactions', 'total');
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
