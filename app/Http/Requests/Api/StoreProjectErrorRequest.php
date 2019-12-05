<?php

namespace App\Http\Requests\Api;

use App\Models\Project;
use App\Models\ProjectError;
use Illuminate\Foundation\Http\FormRequest;
use Log;

class StoreProjectErrorRequest extends FormRequest {
    private $project;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        $this->project = Project::where('token', $this->route('token'))->firstOrFail();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'type' => 'required|in:serverSide,clientSide',
            'url' => 'required|url',
            'message' => 'required|string',
            'exception' => 'required|array',
            'user' => 'nullable|array',
            'extra' => 'nullable|array',
        ];
    }

    public function commit() {
        $error = new ProjectError;

        $error->type = $this->get('type');
        $error->url = $this->get('url');
        $error->message = $this->get('message');
        $error->exception = $this->get('exception');
        $error->user = $this->get('user', []);
        $error->extra_data = $this->get('extra', []);

        $this->project->errors()->save($error);
        return [
            'success' => true
        ];
    }
}
