<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model {
    use HasFactory;

    protected $fillable = ['chat_id', 'usuario_id', 'mensaje', 'enviado_en'];

    public function chat() {
        return $this->belongsTo(Chat::class);
    }

    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    
    /**
     * Obtener los adjuntos relacionados con este mensaje
     */
    public function adjuntos()
    {
        return $this->morphMany(Adjunto::class, 'adjuntable');
    }
}
