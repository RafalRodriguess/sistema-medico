<?php

namespace App;

use App\Support\ModelPossuiLogs;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendamentoProcedimento extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'agendamentos_procedimentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'valor_atual',
        'valor_convenio',
        'agendamentos_id',
        'procedimentos_instituicoes_convenios_id',
        'tipo',
        'valor_repasse',
        'qtd_procedimento',
        'desconto',
        'tipo_cartao',
        'valor_repasse_cartao',
    ];

    public function agendamentos()
    {
        return $this->belongsTo(Agendamentos::class, 'agendamentos_id');
    }
    
    public function procedimentoInstituicaoConvenio()
    {
        return $this->belongsTo(ConveniosProcedimentos::class, 'procedimentos_instituicoes_convenios_id');
    }
    
    public function procedimentoInstituicaoConvenioTrashed()
    {
        return $this->belongsTo(ConveniosProcedimentos::class, 'procedimentos_instituicoes_convenios_id')->withTrashed();
    }

    public function scopeGetIdProcedimentos(Builder $query):Builder
    {
        $query->with(['procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento' => function($q){
            $q->select('id');
        }]);

        return $query;
    }
}
