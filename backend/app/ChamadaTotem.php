<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChamadaTotem extends Model
{
    protected $table = 'chamadas_totem';
    protected $fillable = [
        'senhas_triagem_id',
        'origem_chamada',
        'instituicoes_id',
        'etapa_completa',
        'local',
    ];

    /**
     * As possíveis origens de uma chamada
     * (local onde o paciente foi chamado) este
     * array usa as chaves como IDs e aponta
     * para as slugs que serão utilizada para comparações
     */
    public const origens_chamada = [
        0 => 'guiche',
        1 => 'triagem',
        2 => 'consultorio'
    ];

    /**
     * Constante que relaciona as slugs da constante
     * de origens com nomes para exibição
     */
    public const origens_chamada_nomes = [
        'guiche' => 'Guichê',
        'triagem' => 'Triagem',
        'consultorio' => 'Consultório'
    ];

    /**
     * Retorna o nome de exibição de uma origem a partir
     * de sua slug caso exista
     * @return string|null
     */
    public static function getOrigemText(string $slug)
    {
        return self::origens_chamada_nomes[$slug] ?? null;
    }

    /**
     * Retorna o ID de uma origem a partir de sua slug caso exista
     * @return int|null
     */
    public static function getOrigemId(string $slug)
    {
        $index = array_search($slug, self::origens_chamada);
        return ($index !== false) ? array_keys(self::origens_chamada)[$index] : null;
    }


    /**
     * Chama uma senha para um local caso a mesma não esteja com
     * uma cahamada em andamento (caso etapa_completa = 0 retorna false)
     * @param SenhaTriagem $senha A senha a ser chamada
     * @param string $origem A slug do destino da senha (origem do chamado)
     * @param string $local A informação adicional do local da senha
     * e.g.: $local = 3 quando $origem = Guichê, indica que o portador
     * da senha deve ir ao guichê 3
     * @param bool $completada define se a etapa já foi completada ou não
     * @return ChamadaTotem|false A chamada executada e caso haja uma
     * tentativa de chamar alguem cuja chamada não foi encerrada, retorna
     * false
     */
    public static function chamarSenha(SenhaTriagem $senha, string $origem, string $local = '', bool $completada = false)
    {
        $completada = $completada ? 1 : 0;
        $chamada = null;
        $instituicao = Instituicao::find(request()->session()->get("instituicao"));
        $origem = self::getOrigemId($origem);

        $senha->update(['chamado' => 1]);
        return DB::transaction(function () use (&$chamada, $senha, $origem, $local, $instituicao, $completada) {
            $chamada = self::firstOrCreate([
                'senhas_triagem_id' => $senha->id,
                'instituicoes_id' => $instituicao->id,
            ], [
                'origem_chamada' => $origem,
                'local' => $local,
                'etapa_completa' => $completada,
                'chamado' => 1
            ]);

            abort_unless(!empty($chamada), 403);

            // Decide se somente chama a senha ou move a senha para outra etapa
            if (($chamada->original['local'] ?? -1) == $local && ($chamada->original['origem_chamada'] ?? -1) == $origem) {
                $chamada->update([
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'etapa_completa' => $completada
                ]);
            } else {
                $chamada->update([
                    'origem_chamada' => $origem,
                    'local' => $local,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'etapa_completa' => $completada
                ]);
            }

            return $chamada;
        });
    }

    /**
     * Atualiza a senha como completa
     * @param SenhaTriagem A senha a ser completada
     * @return ChamadaTotem
     */
    public static function completarChamada(SenhaTriagem $senha)
    {
        $chamada = null;
        $instituicao = Instituicao::find(request()->session()->get("instituicao"));
        $senha->update(['chamado' => 0]);
        DB::transaction(function () use (&$chamada, $senha, $instituicao) {
            $chamada = self::firstOrCreate([
                'senhas_triagem_id' => $senha->id,
                'instituicoes_id' => $instituicao->id,
            ], [
                'origem_chamada' => 0
            ]);
            $chamada->update(['etapa_completa' => 1]);
        });

        return $chamada;
    }

    /**
     * Verifica se a senha já passou por determinada etapa, de inicio
     * as etapas serão verificadas pela ordem do id na constante
     * de origens da ChamadaTotem
     * @param SenhaTriagem $senha A senha a ser verificada
     * @param int $origem O local a ser verificado se a senha já passou por
     * @param boolean Retorna true caso a senha já tenha passado por uma etapa
     */
    public static function passouPor(SenhaTriagem $senha, string $slug_origem): bool
    {
        $chamada = $senha->chamadaTotem()->first();
        if (!empty($chamada)) {
            return $chamada->origem_chamada > self::getOrigemId($slug_origem) || $chamada->origem_chamada == self::getOrigemId($slug_origem) && $chamada->etapa_completa;
        } else {
            return false;
        }
    }

    public function senha()
    {
        return $this->belongsTo(SenhaTriagem::class, 'senhas_triagem_id');
    }
}
