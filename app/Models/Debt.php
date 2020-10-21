<?php

namespace App\Models;

use App;
use App\Services\CurrencyConverter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model {
    use HasFactory;

    public function entity() {
        return $this->belongsTo(Entity::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function getNisAmountAttribute() {

        $rate = App::make(CurrencyConverter::class)->getRate($this->currency);

        return $this->amount * $rate;

    }
}
