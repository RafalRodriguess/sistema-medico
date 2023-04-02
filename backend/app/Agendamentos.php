<?php

namespace App;

use App\Support\ModelPossuiLogs;
use App\Support\TraitLogInstituicao;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Agendamentos extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    private static $cacheSetores = [];

    protected $table = 'agendamentos';

    protected $fillable = [
        'id',
        'tipo',
        'data',
        'status',
        'valor_total',
        'instituicoes_agenda_id',
        'usuario_id',
        'parcelas',
        'porcento_parcela',
        'free_parcelas',
        'cartao_id',
        'status_pagamento',
        'codigo_transacao',
        'motivo_cancelamento',
        'forma_pagamento',
        'troco_dinheiro',
        'pessoa_id',
        'desconto',
        'acompanhante',
        'acompanhante_relacao',
        'acompanhante_nome',
        'acompanhante_telefone',
        'obs',
        'solicitante_id',
        'status_motivo',
        'carteirinha_id',
        'cod_aut_convenio',
        'num_guia_convenio',
        'arquivo_guia_convenio',
        'tipo_guia',
        'compromisso_id',
        'id_referente',
        'data_original',
        'data_final',
        'data_final_original',
        'cpf_acompanhante',
        'boleto_acompanhante',
        'tipo_agenda',
        'teleatendimento',
        'internacao_id',
        'profissional_id',
        'motivo_desistencia',

    ];

    // protected $dates = [
    //     'data',
    // ];

    public function prestador()
    {
        return $this->instituicoesPrestadores()
            ->first()
            ->prestador();
    }

    public function instituicoesPrestadores()
    {
        return $this->hasOneThrough(InstituicoesPrestadores::class, InstituicoesAgenda::class, 'id', 'id', 'instituicoes_agenda_id', 'instituicoes_prestadores_id');
    }

    public function instituicoesAgenda()
    {
        return $this->belongsTo(InstituicoesAgenda::class, 'instituicoes_agenda_id')->withTrashed();
    }

    public function instituicoesAgendaGeral()
    {
        return $this->belongsTo(InstituicoesAgenda::class, 'instituicoes_agenda_id')->withTrashed();
    }

    public function instituicaoAgendaNovo()
    {
        return $this->belongsTo(instituicaoAgendaNovo::class, 'instituicoes_agenda_id');
    }

    public function atendimento()
    {
        return $this->hasMany(AgendamentoAtendimento::class, 'agendamento_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function agendamentoProcedimento()
    {
        return $this->hasMany(AgendamentoProcedimento::class, 'agendamentos_id');
    }

    public function conveniosProcedimentos()
    {
        return $this->hasManyThrough(ConveniosProcedimentos::class, AgendamentoProcedimento::class, 'agendamentos_id', 'id', 'id', 'procedimentos_instituicoes_convenios_id');
    }

    public function agendamentoProcedimentoTashed()
    {
        return $this->hasMany(AgendamentoProcedimento::class, 'agendamentos_id')->withTrashed();
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id')->withTrashed();
    }

    public function internacao()
    {
        return $this->belongsTo(Internacao::class, 'internacao_id')->withTrashed();
    }

    public function prontuario()
    {
        return $this->hasMany(ProntuarioPaciente::class, 'agendamento_id');
    }

    public function receituario()
    {
        return $this->hasMany(ReceituarioPaciente::class, 'agendamento_id');
    }
    public function atestado()
    {
        return $this->hasMany(AtestadoPaciente::class, 'agendamento_id');
    }
    public function conclusao()
    {
        return $this->hasMany(ConclusaoPaciente::class, 'agendamento_id');
    }
    public function laudo()
    {
        return $this->hasMany(LaudoPaciente::class, 'agendamento_id');
    }
    public function encaminhamento()
    {
        return $this->hasMany(EncaminhamentoPaciente::class, 'agendamento_id');
    }
    public function relatorio()
    {
        return $this->hasMany(RelatorioPaciente::class, 'agendamento_id');
    }
    public function exame()
    {
        return $this->hasMany(ExamePaciente::class, 'agendamento_id');
    }
    public function refracao()
    {
        return $this->hasMany(RefracaoPaciente::class, 'agendamento_id');
    }

    public function contaReceber()
    {
        return $this->hasMany(ContaReceber::class, 'agendamento_id');
    }

    public function faturamentoLoteGuia()
    {
        return $this->hasMany(FaturamentoLoteGuia::class, 'agendamento_id');
    }

    public function carteirinha()
    {
        return $this->belongsTo(Carteirinha::class, 'carteirinha_id');
    }

    public function compromisso()
    {
        return $this->belongsTo(Compromisso::class, 'compromisso_id')->withTrashed();
    }

    public function agendamentosReferentes()
    {
        return $this->hasMany(Agendamentos::class, 'id_referente');
    }

    public function agendamentoGuias()
    {
        return $this->hasMany(AgendamentoGuia::class, 'agendamento_id');
    }
    
    public function atendimentoPaciente()
    {
        return $this->hasMany(AtendimentoPaciente::class, 'agendamento_id');
    }

    public static function status_para_texto($status)
    {
        $dados = [
            'pendente' => 'Pendente',
            'agendado' => 'Agendado',
            'confirmado' => 'Confirmado',
            'cancelado' => 'Cancelado',
            'finalizado' => 'Finalizado',
            'excluir' => 'Excluir',
            'ausente' => 'Ausente',
            'em_atendimento'  => 'Em Atendimento',
            'finalizado_medico' => 'Finalizado pelo profissional'
        ];

        return $dados[$status];
    }

    public static function status_para_cor($status)
    {
        $dados = [
            'pendente' => '#26c6da',
            'agendado' => '#ffcf8e',
            'confirmado' => '#009688',
            'cancelado' => '#78909C',
            'finalizado' => '#745af2',
            'ausente' => '#899093',
            'em_atendimento' => '#8eff9e',
            'finalizado_medico' => '#63dbae',
            'em_consultorio' => '#ffcf8e',
            'desistencia' => '#727b90'
        ];

        return $dados[$status];
    }

    public static function status_para_cor_texto($status)
    {
        $dados = [
            'pendente' => '#1b5c64',
            'agendado' => '#81653f',
            'confirmado' => '#fff',
            'cancelado' => '#fff',
            'finalizado' => '#fff',
            'ausente' => '#545c5e',
            'em_atendimento' => '#285a2f',
            'finalizado_medico' => '#4db890',
            'em_consultorio' => '#877052',
        ];

        return $dados[$status];
    }

    public function getSetorAttribute(): ?SetorInstituicao
    {
        // eh isto mesmo? nao tem a data peri - nao entendi agora

        // eu pego a data do agendamento
        // ai vou na instituicao agenda
        // procuro o dia unico daquela data
        // achando eu pego o setor
        // e pego o model pelo id_externo
        // ENTENDI, VAI SER ALGUM ERRO QUE TA DANDO AO PEGAR OS DADOS EM ALGUMAS AGENDAS NA API DA SANTA CASA, VOU RESOLVER BLZ?  MAS ENTENDI AGORA

        // nao seria isto? -  a ta, peri, vamos fazer uma coisa

        $diaUnico = Collection::make(json_decode($this->instituicoesAgenda->dias_unicos, true))->firstWhere('date', Carbon::parse($this->data)->format('d/m/Y'));
        // dd(Carbon::parse($this->data)->format('d/m/Y'), $diaUnico);
        if (!$diaUnico) {
            return null;
        }

        if (!isset(static::$cacheSetores[$diaUnico['setor_id']])) {
            static::$cacheSetores[$diaUnico['setor_id']] = SetorInstituicao::query()->firstWhere('id_externo', $diaUnico['setor_id']);
        }

        return static::$cacheSetores[$diaUnico['setor_id']];
    }

    public function scopeSearchByInstituicao(Builder $query, string $search = '', $instituicao_id): Builder
    {
        // dd($search);
        if(empty($search))
        {
            return $query->whereHas('instituicoesAgenda',function($q) use ($instituicao_id) {

                $q->where(function($q) use ($instituicao_id){
                    $q->whereHas('prestadores',function($q)  use ($instituicao_id){
                        $q->where('instituicoes_id', $instituicao_id);
                    });
                })
                ->orWhere(function($q) use ($instituicao_id){
                    $q->whereHas('procedimentos',function($q)  use ($instituicao_id){
                        $q->where('instituicoes_id', $instituicao_id);
                    });
                });

            });
        }

        $query->whereHas('instituicoesAgenda',function($q) use ($instituicao_id) {

            $q->where(function($q) use ($instituicao_id){
                $q->whereHas('prestadores',function($q)  use ($instituicao_id){
                    $q->where('instituicoes_id', $instituicao_id);
                });
            })
            ->orWhere(function($q) use ($instituicao_id){
                $q->whereHas('procedimentos',function($q)  use ($instituicao_id){
                    $q->where('instituicoes_id', $instituicao_id);
                });
            });

        });

        $query->whereHas('pessoa', function($q) use($search){
            $q->where('nome','like', "%{$search}%");
            $q->orWhere('cpf','like', "%{$search}%");
        });
        // ->where(function($q) use ($search){

            // $q->orWhere('status', 'like', "%{$search}%")


            // $q->orWhere(function($q) use ($search){
            //     $q->whereHas('instituicoesAgenda',function($q)  use ($search){

            //         $q->whereHas('prestadores',function($q)  use ($search){
            //             $q->where(function($q) use ($search){
            //                 $q->whereHas('prestador',function($q) use ($search){
            //                     $q->where('nome', 'like', "%{$search}%");
            //                 });
            //             });
            //         });

            //     });
            // })
            // ->orWhere(function($q) use ($search){
            //     $q->whereHas('agendamentoProcedimento',function($q)  use ($search){
            //         $q->whereHas('procedimentoInstituicaoConvenio',function($q)  use ($search){
            //             $q->whereHas('procedimento',function($q)  use ($search){
            //                 $q->where('descricao','like', "%{$search}%");
            //             });
            //         });
            //     });
            // })
            // $q->where(function($q) use ($search){
            //     $q->whereHas('pessoa',function($q)  use ($search){
            //         $q->where('nome','like', "%{$search}%");
            //     });
            // });
        // });

        return $query;
    }

    public function scopeGetRelatorioAgendamento(Builder $query, $dados): Builder
    {

        // $query->whereBetween('data', [$dados['data_inicio'], $dados['data_fim']]);
        $query->whereDate('data','>=', $dados['data_inicio']);
        $query->whereDate('data','<=', $dados['data_fim']);

        $query->whereIn('status', $dados['status']);

        $query->whereHas('instituicoesAgenda',function($q) use ($dados) {

            $q->where(function($q) use ($dados){
                $q->whereHas('prestadores',function($q)  use ($dados){
                    $q->when($dados['profissionais'], function($qu) use($dados) {
                        $qu->whereIn('prestadores_id', $dados['profissionais']);
                    });
                });
            });

            if(!empty($dados['setores'])){
                $q->where(function($qu) use($dados){
                    $qu->where(function($que) use($dados){
                        $que->whereIn('setor_id', $dados['setores']);
                    });
                    $qu->orWhere(function($que) use($dados){
                        foreach ($dados['setores'] as $key => $value) {
                            $que->orWhereJsonContains("dias_unicos", ['setor_id'=>$value]);
                        }
                    });
                });
            }
        });

        $query->whereHas('agendamentoProcedimento.procedimentoInstituicaoConvenioTrashed', function($q) use ($dados){
            $q->whereIn("convenios_id", $dados['convenios']);
            $q->whereHas('procedimentoInstituicao', function($query) use($dados){
                if(!array_keys($dados['procedimentos'], 'todos')){
                    $query->whereIn("procedimentos_id", $dados['procedimentos']);
                }
                $query->whereIn("grupo_id", $dados['grupos']);
            });
        });


        if(!empty($dados['solicitantes'])){
            $query->whereIn('solicitante_id', $dados['solicitantes']);
        }

        return $query;
    }

    static function timeEspera($data_hora){

        $total_secs = strtotime(date("Y-m-d H:i:s")) - strtotime($data_hora);

        $horas = floor($total_secs/3600);

        $minutos = floor(($total_secs % 3600)/60);

        $seg = ($total_secs % 3600) % 60;

        return str_pad($horas, 2, 0, STR_PAD_LEFT).":".str_pad($minutos, 2, 0, STR_PAD_LEFT);
    }


    //FILTRANDO AGENDAMENTOS FINALIZADOS PARA FATURAMENTO SANCOOP

    public function scopeGetAgendamentosFinalizadosGuias(Builder $query, $dados): Builder
    {

        // $query->whereBetween('data', [$dados['data_inicio'], $dados['data_fim']]);
        $query->whereDate('data','>=', $dados['data_inicio']);
        $query->whereDate('data','<=', $dados['data_fim']);

        $query->where('status', 'finalizado');

        $query->whereHas('instituicoesAgenda',function($q) use ($dados) {

            $q->where(function($q) use ($dados){
                $q->whereHas('prestadores',function($q)  use ($dados){
                    $q->whereIn('prestadores_id', $dados['prestadores']);
                });
            });
            // $q->where(function($qu) use($dados){
            //     $qu->where(function($que) use($dados){
            //         $que->whereIn('setor_id', $dados['setores']);
            //     });
            //     $qu->orWhere(function($que) use($dados){
            //         foreach ($dados['setores'] as $key => $value) {
            //             $que->orWhereJsonContains("dias_unicos", ['setor_id'=>$value]);
            //         }
            //     });
            // });
        });

        $query->whereHas('agendamentoProcedimento.procedimentoInstituicaoConvenioTrashed', function($q) use ($dados){
            $q->whereIn("convenios_id", $dados['convenios']);
            // $q->whereHas('procedimentoInstituicao', function($query) use($dados){
            //     if(!array_keys($dados['procedimentos'], 'todos')){
            //         $query->whereIn("procedimentos_id", $dados['procedimentos']);
            //     }
            //     $query->whereIn("grupo_id", $dados['grupos']);
            // });
        });

        

        return $query;
    }

    public function scopeGetAgendamentosUpdate(Builder $query, $dados, $instituicaoId): Builder
    {
        $query->whereIn('id', $dados['cod_agendamentos']);

        $query->where('status', 'finalizado');

        $query->whereHas('instituicoesAgenda',function($q) use ($instituicaoId) {

            $q->where(function($q) use ($instituicaoId){
                $q->whereHas('prestadores',function($q)  use ($instituicaoId){
                    $q->where('instituicoes_id', $instituicaoId);
                });
            })
            ->orWhere(function($q) use ($instituicaoId){
                $q->whereHas('procedimentos',function($q)  use ($instituicaoId){
                    $q->where('instituicoes_id', $instituicaoId);
                });
            });

        });

        return $query;
    }

    public function scopeGetCentroCirurgicoAgendamentos(Builder $query, string $nome = "", int $instituicao):Builder
    {

        $query->where('data', '>=', date('Y-m-d').' 00:00:00')
        ->whereHas('instituicoesAgenda.prestadores.instituicao', function($q) use($instituicao){
            $q->where('id', $instituicao);
        })->whereHas('pessoa', function($q) use($nome, $instituicao){
            $q->where('instituicao_id', $instituicao);
            $q->where('tipo', '<>', 3);

            if(!empty($nome)){
                $q->where('nome', 'like', "%{$nome}%")
                ->orWhere('nome_fantasia', 'like', "%{$nome}%")
                ->orWhere('cpf', 'like', "%{$nome}%");
            }
        })->with(['pessoa' => function($q) use($nome, $instituicao){

            $q->where('instituicao_id', $instituicao);
            $q->where('tipo', '<>', 3);

            if(!empty($nome)){
                $q->where('nome', 'like', "%{$nome}%")
                    ->orWhere('nome_fantasia', 'like', "%{$nome}%")
                    ->orWhere('cpf', 'like', "%{$nome}%");
            }
        }])
        ->orderBy('data', 'DESC');

        return $query;
    }

}
