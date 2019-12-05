<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntityRequest;
use App\Models\Entity;
use Illuminate\Http\Request;

class EntityController extends Controller {

    public function index() {
        return [
            'entities' => Entity::select('id', 'name')->with(['projects' => function ($query) {
                $query->select('id', 'entity_id','name', 'token');
            }])->get()
        ];
    }

    public function store(StoreEntityRequest $request) {
        return $request->commit();
    }

    public function update(StoreEntityRequest $request, Entity $entity) {
        return $request->commit();
    }
}
