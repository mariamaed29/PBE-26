@extends('layouts.app')
@section('title', 'Alunos')
@section('page-title', 'Alunos')

@section('content')
<div class="pt-4 space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-500">{{ $alunos->total() }} alunos cadastrados</p>
        </div>

        @if(auth()->user()->isAqv())
            <a href="{{ route('alunos.create') }}" class="btn-primary">
                <span class="text-base leading-none">+</span>
                Novo aluno
            </a>
        @endif
    </div>

    <div class="panel p-4">
        <form method="GET" class="grid grid-cols-1 gap-3 lg:grid-cols-[1fr_180px_220px_auto_auto] lg:items-end">
            <div>
                <label class="form-label text-xs">Buscar</label>
                <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Nome, RM ou turma" class="form-input">
            </div>

            <div>
                <label class="form-label text-xs">Turma</label>
                <select name="turma" class="form-input">
                    <option value="">Todas</option>
                    @foreach($turmas as $turma)
                        <option value="{{ $turma }}" {{ request('turma') == $turma ? 'selected' : '' }}>{{ $turma }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label text-xs">Curso</label>
                <select name="curso" class="form-input">
                    <option value="">Todos</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso }}" {{ request('curso') == $curso ? 'selected' : '' }}>{{ $curso }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-primary">Buscar</button>
            <a href="{{ route('alunos.index') }}" class="btn-secondary">Limpar</a>
        </form>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse($alunos as $aluno)
            <article class="panel p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-sm font-bold text-blue-700">
                            {{ strtoupper(substr($aluno->nome, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h2 class="truncate text-sm font-semibold text-gray-900">{{ $aluno->nome }}</h2>
                            <p class="text-xs text-gray-500">RM {{ $aluno->rm }}</p>
                        </div>
                    </div>

                    <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $aluno->ativo ? 'bg-green-50 text-green-700 ring-1 ring-green-200' : 'bg-gray-100 text-gray-500 ring-1 ring-gray-200' }}">
                        {{ $aluno->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>

                <dl class="mt-4 grid grid-cols-2 gap-3 border-t border-gray-100 pt-4 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500">Turma</dt>
                        <dd class="font-medium text-gray-800">{{ $aluno->turma }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Ocorrencias</dt>
                        <dd class="font-medium text-gray-800">{{ $aluno->ocorrencias_count }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-xs text-gray-500">Curso</dt>
                        <dd class="truncate font-medium text-gray-800">{{ $aluno->curso }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-xs text-gray-500">Responsavel</dt>
                        <dd class="truncate font-medium text-gray-800">{{ $aluno->responsavel }}</dd>
                    </div>
                </dl>

                <div class="mt-4 flex items-center justify-between gap-3 border-t border-gray-100 pt-4">
                    <a href="{{ route('alunos.show', $aluno) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Ver historico</a>

                    @if(auth()->user()->isAqv())
                        <a href="{{ route('alunos.edit', $aluno) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Editar</a>
                    @endif
                </div>
            </article>
        @empty
            <div class="panel col-span-full p-10 text-center">
                <h2 class="text-base font-semibold text-gray-900">Nenhum aluno encontrado</h2>
                <p class="mt-1 text-sm text-gray-500">Ajuste os filtros ou cadastre um novo aluno.</p>
                @if(auth()->user()->isAqv())
                    <a href="{{ route('alunos.create') }}" class="btn-primary mt-5">Cadastrar primeiro aluno</a>
                @endif
            </div>
        @endforelse
    </div>

    @if($alunos->hasPages())
        <div>{{ $alunos->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
