@extends('layouts.app')
@section('title', 'Nova Ocorr&ecirc;ncia')
@section('page-title', 'Registrar Ocorr&ecirc;ncia')

@section('content')
<div class="pt-4">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-500">Registre entradas atrasadas ou sa&iacute;das antecipadas dos alunos.</p>
        <a href="{{ route('ocorrencias.index') }}" class="btn-secondary">Voltar</a>
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1fr_320px]">
        <section class="panel">
            <form method="POST" action="{{ route('ocorrencias.store') }}" class="space-y-6 p-6">
                @csrf

                <div>
                    <h2 class="text-base font-semibold text-gray-900">Dados da ocorr&ecirc;ncia</h2>
                    <p class="mt-1 text-sm text-gray-500">Selecione o aluno e informe o motivo do registro.</p>
                </div>

                <div>
                    <label for="aluno_id" class="form-label">Aluno *</label>
                    <select id="aluno_id" name="aluno_id" required class="form-input">
                        <option value="">Selecione um aluno</option>
                        @foreach($alunos as $aluno)
                            <option value="{{ $aluno->id }}" {{ old('aluno_id') == $aluno->id ? 'selected' : '' }}>
                                {{ $aluno->nome }} - RM {{ $aluno->rm }} - Turma {{ $aluno->turma }}
                            </option>
                        @endforeach
                    </select>
                    @error('aluno_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <span class="form-label">Tipo *</span>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 bg-white p-4 transition hover:border-blue-300 hover:bg-blue-50/40">
                            <input type="radio" name="tipo" value="entrada_atrasada" required class="mt-1 border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('tipo') === 'entrada_atrasada' ? 'checked' : '' }}>
                            <span>
                                <span class="block text-sm font-semibold text-gray-900">Entrada atrasada</span>
                                <span class="mt-1 block text-xs text-gray-500">Use quando o aluno chegar ap&oacute;s o hor&aacute;rio previsto.</span>
                            </span>
                        </label>

                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 bg-white p-4 transition hover:border-blue-300 hover:bg-blue-50/40">
                            <input type="radio" name="tipo" value="saida_antecipada" required class="mt-1 border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('tipo') === 'saida_antecipada' ? 'checked' : '' }}>
                            <span>
                                <span class="block text-sm font-semibold text-gray-900">Sa&iacute;da antecipada</span>
                                <span class="mt-1 block text-xs text-gray-500">Use quando o aluno precisar sair antes do hor&aacute;rio.</span>
                            </span>
                        </label>
                    </div>
                    @error('tipo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="motivo" class="form-label">Motivo *</label>
                    <textarea id="motivo" name="motivo" rows="5" required class="form-input resize-y" placeholder="Descreva o motivo da ocorr&ecirc;ncia">{{ old('motivo') }}</textarea>
                    @error('motivo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="observacao" class="form-label">Observa&ccedil;&atilde;o</label>
                    <textarea id="observacao" name="observacao" rows="3" class="form-input resize-y" placeholder="Informa&ccedil;&otilde;es complementares, se houver">{{ old('observacao') }}</textarea>
                    @error('observacao') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
                    <a href="{{ route('ocorrencias.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Registrar ocorr&ecirc;ncia</button>
                </div>
            </form>
        </section>

        <aside class="space-y-5">
            <div class="panel p-5">
                <h3 class="text-base font-semibold text-gray-900">Alunos ativos</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $alunos->count() }}</p>
                <p class="mt-1 text-sm text-gray-500">Apenas alunos ativos aparecem na sele&ccedil;&atilde;o.</p>
            </div>

            <div class="panel p-5">
                <h3 class="text-base font-semibold text-gray-900">Depois do registro</h3>
                <p class="mt-2 text-sm leading-6 text-gray-600">
                    A ocorr&ecirc;ncia ser&aacute; salva como pendente. Em seguida, a AQV pode aprovar ou negar no detalhe da ocorr&ecirc;ncia.
                </p>
            </div>
        </aside>
    </div>
</div>
@endsection
