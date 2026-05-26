@extends('layouts.app')
@section('title', 'Aluno')
@section('page-title', 'Hist&oacute;rico do Aluno')

@section('content')
<div class="pt-4 space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-xl bg-blue-600 text-xl font-bold text-white shadow-sm">
                {{ strtoupper(substr($aluno->nome, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <h2 class="truncate text-xl font-semibold text-gray-900">{{ $aluno->nome }}</h2>
                <p class="text-sm text-gray-500">RM {{ $aluno->rm }} &bull; {{ $aluno->curso }} &bull; Turma {{ $aluno->turma }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('alunos.index') }}" class="btn-secondary">Voltar</a>
            @if(auth()->user()->isAqv())
                <a href="{{ route('alunos.edit', $aluno) }}" class="btn-primary">Editar aluno</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="panel p-5">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Atrasos</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_atrasos'] }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ $stats['atrasos_mes'] }} neste m&ecirc;s</p>
        </div>
        <div class="panel p-5">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Sa&iacute;das antecipadas</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_saidas'] }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ $stats['saidas_mes'] }} neste m&ecirc;s</p>
        </div>
        <div class="panel p-5">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Status</p>
            <p class="mt-3 inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $aluno->ativo ? 'bg-green-50 text-green-700 ring-1 ring-green-200' : 'bg-gray-100 text-gray-600 ring-1 ring-gray-200' }}">
                {{ $aluno->ativo ? 'Ativo' : 'Inativo' }}
            </p>
        </div>
        <div class="panel p-5">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Cadastro</p>
            <p class="mt-2 text-sm font-semibold text-gray-900">{{ $aluno->created_at?->format('d/m/Y') }}</p>
            <p class="mt-1 text-xs text-gray-500">Atualizado em {{ $aluno->updated_at?->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-[360px_1fr]">
        <aside class="panel p-5">
            <h3 class="text-base font-semibold text-gray-900">Contato do respons&aacute;vel</h3>
            <dl class="mt-4 space-y-4 text-sm">
                <div>
                    <dt class="text-xs text-gray-500">Nome</dt>
                    <dd class="font-medium text-gray-900">{{ $aluno->responsavel }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Telefone</dt>
                    <dd class="font-medium text-gray-900">{{ $aluno->telefone_responsavel }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">E-mail</dt>
                    <dd class="font-medium text-gray-900">
                        @if($aluno->email_responsavel)
                            {{ $aluno->email_responsavel }}
                        @else
                            N&atilde;o informado
                        @endif
                    </dd>
                </div>
            </dl>

            <div class="mt-5 border-t border-gray-200 pt-5">
                <h3 class="text-base font-semibold text-gray-900">Professor respons&aacute;vel</h3>
                @if($aluno->professorResponsavel)
                    <dl class="mt-4 space-y-4 text-sm">
                        <div>
                            <dt class="text-xs text-gray-500">Nome</dt>
                            <dd class="font-medium text-gray-900">{{ $aluno->professorResponsavel->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">E-mail</dt>
                            <dd class="font-medium text-gray-900">{{ $aluno->professorResponsavel->email }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="mt-3 rounded-lg bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                        Nenhum professor respons&aacute;vel vinculado a este aluno.
                    </p>
                @endif
            </div>

            @if(auth()->user()->isAqv())
                <form method="POST" action="{{ route('alunos.destroy', $aluno) }}" class="mt-6 border-t border-gray-200 pt-5" onsubmit="return confirm('Tem certeza que deseja remover este aluno?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                        Remover aluno
                    </button>
                </form>
            @endif
        </aside>

        <section class="panel overflow-hidden">
            <div class="border-b border-gray-200 px-5 py-4">
                <h3 class="text-base font-semibold text-gray-900">Ocorrencias</h3>
                <p class="mt-1 text-sm text-gray-500">Hist&oacute;rico de entradas atrasadas e saidasdas antecipadas.</p>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($ocorrencias as $ocorrencia)
                    @php
                        $tipo = $ocorrencia->tipo === 'entrada_atrasada' ? 'Entrada atrasada' : 'Sa&iacute;da antecipada';
                        $statusClasses = match($ocorrencia->status) {
                            'aprovado' => 'bg-green-50 text-green-700 ring-green-200',
                            'negado' => 'bg-red-50 text-red-700 ring-red-200',
                            default => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
                        };
                    @endphp
                    <article class="p-5">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="font-semibold text-gray-900">{!! $tipo !!}</h4>
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium ring-1 {{ $statusClasses }}">
                                        {{ ucfirst($ocorrencia->status) }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">{{ $ocorrencia->data_ocorrencia?->format('d/m/Y H:i') }}</p>
                            </div>

                            <a href="{{ route('ocorrencias.show', $ocorrencia) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Ver detalhes</a>
                        </div>

                        <p class="mt-3 text-sm text-gray-700">{{ $ocorrencia->motivo }}</p>

                        @if($ocorrencia->observacao)
                            <p class="mt-2 rounded-lg bg-gray-50 px-3 py-2 text-sm text-gray-600">{{ $ocorrencia->observacao }}</p>
                        @endif

                        <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                            <span>AQV:
                                @if($ocorrencia->aqv)
                                    {{ $ocorrencia->aqv->name }}
                                @else
                                    N&atilde;o informado
                                @endif
                            </span>
                            <span>Portaria: {{ $ocorrencia->portaria?->name ?: 'Pendente' }}</span>
                        </div>
                    </article>
                @empty
                    <div class="p-10 text-center">
                        <h4 class="text-base font-semibold text-gray-900">Sem ocorrencias registradas</h4>
                        <p class="mt-1 text-sm text-gray-500">Este aluno ainda n&atilde;o possui hist&oacute;rico.</p>
                    </div>
                @endforelse
            </div>

            @if($ocorrencias->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $ocorrencias->withQueryString()->links() }}
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
