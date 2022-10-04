<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image'];

    public function rules() {
        return [
            'name' => 'required|unique:brands,name,'.$this->id.'|min:3',
            'image' => 'required|file|mimes:png,jpeg',
        ];
    }

    public function carModels() {
        // A brand has many models
        return $this->hasMany('App\Models\CarModel', 'id_brand');
    }
}
