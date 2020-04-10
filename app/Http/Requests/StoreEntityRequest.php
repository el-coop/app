<?php

namespace App\Http\Requests;

use App\Models\Entity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEntityRequest extends FormRequest {
    /**
     * @var \Illuminate\Routing\Route|object|string
     */
    private $entity;

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
        $this->entity = $this->route('entity');
        return [
            'name' => ['required', Rule::unique('entities')->ignore($this->entity->id ?? 0)]
        ];
    }

    public function commit() {
        $entity = $this->entity ?? new Entity;

        $entity->name = $this->get('name');
        $entity->save();
        return $entity;
    }
}
