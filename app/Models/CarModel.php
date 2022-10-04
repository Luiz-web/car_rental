<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;
    protected $fillable = ['id_brand', 'name', 'image', 'number_doors', 'seats', 'air_bag', 'abs'];

    public function rules() {
        return [
            'id_brand' => 'exists:brands,id',
            'name' => 'required|unique:car_models,name,'.$this->id.'|min:3',
            'image' => 'required|file|mimes:jpeg,png,',
            'number_doors' => 'required|integer|min:2|max:8',
            'seats' => 'required|integer|min:2|max:8',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }

    public function brand() {
        // One model belongs to a brand
        return $this->belongsTo('App\Models\Brand', 'id_brand');
    }

    public function cars() {
        return $this->hasMany('App\Models\Car', 'id_car_model');
    }
}
