<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'ocorrencia_id',
        'usuario_id',
        'titulo',
        'mensagem',
        'lida',
        'lida_em',
    ];

    protected $casts = [
        'lida'    => 'boolean',
        'lida_em' => 'datetime',
    ];

    // Relacionamentos
    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Marcar como lida
    public function marcarComoLida(): void
    {
        $this->update([
            'lida'    => true,
            'lida_em' => now(),
        ]);
    }

    // Scope não lidas
    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }
}
