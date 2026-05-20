@extends('layouts.app')
@section('title', 'Ocorr&ecirc;ncia')
@section('page-title', 'Detalhes da Ocorr&ecirc;ncia')

@section('content')
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

<div class="pt-4 space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-xl font-semibold text-gray-900">{!! $tipoLabel !!}</h2>
                <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $statusClasses }}">{{ $statusLabel }}</span>
            </div>
            <p class="mt-1 text-sm text-gray-500">
                Registrada em {{ $ocorrencia->data_ocorrencia?->format('d/m/Y H:i') }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('ocorrencias.index') }}" class="btn-secondary">Voltar</a>
            @if($aluno)
                <a href="{{ route('alunos.show', $aluno) }}" class="btn-primary">Ver aluno</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1fr_360px]">
        <section class="space-y-5">
            <div class="panel p-5">
                <h3 class="text-base font-semibold text-gray-900">Aluno</h3>
                @if($aluno)
                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-sm font-bold text-blue-700">
                            {{ strtoupper(substr($aluno->nome, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-gray-900">{{ $aluno->nome }}</p>
                            <p class="text-sm text-gray-500">RM {{ $aluno->rm }} &bull; Turma {{ $aluno->turma }}</p>
                        </div>
                    </div>

                    <dl class="mt-5 grid grid-cols-1 gap-4 border-t border-gray-100 pt-5 text-sm md:grid-cols-3">
                        <div>
                            <dt class="text-xs text-gray-500">Curso</dt>
                            <dd class="font-medium text-gray-900">{{ $aluno->curso }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Respons&aacute;vel</dt>
                            <dd class="font-medium text-gray-900">{{ $aluno->responsavel }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Telefone</dt>
                            <dd class="font-medium text-gray-900">{{ $aluno->telefone_responsavel }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="mt-4 rounded-lg bg-gray-50 px-4 py-3 text-sm text-gray-600">
                        Aluno n&atilde;o encontrado para esta ocorr&ecirc;ncia.
                    </p>
                @endif
            </div>

            <div class="panel p-5">
                <h3 class="text-base font-semibold text-gray-900">Motivo</h3>
                <p class="mt-3 text-sm leading-6 text-gray-700">{{ $ocorrencia->motivo }}</p>

                @if($ocorrencia->observacao)
                    <div class="mt-5 rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Observa&ccedil;&atilde;o</p>
                        <p class="mt-2 text-sm leading-6 text-gray-700">{{ $ocorrencia->observacao }}</p>
                    </div>
                @endif
            </div>

            <div class="panel overflow-hidden">
                <div class="border-b border-gray-200 px-5 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Notifica&ccedil;&otilde;es enviadas</h3>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($ocorrencia->notificacoes as $notificacao)
                        <article class="p-5">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $notificacao->titulo }}</p>
                                    <p class="mt-1 text-sm text-gray-600">{{ $notificacao->mensagem }}</p>
                                </div>
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $notificacao->lida ? 'bg-gray-100 text-gray-600' : 'bg-blue-50 text-blue-700' }}">
                                    @if($notificacao->lida)
                                        Lida
                                    @else
                                        N&atilde;o lida
                                    @endif
                                </span>
                            </div>
                            <p class="mt-3 text-xs text-gray-500">
                                Para
                                @if($notificacao->usuario)
                                    {{ $notificacao->usuario->name }}
                                @else
                                    Usu&aacute;rio removido
                                @endif
                                &bull; {{ $notificacao->created_at?->format('d/m/Y H:i') }}
                            </p>
                        </article>
                    @empty
                        <div class="p-8 text-center text-sm text-gray-500">
                            Nenhuma notifica&ccedil;&atilde;o vinculada a esta ocorr&ecirc;ncia.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <aside class="space-y-5">
            <div class="panel p-5">
                <h3 class="text-base font-semibold text-gray-900">Fluxo da ocorr&ecirc;ncia</h3>
                <dl class="mt-4 space-y-4 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500">Registrado por</dt>
                        <dd class="font-medium text-gray-900">
                            @if($ocorrencia->aqv)
                                {{ $ocorrencia->aqv->name }}
                            @else
                                N&atilde;o informado
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Autoriza&ccedil;&atilde;o</dt>
                        <dd class="font-medium text-gray-900">{{ $ocorrencia->data_autorizacao?->format('d/m/Y H:i') ?: 'Pendente' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Confirma&ccedil;&atilde;o da portaria</dt>
                        <dd class="font-medium text-gray-900">{{ $ocorrencia->confirmacao_portaria?->format('d/m/Y H:i') ?: 'Pendente' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Confirmado por</dt>
                        <dd class="font-medium text-gray-900">{{ $ocorrencia->portaria?->name ?: 'Pendente' }}</dd>
                    </div>
                </dl>
            </div>

            @if(auth()->user()->isAqv() && $ocorrencia->status === 'pendente')
                <div class="panel p-5">
                    <h3 class="text-base font-semibold text-gray-900">Decis&atilde;o da AQV</h3>
                    <div class="mt-4 grid grid-cols-1 gap-3">
                        <form method="POST" action="{{ route('ocorrencias.aprovar', $ocorrencia) }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">
                                Aprovar ocorr&ecirc;ncia
                            </button>
                        </form>
                        <form method="POST" action="{{ route('ocorrencias.negar', $ocorrencia) }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                Negar ocorr&ecirc;ncia
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if(auth()->user()->isPortaria() && $ocorrencia->status === 'aprovado' && ! $ocorrencia->confirmacao_portaria)
                <div class="panel p-5">
                    <h3 class="text-base font-semibold text-gray-900">Portaria</h3>
                    <p class="mt-1 text-sm text-gray-500">Confirme quando a entrada ou sa&iacute;da for realizada fisicamente.</p>
                    <form method="POST" action="{{ route('ocorrencias.confirmar-portaria', $ocorrencia) }}" class="mt-4">
                        @csrf
                        <button type="submit" class="btn-primary w-full">Confirmar portaria</button>
                    </form>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
