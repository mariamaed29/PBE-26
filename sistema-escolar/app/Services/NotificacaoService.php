<?php
namespace App\Services;

use App\Models\Notificacao;
use App\Models\Ocorrencia;
use App\Models\User;

class NotificacaoService
{
    /**
     * Envia uma notificação para um usuário
     */
    public function enviar(
        Ocorrencia $ocorrencia,
        User $usuario,
        string $titulo,
        string $mensagem
    ): Notificacao {
        return Notificacao::create([
            'ocorrencia_id' => $ocorrencia->id,
            'usuario_id'    => $usuario->id,
            'titulo'        => $titulo,
            'mensagem'      => $mensagem,
            'lida'          => false,
        ]);
    }
}