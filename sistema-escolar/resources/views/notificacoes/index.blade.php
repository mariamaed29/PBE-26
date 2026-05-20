@extends('layouts.app')
@section('title', 'Notifica&ccedil;&otilde;es')
@section('page-title', 'Notifica&ccedil;&otilde;es')

@section('content')
<div class="pt-4 space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-500">{{ $notificacoes->total() }} notifica&ccedil;&otilde;es recebidas</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-secondary">Dashboard</a>
    </div>

    <section class="panel overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($notificacoes as $notificacao)
                @php
                    $ocorrencia = $notificacao->ocorrencia;
                    $aluno = $ocorrencia?->aluno;
                    $statusClasses = match($ocorrencia?->status) {
                        'aprovado' => 'bg-green-50 text-green-700 ring-green-200',
                        'negado' => 'bg-red-50 text-red-700 ring-red-200',
                        'pendente' => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
                        default => 'bg-gray-100 text-gray-600 ring-gray-200',
                    };
                @endphp

                <article class="p-5 {{ $notificacao->lida ? 'bg-white' : 'bg-blue-50/40' }}">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                @if(! $notificacao->lida)
                                    <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                                @endif
                                <h2 class="font-semibold text-gray-900">{{ $notificacao->titulo }}</h2>
                                @if($ocorrencia)
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium ring-1 {{ $statusClasses }}">
                                        {{ ucfirst($ocorrencia->status) }}
                                    </span>
                                @endif
                            </div>

                            <p class="mt-2 text-sm leading-6 text-gray-700">{{ $notificacao->mensagem }}</p>

                            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                                <span>{{ $notificacao->created_at?->format('d/m/Y H:i') }}</span>
                                @if($aluno)
                                    <span>{{ $aluno->nome }} &bull; RM {{ $aluno->rm }} &bull; Turma {{ $aluno->turma }}</span>
                                @endif
                                @if($notificacao->lida_em)
                                    <span>Lida em {{ $notificacao->lida_em->format('d/m/Y H:i') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($ocorrencia)
                            <a href="{{ route('ocorrencias.show', $ocorrencia) }}" class="btn-secondary flex-shrink-0">Ver ocorr&ecirc;ncia</a>
                        @endif
                    </div>
                </article>
            @empty
                <div class="p-10 text-center">
                    <h2 class="text-base font-semibold text-gray-900">Nenhuma notifica&ccedil;&atilde;o</h2>
                    <p class="mt-1 text-sm text-gray-500">Quando houver atualiza&ccedil;&otilde;es de ocorr&ecirc;ncias, elas aparecer&atilde;o aqui.</p>
                </div>
            @endforelse
        </div>

        @if($notificacoes->hasPages())
            <div class="border-t border-gray-200 px-5 py-4">
                {{ $notificacoes->withQueryString()->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
