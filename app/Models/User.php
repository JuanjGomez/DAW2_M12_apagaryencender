<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role_id', 'sede_id'];

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function incidenciasCliente() {
        return $this->hasMany(Incidencia::class, 'cliente_id');
    }

    public function incidenciasTecnico() {
        return $this->hasMany(Incidencia::class, 'tecnico_id');
    }

    public function sede() {
        return $this->belongsTo(Sede::class);
    }
}
