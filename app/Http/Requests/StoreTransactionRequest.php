<?php

namespace App\Http\Requests;

use App;
use App\Models\Transaction;
use App\Models\TransactionAttachment;
use App\Rules\Currency;
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
            'currency' => ['required', new Currency],
            'rate' => 'nullable|numeric|min:0',
            'comment' => 'string|nullable',
            'attachments' => 'array',
            'attachments.id' => 'integer',
            'attachments.checked' => 'boolean',
            'files' => 'array',
            'files.*' => 'clamav|file|max:10000'
        ];
    }

    public function commit() {
        $transaction = $this->route('transaction', new Transaction);
        $transaction->entity_id = $this->get('entity');
        $transaction->reason = $this->get('reason');
        $transaction->date = new Carbon($this->get('date'));
        $transaction->amount = $this->get('amount');
        $transaction->currency = $this->get('currency');
        $transaction->load('attachments');


        $rate = 1;
        if ($transaction->currency != '₪') {
            if (!$rate = $this->get('rate')) {

                $rate = App::make(CurrencyConverter::class)->getRate($transaction->currency);
            }
        }
        $transaction->rate = $rate;
        $transaction->comment = $this->get('comment');

        $transaction->save();

        collect($this->get('attachments', []))
            ->where('checked', "false")
            ->pluck('id')
            ->each(function ($attachmentId) use ($transaction) {
                if ($realAttachment = $transaction->attachments->firstWhere('id', $attachmentId)) {
                    $realAttachment->delete();
                }
            });

        foreach ($this->file('files', []) as $file) {
            $attachment = new TransactionAttachment;
            $attachment->path = $file->hashName();
            $attachment->name = $file->getClientOriginalName();
            $file->store('transactionAttachments');

            $transaction->attachments()->save($attachment);
        }

        return $transaction->load('attachments:id,transaction_id,name');
    }
}
