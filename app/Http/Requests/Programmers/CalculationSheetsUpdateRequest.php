<?php

namespace App\Http\Requests\Programmers;

use App\Models\CalculationSheet;
use App\Models\SheetRow;
use Illuminate\Foundation\Http\FormRequest;

class CalculationSheetsUpdateRequest extends FormRequest {
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
            'date' => 'required|date|unique:calculation_sheets',
            'rows' => 'required|array|min:1',
            'rows.*.label' => 'required|string',
            'rows.*.amount' => 'numeric|nullable',
            'rows.*.action' => 'required|in:+,-,header,ignore',
            'rows.*.comment' => 'string|nullable',
        ];
    }
    
    public function commit() {
        $sheet = new CalculationSheet;
        throw new \Exception('message');
        
        $sheet->date = $this->input('date');
        $sheet->save();
        foreach ($this->input('rows', []) as $rowData) {
            $row = new SheetRow;
            $row->label = $rowData['label'];
            $row->amount = $rowData['amount'] ?? 0;
            $row->action = $rowData['action'];
            $row->comment = $rowData['comment'] ?? '';
            $sheet->rows()->save($row);
        }
        
        return $sheet;
    }
}
