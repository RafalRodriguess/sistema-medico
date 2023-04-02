<?php

namespace App;

use App\Support\ModelOverwrite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChatContato extends Model
{
    protected $table = 'chat_contatos';
    protected $fillable = [
        'usuario_origem',
        'usuario_contato',
        'ultima_mensagem_enviada',
        'ultima_mensagem_recebida',
        'prioridade'
    ];

    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'usuario_origem');
    }

    public function usuarioContato()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'usuario_contato');
    }

    public function ultimaMensagem()
    {
        return $this->belongsTo(ChatMensagem::class, 'ultima_mensagem_recebida');
    }

    public static function contatos(InstituicaoUsuario $usuario, Instituicao $instituicao, ?string $busca = '')
    {
        $contatos_origem = ChatContato::select(
                'chat_contatos.id as contato_id',
                'usuario_origem',
                'usuario_contato',
                'prioridade',
                'instituicao_usuarios.*',
                'mensagem_enviada.data_hora as ultimo_envio',
                'mensagem_recebida.id as mensagem_id',
                'mensagem_recebida.mensagem as mensagem_conteudo',
                'mensagem_recebida.data_hora as mensagem_data',
                'mensagem_recebida.visualizada as mensagem_visualizada'
            )
            ->join('instituicao_usuarios', 'instituicao_usuarios.id', 'usuario_contato')
            ->join('instituicao_has_usuarios', 'instituicao_has_usuarios.usuario_id', 'instituicao_usuarios.id')
            ->leftJoin('chat_mensagens as mensagem_enviada', 'ultima_mensagem_enviada', 'mensagem_enviada.id')
            ->leftJoin('chat_mensagens as mensagem_recebida', 'ultima_mensagem_recebida', 'mensagem_recebida.id')
            ->where('instituicao_usuarios.nome', 'like', "%$busca%")
            ->where('instituicao_has_usuarios.instituicao_id', $instituicao->id)
            ->where('usuario_origem', $usuario->id)
            ->orderBy('prioridade', 'DESC')
            ->orderBy('mensagem_enviada.data_hora', 'DESC')
            ->get();

        $contatos_contato = ChatContato::select(
                'chat_contatos.id as contato_id',
                'usuario_origem',
                'usuario_contato',
                'prioridade',
                'instituicao_usuarios.*',
                'mensagem_recebida.data_hora as ultimo_envio',
                'mensagem_enviada.id as mensagem_id',
                'mensagem_enviada.mensagem as mensagem_conteudo',
                'mensagem_enviada.data_hora as mensagem_data',
                'mensagem_enviada.visualizada as mensagem_visualizada'
            )
            ->join('instituicao_usuarios', 'instituicao_usuarios.id', 'usuario_origem')
            ->join('instituicao_has_usuarios', 'instituicao_has_usuarios.usuario_id', 'instituicao_usuarios.id')
            ->leftJoin('chat_mensagens as mensagem_enviada', 'ultima_mensagem_enviada', 'mensagem_enviada.id')
            ->leftJoin('chat_mensagens as mensagem_recebida', 'ultima_mensagem_recebida', 'mensagem_recebida.id')
            ->where('instituicao_usuarios.nome', 'like', "%$busca%")
            ->where('instituicao_has_usuarios.instituicao_id', $instituicao->id)
            ->where('usuario_contato', $usuario->id)
            ->orderBy('prioridade', 'DESC')
            ->orderBy('mensagem_recebida.data_hora', 'DESC')
            ->get();

        return collect($contatos_contato)->merge($contatos_origem);
    }

    public static function getContato(InstituicaoUsuario $contato_a, InstituicaoUsuario $contato_b) : ChatContato
    {
        $contato = ChatContato::where(function($query) use ($contato_a, $contato_b) {
            $query->where('usuario_origem', $contato_a->id)
            ->where('usuario_contato', $contato_b->id);
        })->orWhere(function($query) use ($contato_a, $contato_b) {
            $query->where('usuario_contato', $contato_a->id)
            ->where('usuario_origem', $contato_b->id);
        })->first();
        if(empty($contato)) {
            return ChatContato::create([
                'usuario_origem' => $contato_a->id,
                'usuario_contato' => $contato_b->id,
            ]);
        }
        return $contato;
    }
}
