<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ocorrencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('aqv_id')->constrained('users')->onDelete('cascade'); // Usuário AQV
            $table->enum('tipo', ['entrada_atrasada', 'saida_antecipada']);
            $table->text('motivo');
            $table->enum('status', ['pendente', 'aprovado', 'negado'])->default('pendente');
            $table->timestamp('data_ocorrencia');
            $table->timestamp('data_autorizacao')->nullable();
            $table->timestamp('confirmacao_portaria')->nullable(); // Portaria confirmou
            $table->foreignId('portaria_id')->nullable()->constrained('users'); // Quem confirmou na portaria
            $table->text('observacao')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ocorrencias');
    }
};