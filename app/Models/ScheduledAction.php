<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledAction extends Model {
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getAsCommandAttribute() {
        switch ($this->action) {
            case 'backupDatabase':
                return "db:backup " . $this->user->email;
                break;
        }

        return null;
    }
}
