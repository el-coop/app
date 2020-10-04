<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyDebtRequest;
use App\Http\Requests\StoreDebtRequest;
use App\Models\Debt;

class DebtController extends Controller {
    public function index() {
        return [
            'debts' => Debt::select('debts.id', 'entity_id as entity', 'project_id as project', 'date', 'currency', 'type', 'amount','rate',     'invoiced', 'comment')
                ->orderByDesc('date')
                ->get()
        ];
    }

    public function store(StoreDebtRequest $request) {
        return $request->commit();
    }

    public function update(StoreDebtRequest $request, Debt $debt) {
        return $request->commit();
    }

    public function destroy(DestroyDebtRequest $request, Debt $debt) {
        return [
            'success' => $request->commit()
        ];
    }
}
