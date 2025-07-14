<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedLangkah extends Model
{
    protected $table = 'archived_langkah';
    protected $fillable = ['teras_id', 'name', 'description', 'archive_id'];

    public function teras()
    {
        return $this->belongsTo(ArchivedTeras::class);
    }

    public function programs()
    {
        return $this->hasMany(ArchivedProgram::class, 'langkah_id'); // langkah_id
    }
}
