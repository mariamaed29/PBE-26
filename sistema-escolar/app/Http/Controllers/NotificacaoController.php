<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public function index()
    {
        $notificacoes = auth()->user()->notificacoes()
            ->with('ocorrencia.aluno')
            ->latest()
            ->paginate(20);

        // Marca todas como lidas ao acessar a página
        auth()->user()->notificacoesNaoLidas()->update([
            'lida'    => true,
            'lida_em' => now(),
        ]);

        return view('notificacoes.index', compact('notificacoes'));
    }

    public function marcarLida(Notificacao $notificacao)
    {
        if ($notificacao->usuario_id !== auth()->id()) {
            abort(403);
        }

        $notificacao->marcarComoLida();

        return response()->json(['success' => true]);
    }

    public function contarNaoLidas()
    {
        $count = auth()->user()->notificacoesNaoLidas()->count();

        return response()->json(['count' => $count]);
    }
}

