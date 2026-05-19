<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('rm')->unique(); // Registro do aluno
            $table->string('turma');
            $table->string('curso');
            $table->string('responsavel'); // Nome do responsável
            $table->string('telefone_responsavel');
            $table->string('email_responsavel')->nullable();
            $table->boolean('ativo')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
