<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'role_id', 'sede_id'];

    // Relación con el rol del usuario (administrador, cliente, técnico, etc.)
    public function role() {
        return $this->belongsTo(Role::class);
    }

    // Relación con las incidencias del cliente (un usuario cliente puede tener muchas incidencias)
    public function incidenciasCliente() {
        return $this->hasMany(Incidencia::class, 'cliente_id');
    }

    // Relación con las incidencias del técnico (un técnico puede tener muchas incidencias)
    public function incidenciasTecnico() {
        return $this->hasMany(Incidencia::class, 'tecnico_id');
    }

    // Relación uno a muchos con la sede (un usuario tiene una sede)
    public function sede() {
        return $this->belongsTo(Sede::class);
    }

    // Relación muchos a muchos con las sedes para los técnicos (un técnico puede estar asignado a muchas sedes)
    public function sedes() {
        return $this->belongsToMany(Sede::class, 'sede_tecnico', 'tecnico_id', 'sede_id');
    }
}
