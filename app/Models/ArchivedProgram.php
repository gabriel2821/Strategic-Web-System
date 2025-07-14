<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedProgram extends Model
{
    protected $table = 'archived_programs';
    protected $fillable = ['langkah_id', 'program_name', 'archive_id'];

    public function archived_langkah()
    {
        return $this->belongsTo(ArchivedLangkah::class, 'langkah_id'); // langkah_id
    }

    public function programRows()
    {
        return $this->hasMany(ArchivedProgramRow::class, 'program_id'); // program_id
    }
}
