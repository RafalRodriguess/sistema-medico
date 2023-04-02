<?php

namespace App;

use App\Casts\Checkbox;
use App\Support\ModelPossuiLogs;
use App\Support\ModelOverwrite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\ProcessoFilaTriagem;

/**
 * Representa uma fila para triagem
 */
class FilaTriagem extends Model
{
    use ModelPossuiLogs;
    use ModelOverwrite;

    protected $table = 'filas_triagem';

    protected $fillable = [
        'instituicoes_id',
        'descricao',
        'identificador',
        'origens_id',
        'ativo',
        'prioridade'
    ];

    protected $allowed_overwrite = [
        ProcessoFilaTriagem::class
    ];

    protected $casts = [
        'ativo' => Checkbox::class,
        'prioridade' => Checkbox::class
    ];

    public static function boot() {
        parent::boot();

        // Limpar o cache da instituicao cuja fila foi inserida

        // Depois de inserir
        static::created(function (FilaTriagem $item) {
            Cache::forget('fila_triagem/identificadores/instituicao/'.$item->instituicoes_id);
        });

        // Depois de alterar
        static::updated(function (FilaTriagem $item) {
            Cache::forget('fila_triagem/identificadores/instituicao/'.$item->instituicoes_id);
        });

        // Depois de deletar
        static::deleted(function (FilaTriagem $item) {
            Cache::forget('fila_triagem/identificadores/instituicao/'.$item->instituicoes_id);
        });
    }

    /**
     * Gera uma lista de letras que podem ser utilizadas no sistema
     * a lista vai de A até Z, por ser uma operação de ordem 26 ^ comprimento
     * esta função guarda o resultado indefinitivamente no cache, só executando
     * essa operação novamente caso o cache seja apagado manualmente.
     * @param int $comprimento_identificador Representa a quantidade de caracteres
     * que serão gerados
     * @return string[] Um array com os identificadores permitidos
     */
    public static function getAllIdentificadores($comprimento_identificador = 2)
    {
        // Evitando rodar o commando com o uso de cache
        $resultado = Cache::get('fila_triagem/identificadores', []);
        if(!empty($resultado)) {
            return $resultado;
        }

        $alfabeto = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $aux = [$alfabeto];
        $resultado = $alfabeto;

        // Gerar letra recursivametne
        $generate = function($anterior) use ($alfabeto) {
            $temp = [];
            foreach($anterior as $char) {
                for($i = 0; $i < 26; $i ++) {
                    array_push($temp, $char . $alfabeto[$i]);
                }
            }
            return $temp;
        };

        // Gerando o resultado baseado no comprimento e o passe anterior
        for ($i = 1; $i < $comprimento_identificador; $i ++) {
            $aux[$i] = $generate($aux[$i - 1]);
            $resultado = array_merge($resultado, $aux[$i]);
        }

        Cache::store()->put('fila_triagem/identificadores', $resultado);
        return $resultado;
    }

    /**
     * Operação que retorna os identificadores que podem ser usados no momento
     * ela também, por ser de alto custo, é guardada no cache da instituição da fila
     * este cache é armazenado até que outra fila da mesma instituição seja criada ou
     * alterada
     * @param int $instituicoes_id O identificador da instituição
     * @param int $identificador_atual O identificador da fila que está sendo alterada
     * inserindo um permite que seja exibido o identificador da fila atual na lista mesmo
     * ela já existindo no banco de dados
     */
    public static function getAvailableIdenficadores($instituicoes_id, $identificador_atual = null)
    {
        // Por ser uma operação muito pesada, o uso de cache é obrigatório
        $identificadores = Cache::get('fila_triagem/identificadores/instituicao/'.$instituicoes_id, []);
        if(empty($identificadores) || $identificador_atual != null) {
            $selected = self::where('instituicoes_id', '=', $instituicoes_id)->get()->pluck('identificador')->toArray();
            $identificadores = collect(self::getAllIdentificadores())->reject(function($item, $key) use ($selected, $identificador_atual) {
                return in_array($item, $selected) && $item != $identificador_atual;
            })->toArray();
            Cache::store()->put('fila_triagem/identificadores/instituicao/'.$instituicoes_id, $identificadores);
        }
        return $identificadores;
    }

    public function getIdentificadorAttribute()
    {
        return strtoupper($this->attributes['identificador']);
    }

    public function setIdentificadorAttribute($value)
    {
        $this->attributes['identificador'] = strtoupper($value);
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicoes_id');
    }

    public function origem()
    {
        return $this->belongsTo(Origem::class, 'origens_id');
    }

    public function filasTotem()
    {
        return $this->hasMany(FilaTotem::class, 'filas_triagem_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%")->orWhere('identificador', 'like', "%{$search}%");
    }

    public function processosFilaTriagem()
    {
        return $this->hasMany(ProcessoFilaTriagem::class, 'filas_triagem_id');
    }

    public function processos()
    {
        return $this->hasManyThrough(ProcessoTriagem::class, ProcessoFilaTriagem::class, 'filas_triagem_id', 'id', 'id', 'processos_triagem_id');
    }

    public function processosCompletos()
    {
        return $this->processos()
            ->select('processos_triagem.*', 'processos_fila_triagem.ordem');
    }
}
