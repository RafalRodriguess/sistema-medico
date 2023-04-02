<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegraCobrancaItem extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = "regras_cobranca_has_itens";

    protected $fillable = [
        'regra_cobranca_id',
        'grupo_procedimento_id',
        'faturamento_id',
        'pago',
        'base',
    ];

    const total = 'total';
    const operacional = 'operacional';
    const honorario = 'honorario';
    
    public static function base()
    {
        return [
            self::total => 'total',
            self::operacional => 'operacional',
            self::honorario => 'honorario',
        ];
    }
    
    public static function baseTexto($texto)
    {
        $dados = [
            self::total => 'Total',
            self::operacional => 'Operacional',
            self::honorario => 'Honorario',
        ];

        return $dados[$texto];
    }

    public function grupoProcedimento()
    {
        return $this->belongsTo(GruposProcedimentos::class, 'grupo_procedimento_id');
    }

    public function faturamento()
    {
        return $this->belongsTo(Faturamento::class, 'faturamento_id');
    }
    
    public function scopeSearch(Builder $query, int $grupo = 0, int $faturamento = 0): Builder
    {
        
        if($grupo != 0){
            $query->where('grupo_procedimento_id', $grupo);
        }

        if($faturamento != 0){
            $query->where('faturamento_id', $faturamento);
        }

        return $query;
    }
}
