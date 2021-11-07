<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller {

    public function csrf() {
        return [
            'csrfToken' => csrf_token()
        ];
    }

    public function any() {
        return view('spa');
    }
}
