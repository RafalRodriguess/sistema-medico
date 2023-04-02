<?php

namespace App;

use App\Casts\Encrypted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ChatMensagem extends Model
{
    protected $table = 'chat_mensagens';
    protected $fillable = [
        'instituicao_usuarios_remetente',
        'instituicao_usuarios_destinatario',
        'mensagem',
        'visualizada'
    ];

    public $timestamps = false;

    protected $casts = [
        'mensagem' => Encrypted::class
    ];

    public function remetente()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'instituicao_usuarios_remetente');
    }

    public function destinatario()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'instituicao_usuarios_destinatario');
    }

    public function destinatarioInstituicao()
    {
        // return $this->hasOneThrough()
    }

    public static function decifrarMensagem(string $mensagem_criptografada, ?int $tamanho = null)
    {
        $message = (new Encrypted())->get(new ChatMensagem(), 0, $mensagem_criptografada, []);
        if($tamanho != null && $tamanho > 0) {
            return mb_strimwidth($message, 0, $tamanho, '...');
        }
        return $message;
    }

    public static function mensagens(InstituicaoUsuario $usuario, InstituicaoUsuario $contato, bool $visualizar = true, int $pages = 1)
    {
        $mensagens = self::where(function ($query) use ($usuario, $contato) {
                $query->where('instituicao_usuarios_remetente', $usuario->id)
                ->where('instituicao_usuarios_destinatario', $contato->id);
            })->orWhere(function ($query) use ($usuario, $contato) {
                $query->where('instituicao_usuarios_destinatario', $usuario->id)
                    ->where('instituicao_usuarios_remetente', $contato->id);
            })
            ->orderBy('data_hora', 'asc')
            ->simplePaginate(30 * $pages);

        if ($visualizar) {
            self::where('instituicao_usuarios_destinatario', $usuario->id)
                ->where('instituicao_usuarios_remetente', $contato->id)
                ->update([
                    'visualizada' => true
                ]);
        }

        return $mensagens;
    }
}
