<?php

namespace App\Http\Controllers;

use App\Models\Project;
use DB;
use Illuminate\Http\Request;

class ProjectErrorController extends Controller {
    public function index(Project $project) {
        return $project->errors()->select('created_at', DB::raw("IF(type='serverSide', 'Server Side', 'Client Side') as type"), 'url', 'message', 'exception', 'user', 'extra_data')->get()->groupBy(function ($error) {
            return "{$error->url}_{$error->message}";
        })->map(function ($errors) {
            return [
                'created_at' => $errors->first()->created_at,
                'url' => $errors->first()->url,
                'message' => $errors->first()->message,
                'entries' => $errors
            ];
        })->values();
    }
}
