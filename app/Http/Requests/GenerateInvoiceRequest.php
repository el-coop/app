<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'dueDate' => 'required|date|gte:date',
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

    }
}
