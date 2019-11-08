<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest {
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
            'label' => 'required|string',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'comment' => 'string|nullable',
        ];
    }
    
    public function commit() {
        $transaction = $this->route('transaction');
        $transaction->label = $this->get('label');
        $transaction->date = new Carbon($this->get('date'));
        $transaction->amount = $this->get('amount');
        $transaction->comment = $this->get('comment');
        
        $transaction->save();
        
        return $transaction;
    }
}
