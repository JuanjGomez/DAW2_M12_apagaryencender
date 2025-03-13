<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model {
    use HasFactory;

    protected $fillable = [
        'cliente_id', 'tecnico_id', 'categoria_id', 'subcategoria_id','estado_id', 'prioridad_id', 'descripcion', 'imagen', 'fecha_creacion', 'fecha_resolucion', 'sede_id','chat_id'
];

    public function cliente() {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function tecnico() {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria() {
        return $this->belongsTo(Subcategoria::class);
    }

    public function estado() {
        return $this->belongsTo(Estado::class);
    }

    public function prioridad() {
        return $this->belongsTo(Prioridad::class);
    }

    public function sede() {
        return $this->belongsTo(Sede::class);
    }

    public function chat() {
        return $this->hasOne(Chat::class);
    }
    
}
