<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use HasFactory;
    protected $fillable = ['id_customer', 'id_car', 'start_date', 'end_date_expected', 'end_date_accomplished', 'daily_value', 'initial_km', 'final_km'];

    public function car() {
        return $this->belongsTo('App\Models\Car', 'id_car');
    }

    public function customer() {
        return $this->belongsTo('App\Models\Customer', 'id_customer');
    }
    
}
