<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model {
    use HasFactory;

    protected $fillable = ['nombre', 'direccion', 'ciudad', 'pais', 'gestor_id'];

    public function incidencias() {
        return $this->hasMany(Incidencia::class);
    }

    public function tecnicos()
    {
        return $this->belongsToMany(User::class, 'sede_tecnico', 'sede_id', 'tecnico_id');
    }
}
