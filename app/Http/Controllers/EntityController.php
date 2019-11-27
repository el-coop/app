<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    
    public function index() {
        return [
            'entities' => Entity::select('id','name')->get()
        ];
    }
}
