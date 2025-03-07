<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prioridad extends Model {
    use HasFactory;

    protected $fillable = ['nombre'];

    public function incidencias() {
        return $this->hasMany(Incidencia::class);
    }
}
