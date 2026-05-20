@extends('layouts.app')
@section('title', 'Dashboard Portaria')
@section('page-title', 'Dashboard — Portaria')

@section('content')
<div class="pt-4 space-y-6">

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Liberações Hoje</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['liberacoes_hoje'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Entradas Auth.</p>
            <p class="text-3xl font-bold text-orange-600 mt-1">{{ $stats['entradas_hoje'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Saídas Auth.</p>
            <p class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['saidas_hoje'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Confirmadas</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['confirmadas'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">✅ Autorizações do Dia</h2>
            <p class="text-xs text-gray-500 mt-0.5">Apenas autorizações aprovadas pela AQV</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left">Aluno</th>
                        <th class="px-5 py-3 text-left">Turma</th>
                        <th class="px-5 py-3 text-left">Tipo</th>
                        <th class="px-5 py-3 text-left">Horário</th>
                        <th class="px-5 py-3 text-left">Portaria</th>
                        <th class="px-5 py-3 text-left">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($liberacoes_hoje as $oc)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">{{ $oc->aluno->nome }}</p>
                            <p class="text-xs text-gray-400">RM: {{ $oc->aluno->rm }}</p>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $oc->aluno->turma }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($oc->tipo === 'entrada_atrasada') bg-orange-100 text-orange-700
                                @else bg-purple-100 text-purple-700
                                @endif">
                                {{ $oc->tipoLabel() }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $oc->data_ocorrencia->format('H:i') }}</td>
                        <td class="px-5 py-3">
                            @if($oc->confirmacao_portaria)
                                <span class="text-xs text-green-600 font-medium">✓ Confirmado {{ $oc->confirmacao_portaria->format('H:i') }}</span>
                            @else
                                <span class="text-xs text-gray-400">Aguardando</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if(!$oc->confirmacao_portaria)
                                <form method="POST" action="{{ route('ocorrencias.confirmar-portaria', $oc) }}">
                                    @csrf
                                    <button class="text-xs px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 font-medium">
                                        Confirmar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-400">Nenhuma autorização para hoje ainda</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

