<?php

namespace App\Http\Requests;

use App\Models\AppConfiguration;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppConfigurationRequest extends FormRequest {
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
        $rules = [
            'value' => 'required'
        ];

        if (!$this->route('appConfiguration', false)) {
            $rules['key'] = 'required|string';
        }

        return $rules;
    }

    public function commit() {
        $config = $this->route('appConfiguration', new AppConfiguration);
        if (!$config->exists) {
            $config->key = $this->get('key');
        }

        $config->value = $this->get('value');

        $config->save();
        return $config;
    }
}
