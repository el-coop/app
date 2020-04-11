<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateScheduledActionsRequest;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function show(Request $request) {
        $user = $request->user()->load('scheduledActions');
        return [
            'scheduledActions' => $user->scheduledActions->pluck('frequency', 'action')
        ];
    }

    public function updateScheduledAction(UpdateScheduledActionsRequest $request) {
        $request->commit();
        return [
            'success' => 'true'
        ];
    }
}
