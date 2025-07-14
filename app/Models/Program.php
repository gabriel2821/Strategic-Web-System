<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['langkah_id', 'program_name'];   //fk langkah_id and program_name

    public function langkah()
    {
        return $this->belongsTo(Langkah::class, 'langkah_id'); //langkah_id
    }

    public function programRows()
    {
        return $this->hasMany(ProgramRow::class, 'program_id'); //fk program_id
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
