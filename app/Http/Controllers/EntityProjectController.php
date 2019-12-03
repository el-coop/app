<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Entity;
use App\Models\Project;
use Illuminate\Http\Request;

class EntityProjectController extends Controller {
    public function store(StoreProjectRequest $request, Entity $entity) {
        return $request->commit();
    }

    public function update(StoreProjectRequest $request, Entity $entity, Project $project) {
        return $request->commit();
    }

    public function destroy(DestroyProjectRequest $request, Entity $entity, Project $project) {
        return [
            'success' => $request->commit()
        ];
    }
}
