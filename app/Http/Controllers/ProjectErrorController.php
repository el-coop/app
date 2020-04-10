<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyProjectErrorRequest;
use App\Models\Project;
use App\Models\ProjectError;
use DB;
use Illuminate\Http\Request;

class ProjectErrorController extends Controller {
    public function index(Project $project) {
        return $project->errors()->select('id','created_at', DB::raw("CASE WHEN type='serverSide' THEN 'Server Side' ELSE 'Client Side' END as type"), 'url', 'message', 'exception', 'user', 'extra_data')->get()->groupBy(function ($error) {
            return "{$error->url}_{$error->message}";
        })->map(function ($errors) {
            return [
                'id' => $errors->first()->id,
                'created_at' => $errors->first()->created_at,
                'url' => $errors->first()->url,
                'message' => $errors->first()->message,
                'entries' => $errors
            ];
        })->values();
    }

    public function destroy(DestroyProjectErrorRequest $request, Project $project, ProjectError $projectError) {
        return [
            'success' => $request->commit()
        ];
    }
}
