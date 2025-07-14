<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $table = 'archives';
    protected $fillable = ['name'];

    public function archivedteras()
    {
        return $this->hasMany(ArchivedTeras::class);
    }
}