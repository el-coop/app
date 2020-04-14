<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyTransactionRequest extends FormRequest {
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
            //
        ];
    }

    public function commit() {
        $transaction = $this->route('transaction');
        $transaction->attachments->each->delete();
        $transaction->delete();

        return true;
    }
}
