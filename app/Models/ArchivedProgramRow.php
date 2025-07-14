<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedProgramRow extends Model
{
    protected $table = 'archived_program_rows';
    protected $fillable = [
        'program_id', //program_id
        'inisiatif',
        'peneraju_utama',
        'tahun_mula_siap',
        'petunjuk_prestasi',
        'pencapaian',
        'status',
        'completion',
        'archive_id'
    ];

    public function programs()
    {
        return $this->belongsTo(ArchivedProgram::class, 'program_id'); // program_id
    }
}
