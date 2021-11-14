<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyEntityRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

	public function commit() {
        $entity = $this->route('entity');
        $entity->debts()->delete();
        $entity->transactions()->delete();
        $entity->projects()->delete();
        $entity->delete();

        return true;
	}
}
