<?php

namespace App\Http\Controllers;

use App\Models\Debt;

class DebtController extends Controller {
    public function index() {
        return [
            'debts' => Debt::select('id','entity_id','project_id','date','currency','type','amount','invoiced','comment')
                ->with('entity','project')->get()
        ];

    }
}
