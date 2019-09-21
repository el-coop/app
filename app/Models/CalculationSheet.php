<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalculationSheet extends Model {
    
    public function rows(){
        return $this->hasMany(SheetRow::class);
    }
}
