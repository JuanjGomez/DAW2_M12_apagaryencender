<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model {
    use HasFactory;

    protected $fillable = ['incidencia_id'];

    public function incidencia() {
        return $this->belongsTo(Incidencia::class);
    }

    public function mensajes() {
        return $this->hasMany(Mensaje::class);
    }
}
