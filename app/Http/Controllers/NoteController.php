<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyNoteRequest;
use App\Http\Requests\StoreNoteRequest;
use App\Models\Entity;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller {
    public function index($entity) {
        return [
            'notes' => Note::where('entity_id', $entity)->get()
        ];
    }

    public function store(StoreNoteRequest $request, Entity $entity) {
        return $request->commit();
    }

    public function update(StoreNoteRequest $request, Entity $entity, Note $note) {
        return $request->commit();
    }

    public function destroy(DestroyNoteRequest $request, Entity $entity, Note $note) {
        return [
            'success' => $request->commit()
        ];
    }


}
