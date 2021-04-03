<?php

namespace App\Http\Requests;

use App\Mail\SmartechInvoiceEmail;
use App\Models\Debt;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Mail;

class GenerateSmartechInvoiceEmailRequest extends GenerateInvoiceRequest {
    // Class inherits validation from GenerateInvoiceRequest
    public function rules() {
        $rules = parent::rules();

        unset($rules['date']);
        unset($rules['dueDate']);
        unset($rules['invoiceNumber']);
        unset($rules['from']);

        return $rules;
    }

    public function commit() {
        $invoice = (object)$this->except('markBilled');
        $invoice->total = collect($invoice->items)->reduce(function($carry, $item) {
            return $carry + ($item['amount'] * $item['rate']);
        }, 0);
        $user = $this->user();

        if ($this->get('markBilled')) {
            $debts = [];
            foreach ($invoice->items as $item) {
                array_push($debts, ...($item['debts'] ?? []));
            }

            if (count($debts)) {
                Debt::whereIn('id', $debts)->update(['invoiced' => Carbon::now()]);
            }
        }

        Mail::to($user)
            ->queue(new SmartechInvoiceEmail($invoice, $user->name));
    }
}
