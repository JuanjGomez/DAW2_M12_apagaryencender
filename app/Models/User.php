<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'role_id', 'jefe_id', 'sede_id'];

    // Relación con el rol del usuario (administrador, cliente, técnico, etc.)
    public function role() {
        return $this->belongsTo(Role::class);
    }

    // Relación con las incidencias del cliente (un usuario cliente puede tener muchas incidencias)
    public function incidenciasCliente() {
        return $this->hasMany(Incidencia::class, 'cliente_id');
    }

    // Relación con las incidencias del técnico (un técnico puede tener muchas incidencias)
    public function incidencias() {
        return $this->hasMany(Incidencia::class, 'tecnico_id');
    }

    // Relación uno a muchos con la sede (un usuario tiene una sede)
    public function sede() {
        return $this->belongsTo(Sede::class);
    }

    // Relación con el jefe (usuario que supervisa a este usuario)
    public function jefe() {
        return $this->belongsTo(User::class, 'jefe_id');
    }

    // Relación con los empleados supervisados por este usuario
    public function empleados() {
        return $this->hasMany(User::class, 'jefe_id');
    }
}
