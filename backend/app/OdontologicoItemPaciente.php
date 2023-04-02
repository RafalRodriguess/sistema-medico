<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OdontologicoItemPaciente extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'odontologico_itens_paciente';

    protected $fillable = [
        'id',
        'odontologico_paciente_id',
        'status',
        'valor',
        'dente_id',
        'procedimento_instituicao_convenio_id',
        'regiao_procedimento_id',
        'concluido',
        'data_conclusao',
        'tipo',
        'valor_repasse',
        'valor_convenio',
        'prestador_id',
        'procedimento_id',
        'valor_custo',
        'desconto',
        'laboratorio',
    ];

    protected $casts = [
        'data_conclusao' => 'date'
    ];

    public function odontologico()
    {
        return $this->belongsTo(OdontologicoPaciente::class, 'odontologico_paciente_id');
    }

    public function procedimentos()
    {
        return $this->belongsTo(ConveniosProcedimentos::class, 'procedimento_instituicao_convenio_id')->withTrashed();
    }

    public function regiao()
    {
        return $this->belongsTo(RegiaoProcedimento::class, 'regiao_procedimento_id');
    }

    public function regiaoProcedimento()
    {
        return $this->belongsToMany(RegiaoProcedimento::class, 'odontologico_item_has_regiao', 'odontologico_item_id', 'regiao_id');
    }

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }

    public function procedimentosItens()
    {
        return $this->belongsTo(Procedimento::class, 'procedimento_id');
    }

    public function scopeGetProcedimentosDashboard(Builder $query, $data):Builder
    {
        $query->selectRaw('COUNT(id) as quantidade, procedimento_instituicao_convenio_id');
        $query->whereDate('created_at', '>=', $data[0])
            ->whereDate('created_at', '<=', $data[1]);

        $query->whereHas('procedimentos.procedimentoInstituicao', function($q){
            $q->where('instituicoes_id', request()->session()->get('instituicao'));
            $q->whereHas('procedimento');
        });
        
        $query->with(['procedimentos.procedimentoInstituicao' => function($q){
            $q->where('instituicoes_id', request()->session()->get('instituicao'));
            $q->with('procedimento');
        }]);

        $query->groupBy('procedimento_instituicao_convenio_id');
        return $query;
    }

    public function scopeGetRepasseOdontologico(Builder $query, $dados): Builder
    {

        $query->whereIn('prestador_id', $dados['prestadores']);
        
        $query->whereHas('odontologico.contaReceber', function($q) use($dados){
            $q->whereIn('forma_pagamento', $dados['formas_pagamento']);
        });
        
        $query->whereHas('procedimentos.convenios', function($q) use($dados){
            $q->whereIn('id', $dados['convenios']);
        });

        if(array_key_exists('procedimentos', $dados)){
            $query->whereIn('procedimento_id', $dados['procedimentos']);
        }

        $query->whereDate('data_conclusao', '>=', $dados['data_inicio'])
            ->whereDate('data_conclusao', '<=', $dados['data_fim']);

        $query->whereHas('odontologico.paciente', function($q){
            $q->where('instituicao_id', request()->session()->get('instituicao'));
        });

        $query->orderBy('data_conclusao', 'DESC');

        return $query;
    }

    public function scopeGetTotalProcedimentos(Builder $query, $dados):Builder
    {

        $query->whereHas('procedimentos.procedimentoInstituicao', function($g) use($dados){
            $g->whereIn('grupo_id', $dados['grupos']);
            if(array_key_exists('procedimentos', $dados)){
                $g->whereIn('procedimentos_id', $dados['procedimentos']);
            }
        });

        return $query;
    }
}
