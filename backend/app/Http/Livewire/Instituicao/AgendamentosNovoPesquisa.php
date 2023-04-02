<?php

namespace App\Http\Livewire\Instituicao;


use App\Instituicao;
use App\Especialidade;
use App\GruposProcedimentos;
use App\GruposInstituicoes;
use App\Agendamentos as Agendamento;
use App\AuditoriaAgendamento;
use App\Convenio;
use App\ConvenioInstituicao;
use App\InstituicaoProcedimentos;
use App\InstituicoesAgenda;
use App\SetorInstituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use carbon\carbon;
use Illuminate\Support\Facades\Gate;

use function Clue\StreamFilter\fun;
use function PHPSTORM_META\map;

class AgendamentosNovoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;


    public $prestador_especialidade_id ='';
    public $procedimento_instituicao_id ='';
    public $grupo_id ='';
    public $setor_id = 0;
    public $convenio_id ='';
    public $faixa_idade ='';
    public $data = '';
    public $dia_semana = '';
    public $agenda;
    public $agendamentos;
    public $agendaAusente;
    public $especialidade;
    public $grupos;
    public $grupos_instituicao;
    public $setores_instituicao;
    public $convenios_instituicao;
    public $horario_ausente = true;
    public $horario_disponivel = true;
    public $horario_vazio = true;
    public $tipo_continuo = false;
    public $qtdAgendamentos;
    public $procedimento_selected = [];
    
    public $usuario_logado;
    public $instituicao;
    public $usuario_prestador;
    public $prestadoresIds;
    public $setoresIds;
    public $existeAgenda = true;
    public $hora_avulsa;

    public $agendaDados;

    public $maisAgenda = false;
    public $totalAgendamentos = 0;

    protected $updatesQueryString = [
        'prestador_especialidade_id' => ['except' => ''],
        'data' => ['except' => ''],
        'procedimento_instituicao_id' => ['except' => ''],
        'grupo_id' => ['except' => ''],
        'setor_id' => ['except' => 0],
        'convenio_id' => ['except' => ''],
        'faixa_idade' => ['except' => ''],
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Request $request)
    {        
        $this->usuario_logado = $request->user('instituicao');

        $instituicao_logada = $this->usuario_logado->instituicao->where('id', $request->session()->get('instituicao'))->first();
        $this->prestadoresIds = explode(',', $instituicao_logada->pivot->visualizar_prestador);
        $this->setoresIds = explode(',', $instituicao_logada->pivot->visualizar_setores);
        
        $this->usuario_prestador = $this->usuario_logado->prestadorMedico()->get();
        if(count($this->usuario_prestador) > 0 && $this->usuario_prestador[0]->tipo == 2){
            $this->prestador_especialidade_id = $this->usuario_prestador[0]->id;
        }

        ($request->prestador_especialidade_id) ? $this->prestador_especialidade_id = $request->prestador_especialidade_id : '';
        ($request->procedimento_instituicao_id) ? $this->procedimento_instituicao_id = $request->procedimento_instituicao_id : '';
        ($request->grupo_id) ? $this->grupo_id = $request->grupo_id : '';
        ($request->setor_id) ? $this->setor_id = $request->setor_id : '';
        ($request->convenio_id) ? $this->convenio_id = $request->convenio_id : '';
        ($request->faixa_idade) ? $this->faixa_idade = $request->faixa_idade : '';

        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));

        if($request->data){
            $this->data = \Carbon\Carbon::createFromFormat('d/m/Y', $request->data)->format('d/m/Y');
            $this->dia_semana = explode("-",\Carbon\Carbon::createFromFormat('d/m/Y', $request->data)->dayName)[0];
        }else{
            $this->data = \Carbon\Carbon::now()->format('d/m/Y');
            $this->dia_semana = explode("-",\Carbon\Carbon::now()->dayName)[0];
        }

        // $this->grupos = GruposProcedimentos::
        //     whereHas('procedimentos_instituicoes', function($q){
        //         $q->where('instituicoes_id',$this->instituicao->id);
        //     })
        //     ->with($this->getRelationConfigForGrupoProcedimento())->get();

        // $this->grupos_instituicao = GruposInstituicoes::where('instituicao_id', $this->instituicao->id)->get();

        $this->grupos_instituicao = GruposProcedimentos::get();

        // $this->setores_instituicao = SetorInstituicao::where('instituicoes_id', $this->instituicao->id)
        //                                              ->where('utiliza_agenda', 1)
        //                                              ->get();
        $this->setores_instituicao = $this->instituicao->setoresExame()
                                                     ->when($this->setoresIds, function($q){
                                                        if(!in_array('', $this->setoresIds)){
                                                            $q->whereIn('id', $this->setoresIds);
                                                        }
                                                     })
                                                     ->where('ativo', 1)
                                                     ->get();

        $this->convenios_instituicao = Convenio::whereHas('procedimentoConvenioInstuicao', function($q) {
            $q->where('instituicoes_id', $this->instituicao->id);
        })->get();

        $this->especialidade = Especialidade::
        whereHas('prestadoresInstituicao', function($q){
            $q->where('ativo', 1);
            if(!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_agenda_prestador')){
                if(count($this->usuario_prestador) > 0 && $this->usuario_prestador[0]->tipo == 2){
                    $q->where('instituicao_usuario_id', $this->usuario_logado->id);
                }
            }else{
                if($this->prestadoresIds != null){
                    if(!in_array('', $this->prestadoresIds)){
                        $q->whereIn('id', $this->prestadoresIds);
                    }
                }
            }
            $q->where('instituicoes_id',$this->instituicao->id);
        })
        ->with($this->getRelationConfigForEspecialidade())->get();
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_agendamentos');
        $medico = false;

        if($this->usuario_logado->prestadorMedico()->first() || (!empty($this->usuario_prestador[0]) && $this->usuario_prestador[0]->tipo == 3)){
            $medico = true;
        }

        // $medico = true;

        $startQueries = microtime(true);
        $this->performQuery();
        $endQueries = microtime(true);

        $this->emit('reset_icheck');
        // dd($this->faixa_idade);

        $startView = microtime(true);
        $view = view('livewire.instituicao.agendamentos-novo-pesquisa', ['medico' => $medico]);
        $endView = microtime(true);

        // \Log::debug("Tempo de processamento", [
        //     'performQuery()' => (($endQueries - $startQueries) * 1000).'ms',
        //     'renderView' => (($endView - $startView) * 1000).'ms',
        // ]);
        
        return $view;
    }

    private function performQuery(): void
    {
        $this->existeAgenda = true;
        
        if($this->procedimento_instituicao_id == 0){
            $this->procedimento_selected = [
                'id' => 0,
                'descricao' => 'Todos os procedimento'
            ];
        }else{
            if(count($this->procedimento_selected) == 0){
                $procedimento_pesquisa = InstituicaoProcedimentos::find($this->procedimento_instituicao_id);
                $this->procedimento_selected = [
                    'id' => $procedimento_pesquisa->id,
                    'descricao' => $procedimento_pesquisa->procedimento->descricao
                ];
            }
        }
        // if ($this->grupos) {
        //     $this->grupos->loadMissing($this->getRelationConfigForGrupoProcedimento());
        // }
        if ($this->especialidade) {
            $this->especialidade->loadMissing($this->getRelationConfigForEspecialidade());
        }


        // $query = InstituicoesAgenda::SearchLista($this->data, $this->prestador_especialidade_id);

        //   dd($this->procedimento_instituicao_id);

        $data_usa = \Carbon\Carbon::createFromFormat('d/m/Y', $this->data)->format('Y-m-d');
        
        //SE TIVER SETOR
        if(!empty($this->setor_id)){

            $this->agenda = InstituicoesAgenda::
            // when($this->prestador_especialidade_id, function($q){
                where('instituicoes_prestadores_id', $this->prestador_especialidade_id)
                // ->orWhere('procedimentos_instituicoes_id', $this->procedimento_instituicao_id)
                // ->orWhere('grupos_instituicoes_id', $this->grupo_id);
            // })
            // ->whereJsonContains("dias_unicos", ['date' => $this->data])
            ->where(function($q) use($data_usa){
                $q->whereRaw("json_contains(`dias_unicos`, '".json_encode(['date' => $this->data])."')");
                $q->orWhereRaw("json_contains(`dias_unicos`, '".json_encode(['data' => $data_usa])."')");
            })
            ->whereJsonContains("dias_unicos", ['setor_id' => $this->setor_id])
            ->when($this->faixa_idade,function($q){
                $q->whereJsonContains("dias_unicos", ['faixa_etaria' => $this->faixa_idade]);
            })
            // ->whereHas('prestadores', function($query){
            //     if(empty($this->prestador_especialidade_id)){
            //         if(count($this->usuario_prestador) > 0){
            //             $query->where('instituicao_usuario_id', $this->usuario_logado->id);
            //         }
            //     }
            // })
            // ->when($this->procedimento_instituicao_id,function($q){
            //     $q->whereHas('agendamentos.agendamentoProcedimento', function($query){
            //         $query->where("procedimentos_instituicoes_convenios_id", $this->procedimento_instituicao_id);
            //     });
            // })
            ->first();

        }else{
            DB::enableQueryLog();

            $this->agenda = InstituicoesAgenda::
            // when($this->prestador_especialidade_id, function($q){
                where('instituicoes_prestadores_id',$this->prestador_especialidade_id)
                // ->orWhere('procedimentos_instituicoes_id', $this->procedimento_instituicao_id)
                // ->orWhere('grupos_instituicoes_id', $this->grupo_id);
            // })
            ->when($this->setor_id,function($q){
                $q->whereJsonContains("dias_unicos", ['setor_id' => $this->setor_id]);
            })
            ->when($this->faixa_idade,function($q){
                $q->whereJsonContains("dias_unicos", ['faixa_etaria' => $this->faixa_idade]);
            })
            ->when($this->setoresIds,function($q){
                if(!in_array('', $this->setoresIds)){
                    for ($i=0; $i < count($this->setoresIds); $i++) { 
                        $q->whereJsonContains("dias_unicos", ['setor_id' => $this->setoresIds[$i]]);
                    }
                }
            })
            ->where(function($q) {
                if($this->setoresIds != null){
                    if(!in_array('', $this->setoresIds)){
                        for ($i=0; $i < count($this->setoresIds); $i++) { 
                            $q->orWhereJsonContains("dias_unicos", ['setor_id' => $this->setoresIds[$i]]);
                        }
                    }
                }
            })
            // ->whereHas('prestadores', function($query){
            //     if(empty($this->prestador_especialidade_id)){
            //         if(count($this->usuario_prestador) > 0){
            //             $query->where('instituicao_usuario_id', $this->usuario_logado->id);
            //         }
            //     }
            // })
            // ->when($this->procedimento_instituicao_id,function($q){
            //     $q->whereHas('agendamentos.agendamentoProcedimento', function($query){
            //         $query->where("procedimentos_instituicoes_convenios_id", $this->procedimento_instituicao_id);
            //     });
            // })
            // ->whereJsonContains("dias_unicos", ['date' => $this->data])
            // ->where(DB::raw("json_contains('dias_unicos', '$json'"))->first();
            // ->whereRaw("json_contains(`dias_unicos`, '".json_encode(['date' => $this->data])."')")
            ->where(function($q) use($data_usa){
                $q->whereRaw("json_contains(`dias_unicos`, '".json_encode(['date' => $this->data])."')");
                $q->orWhereRaw("json_contains(`dias_unicos`, '".json_encode(['data' => $data_usa])."')");
            })
            ->first();

            // dd($this->agenda, DB::getQueryLog());
        }

        

        if($this->agenda){
            $this->tipo_continuo = false;
            $data = $this->data;
            $dados = array_filter(
                JSON_DECODE($this->agenda->dias_unicos),
                function ($e) use ($data, $data_usa){
                    if($this->setor_id > 0){
                        if($this->setor_id == $e->setor_id){
                            if($e->date == $data){
                                return $e->date==$data;
                            }
                            
                            if(array_key_exists('data', $e)){
                                if($e->data == $data_usa){
                                    return $e->data==$data_usa;
                                }
                            }
                        }
                    }else{
                        
                        if($e->date == $data){
                            return $e->date==$data;
                        }
                        
                        if(array_key_exists('data', $e)){
                            if($e->data == $data_usa){
                                return $e->data==$data_usa;
                            }
                        }
                        
                    }
                }
            );
            // $dados = current(array_filter(
            //     JSON_DECODE($this->agenda->dias_unicos),
            //     function ($e) use ($data){
            //         return $e->date==$data;
            //     }
            // ));
            // $dados = current(array_filter(
            //     JSON_DECODE($this->agenda->dias_unicos),
            //     function ($e) use ($data){
            //         return $e->date==$data;
            //     }
            // ))
            //     ->map(function ($e){
            //         return [
            //             'hora_inicio' => $e->hora_inicio.':00',
            //             'hora_fim' => $e->hora_fim.':00',
            //             'hora_intervalo' => $e->hora_intervalo.':00',
            //             'duracao_intervalo' => $e->duracao_intervalo.':00',
            //             'duracao_atendimento' => $e->duracao_atendimento.':00',
            //         ];
            //     });
            usort($dados, function($a, $b) {
                $datetime1 = strtotime($a->hora_inicio);
                $datetime2 = strtotime($b->hora_inicio);
                return $datetime1 - $datetime2;
            });

            foreach ($dados as $key => $value) {
                $agendaUnica[] = [
                    'id' => $this->agenda->id,
                    'hora_inicio' => $value->hora_inicio.':00',
                    'hora_fim' => $value->hora_fim.':00',
                    'hora_intervalo' => $value->hora_intervalo.':00',
                    'duracao_intervalo' => $value->duracao_intervalo.':00',
                    'duracao_atendimento' => $value->duracao_atendimento.':00',
                ];
            }
            $this->agenda->unico = $agendaUnica;
            $this->agenda->hora_inicio = $dados[0]->hora_inicio;
            $this->agenda->hora_fim = $dados[0]->hora_fim;
            $this->agenda->hora_intervalo = $dados[0]->hora_intervalo;
            $this->agenda->duracao_intervalo = $dados[0]->duracao_intervalo;
            $this->agenda->duracao_atendimento = $dados[0]->duracao_atendimento;
            // dd($this->agenda->toArray());
            $this->agendaDados = $agendaUnica;
            $this->hora_avulsa = $this->agendaDados[0]['hora_inicio'];
            
            if(empty($this->agenda)){
                $this->existeAgenda = false;
            }
        }else{
            $this->tipo_continuo = true;
            $this->agenda = InstituicoesAgenda::
            // when($this->prestador_especialidade_id, function($q){
                // $q->where('instituicoes_prestadores_id',$this->prestador_especialidade_id);
                where('instituicoes_prestadores_id',$this->prestador_especialidade_id)
                // ->orWhere('procedimentos_instituicoes_id', $this->procedimento_instituicao_id)
                // ->orWhere('grupos_instituicoes_id', $this->grupo_id);
            // })
            ->where('dias_continuos',$this->dia_semana)
            ->orderBy('hora_inicio', 'ASC')
            ->where(function($q){
                if(!empty($this->setor_id)){
                    $q->where('setor_id', $this->setor_id);
                }else if($this->setoresIds != null){
                    if(!in_array('', $this->setoresIds)){
                        $q->whereIn('setor_id', $this->setoresIds);
                    }
                }
            })
            ->when($this->faixa_idade,function($q){
                $q->where('faixa_etaria', $this->faixa_idade);
            })
            // ->whereHas('prestadores', function($query){
            //     if(empty($this->prestador_especialidade_id)){
            //         if(count($this->usuario_prestador) > 0){
            //             $query->where('instituicao_usuario_id', $this->usuario_logado->id);
            //         }
            //     }
            // })
            // ->when($this->procedimento_instituicao_id,function($q){
            //     $q->whereHas('agendamentos.agendamentoProcedimento', function($query){
            //         $query->where("procedimentos_instituicoes_convenios_id", $this->procedimento_instituicao_id);
            //     });
            // })
            ->get();
            if(count($this->agenda) > 0){
                $this->hora_avulsa = $this->agenda[0]->hora_inicio;

                foreach ($this->agenda as $key => $value) {
                    $agendaUnica[] = [
                        'id' => $value->id,
                        'hora_inicio' => $value->hora_inicio,
                        'hora_fim' => $value->hora_fim,
                        'hora_intervalo' => $value->hora_intervalo,
                        'duracao_intervalo' => $value->duracao_intervalo,
                        'duracao_atendimento' => $value->duracao_atendimento,
                    ];
                }
    
                $this->agendaDados = $agendaUnica;
            }
            
            if(count($this->agenda) == 0){
                $this->existeAgenda = false;
            }

            if(count($this->agenda) > 1){
                $this->maisAgenda = true;
            }
        }

        $this->agendamentos = Agendamento::where('status', '<>', 'excluir')->whereNotNull('instituicoes_agenda_id')
            // ->whereNotNull('pessoa_id')
            ->whereHas('instituicoesAgendaGeral', function($q){
            // $q->when($this->prestador_especialidade_id, function($q){
                $q->whereHas('prestadores',function($q){
                    $q->where('instituicoes_prestadores_id', $this->prestador_especialidade_id);
                });
            // });
            // if(empty($this->prestador_especialidade_id)){
            //     $q->whereHas('prestadores', function($query){
            //         if(count($this->usuario_prestador) > 0){
            //             $query->where('instituicao_usuario_id', $this->usuario_logado->id);
            //         }
            //     });
            // }
            })
        ->when($this->grupo_id, function($q) {

            $q->whereHas('agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao', function($q){
                $q->where('grupo_id', $this->grupo_id);
            });

        })
        ->where(function($q) {
            if(!empty($this->setor_id)){
                $q->whereHas('instituicoesAgenda', function($q1){
                    $q1->whereJsonContains("dias_unicos", ['setor_id' => $this->setor_id]);
                    $q1->orWhere('setor_id', $this->setor_id);
                });
            }else if($this->setoresIds != null){
                if(!in_array('', $this->setoresIds)){
                    $q->whereHas('instituicoesAgenda', function($q1){
                        $q1->whereIn('setor_id', $this->setoresIds);
                        for ($i=0; $i < count($this->setoresIds); $i++) { 
                            $q1->orWhereJsonContains("dias_unicos", ['setor_id' => $this->setoresIds[$i]]);
                        }
                    });
                }
            }
            

        })
        ->whereDate('data',\Carbon\Carbon::createFromFormat('d/m/Y', $this->data)->format('Y-m-d'))
        ->with([
            'agendamentoProcedimento',
            'agendamentoProcedimento.procedimentoInstituicaoConvenio' => function($q){
                $q->withTrashed();
            },
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios',
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao' => function($q){
                $q->withTrashed();
            },
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento',
            'atendimento',
            'instituicoesAgenda',
            'instituicoesAgenda.prestadores',
            'instituicoesAgenda.prestadores.prestador',
            'pessoa',
            'usuario',
            'agendamentoGuias',
        ])->orderBy(DB::raw('IF(`data_original` IS NOT NULL, `data_original`, `data`)'), "ASC");

        if($this->procedimento_instituicao_id or $this->convenio_id){
            $this->agendamentos->whereHas('agendamentoProcedimento.procedimentoInstituicaoConvenio', function($q){
                $q->when($this->procedimento_instituicao_id, function($q) {
                    $q->where('procedimentos_instituicoes_id', $this->procedimento_instituicao_id);
                });
                $q->when($this->convenio_id, function($q) {
                    $q->where('convenios_id', $this->convenio_id);
                });
            });
        }

        // $this->agendamentos = Agendamento::whereDate('data',\Carbon\Carbon::createFromFormat('d/m/Y', $this->data)->format('Y-m-d'))
        //     ->whereHas('instituicoesAgendaGeral', function($q){
        //     // $q->when($this->prestador_especialidade_id, function($q){
        //         $q->whereHas('prestadores',function($q){
        //             $q->where('instituicoes_prestadores_id', $this->prestador_especialidade_id);
        //         });
        //     })
        //     ->where('status', '<>', 'excluir');
            // ->whereHas('agendamentoProcedimento.procedimentoInstituicaoConvenio', function($q){
                // $q->when($this->procedimento_instituicao_id, function($q) {
                //     $q->where('procedimentos_instituicoes_id', $this->procedimento_instituicao_id);
                // });
                // $q->when($this->convenio_id, function($q) {
                //     $q->where('convenios_id', $this->convenio_id);
                // });
            // });

        // dd($this->agendamentos->get(), $this->procedimento_instituicao_id);

        if($this->tipo_continuo == false){
            if(count($this->agenda->unico) == 1){
                // $inicio = \Carbon\Carbon::createFromFormat('d/m/Y', $this->data)->format('Y-m-d').' '.$this->agenda->hora_inicio;
                // $fim = \Carbon\Carbon::createFromFormat('d/m/Y', $this->data)->format('Y-m-d').' '.$this->agenda->hora_fim;
                // // dd($inicio);
                // $this->agendamentos->whereBetween('data', [$inicio, $fim]);
            }
        }

        $this->agendamentos = $this->agendamentos->get();

        // dd($this->agendamentos->toArray());
        
        // $this->agendas = $query->get();

        $this->agendaAusente = $this->instituicao->agendasAusente()
            ->where('data', \Carbon\Carbon::createFromFormat('d/m/Y', $this->data)->format('Y-m-d'))
            ->when($this->prestador_especialidade_id, function($q){
                $q->whereHas('instituicoesPrestadores',function($q){
                    $q->where('id', $this->prestador_especialidade_id);
                });
            })
        ->get();

        $this->qtdAgendamentos = json_encode(array_filter(array_column(array_filter($this->agendamentos->toArray(), function($i){
            if($i['status'] != 'cancelado' AND $i['pessoa_id'] != null ){
                return $i;
            }
        }), 'status')));
        
        $this->changeStatus();
    }

    private function date_compare($element1, $element2) {
        $datetime1 = strtotime($element1['hora_inicio']);
        $datetime2 = strtotime($element2['hora_inicio']);
        return $datetime1 - $datetime2;
    } 

    public function updatingData($value): void
    {
        $this->data = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('d/m/Y');
        $this->dia_semana = explode("-",\Carbon\Carbon::createFromFormat('d/m/Y', $value)->dayName)[0];        
        $this->resetPage();
    }

    public function refresh($value) : void {
        $this->resetPage();
    }

    public function changeStatus()
    {
        foreach ($this->agendamentos as $key => $value) {
            if(in_array($value->status, ['pendente', 'confirmado'])){
                $data = date('Y-m-d H:i', strtotime($value->data.' +3 hours'));
                if($data < date('Y-m-d H:i')){
                    DB::transaction(function() use($value){
                        $value->update(['status' => 'ausente']);
                        $value->criarLog($this->usuario_logado, 'Agendamento para ausente automatico', 'ausente', $this->instituicao->id);
                        AuditoriaAgendamento::logAgendamento($value->id, $value['status'], $this->usuario_logado->id, 'ausenteAutomatico');
                    });
                }
            }
        }
    }

    private function getRelationConfigForGrupoProcedimento(): array {
        return [
            'procedimentos_instituicoes' => function($q){
                $q->where('instituicoes_id',$this->instituicao->id);
            },
            'procedimentos_instituicoes.procedimento' => function($q){
                $q->select('id','descricao');
            }
        ];
    }

    private function getRelationConfigForEspecialidade(): array {
        
        return [
            'prestadoresInstituicao' => function($q){
                $q->where('ativo', 1);
                $q->where('instituicoes_id',$this->instituicao->id);
                if(!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_agenda_prestador')){
                    if(count($this->usuario_prestador) > 0 && $this->usuario_prestador[0]->tipo == 2){
                        $q->where('instituicao_usuario_id', $this->usuario_logado->id);
                    }
                }else{
                    if($this->prestadoresIds != null){
                        if(!in_array('', $this->prestadoresIds)){
                            $q->whereIn('id', $this->prestadoresIds);
                        }
                    }
                }
            },
            'prestadoresInstituicao.prestador' => function($q){
                $q->select('id','nome');
            }
        ];
    }

}
