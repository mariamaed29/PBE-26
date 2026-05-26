<?php

namespace Database\Seeders;

use App\Models\Aluno;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'AQV - Ana Paula',
            'email'    => 'aqv@escola.com',
            'password' => Hash::make('password'),
            'role'     => 'aqv',
        ]);

        User::create([
            'name'     => 'Portaria - Carlos',
            'email'    => 'portaria@escola.com',
            'password' => Hash::make('password'),
            'role'     => 'portaria',
        ]);

        $professoraMaria = User::create([
            'name'     => 'Prof. Maria Silva',
            'email'    => 'professor@escola.com',
            'password' => Hash::make('password'),
            'role'     => 'professor',
        ]);

        $professorJoao = User::create([
            'name'     => 'Prof. Joao Souza',
            'email'    => 'professor2@escola.com',
            'password' => Hash::make('password'),
            'role'     => 'professor',
        ]);

        $alunos = [
            ['nome' => 'Pedro Almeida', 'rm' => '2024001', 'turma' => '3A', 'curso' => 'Desenvolvimento de Sistemas'],
            ['nome' => 'Julia Santos', 'rm' => '2024002', 'turma' => '3A', 'curso' => 'Desenvolvimento de Sistemas'],
            ['nome' => 'Lucas Oliveira', 'rm' => '2024003', 'turma' => '2B', 'curso' => 'Administracao'],
            ['nome' => 'Fernanda Costa', 'rm' => '2024004', 'turma' => '2B', 'curso' => 'Administracao'],
            ['nome' => 'Thiago Martins', 'rm' => '2024005', 'turma' => '1C', 'curso' => 'Contabilidade'],
            ['nome' => 'Camila Rocha', 'rm' => '2024006', 'turma' => '1C', 'curso' => 'Contabilidade'],
            ['nome' => 'Rafael Lima', 'rm' => '2024007', 'turma' => '3B', 'curso' => 'Desenvolvimento de Sistemas'],
            ['nome' => 'Isabela Ferreira', 'rm' => '2024008', 'turma' => '2A', 'curso' => 'Logistica'],
        ];

        foreach ($alunos as $alunoData) {
            Aluno::create([
                ...$alunoData,
                'professor_id'          => in_array($alunoData['turma'], ['3A', '3B']) ? $professoraMaria->id : $professorJoao->id,
                'responsavel'           => 'Responsavel de ' . explode(' ', $alunoData['nome'])[0],
                'telefone_responsavel'  => '(19) 9' . rand(1000, 9999) . '-' . rand(1000, 9999),
                'email_responsavel'     => strtolower(str_replace(' ', '.', $alunoData['nome'])) . '@email.com',
                'ativo'                 => true,
            ]);
        }

        $this->command->info('Seed executado com sucesso!');
        $this->command->table(
            ['E-mail', 'Senha', 'Perfil'],
            [
                ['aqv@escola.com', 'password', 'AQV'],
                ['portaria@escola.com', 'password', 'Portaria'],
                ['professor@escola.com', 'password', 'Professor'],
                ['professor2@escola.com', 'password', 'Professor'],
            ]
        );
    }
}
