<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Ocorrencia;
use App\Models\Notificacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match($user->role) {
            'aqv'       => $this->dashboardAqv(),
            'portaria'  => $this->dashboardPortaria(),
            'professor' => $this->dashboardProfessor(),
            default     => redirect()->route('login'),
        };
    }

    private function dashboardAqv()
    {
        $stats = [
            'total_atrasos_hoje'       => Ocorrencia::hoje()->where('tipo', 'entrada_atrasada')->count(),
            'total_saidas_hoje'        => Ocorrencia::hoje()->where('tipo', 'saida_antecipada')->count(),
            'pendentes'                => Ocorrencia::pendentes()->count(),
            'aprovados_hoje'           => Ocorrencia::hoje()->aprovados()->count(),
            'total_alunos'             => Aluno::where('ativo', true)->count(),
        ];

        $ocorrencias_recentes = Ocorrencia::with('aluno', 'aqv')
            ->latest()
            ->take(10)
            ->get();

        $pendentes = Ocorrencia::with('aluno')
            ->pendentes()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.aqv', compact('stats', 'ocorrencias_recentes', 'pendentes'));
    }

    private function dashboardPortaria()
    {
        $liberacoes_hoje = Ocorrencia::with('aluno', 'aqv')
            ->hoje()
            ->aprovados()
            ->orderBy('data_ocorrencia', 'desc')
            ->get();

        $stats = [
            'liberacoes_hoje'  => $liberacoes_hoje->count(),
            'entradas_hoje'    => $liberacoes_hoje->where('tipo', 'entrada_atrasada')->count(),
            'saidas_hoje'      => $liberacoes_hoje->where('tipo', 'saida_antecipada')->count(),
            'confirmadas'      => $liberacoes_hoje->whereNotNull('confirmacao_portaria')->count(),
        ];

        return view('dashboard.portaria', compact('stats', 'liberacoes_hoje'));
    }

    private function dashboardProfessor()
    {
        $user = Auth::user();

        $notificacoes = Notificacao::with('ocorrencia.aluno')
            ->where('usuario_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

        $nao_lidas = Notificacao::where('usuario_id', $user->id)
            ->whereNull('lida_em')
            ->count();

        $ocorrencias_turma = Ocorrencia::with('aluno')
            ->whereHas('aluno')
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'notificacoes_nao_lidas' => $nao_lidas,
            'total_notificacoes'     => Notificacao::where('usuario_id', $user->id)->count(),
        ];

        return view('dashboard.professor', compact('stats', 'notificacoes', 'ocorrencias_turma', 'nao_lidas'));
    }
}

