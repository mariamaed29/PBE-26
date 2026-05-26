<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\User;
use App\Http\Requests\AlunoRequest;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function __construct()
    {
        // Apenas AQV pode gerenciar alunos
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAqv()) {
                abort(403, 'Acesso não autorizado.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Aluno::query();

        if ($request->filled('busca')) {
            $query->busca($request->busca);
        }

        if ($request->filled('turma')) {
            $query->where('turma', $request->turma);
        }

        if ($request->filled('curso')) {
            $query->where('curso', $request->curso);
        }

        $alunos = $query->with('professorResponsavel')->withCount('ocorrencias')->latest()->paginate(15);
        $turmas = Aluno::select('turma')->distinct()->pluck('turma');
        $cursos = Aluno::select('curso')->distinct()->pluck('curso');

        return view('alunos.index', compact('alunos', 'turmas', 'cursos'));
    }

    public function create()
    {
        $professores = User::where('role', 'professor')->orderBy('name')->get();

        return view('alunos.create', compact('professores'));
    }

    public function store(AlunoRequest $request)
    {
        Aluno::create($request->validated());

        return redirect()->route('alunos.index')
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    public function show(Aluno $aluno)
    {
        $aluno->load('professorResponsavel');

        $ocorrencias = $aluno->ocorrencias()
            ->with('aqv', 'portaria')
            ->latest()
            ->paginate(10);

        $stats = [
            'total_atrasos'    => $aluno->atrasos()->count(),
            'total_saidas'     => $aluno->saidasAntecipadas()->count(),
            'atrasos_mes'      => $aluno->atrasos()->whereMonth('data_ocorrencia', now()->month)->count(),
            'saidas_mes'       => $aluno->saidasAntecipadas()->whereMonth('data_ocorrencia', now()->month)->count(),
        ];

        return view('alunos.show', compact('aluno', 'ocorrencias', 'stats'));
    }

    public function edit(Aluno $aluno)
    {
        $professores = User::where('role', 'professor')->orderBy('name')->get();

        return view('alunos.edit', compact('aluno', 'professores'));
    }

    public function update(AlunoRequest $request, Aluno $aluno)
    {
        $aluno->update($request->validated());

        return redirect()->route('alunos.show', $aluno)
            ->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy(Aluno $aluno)
    {
        $aluno->delete();

        return redirect()->route('alunos.index')
            ->with('success', 'Aluno removido com sucesso!');
    }
}
