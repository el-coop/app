<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SheetRow extends Model {
    
    public function sheet() {
        return $this->belongsTo(CalculationSheet::class);
    }
}
