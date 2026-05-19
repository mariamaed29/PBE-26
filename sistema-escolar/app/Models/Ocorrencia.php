<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ocorrencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'aluno_id',
        'aqv_id',
        'tipo',
        'motivo',
        'status',
        'data_ocorrencia',
        'data_autorizacao',
        'confirmacao_portaria',
        'portaria_id',
        'observacao',
    ];

    protected $casts = [
        'data_ocorrencia'    => 'datetime',
        'data_autorizacao'   => 'datetime',
        'confirmacao_portaria' => 'datetime',
    ];

    // Relacionamentos
    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function aqv()
    {
        return $this->belongsTo(User::class, 'aqv_id');
    }

    public function portaria()
    {
        return $this->belongsTo(User::class, 'portaria_id');
    }

    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class);
    }

    // Scopes
    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeAprovados($query)
    {
        return $query->where('status', 'aprovado');
    }

    public function scopeHoje($query)
    {
        return $query->whereDate('data_ocorrencia', today());
    }

    // Helpers
    public function isPendente(): bool
    {
        return $this->status === 'pendente';
    }

    public function isAprovado(): bool
    {
        return $this->status === 'aprovado';
    }

    public function tipoLabel(): string
    {
        return match($this->tipo) {
            'entrada_atrasada' => 'Entrada Atrasada',
            'saida_antecipada' => 'Saída Antecipada',
            default => $this->tipo,
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pendente' => 'Pendente',
            'aprovado' => 'Aprovado',
            'negado'   => 'Negado',
            default    => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'pendente' => 'yellow',
            'aprovado' => 'green',
            'negado'   => 'red',
            default    => 'gray',
        };
    }
}