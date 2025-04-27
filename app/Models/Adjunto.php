<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjunto extends Model
{
    use HasFactory;

    protected $table = 'adjuntos';
    
    protected $fillable = [
        'nombre', 
        'ruta', 
        'tipo_mime', 
        'tamano', 
        'adjuntable_id', 
        'adjuntable_type', 
        'usuario_id'
    ];

    /**
     * Obtener el modelo adjuntable (incidencia o mensaje)
     */
    public function adjuntable()
    {
        return $this->morphTo();
    }

    /**
     * Obtener el usuario que subió el adjunto
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
} 