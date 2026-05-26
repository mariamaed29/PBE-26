<?php

namespace App\Services;

use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OcorrenciaService
{
    public function __construct(private NotificacaoService $notificacaoService)
    {
    }

    /**
     * Registra uma nova ocorrência
     */
    public function registrar(array $dados): Ocorrencia
    {
        return DB::transaction(function () use ($dados) {
            $ocorrencia = Ocorrencia::create([
                ...$dados,
                'aqv_id'           => Auth::id(),
                'data_ocorrencia'  => now(),
                'status'           => 'pendente',
            ]);

            $this->notificarProfessorResponsavel($ocorrencia);

            return $ocorrencia;
        });
    }

    /**
     * Aprova uma ocorrência e envia notificações
     */
    public function aprovar(Ocorrencia $ocorrencia): void
    {
        DB::transaction(function () use ($ocorrencia) {
            $ocorrencia->update([
                'status'           => 'aprovado',
                'data_autorizacao' => now(),
            ]);

            // Notifica portaria
            /** @var \Illuminate\Database\Eloquent\Collection<int, User> $portarias */
            $portarias = User::where('role', 'portaria')->get();
            foreach ($portarias as $portaria) {
                /** @var User $portaria */
                $this->notificacaoService->enviar(
                    ocorrencia: $ocorrencia,
                    usuario: $portaria,
                    titulo: "Nova autorização: {$ocorrencia->aluno->nome}",
                    mensagem: "O aluno {$ocorrencia->aluno->nome} (RM: {$ocorrencia->aluno->rm}) teve sua {$ocorrencia->tipoLabel()} autorizada."
                );
            }

        });
    }

    private function notificarProfessorResponsavel(Ocorrencia $ocorrencia): void
    {
        $ocorrencia->loadMissing('aluno.professorResponsavel');
        $professor = $ocorrencia->aluno?->professorResponsavel;

        if ($professor) {
            $this->notificacaoService->enviar(
                ocorrencia: $ocorrencia,
                usuario: $professor,
                titulo: "Aluno com {$ocorrencia->tipoLabel()}: {$ocorrencia->aluno->nome}",
                mensagem: "O aluno {$ocorrencia->aluno->nome} (Turma: {$ocorrencia->aluno->turma}) registrou {$ocorrencia->tipoLabel()}. Motivo: {$ocorrencia->motivo}"
            );

            return;
        }

        Log::warning('Ocorrencia registrada sem professor responsavel vinculado ao aluno.', [
            'ocorrencia_id' => $ocorrencia->id,
            'aluno_id' => $ocorrencia->aluno_id,
        ]);
    }

    /**
     * Nega uma ocorrência
     */
    public function negar(Ocorrencia $ocorrencia): void
    {
        $ocorrencia->update([
            'status'           => 'negado',
            'data_autorizacao' => now(),
        ]);
    }

    /**
     * Portaria confirma a entrada/saída física
     */
    public function confirmarPortaria(Ocorrencia $ocorrencia): void
    {
        $ocorrencia->update([
            'confirmacao_portaria' => now(),
            'portaria_id'          => Auth::id(),
        ]);
    }
}
