<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public function index()
    {
        $notificacoes = Notificacao::where('usuario_id', auth()->id())
            ->with('ocorrencia.aluno')
            ->latest()
            ->paginate(20);

        // Marca todas como lidas ao acessar a página
        Notificacao::where('usuario_id', auth()->id())
            ->where('lida', false)
            ->update([
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
        $count = Notificacao::where('usuario_id', auth()->id())
            ->where('lida', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}

