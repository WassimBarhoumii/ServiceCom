<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function serviceType() {
        return $this->belongsTo(ServiceType::class);
    }
    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function applications(){
        return $this->hasMany(ServiceApplication::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
