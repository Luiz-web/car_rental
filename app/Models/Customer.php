<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function rules() {
        return [
            'name' => 'required|unique:customers,name,'.$this->id.'|min:3|max:50',
        ];
    }
}
