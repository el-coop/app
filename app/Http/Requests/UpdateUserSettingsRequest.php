<?php

namespace App\Http\Requests;

use App\Models\InvoiceSetting;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'from' => 'string',
            'nextInvoice' => 'string'
        ];
    }

	public function commit() {
        InvoiceSetting::upsert([
            ['user_id' => $this->user()->id, 'key' => 'from', 'value' => $this->get('from')],
            ['user_id' => $this->user()->id, 'key' => 'nextInvoice', 'value' => $this->get('nextInvoice')],
        ],['user_id','key'],['value']);
	}
}
