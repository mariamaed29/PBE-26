<?php
namespace App\Services;

use App\Mail\OcorrenciaProfessorMail;
use App\Models\Notificacao;
use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

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
        $notificacao = Notificacao::create([
            'ocorrencia_id' => $ocorrencia->id,
            'usuario_id'    => $usuario->id,
            'titulo'        => $titulo,
            'mensagem'      => $mensagem,
            'lida'          => false,
        ]);

        if ($usuario->isProfessor() && $usuario->email) {
            $this->enviarEmailProfessor($usuario, $notificacao);
        }

        return $notificacao;
    }

    private function enviarEmailProfessor(User $professor, Notificacao $notificacao): void
    {
        try {
            Mail::to($professor->email)->send(new OcorrenciaProfessorMail($notificacao));
        } catch (Throwable $erro) {
            Log::error('Falha ao enviar e-mail de ocorrencia para professor.', [
                'professor_id' => $professor->id,
                'professor_email' => $professor->email,
                'notificacao_id' => $notificacao->id,
                'erro' => $erro->getMessage(),
            ]);
        }
    }
}
