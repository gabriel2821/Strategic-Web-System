<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedTeras extends Model
{
    protected $table = 'archived_teras';
     protected $fillable = ['name', 'description', 'archive_id'];

    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    public function langkah()
    {
        return $this->hasMany(ArchivedLangkah::class, 'teras_id');
    }
}
