<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movimentacao extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'movimentacoes';

    protected $fillable = [
        'instituicao_id',
        'tipo_movimentacao',
        'data',
        'conta_id_origem',
        'conta_id_destino',
        'valor',
        'obs',
        'usuario_instituicao_id',
    ];

    protected $cast = [
        'data' => 'date',
    ];

    const transferencia = 'transferencia';
    const resgate = 'resgate';
    const deposito = 'deposito';

    public static function natureza_para_texto($natureza = null)
    {
        $dados = [
            self::deposito => 'Depósito',
            self::transferencia => 'Transferência entre contas',
            self::resgate => 'Resgate de aplicação',
        ];

        if($natureza == null){
            return $dados;
        }else{
            return $dados[$natureza];
        }
    }

    public static function naturezas()
    {
        return [
            self::deposito,
            self::resgate,
            self::transferencia,
        ];
    }
    
    public static function naturezas_all()
    {
        return [
            self::deposito,
            self::resgate,
            self::transferencia,
        ];
    }

    public function usuarioInstituicao()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'usuario_instituicao_id');
    }
    
    public function contaOrigem()
    {
        return $this->belongsTo(Conta::class, 'conta_id_origem');
    }
    
    public function contaDestino()
    {
        return $this->belongsTo(Conta::class, 'conta_id_destino');
    }

    public function contaPagar()
    {
        return  $this->hasMany(ContaPagar::class, 'movimentacao_id');
    }
    
    public function contaReceber()
    {
        return  $this->hasMany(ContaReceber::class, 'movimentacao_id');
    }

    public function scopeSearch(Builder $query, int $conta_origem = 0, int $conta_destino = 0, string $data_inicio = '', string $data_fim = ''): Builder
    {
        if(!empty($data_inicio)){            
            $query->where('data', '>=', $data_inicio);
        }

        if(!empty($data_fim)){            
            $query->where('data', '<=', $data_fim);
        }

        if($conta_origem > 0){
            $query->where('conta_id_origem', $conta_origem);
        }

        if($conta_destino > 0){
            $query->where('conta_id_destino', $conta_destino);
        }

        return $query;
    }
}
