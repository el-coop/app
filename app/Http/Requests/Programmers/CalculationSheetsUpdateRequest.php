<?php

namespace App\Http\Requests\Programmers;

use App\Models\CalculationSheet;
use App\Models\SheetRow;
use Illuminate\Foundation\Http\FormRequest;

class CalculationSheetsUpdateRequest extends FormRequest {
    /**
     * @var \Illuminate\Routing\Route|object|string
     */
    private $sheet;
    
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
        $rules =  [
            'date' => 'required|date|unique:calculation_sheets',
            'rows' => 'required|array|min:1',
            'rows.*.label' => 'required|string',
            'rows.*.amount' => 'numeric|nullable',
            'rows.*.action' => 'required|in:+,-,header,ignore',
            'rows.*.comment' => 'string|nullable',
        ];
        
       if($this->sheet = $this->route('sheet')){
           $rules['date'] .= ",date,{$this->sheet->id}";
       } else {
           $this->sheet = new CalculationSheet;
       }
       
       return $rules;
       
    }
    
    public function commit() {
        
        $this->sheet->date = $this->input('date');
        $this->sheet->save();
        $this->sheet->rows()->delete();
        foreach ($this->input('rows', []) as $rowData) {
            $row = new SheetRow;
            $row->label = $rowData['label'];
            $row->amount = $rowData['amount'] ?? 0;
            $row->action = $rowData['action'];
            $row->comment = $rowData['comment'] ?? '';
            $this->sheet->rows()->save($row);
        }
        
        return $this->sheet;
        
    }
}
