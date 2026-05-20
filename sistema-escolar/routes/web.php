<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\OcorrenciaController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Rota raiz redireciona para login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas autenticadas
Route::middleware(['auth'])->group(function () {

    // Dashboard (cada perfil vê o seu)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============================
    // ALUNOS — AQV gerencia, outros visualizam
    // ============================
    Route::resource('alunos', AlunoController::class);

    // ============================
    // OCORRÊNCIAS
    // ============================
    Route::resource('ocorrencias', OcorrenciaController::class)->only([
        'index', 'create', 'store', 'show'
    ]);

    // Ações da AQV
    Route::post('ocorrencias/{ocorrencia}/aprovar', [OcorrenciaController::class, 'aprovar'])
        ->name('ocorrencias.aprovar')
        ->middleware('role:aqv');

    Route::post('ocorrencias/{ocorrencia}/negar', [OcorrenciaController::class, 'negar'])
        ->name('ocorrencias.negar')
        ->middleware('role:aqv');

    // Ação da Portaria
    Route::post('ocorrencias/{ocorrencia}/confirmar-portaria', [OcorrenciaController::class, 'confirmarPortaria'])
        ->name('ocorrencias.confirmar-portaria')
        ->middleware('role:portaria');

    // ============================
    // NOTIFICAÇÕES
    // ============================
    Route::get('/notificacoes', [NotificacaoController::class, 'index'])
        ->name('notificacoes.index');

    Route::post('/notificacoes/{notificacao}/marcar-lida', [NotificacaoController::class, 'marcarLida'])
        ->name('notificacoes.marcar-lida');

    // API para contador de notificações (AJAX)
    Route::get('/api/notificacoes/count', [NotificacaoController::class, 'contarNaoLidas'])
        ->name('api.notificacoes.count');
});

// Rotas de autenticação geradas pelo Breeze
require __DIR__.'/auth.php';


// ============================================================
// REGISTRAR MIDDLEWARE — app/Http/Kernel.php (Laravel 11: bootstrap/app.php)
// ============================================================
// No Laravel 11, adicione em bootstrap/app.php:
//
// ->withMiddleware(function (Middleware $middleware) {
//     $middleware->alias([
//         'role' => \App\Http\Middleware\CheckRole::class,
//     ]);
// })
