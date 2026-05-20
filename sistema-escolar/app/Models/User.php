<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // aqv | portaria | professor
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relacionamentos
    public function ocorrenciasAqv()
    {
        return $this->hasMany(Ocorrencia::class, 'aqv_id');
    }

    public function ocorrenciasPortaria()
    {
        return $this->hasMany(Ocorrencia::class, 'portaria_id');
    }

    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class, 'usuario_id');
    }

    // Helpers de role
    public function isAqv(): bool
    {
        return $this->role === 'aqv';
    }

    public function isPortaria(): bool
    {
        return $this->role === 'portaria';
    }

    public function isProfessor(): bool
    {
        return $this->role === 'professor';
    }

    // Notificações não lidas
    public function notificacoesNaoLidas()
    {
        return $this->notificacoes()->where('lida', false);
    }
}
