<?php

namespace App\Http\Requests;

use App\Models\ProjectError;
use Illuminate\Foundation\Http\FormRequest;

class DestroyProjectErrorRequest extends FormRequest {
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
            //
        ];
    }

    public function commit() {
        $projectError = $this->route('projectError');

        ProjectError::where('project_id', $projectError->project_id)
            ->where('url', $projectError->url)
            ->where('message', $projectError->message)
            ->delete();

        return true;
    }
}
