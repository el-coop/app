<?php

namespace App\Http\Requests;

use App\Models\Debt;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreDebtRequest extends FormRequest {
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
            'project' => 'nullable|exists:projects,id',
            'date' => 'required|date',
            'type' => 'required|in:fixed,hourly',
            'amount' => 'required|numeric',
            'rate' => 'nullable|numeric|min:0',
            'currency' => 'required|in:₪,$,€',
            'comment' => 'string|nullable',
        ];
    }

    public function commit() {
        $debt = $this->route('debt', new Debt);
        $debt->entity_id = $this->get('entity');
        $debt->project_id = $this->get('project', null);
        $debt->date = new Carbon($this->get('date'));
        $debt->type = $this->get('type');
        $debt->amount = $this->get('amount');
        $debt->rate = $this->get('rate');
        $debt->currency = $this->get('currency');
        $debt->comment = $this->get('comment');

        $debt->save();

        return $debt;
    }
}
