<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = ['id_car_model', 'lisence_plate', 'available', 'km'];

    public function rules() {
        return [
            'id_car_model' => 'exists:car_models,id' ,
            'linsence_plate' => 'required|unique|min:6|max:7',
            'available' => 'required|boolean',
            'km' => 'required|integer',
        ];
    }

    public function carModel() {
        return $this->belongsTo('App\Models\CarModel', 'id_car_model' );
    }
}
