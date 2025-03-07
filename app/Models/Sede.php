<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model {
    use HasFactory;

    protected $fillable = ['nombre', 'direccion', 'ciudad'];

    public function incidencias() {
        return $this->hasMany(Incidencia::class);
    }
}
