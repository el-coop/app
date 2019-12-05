<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProjectErrorRequest;
use Illuminate\Http\Request;

class ProjectErrorController extends Controller {

    public function store(StoreProjectErrorRequest $request, $token) {
        return $request->commit();
    }

}
