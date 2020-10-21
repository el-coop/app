<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyDebtRequest;
use App\Http\Requests\StoreDebtRequest;
use App\Models\Debt;
use DB;

class DebtController extends Controller {
    public function index() {
        return [
            'debts' => Debt::select('debts.id', 'entity_id as entity', 'project_id as project', 'date', 'currency', 'type', 'amount', 'rate', 'comment',
                DB::raw('IF(invoiced IS NOT NULL, 1, 0) as invoiced'))
                ->orderByDesc('date')
                ->get()->append('nisAmount')
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
