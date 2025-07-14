<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teras extends Model
{
    protected $fillable = ['name', 'description'];

    public function langkah()
    {
        return $this->hasMany(Langkah::class);
    }
}
