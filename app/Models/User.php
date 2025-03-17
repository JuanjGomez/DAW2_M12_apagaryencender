<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'sede_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relación con el rol del usuario (administrador, cliente, técnico, etc.)
    public function role() {
        return $this->belongsTo(Role::class);
    }

    // Relación con las incidencias del cliente
    public function incidenciasCliente() {
        return $this->hasMany(Incidencia::class, 'cliente_id');
    }

    // Relación con las incidencias del técnico
    public function incidenciasTecnico() {
        return $this->hasMany(Incidencia::class, 'tecnico_id');
    }

    // Relación con la sede
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
