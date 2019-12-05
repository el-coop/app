<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreProjectRequest extends FormRequest {
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
            'name' => 'required'
        ];
    }

    public function commit() {
        $entity = $this->route('entity');
        $project = $this->route('project', new Project);

        $project->name = $this->get('name');
        $project->entity_id = $entity->id;
        if (!$project->token) {
            $project->token = Str::random(80);
        }
        $project->save();

        return $project;
    }
}
