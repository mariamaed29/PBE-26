<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Ocorrencia;
use App\Http\Requests\OcorrenciaRequest;
use App\Services\OcorrenciaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OcorrenciaController extends Controller
{
    public function __construct(private OcorrenciaService $ocorrenciaService)
    {
    }

    public function index(Request $request)
    {
        $query = Ocorrencia::with('aluno', 'aqv');

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Portaria vê apenas aprovados
        if ($user?->isPortaria()) {
            $query->aprovados();
        }

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data_ocorrencia', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_ocorrencia', '<=', $request->data_fim);
        }

        if ($request->filled('busca')) {
            $query->whereHas('aluno', function ($q) use ($request) {
                $q->where('nome', 'like', "%{$request->busca}%")
                  ->orWhere('rm', 'like', "%{$request->busca}%");
            });
        }

        $ocorrencias = $query->latest()->paginate(15);

        return view('ocorrencias.index', compact('ocorrencias'));
    }

    public function create()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Apenas AQV pode registrar
        if (! $user || ! $user->isAqv()) {
            abort(403);
        }

        $alunos = Aluno::with('professorResponsavel')
            ->where('ativo', true)
            ->whereNotNull('professor_id')
            ->orderBy('nome')
            ->get();

        return view('ocorrencias.create', compact('alunos'));
    }

    public function store(OcorrenciaRequest $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user || ! $user->isAqv()) {
            abort(403);
        }

        $ocorrencia = $this->ocorrenciaService->registrar($request->validated());

        return redirect()->route('ocorrencias.show', $ocorrencia)
            ->with('success', 'Ocorrência registrada com sucesso!');
    }

    public function show(Ocorrencia $ocorrencia)
    {
        $ocorrencia->load('aluno', 'aqv', 'portaria', 'notificacoes.usuario');

        return view('ocorrencias.show', compact('ocorrencia'));
    }

    public function aprovar(Ocorrencia $ocorrencia)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user || ! $user->isAqv()) {
            abort(403);
        }

        $this->ocorrenciaService->aprovar($ocorrencia);

        return redirect()->back()
            ->with('success', 'Ocorrência aprovada! Notificações enviadas.');
    }

    public function negar(Ocorrencia $ocorrencia)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user || ! $user->isAqv()) {
            abort(403);
        }

        $this->ocorrenciaService->negar($ocorrencia);

        return redirect()->back()
            ->with('success', 'Ocorrência negada.');
    }

    public function confirmarPortaria(Ocorrencia $ocorrencia)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user || ! $user->isPortaria()) {
            abort(403);
        }

        $this->ocorrenciaService->confirmarPortaria($ocorrencia);

        return redirect()->back()
            ->with('success', 'Entrada/saída confirmada!');
    }
}
