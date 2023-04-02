<?php

namespace App\Http\Controllers\Instituicao;

use App\ChatContato;
use App\ChatMensagem;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\EnviarMensagemRequest;
use App\Http\Requests\ChatApp\BuscarContatosRequest;
use App\Http\Requests\ChatApp\BuscarNotificacoesRequest;
use App\Instituicao;
use App\InstituicaoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use stdClass;

class Chat extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'utilizar_chat');
        $usuario = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $tab = $request->get('tab', -1);
        if (!$instituicao->instituicaoUsuarios()->where('id', $tab)->exists()) {
            $tab = -1;
        }

        return view('instituicao.chat.index', \compact(
            'usuario',
            'tab'
        ));
    }

    public function buscarMensagens(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'utilizar_chat');
        $usuario = $request->user('instituicao');

        $ultima_mensagem_front = $request->get('ultima_mensagem', null);
        $contato = InstituicaoUsuario::find($request->get('contato', -1));
        $paginas = (int)$request->get('pages', 1);

        if (!empty($contato)) {
            $mensagens = ChatMensagem::mensagens($usuario, $contato, true, $paginas);
            $ultima_mensagem_back = $mensagens->last();
            if (empty($ultima_mensagem_front) || (new \DateTime($ultima_mensagem_back->data_hora)) > (new \DateTime($ultima_mensagem_front))) {
                return response()->json([
                    'mensagens' => $mensagens,
                    'result' => true,
                    'html' => view('instituicao.chat.mensagens', \compact('usuario', 'mensagens'))->toHtml()
                ]);
            }
        }
        return response()->json([
            'result' => false
        ]);
    }

    public function buscarContatos(BuscarContatosRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'utilizar_chat');
        $dados = collect($request->validated());
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $usuario = $request->user('instituicao');

        $busca = $dados->get('busca');
        $possui_alteracoes = true;
        // Deve chegar um array com todos os ids no front, mas eles devem estar nas chaves do array
        $ultimos_contatos = $dados->get('ultimos_contatos');
        // Id do contato mais recente no front-end, caso re-ordenações devam ser exibidas
        $contato_recente = $dados->get('contato_recente');
        $ignorar_ordenacoes = !empty($dados->get('ignorar_ordenacoes'));
        $exibir_ultima_mensagem = !empty($dados->get('exibir_ultima_mensagem'));
        $contatos = ChatContato::contatos($usuario, $instituicao, $busca);
        if (!empty($ultimos_contatos)) {
            $possui_alteracoes = false;
            $ultimos_contatos = collect($ultimos_contatos);
            $tamanho_ultimos_contatos = $ultimos_contatos->count();
            $tamanho_contatos = 0;
            $contatos->map(function ($contato) use ($ultimos_contatos, &$possui_alteracoes, &$tamanho_contatos, &$test) {
                $possui_alteracoes |=
                    ($ultimos_contatos[$contato->id] ?? null) === null ||
                    ($ultimos_contatos[$contato->id] >= 0 &&
                        ($contato->mensagem_visualizada ?? null) !== null &&
                        $ultimos_contatos[$contato->id] != ($contato->mensagem_visualizada ?? null)
                    );
                $tamanho_contatos++;
            });
            // Caso contatos esteja contido mas não igual ao cache
            $possui_alteracoes |= $tamanho_contatos != $tamanho_ultimos_contatos;
            $possui_alteracoes |= !$ignorar_ordenacoes && !empty($contato_recente) && $tamanho_contatos > 0 && $contato_recente != $contatos[0]->id;
        }

        if ($possui_alteracoes) {
            return response()->json([
                'contatos' => $contatos,
                'result' => true,
                'html' => view('instituicao.chat.contatos', \compact(
                    'usuario',
                    'contatos',
                    'exibir_ultima_mensagem'
                ))->toHtml()
            ]);
        } else {
            return response()->json(['result' => false]);
        }
    }

    public function buscarUsuarios(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'utilizar_chat');

        $busca = $request->get('busca');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario_logado = $request->user('instituicao');

        $contatos = DB::table('instituicao_usuarios')
            ->select('instituicao_usuarios.*')
            ->join('instituicao_has_usuarios', 'usuario_id', 'instituicao_usuarios.id')
            ->where('instituicao_id', $instituicao->id)
            ->where('usuario_id', '!=', $usuario_logado->id)
            ->where('nome', 'like', "%$busca%")
            ->simplePaginate(50);

        return response()->json([
            'contatos' => $contatos,
            'result' => true,
            'html' => view('instituicao.chat.contatos', [
                'usuario' => $usuario_logado,
                'contatos' => $contatos,
                'exibir_ultima_mensagem' => false,
                'adicionar_contato' => true
            ])->toHtml()
        ]);
    }

    public function adicionarContato(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'utilizar_chat');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario_logado = $request->user('instituicao');
        $usuario_contato = $request->validate([
            'usuario_id' => ['required', Rule::exists('instituicao_has_usuarios', 'usuario_id')->where('instituicao_id', $instituicao->id)]
        ]);

        $contato = null;
        DB::transaction(function () use ($usuario_logado, $usuario_contato, &$contato) {
            $contato = ChatContato::getContato($usuario_logado, InstituicaoUsuario::find($usuario_contato)->first());
        });

        if (!empty($contato)) {
            return response()->json([
                'contato' => $contato,
                'result' => true
            ]);
        } else {
            return response()->json([
                'result' => false
            ]);
        }
    }

    public function enviarMensagem(EnviarMensagemRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'utilizar_chat');
        $usuario = $request->user('instituicao');
        $dados = collect($request->validated());
        abort_if($usuario->id == $dados->get('destinatario'), 403);

        DB::transaction(function () use ($dados, $usuario) {
            $contato_destinatario = InstituicaoUsuario::find($dados->get('destinatario'));
            $mensagem = ChatMensagem::create([
                'instituicao_usuarios_remetente' => $usuario->id,
                'instituicao_usuarios_destinatario' => $contato_destinatario->id,
                'mensagem' => strip_tags($dados->get('mensagem'))
            ]);

            $contato = ChatContato::getContato($usuario, $contato_destinatario);
            if ($contato->usuario_origem == $usuario->id) {
                $contato->update(['ultima_mensagem_enviada' => $mensagem->id]);
            } else {
                $contato->update(['ultima_mensagem_recebida' => $mensagem->id]);
            }
        });
    }

    public function notificacoes(BuscarNotificacoesRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'utilizar_chat');
        $usuario = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $ultima_mensagem_front = $request->validated()['ultima_mensagem'] ?? null;
        // Método que limita a quantidade de resultados de busca para a quantidade de contatos
        $mensagens = ChatMensagem::join('instituicao_usuarios', 'chat_mensagens.instituicao_usuarios_remetente', 'instituicao_usuarios.id')
            ->select('*', 'chat_mensagens.id as mensagem_id')
            ->join('instituicao_has_usuarios', 'instituicao_has_usuarios.usuario_id', 'instituicao_usuarios.id')
            ->where('instituicao_has_usuarios.instituicao_id', '=', $instituicao->id)
            ->where('chat_mensagens.instituicao_usuarios_destinatario', '=', $usuario->id)
            ->where('visualizada', 0)
            ->orderBy('data_hora', 'DESC')
            ->limit(40)
            ->get();

        $visitados = [];
        $cont_mensagens = 0;
        $ultima_mensagem_db = $mensagens[0] ?? null;
        $mensagens = $mensagens->filter(function ($mensagem) use (&$visitados, &$cont_mensagens) {
            if (!($visitados[$mensagem->instituicao_usuarios_remetente] ?? false)) {
                $visitados[$mensagem->instituicao_usuarios_remetente] = true;
                $cont_mensagens++;
                return true;
            } else {
                return false;
            }
        });

        if (
            empty($ultima_mensagem_front) ||
            (!empty($ultima_mensagem_front) && $cont_mensagens == 0) ||
            (!empty($ultima_mensagem_db) && (new \DateTime($ultima_mensagem_front)) < (new \DateTime($ultima_mensagem_db->data_hora)))
        ) {
            return response()->json([
                'mensagens' => $mensagens,
                'result' => true,
                'html' => view('instituicao.chat.notificacoes', \compact('mensagens'))->toHtml()
            ]);
        } else {
            return response()->json(['result' => false]);
        }
    }

    public function getImagemUsuario(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'utilizar_chat');
        $usuario = InstituicaoUsuario::find($request->validate([
            'usuario' => ['exists:instituicao_usuarios,id', 'required']
        ])['usuario']);

        return view('instituicao.chat.imagem-usuario', \compact('usuario'))->toHtml();
    }
}
