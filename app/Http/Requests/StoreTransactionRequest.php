<?php

namespace App\Http\Requests;

use App;
use App\Models\Transaction;
use App\Services\CurrencyConverter;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'entity' => 'required|exists:entities,id',
            'reason' => 'required|string',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'currency' => 'required|in:₪,$,€',
            'rate' => 'nullable|numeric|min:0',
            'comment' => 'string|nullable',
        ];
    }
    
    public function commit() {
        $transaction = $this->route('transaction', new Transaction);
        $transaction->entity_id = $this->get('entity');
        $transaction->reason = $this->get('reason');
        $transaction->date = new Carbon($this->get('date'));
        $transaction->amount = $this->get('amount');
        $transaction->currency = $this->get('currency');
        
        
        $rate = 1;
        if ($transaction->currency != '₪') {
            if (!$rate = $this->get('rate')) {
                
                $rate = App::make(CurrencyConverter::class)->getRate($transaction->currency);
            }
        }
        $transaction->rate = $rate;
        $transaction->comment = $this->get('comment');
        
        $transaction->save();
        
        return $transaction;
        
        return $transaction;
    }
}
