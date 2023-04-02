<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class OdontologicoPaciente extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'odontologicos_paciente';

    protected $fillable = [
        'id',
        'paciente_id',
        'agendamento_id',
        'status',
        'valor_total',
        'valor_aprovado',
        'desconto',
        'prestador_id',
        'responsavel_id',
        'negociador_id',
        'data_aprovacao',
        'data_reprovacao',
        'finalizado',
        'data_finalizado',
        'avaliador_id'
    ];

    protected $casts = [
        'data_aprovacao' => 'date',
        'data_reprovacao' => 'date',
        'data_finalizado' => 'date',
    ];

    public function agendamento()
    {
        return $this->belongsTo(Agendamentos::class, 'agendamento_id');
    }
    
    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }
    
    public function negociador()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'negociador_id');
    }
    
    public function responsavel()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'responsavel_id');
    }
    
    public function avaliador()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'avaliador_id');
    }
    
    public function paciente()
    {
        return $this->belongsTo(Pessoa::class, 'paciente_id');
    }
    
    public function itens()
    {
        return $this->hasMany(OdontologicoItemPaciente::class, 'odontologico_paciente_id');
    }

    public function contaReceber()
    {
        return $this->hasMany(ContaReceber::class, 'odontologico_id');
    }

    public function scopeGetQuantidades(Builder $query, $data, $tipo, $aprovado = null):Builder
    {
        if($tipo == 'criado'){
            $query->selectRaw('COUNT(id) as quantidade');
            $query->whereDate('created_at', '>=', $data[0])
            ->whereDate('created_at', '<=', $data[1]);
            $query->where('status', 'criado');

        }else{
            if($aprovado == null){
                $query->selectRaw('COUNT(id) as quantidade, SUM(valor_aprovado) as valor');
                $query->whereDate('data_aprovacao', '>=', $data[0])
                ->whereDate('data_aprovacao', '<=', $data[1]);
                $query->where('status', 'aprovado');
                $query->where('finalizado', 0);

            }else if($aprovado == "em_tratamento"){
                $query->selectRaw('COUNT(id) as quantidade');
                $query->where('status', 'aprovado');
                $query->where('finalizado', 0);

            }else if($aprovado == "finalizados"){
                $query->selectRaw('COUNT(id) as quantidade');
                $query->whereDate('data_finalizado', '>=', $data[0])
                ->whereDate('data_finalizado', '<=', $data[1]);
                $query->where('status', 'aprovado');
                $query->where('finalizado', 1);
            }
        }

        $query->whereHas('paciente', function($q){
            $q->where('instituicao_id', request()->session()->get('instituicao'));
        });

        return $query;
    }

    public function scopeGetOrcamentosDashboard(Builder $query, $dados):Builder
    {
        $query->whereDate('created_at', '>=', $dados[0])
            ->whereDate('created_at', '<=', $dados[1]);

        $query->whereHas('paciente', function($q){
            $q->where('instituicao_id', request()->session()->get('instituicao'));
        });

        $query->where('status', '!=', 'reprovado');
        
        return $query;
    }

    public function scopeGetDemonstrativoOdontologico(Builder $query, $dados): Builder
    {
        $query->whereDate('data_aprovacao', '>=', $dados['data_inicio'])
            ->whereDate('data_aprovacao', '<=', $dados['data_fim']);

        if($dados['avaliadores']){
            $query->where('avaliador_id', $dados['avaliadores']);
        }

        $query->where('status', 'aprovado');
        
        $query->whereHas('itens', function($q) use($dados){
            $q->whereHas('procedimentos', function($qu) use($dados){
                if($dados['convenios']){
                    $qu->whereHas('conveniosTrashed', function($c) use($dados){
                        $c->where('id', $dados['convenios']);
                    });
                }
            });
        });

        $query->whereHas('contaReceber', function($q) use($dados){
            $q->whereIn('conta_id', $dados['contas']);
            $q->whereIn('forma_pagamento', $dados['formas_pagamento']);
        });

        $query->whereIn('negociador_id', $dados['negociadores']);

        $query->whereHas('paciente', function($q){
            $q->where('instituicao_id', request()->session()->get('instituicao'));
        });

        return $query;
    }
    
    public function scopeGetDemonstrativoOdontologicoGrupo(Builder $query, $dados): Builder
    {
        $query->whereDate('data_aprovacao', '>=', $dados['data_inicio'])
            ->whereDate('data_aprovacao', '<=', $dados['data_fim']);

        if($dados['avaliadores']){
            $query->where('avaliador_id', $dados['avaliadores']);
        }

        $query->where('status', 'aprovado');
        
        $query->whereHas('itens', function($q) use($dados){
            $q->whereHas('procedimentos', function($qu) use($dados){
                $qu->whereHas('procedimentoInstituicao', function($g) use($dados){
                    $g->whereIn('grupo_id', $dados['grupos']);
                    if(array_key_exists('procedimentos', $dados)){
                        $g->whereIn('procedimentos_id', $dados['procedimentos']);
                    }
                });
                if($dados['convenios']){
                    $qu->whereHas('conveniosTrashed', function($c) use($dados){
                        $c->where('id', $dados['convenios']);
                    });
                }
            });
        });

        $query->whereHas('contaReceber', function($q) use($dados){
            $q->whereIn('conta_id', $dados['contas']);
            $q->whereIn('forma_pagamento', $dados['formas_pagamento']);
        });

        $query->whereIn('negociador_id', $dados['negociadores']);

        $query->whereHas('paciente', function($q){
            $q->where('instituicao_id', request()->session()->get('instituicao'));
        });

        return $query;
    }

    public function scopeGetOrcamentos(Builder $query, $dados):Builder
    {
        $query->whereDate('created_at', '>=', $dados['data_inicio'])
            ->whereDate('created_at', '<=', $dados['data_fim']);
        
        if ($dados['situacao'] != 'all') {
            $query->where('status', $dados['situacao']);
        }
        
        if(!in_array(null, $dados['negociadores'])){
            $query->whereIn('negociador_id', $dados['negociadores']);
        }
        
        if(!in_array(null, $dados['avaliadores'])){
            $query->whereIn('avaliador_id', $dados['avaliadores']);
        }
        
        if(!in_array(null, $dados['responsaveis'])){
            $query->whereIn('responsavel_id', $dados['responsaveis']);
        }

        $query->whereHas('paciente', function($q){
            $q->where('instituicao_id', request()->session()->get('instituicao'));
        });

        return $query;
    }
    
    public function scopeGetOrcamentosAprovados(Builder $query, $dados):Builder
    {
        $query->whereDate('data_aprovacao', '>=', $dados['data_inicio'])
            ->whereDate('data_aprovacao', '<=', $dados['data_fim']);

        $query->where('status', 'aprovado');

        if(!in_array(null, $dados['negociadores'])){
            $query->whereIn('negociador_id', $dados['negociadores']);
        }

        $query->whereHas('paciente', function($q){
            $q->where('instituicao_id', request()->session()->get('instituicao'));
        });

        return $query;
    }
    
    public function scopeGetProcedimentosNRealizados(Builder $query, $dados):Builder
    {
        $query->whereDate('data_aprovacao', '>=', $dados['data_inicio'])
            ->whereDate('data_aprovacao', '<=', $dados['data_fim']);

        $query->where('status', 'aprovado');

        if(!in_array(null, $dados['contas'])){
            $query->whereHas('contaReceber', function($q) use($dados){
                $q->whereIn('conta_id', $dados['contas']);
            });
        }

        $query->whereHas('itens', function($q){
            $q->where('concluido', 0);
        });
        $query->with(['itens'=> function($q){
            $q->where('concluido', 0);
        }]);

        $query->whereHas('paciente', function($q){
            $q->where('instituicao_id', request()->session()->get('instituicao'));
        });

        return $query;
    }
    
    public function scopeGetOrcamentosConcluidos(Builder $query, $dados):Builder
    {
        $query->whereDate('data_finalizado', '>=', $dados['data_inicio'])
            ->whereDate('data_finalizado', '<=', $dados['data_fim']);

        $query->where('status', 'aprovado');
        $query->where('finalizado', 1);

        if(!in_array(null, $dados['contas'])){
            $query->whereHas('contaReceber', function($q) use($dados){
                $q->whereIn('conta_id', $dados['contas']);
            });
        }

        $query->whereHas('itens', function($q){
            $q->where('concluido', 1);
        });
        $query->with(['itens'=> function($q){
            $q->where('concluido', 1);
        }]);

        $query->whereHas('paciente', function($q){
            $q->where('instituicao_id', request()->session()->get('instituicao'));
        });

        return $query;
    }
}
