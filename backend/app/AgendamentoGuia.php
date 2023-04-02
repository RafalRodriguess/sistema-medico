<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendamentoGuia extends Model
{
    // use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'agendamentos_guias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'agendamento_id',
        'cod_aut_convenio',
        'num_guia_convenio',
        'tipo_guia',
    ];

    public function agendamentos()
    {
        return $this->belongsTo(Agendamentos::class, 'agendamento_id');
    }
    
    // public function procedimentoInstituicaoConvenio()
    // {
    //     return $this->belongsTo(ConveniosProcedimentos::class, 'procedimentos_instituicoes_convenios_id');
    // }

    // public function scopeGetIdProcedimentos(Builder $query):Builder
    // {
    //     $query->with(['procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento' => function($q){
    //         $q->select('id');
    //     }]);

    //     return $query;
    // }
}
