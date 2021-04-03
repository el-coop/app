<?php

namespace App\Http\Requests;

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
            'currency' => 'required|in:₪,$,€',
            'markBilled' => 'required|boolean',
            'from' => 'required|string|min:1',
            'to' => 'required|string|min:1',
            'invoiceNumber' => 'required|numeric|min:1',
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

        $invoice = Http::post('https://invoice-generator.com', [
            'from' => $this->get('from'),
            'to' => $this->get('to'),
            'number' => $this->get('invoiceNumber'),
            'date' => Carbon::parse($this->get('date'))->format('M d, Y'),
            'due_date' => Carbon::parse($this->get('due_date'))->format('M d, Y'),
            'notes' => $this->get('notes'),
            'items' => $items
        ]);

        return $invoice->body();
    }
}
