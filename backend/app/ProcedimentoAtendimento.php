<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcedimentoAtendimento extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'procedimentos_atendimentos';

    protected $fillable = [
        'id',
        'instituicao_id',
        'convenio_id',
        'plano_id',
        'tipo_atendimento',
        'origem_id',
        'unidade_internacao_id',
        'procedimento_id',
    ];

    public function convenio()
    {
        return $this->belongsTo(Convenio::class, 'convenio_id');
    }
    public function plano()
    {
        return $this->belongsTo(ConvenioPlano::class, 'plano_id');
    }
    public function origem()
    {
        return $this->belongsTo(Origem::class, 'origem_id');
    }
    public function unidadeInternacao()
    {
        return $this->belongsTo(UnidadeInternacao::class, 'unidade_internacao_id');
    }
    public function procedimentoOrigem()
    {
        return $this->belongsTo(Procedimento::class, 'procedimento_id');
    }

    public function procedimento()
    {
        return $this->belongsToMany(Procedimento::class, 'procedimentos_atendimentos_has_procedimentos', 'procedimento_atendimento_id', 'procedimento_id')->withPivot('quantidade', 'grupo_faturamento_id', 'procedimento_cod');
    }
    
    public function grupoFaturamento()
    {
        return $this->belongsToMany(GrupoFaturamento::class, 'procedimentos_atendimentos_has_procedimentos', 'procedimento_atendimento_id', 'grupo_faturamento_id')->withPivot('quantidade', 'procedimento_id', 'procedimento_cod');
    }

    public function scopeSearch(Builder $query, int $convenio = 0, int $plano = 0):Builder
    {
        if($convenio != 0){
            $query->where('convenio_id', $convenio);
        }
        if($plano != 0){
            $query->where('plano_id', $plano);
        }

        return $query;
    }

    public function scopeGetRegraItens(Builder $query, $agendamento, $ids):Builder
    {
        
        $query->where('convenio_id', $agendamento->carteirinha->convenio_id);
        $query->where('plano_id', $agendamento->carteirinha->plano_id);

        $query->where('tipo_atendimento', 'ambulatorio');
        $query->whereIn('procedimento_id', $ids);

        $query->with(['procedimento', 'procedimento.procedimentoInstituicaoId', 'procedimento.procedimentoInstituicaoId.grupoProcedimento', 'procedimento.prestadorExcessoes' => function($q) use($agendamento){
            $q->wherePivot('prestador_id', $agendamento->instituicoesAgenda->prestadores->id);
            $q->where('instituicoes_id', request()->session()->get('instituicao'));
        }]);

        return $query;
    }
}
