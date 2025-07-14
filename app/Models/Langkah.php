<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Langkah extends Model
{
    protected $table = 'langkah';
    protected $fillable = ['teras_id', 'name', 'description'];

    public function teras()
    {
        return $this->belongsTo(Teras::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class, 'langkah_id'); //langkah_id
    }
}
