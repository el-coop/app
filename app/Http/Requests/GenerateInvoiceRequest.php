<?php

namespace App\Http\Requests;

use App\Models\Debt;
use App\Models\InvoiceSetting;
use App\Rules\Currency;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Http;
use Storage;

class GenerateInvoiceRequest extends FormRequest {
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
            'currency' => ['required', new Currency],
            'markBilled' => 'required|boolean',
            'from' => 'required|string|min:1',
            'to' => 'required|string|min:1',
            'invoiceNumber' => 'required|min:1',
            'date' => 'required|date',
            'dueDate' => 'nullable|date|gte:date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.comment' => 'required|string|min:1',
            'items.*.debts' => 'nullable|array',
            'items.*.debts.*' => 'integer',
        ];
    }

    public function commit() {
        $items = collect($this->get('items'))->map(function($item) {
            return [
                'name' => $item['comment'],
                'quantity' => $item['amount'],
                'unit_cost' => $item['rate']
            ];
        });

        switch ($this->get('currency')) {
            case '$':
                $currency = 'USD';
                break;
            case '€':
                $currency = 'EUR';
                break;
            default:
                $currency = 'ILS';
        }

        $invoice = Http::post('https://invoice-generator.com', [
            'from' => $this->get('from'),
            'to' => $this->get('to'),
            'currency' => $currency,
            'number' => $this->get('invoiceNumber'),
            'date' => Carbon::parse($this->get('date'))->format('M d, Y'),
            'due_date' => Carbon::parse($this->get('due_date'))->format('M d, Y'),
            'notes' => $this->get('notes'),
            'items' => $items
        ]);

        if(! $invoice->successful()){
            abort(500,'Invoice Generator Error');
        }


        if ($this->get('markBilled')) {
            $this->markBilled($this->get('items'));
        }

        InvoiceSetting::upsert([
            ['user_id' => $this->user()->id, 'key' => 'nextInvoice', 'value' => $this->get('invoiceNumber')],
        ],['user_id','key'],['value']);


        return $invoice->body();
    }

    private function markBilled($items) {
        $debts = [];
        foreach ($items as $item) {
            array_push($debts, ...($item['debts'] ?? []));
        }

        if (count($debts)) {
            Debt::whereIn('id', $debts)->update(['invoiced' => Carbon::now()]);
        }
    }
}
