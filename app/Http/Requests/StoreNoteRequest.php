<?php

namespace App\Http\Requests;

use App\Models\Note;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreNoteRequest extends FormRequest {
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
            'title' => 'required|string',
            'content' => 'required|string',
            'x' => 'required|integer',
            'y' => 'required|integer',
        ];

    }

    public function commit() {
        $entity = $this->route('entity');
        $note = $this->route('note', new Note());


        $note->entity_id = $entity->id;
        $note->title = $this->get('title');
        $note->content = $this->get('content');
        $note->x = $this->get('x');
        $note->y = $this->get('y');

        $note->save();

        return $note;

    }
}
