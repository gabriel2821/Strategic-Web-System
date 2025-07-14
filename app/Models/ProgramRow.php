<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramRow extends Model
{
    protected $primaryKey = 'row_id';
    protected $fillable = [
        'program_id', //program_id
        'inisiatif',
        'peneraju_utama',
        'tahun_mula_siap',
        'petunjuk_prestasi',
        'pencapaian',
        'status',
        'completion'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id'); //program_id
    }
}
