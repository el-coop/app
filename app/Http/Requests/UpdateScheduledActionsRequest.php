<?php

namespace App\Http\Requests;

use App\Models\ScheduledAction;
use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduledActionsRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'action' => 'required|in:backupDatabase',
            'frequency' => 'nullable|string|cron'
        ];
    }

    public function commit() {
        $action = $this->user()->scheduledActions()->where('action', $this->get('action'))->first();
        $frequency = $this->get('frequency', false);


        if (!$action) {
            if (!$frequency) {
                return;
            }
            $action = new ScheduledAction;
            $action->action = $this->get('action');
            $action->user_id = $this->user()->id;
        }

        if (!$frequency) {
            $action->delete();
            return;
        }

        $action->frequency = $frequency;
        $action->save();
    }
}
