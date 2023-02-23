<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateScheduledActionsRequest;
use App\Http\Requests\UpdateUserSettingsRequest;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function show(Request $request) {
        $user = $request->user()->load('invoiceSettings','scheduledActions');
        return [
            'scheduledActions' => $user->scheduledActions->pluck('frequency', 'action'),
            'invoiceSettings' => $user->invoiceSettings->pluck('value', 'key')
        ];
    }

    public function updateScheduledAction(UpdateScheduledActionsRequest $request) {
        $request->commit();
        return [
            'success' => 'true'
        ];
    }

    public function updateInvoiceSettings(UpdateUserSettingsRequest $request) {
        $request->commit();
        return [
            'success' => 'true'
        ];
    }
}
