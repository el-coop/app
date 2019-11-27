<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntityRequest;
use App\Models\Entity;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    
    public function index() {
        return [
            'entities' => Entity::select('id','name')->get()
        ];
    }
    
    public function store(StoreEntityRequest $request) {
        return $request->commit();
    }
}
