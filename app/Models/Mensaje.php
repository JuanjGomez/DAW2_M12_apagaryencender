<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model {
    use HasFactory;

    protected $fillable = ['chat_id', 'usuario_id', 'mensaje'];

    public function chat() {
        return $this->belongsTo(Chat::class);
    }

    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
