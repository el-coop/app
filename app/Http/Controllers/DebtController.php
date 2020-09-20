<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;

class DebtController extends Controller {
    public function index() {
        return [
            'debts' => Entity::select('id', 'name')->with('debts')->get()
        ];

    }
}
