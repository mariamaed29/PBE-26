@extends('layouts.app')
@section('title', 'Editar Aluno')
@section('page-title', 'Editar Aluno')

@section('content')
<div class="pt-4">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-500">Atualize os dados de {{ $aluno->nome }}.</p>
        <a href="{{ route('alunos.show', $aluno) }}" class="btn-secondary">Ver aluno</a>
    </div>

    <div class="panel max-w-4xl">
        <form method="POST" action="{{ route('alunos.update', $aluno) }}" class="space-y-6 p-6">
            @csrf
            @method('PUT')

            <section>
                <h2 class="text-base font-semibold text-gray-900">Dados do aluno</h2>
                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="form-label">Nome completo *</label>
                        <input type="text" name="nome" value="{{ old('nome', $aluno->nome) }}" required class="form-input">
                        @error('nome') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">RM / Matr&iacute;cula *</label>
                        <input type="text" name="rm" value="{{ old('rm', $aluno->rm) }}" required class="form-input">
                        @error('rm') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Turma *</label>
                        <input type="text" name="turma" value="{{ old('turma', $aluno->turma) }}" required class="form-input">
                        @error('turma') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Curso *</label>
                        <input type="text" name="curso" value="{{ old('curso', $aluno->curso) }}" required class="form-input">
                        @error('curso') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="border-t border-gray-200 pt-6">
                <h2 class="text-base font-semibold text-gray-900">Respons&aacute;vel</h2>
                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="form-label">Nome do respons&aacute;vel *</label>
                        <input type="text" name="responsavel" value="{{ old('responsavel', $aluno->responsavel) }}" required class="form-input">
                        @error('responsavel') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Telefone do respons&aacute;vel *</label>
                        <input type="text" name="telefone_responsavel" value="{{ old('telefone_responsavel', $aluno->telefone_responsavel) }}" required class="form-input">
                        @error('telefone_responsavel') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">E-mail do respons&aacute;vel</label>
                        <input type="email" name="email_responsavel" value="{{ old('email_responsavel', $aluno->email_responsavel) }}" class="form-input">
                        @error('email_responsavel') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="border-t border-gray-200 pt-6">
                <input type="hidden" name="ativo" value="0">
                <label class="inline-flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700">
                    <input type="checkbox" name="ativo" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('ativo', $aluno->ativo) ? 'checked' : '' }}>
                    Aluno ativo
                </label>
            </section>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
                <a href="{{ route('alunos.show', $aluno) }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Salvar alteracoes</button>
            </div>
        </form>
    </div>
</div>
@endsection
