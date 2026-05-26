@extends('layouts.app')
@section('title', 'Novo Aluno')
@section('page-title', 'Cadastrar Aluno')

@section('content')
<div class="pt-4">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-500">Preencha os dados cadastrais e de contato do respons&aacute;vel.</p>
        <a href="{{ route('alunos.index') }}" class="btn-secondary">Voltar</a>
    </div>

    <div class="panel max-w-4xl">
        <form method="POST" action="{{ route('alunos.store') }}" class="space-y-6 p-6">
            @csrf

            <section>
                <h2 class="text-base font-semibold text-gray-900">Dados do aluno</h2>
                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="form-label">Nome completo *</label>
                        <input type="text" name="nome" value="{{ old('nome') }}" required class="form-input" placeholder="Ex: Ana Clara Santos">
                        @error('nome') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">RM / Matr&iacute;cula *</label>
                        <input type="text" name="rm" value="{{ old('rm') }}" required class="form-input" placeholder="Ex: 12345">
                        @error('rm') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Turma *</label>
                        <input type="text" name="turma" value="{{ old('turma') }}" required class="form-input" placeholder="Ex: 3&ordm;A">
                        @error('turma') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Curso *</label>
                        <input type="text" name="curso" value="{{ old('curso') }}" required class="form-input" placeholder="Ex: Desenvolvimento de Sistemas">
                        @error('curso') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Professor respons&aacute;vel *</label>
                        <select name="professor_id" required class="form-input">
                            <option value="">Selecione o professor</option>
                            @foreach($professores as $professor)
                                <option value="{{ $professor->id }}" {{ old('professor_id') == $professor->id ? 'selected' : '' }}>
                                    {{ $professor->name }} - {{ $professor->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('professor_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="border-t border-gray-200 pt-6">
                <h2 class="text-base font-semibold text-gray-900">Respons&aacute;vel</h2>
                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="form-label">Nome do respons&aacute;vel *</label>
                        <input type="text" name="responsavel" value="{{ old('responsavel') }}" required class="form-input">
                        @error('responsavel') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Telefone do respons&aacute;vel *</label>
                        <input type="text" name="telefone_responsavel" value="{{ old('telefone_responsavel') }}" required class="form-input" placeholder="(00) 00000-0000">
                        @error('telefone_responsavel') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">E-mail do respons&aacute;vel</label>
                        <input type="email" name="email_responsavel" value="{{ old('email_responsavel') }}" class="form-input" placeholder="responsavel@email.com">
                        @error('email_responsavel') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
                <a href="{{ route('alunos.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Cadastrar aluno</button>
            </div>
        </form>
    </div>
</div>
@endsection
