<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model {
    use HasFactory;

    protected $fillable = ['nombre', 'direccion', 'ciudad', 'pais'];

    public function incidencias() {
        return $this->hasMany(Incidencia::class);
    }

    public function gestor() {
        return $this->belongsTo(User::class, 'gestor_id')
                    ->where('role_id', 3); // role_id 3 corresponde a gestores
    }

    public function tecnicos() {
        return $this->hasMany(User::class)
                    ->where('role_id', 2); // role_id 2 corresponde a técnicos
    }
}
