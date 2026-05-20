@extends('layouts.app')
@section('title', 'Dashboard Professor')
@section('page-title', 'Dashboard — Professor')

@section('content')
<div class="pt-4 space-y-6">

    @if($nao_lidas > 0)
    <div class="bg-blue-50 border border-blue-200 rounded-xl px-5 py-4 flex items-center gap-3">
        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
            {{ $nao_lidas }}
        </div>
        <div>
            <p class="text-sm font-medium text-blue-800">Você tem {{ $nao_lidas }} notificação(ões) não lida(s)</p>
            <a href="{{ route('notificacoes.index') }}" class="text-xs text-blue-600 hover:underline">Ver todas →</a>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Não Lidas</p>
            <p class="text-3xl font-bold text-red-500 mt-1">{{ $stats['notificacoes_nao_lidas'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Notificações</p>
            <p class="text-3xl font-bold text-slate-700 mt-1">{{ $stats['total_notificacoes'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">🔔 Notificações Recentes</h2>
            <a href="{{ route('notificacoes.index') }}" class="text-xs text-blue-600 hover:underline">Ver todas</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($notificacoes as $notif)
            <div class="px-5 py-4 {{ !$notif->lida ? 'bg-blue-50/50' : '' }}">
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0 {{ !$notif->lida ? 'bg-blue-500' : 'bg-gray-300' }}"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ $notif->titulo }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">{{ $notif->mensagem }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @empty
            <p class="px-5 py-8 text-center text-sm text-gray-400">Nenhuma notificação ainda</p>
            @endforelse
        </div>
    </div>
</div>
@endsection