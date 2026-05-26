@extends('layouts.app')
@section('title', 'Ocorrencias')
@section('page-title', 'Ocorrencias')

@section('content')
<div class="pt-4 space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-500">{{ $ocorrencias->total() }} ocorrencias encontradas</p>
        </div>

        @if(auth()->user()->isAqv())
            <a href="{{ route('ocorrencias.create') }}" class="btn-primary">
                <span class="text-base leading-none">+</span>
                Nova ocorrencia
            </a>
        @endif
    </div>

    <div class="panel p-4">
        <form method="GET" class="grid grid-cols-1 gap-3 xl:grid-cols-[1fr_190px_160px_160px_160px_auto_auto] xl:items-end">
            <div>
                <label for="busca" class="form-label text-xs">Buscar</label>
                <input
                    id="busca"
                    type="text"
                    name="busca"
                    value="{{ request('busca') }}"
                    placeholder="Nome ou RM do aluno"
                    class="form-input"
                >
            </div>

            <div>
                <label for="tipo" class="form-label text-xs">Tipo</label>
                <select id="tipo" name="tipo" class="form-input">
                    <option value="">Todos</option>
                    <option value="entrada_atrasada" {{ request('tipo') === 'entrada_atrasada' ? 'selected' : '' }}>Entrada atrasada</option>
                    <option value="saida_antecipada" {{ request('tipo') === 'saida_antecipada' ? 'selected' : '' }}>Sa&iacute;da antecipada</option>
                </select>
            </div>

            <div>
                <label for="status" class="form-label text-xs">Status</label>
                <select id="status" name="status" class="form-input">
                    <option value="">Todos</option>
                    <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="aprovado" {{ request('status') === 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                    <option value="negado" {{ request('status') === 'negado' ? 'selected' : '' }}>Negado</option>
                </select>
            </div>

            <div>
                <label for="data_inicio" class="form-label text-xs">De</label>
                <input id="data_inicio" type="date" name="data_inicio" value="{{ request('data_inicio') }}" class="form-input">
            </div>

            <div>
                <label for="data_fim" class="form-label text-xs">At&eacute;</label>
                <input id="data_fim" type="date" name="data_fim" value="{{ request('data_fim') }}" class="form-input">
            </div>

            <button type="submit" class="btn-primary">Filtrar</button>
            <a href="{{ route('ocorrencias.index') }}" class="btn-secondary">Limpar</a>
        </form>
    </div>

    <section class="panel overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($ocorrencias as $ocorrencia)
                @php
                    $aluno = $ocorrencia->aluno;
                    $tipoLabel = $ocorrencia->tipo === 'entrada_atrasada' ? 'Entrada atrasada' : 'Sa&iacute;da antecipada';
                    $statusLabel = match($ocorrencia->status) {
                        'aprovado' => 'Aprovado',
                        'negado' => 'Negado',
                        default => 'Pendente',
                    };
                    $statusClasses = match($ocorrencia->status) {
                        'aprovado' => 'bg-green-50 text-green-700 ring-green-200',
                        'negado' => 'bg-red-50 text-red-700 ring-red-200',
                        default => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
                    };
                @endphp

                <article class="p-5 transition hover:bg-gray-50/70">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="font-semibold text-gray-900">{!! $tipoLabel !!}</h2>
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium ring-1 {{ $statusClasses }}">{{ $statusLabel }}</span>

                                @if($ocorrencia->confirmacao_portaria)
                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 ring-1 ring-blue-200">
                                        Confirmada na portaria
                                    </span>
                                @endif
                            </div>

                            <div class="mt-3 flex items-center gap-3">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-sm font-bold text-blue-700">
                                    {{ $aluno ? strtoupper(substr($aluno->nome, 0, 1)) : '?' }}
                                </div>
                                <div class="min-w-0">
                                    @if($aluno)
                                        <p class="truncate text-sm font-semibold text-gray-900">{{ $aluno->nome }}</p>
                                        <p class="text-xs text-gray-500">RM {{ $aluno->rm }} &bull; Turma {{ $aluno->turma }}</p>
                                    @else
                                        <p class="text-sm font-semibold text-gray-900">Aluno n&atilde;o encontrado</p>
                                        <p class="text-xs text-gray-500">Cadastro removido ou indispon&iacute;vel</p>
                                    @endif
                                </div>
                            </div>

                            <p class="mt-3 line-clamp-2 text-sm leading-6 text-gray-600">{{ $ocorrencia->motivo }}</p>

                            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                                <span>Registrada em {{ $ocorrencia->data_ocorrencia?->format('d/m/Y H:i') }}</span>
                                @if($ocorrencia->aqv)
                                    <span>AQV: {{ $ocorrencia->aqv->name }}</span>
                                @endif
                                @if($ocorrencia->data_autorizacao)
                                    <span>Autorizada em {{ $ocorrencia->data_autorizacao->format('d/m/Y H:i') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 lg:flex-shrink-0 lg:justify-end">
                            <a href="{{ route('ocorrencias.show', $ocorrencia) }}" class="btn-secondary">Detalhes</a>

                            @if(auth()->user()->isAqv() && $ocorrencia->status === 'pendente')
                                <form method="POST" action="{{ route('ocorrencias.aprovar', $ocorrencia) }}">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">
                                        Aprovar
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('ocorrencias.negar', $ocorrencia) }}">
                                    @csrf
                                    <button type="submit" class="rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                        Negar
                                    </button>
                                </form>
                            @endif

                            @if(auth()->user()->isPortaria() && $ocorrencia->status === 'aprovado' && ! $ocorrencia->confirmacao_portaria)
                                <form method="POST" action="{{ route('ocorrencias.confirmar-portaria', $ocorrencia) }}">
                                    @csrf
                                    <button type="submit" class="btn-primary">Confirmar</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="p-10 text-center">
                    <h2 class="text-base font-semibold text-gray-900">Nenhuma ocorrencia encontrada</h2>
                    <p class="mt-1 text-sm text-gray-500">Ajuste os filtros ou registre uma nova ocorrencia.</p>

                    @if(auth()->user()->isAqv())
                        <a href="{{ route('ocorrencias.create') }}" class="btn-primary mt-5">
                            Registrar primeira ocorrencia
                        </a>
                    @endif
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
@endsection
