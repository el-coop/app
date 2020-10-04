<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller {
    public function index() {
        return [
            'projects' => Project::select('id','entity_id as entity','name')->get()
        ];
    }
}
