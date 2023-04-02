<?php

namespace App\Http\Controllers\Instituicao;

use App\ContaReceber;
use App\Http\Controllers\Controller;
use App\Instituicao;
use Illuminate\Http\Request;
use App\Agendamentos;

class RelatoriosEstatisticos extends Controller
{
    public function showFinanceioAmbulatorial(Request $request){

        
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $convenios = $instituicao->convenios()->get();
        $procedimentos = $instituicao->procedimentos()->get();
        $profissionais = $instituicao->medicos();

        return view('instituicao.relatorios_estatisticos.financeiro', compact('convenios', 'procedimentos', 'profissionais'));
    }

    public function resultFinaceiro(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $faturamento = $instituicao->contasReceber()
            ->whereBetween('data_pago', [$request->input('start'), $request->input('end')])
            ->selectRaw('DATE_FORMAT(data_pago, "%Y-%m-01") as mes, SUM(valor_pago) as valor')
            ->groupBy('mes')
            ->orderBy('mes')
        ->get();

        $despesas = $instituicao->contasPagar()
            ->whereBetween('data_pago', [$request->input('start'), $request->input('end')])
            ->selectRaw('DATE_FORMAT(data_pago, "%Y-%m-01") as mes, SUM(valor_pago) as valor')
            ->groupBy('mes')
            ->orderBy('mes')
        ->get();

        // $faturamento_label = json_encode(array_column($faturamento->toArray(), 'mes'));
        // $faturamento_valores =  json_encode(array_column($faturamento->toArray(), 'valor'));

        // dd($faturamento_label, $faturamento_valores);

        return view('instituicao.relatorios_estatisticos.financeiro_result', compact('faturamento', 'despesas'));
    }

    public function showAgenda(Request $request){

        
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $convenios = $instituicao->convenios()->get();
        $procedimentos = $instituicao->procedimentos()->get();
        $profissionais = $instituicao->medicos()->get();

        return view('instituicao.relatorios_estatisticos.agenda', compact('convenios', 'procedimentos', 'profissionais'));
    }

    public function resultAgenda(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = Agendamentos::
            whereBetween('data', [$request->input('start'), $request->input('end')])
            ->selectRaw('DATE_FORMAT(data, "%Y-%m-01") as mes, count(*) as total')
            ->whereHas('instituicoesAgenda', function($q) use ($instituicao, $request){
                $q->whereHas('prestadores', function($q) use ($instituicao, $request){
                    $q->where('instituicoes_id', $instituicao->id);
                    $q->when($request->input('profissionais') != 'todos', function($q) use($request){
                        $q->where('prestadores_id', $request->input('profissionais'));
                    });
                });                
            })
            ->when($request->input('procedimentos') != 'todos', function($q) use($request){
                $q->whereHas('agendamentoProcedimento', function($q) use($request){
                    $q->wherehas('procedimentoInstituicaoConvenio', function($q) use($request){
                        $q->where('procedimentos_instituicoes_id', $request->input('procedimentos'));
                    });
                });
            })
            ->when($request->input('convenios') != 'todos', function($q) use($request){
                $q->whereHas('agendamentoProcedimento', function($q) use($request){
                    $q->wherehas('procedimentoInstituicaoConvenio', function($q) use($request){
                        $q->where('convenios_id', $request->input('convenios'));
                    });
                });
            })
            ->groupBy('mes')
        ->get();

        $status = Agendamentos::
            whereBetween('data', [$request->input('start'), $request->input('end')])
            ->selectRaw('status, DATE_FORMAT(data, "%Y-%m-01") as mes, count(*) as total')
            ->whereHas('instituicoesAgenda', function($q) use ($instituicao, $request){
                $q->whereHas('prestadores', function($q) use ($instituicao, $request){
                    $q->where('instituicoes_id', $instituicao->id);
                    $q->when($request->input('profissionais') != 'todos', function($q) use($request){
                        $q->where('prestadores_id', $request->input('profissionais'));
                    });
                });                
            })
            ->when($request->input('procedimentos') != 'todos', function($q) use($request){
                $q->whereHas('agendamentoProcedimento', function($q) use($request){
                    $q->wherehas('procedimentoInstituicaoConvenio', function($q) use($request){
                        $q->where('procedimentos_instituicoes_id', $request->input('procedimentos'));
                    });
                });
            })
            ->when($request->input('convenios') != 'todos', function($q) use($request){
                $q->whereHas('agendamentoProcedimento', function($q) use($request){
                    $q->wherehas('procedimentoInstituicaoConvenio', function($q) use($request){
                        $q->where('convenios_id', $request->input('convenios'));
                    });
                });
            })
            ->groupBy(['mes', 'status'])
        ->get();
        
        $porStatus = [];
        
        foreach($status as $k => $v){
            if(empty($porStatus[$v['mes']])){
                $porStatus[$v['mes']] = [
                    'agendados' => 0,
                    'atendidos' => 0,
                    'confirmados' => 0,
                    'ausentes' => 0,
                    'cancelados' => 0,
                ];
            }
            
            switch($v['status']){
                case 'pendente':
                    $porStatus[$v['mes']]['agendados'] = $v['total'];
                break;
                case 'finalizado':
                    $porStatus[$v['mes']]['atendidos'] = $v['total'];
                break;
                case 'confirmado':
                    $porStatus[$v['mes']]['confirmados'] = $v['total'];
                break;
                case 'ausente':
                    $porStatus[$v['mes']]['ausentes'] = $v['total'];
                break;
                case 'cancelado':
                    $porStatus[$v['mes']]['cancelados'] = $v['total'];
                break;
            }            
        }

        $convenios = Agendamentos::
            whereBetween('data', [$request->input('start'), $request->input('end')])
            ->whereHas('instituicoesAgenda', function($q) use ($instituicao, $request){
                $q->whereHas('prestadores', function($q) use ($instituicao, $request){
                    $q->where('instituicoes_id', $instituicao->id);
                    $q->when($request->input('profissionais') != 'todos', function($q) use($request){
                        $q->where('prestadores_id', $request->input('profissionais'));
                    });
                });
            })
            ->with(['agendamentoProcedimento' => function($q){
                $q->with(['procedimentoInstituicaoConvenio' => function($q){
                    $q->with('convenios');
                }]);
            }])
            ->when($request->input('procedimentos') != 'todos', function($q) use($request){
                $q->whereHas('agendamentoProcedimento', function($q) use($request){
                    $q->wherehas('procedimentoInstituicaoConvenio', function($q) use($request){
                        $q->where('procedimentos_instituicoes_id', $request->input('procedimentos'));
                    });
                });
            })
            ->when($request->input('convenios') != 'todos', function($q) use($request){
                $q->whereHas('agendamentoProcedimento', function($q) use($request){
                    $q->wherehas('procedimentoInstituicaoConvenio', function($q) use($request){
                        $q->where('convenios_id', $request->input('convenios'));
                    });
                });
            })
        ->get();

       $porConvenio = [];

        // foreach($convenios as $k => $v){            
        //     $porConvenio[$v['mes']][$v['nome']] = $v['total'];
        // }

        $a = [['x']];

        foreach($convenios as $k => $v){           
            if(!in_array(date("Y-m-01", strtotime($v->data)), $a[0])){
                $a[0][] = date("Y-m-01", strtotime($v->data));
            }

            $achei = false;
            if(!empty($v->agendamentoProcedimento[0])){                
                foreach($a as $key => $value){
                    if($a[$key][0] == $v->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome){
                        $a[$key][array_keys($a[0], date("Y-m-01", strtotime($v->data)))[0]] = (empty($a[$key][array_keys($a[0], date("Y-m-01", strtotime($v->data)))[0]])) ? 1 : $a[$key][array_keys($a[0], date("Y-m-01", strtotime($v->data)))[0]] + 1;
                        $achei = true;
                        break;
                    }
                }

                if(!$achei){
                    $a[] = [
                        0 => $v->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome,
                        array_keys($a[0], date("Y-m-01", strtotime($v->data)))[0] => 1
                    ];

                }
            }
        }

        $meses = count($a[0]);

        foreach($a as $key => $value){
            for($i = 0; $i < $meses; $i++){
                if(!isset($a[$key][$i])){
                    $a[$key][$i] = 0;
                }
            }

            ksort($a[$key]);
        }        

        $porConvenio = $a;

        return view('instituicao.relatorios_estatisticos.agenda_result', compact('agendamento', 'porStatus', 'porConvenio'));
    }

    public function showProcedimentos(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.relatorios_estatisticos.procedimento');
    }

    public function resultProcedimentos(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
       
        $procedimentos = Agendamentos::
            whereBetween('data', [$request->input('start'), $request->input('end')])
            ->whereHas('instituicoesAgenda', function($q) use ($instituicao, $request){
                $q->whereHas('prestadores', function($q) use ($instituicao, $request){
                    $q->where('instituicoes_id', $instituicao->id);
                });
            })
            ->with(['agendamentoProcedimento' => function($q){
                $q->with(['procedimentoInstituicaoConvenio' => function($q){
                    $q->with('procedimento');
                }]);
            }])
        ->get();

        $a = collect($procedimentos)
        ->filter(function ($procedimento) {
            return !empty($procedimento->agendamentoProcedimento[0]);
        })
        ->map(function ($procedimento){
            foreach($procedimento->agendamentoProcedimento as $k => $v){
                return [
                    "data" => $procedimento->data,
                    "procedimento" => $v->procedimentoInstituicaoConvenio->procedimento->descricao,
                ];
            }
        });

        $porProcedimento = [['x']];

        foreach($a as $k => $v){
            if(!in_array(date("Y-m-01", strtotime($v['data'])), $porProcedimento[0])){
                $porProcedimento[0][] = date("Y-m-01", strtotime($v['data']));
            }

            $achei = false;
                           
            foreach($porProcedimento as $key => $value){
                if($porProcedimento[$key][0] == $v['procedimento']){
                    $porProcedimento[$key][array_keys($porProcedimento[0], date("Y-m-01", strtotime($v['data'])))[0]] = (empty($porProcedimento[$key][array_keys($porProcedimento[0], date("Y-m-01", strtotime($v['data'])))[0]])) ? 1 : $porProcedimento[$key][array_keys($porProcedimento[0], date("Y-m-01", strtotime($v['data'])))[0]] + 1;
                    $achei = true;
                    break;
                }
            }

            if(!$achei){
                $porProcedimento[] = [
                    0 => $v['procedimento'],
                    array_keys($porProcedimento[0], date("Y-m-01", strtotime($v['data'])))[0] => 1
                ];

            }
        }

        $meses = count($a[0]);

        foreach($porProcedimento as $key => $value){
            for($i = 0; $i < $meses; $i++){
                if(!isset($porProcedimento[$key][$i])){
                    $porProcedimento[$key][$i] = 0;
                }
            }

            ksort($porProcedimento[$key]);
        }

        //agendamentos por grupo    
        $grupos = Agendamentos::
            whereBetween('data', [$request->input('start'), $request->input('end')])
            ->whereHas('instituicoesAgenda', function($q) use ($instituicao, $request){
                $q->whereHas('prestadores', function($q) use ($instituicao, $request){
                    $q->where('instituicoes_id', $instituicao->id);
                });
            })
            ->with(['agendamentoProcedimento' => function($q){
                $q->with(['procedimentoInstituicaoConvenio' => function($q){
                    $q->with(['procedimentoInstituicao' => function($q){
                        $q->with('grupoProcedimento');
                    }]);
                }]);
            }])
        ->get();

        $a = collect($grupos)
        ->filter(function ($grupo) {
            return !empty($grupo->agendamentoProcedimento[0]);
        })
        ->map(function ($grupo){
            foreach($grupo->agendamentoProcedimento as $k => $v){
                return [
                    "data" => $grupo->data,
                    "grupo" => $v->procedimentoInstituicaoConvenio->procedimentoInstituicao->grupoProcedimento->nome,
                ];
            }  
        });

        $porGrupo = [['x']];

        foreach($a as $k => $v){
            if(!in_array(date("Y-m-01", strtotime($v['data'])), $porGrupo[0])){
                $porGrupo[0][] = date("Y-m-01", strtotime($v['data']));
            }

            $achei = false;
                           
            foreach($porGrupo as $key => $value){
                if($porGrupo[$key][0] == $v['grupo']){
                    $porGrupo[$key][array_keys($porGrupo[0], date("Y-m-01", strtotime($v['data'])))[0]] = (empty($porGrupo[$key][array_keys($porGrupo[0], date("Y-m-01", strtotime($v['data'])))[0]])) ? 1 : $porGrupo[$key][array_keys($porGrupo[0], date("Y-m-01", strtotime($v['data'])))[0]] + 1;
                    $achei = true;
                    break;
                }
            }

            if(!$achei){
                $porGrupo[] = [
                    0 => $v['grupo'],
                    array_keys($porGrupo[0], date("Y-m-01", strtotime($v['data'])))[0] => 1
                ];

            }
        }

        $meses = count($a[0]);

        foreach($porGrupo as $key => $value){
            for($i = 0; $i < $meses; $i++){
                if(!isset($porGrupo[$key][$i])){
                    $porGrupo[$key][$i] = 0;
                }
            }

            ksort($porGrupo[$key]);
        }  
        
        
        //agendamentos por profissional

        $profissionais = Agendamentos::
            whereBetween('data', [$request->input('start'), $request->input('end')])
            ->whereHas('instituicoesAgenda', function($q) use ($instituicao, $request){
                $q->whereHas('prestadores', function($q) use ($instituicao, $request){
                    $q->where('instituicoes_id', $instituicao->id);
                });
            })
            ->with(['instituicoesAgenda' => function($q){
                $q->with(['prestadores' => function($q){
                    $q->with('prestador');
                }]);
            }])
        ->get();

        $a = collect($profissionais)
        ->filter(function ($profissional) {
            return !empty($profissional->instituicoesAgenda->prestadores->prestador);
        })
        ->map(function ($profissional){
            return [
                "data" => $profissional->data,
                "profissional" => $profissional->instituicoesAgenda->prestadores->prestador->nome,
            ];
        });

        $porProfissional = [['x']];        


        foreach($a as $k => $v){
            if(!in_array(date("Y-m-01", strtotime($v['data'])), $porProfissional[0])){
                $porProfissional[0][] = date("Y-m-01", strtotime($v['data']));
            }

            $achei = false;
                           
            foreach($porProfissional as $key => $value){
                if($porProfissional[$key][0] == $v['profissional']){
                    $porProfissional[$key][array_keys($porProfissional[0], date("Y-m-01", strtotime($v['data'])))[0]] = (empty($porProfissional[$key][array_keys($porProfissional[0], date("Y-m-01", strtotime($v['data'])))[0]])) ? 1 : $porProfissional[$key][array_keys($porProfissional[0], date("Y-m-01", strtotime($v['data'])))[0]] + 1;
                    $achei = true;
                    break;
                }
            }

            if(!$achei){
                $porProfissional[] = [
                    0 => $v['profissional'],
                    array_keys($porProfissional[0], date("Y-m-01", strtotime($v['data'])))[0] => 1
                ];

            }
        }

        $meses = count($a[0]);

        foreach($porProfissional as $key => $value){
            for($i = 0; $i < $meses; $i++){
                if(!isset($porProfissional[$key][$i])){
                    $porProfissional[$key][$i] = 0;
                }
            }

            ksort($porProfissional[$key]);
        }  

        return view('instituicao.relatorios_estatisticos.procedimento_result', compact('porProcedimento', 'porGrupo', 'porProfissional'));
    }

    public function showConvenios(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.relatorios_estatisticos.convenios');
    }

    public function resultConvenios(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
       

        //Procedimentos X Convenios X Mes
        $convenios = Agendamentos::
            whereBetween('data', [$request->input('start'), $request->input('end')])
            ->whereHas('instituicoesAgenda', function($q) use ($instituicao, $request){
                $q->whereHas('prestadores', function($q) use ($instituicao, $request){
                    $q->where('instituicoes_id', $instituicao->id);
                    $q->with('prestador');
                });
            })
            ->with(['agendamentoProcedimento' => function($q){
                $q->with(['procedimentoInstituicaoConvenio' => function($q){
                    $q->with(['convenios', 'procedimento']);
                }]);
            }])
        ->get();

        $a = collect($convenios)
        ->filter(function ($convenio) {
            return !empty($convenio->agendamentoProcedimento[0]);
        })
        ->map(function ($convenio){
            foreach($convenio->agendamentoProcedimento as $k => $v){
                return [
                    "data" => $convenio->data,
                    "text" => $v->procedimentoInstituicaoConvenio->convenios->nome. " - ".$v->procedimentoInstituicaoConvenio->procedimento->descricao,                    
                ];
            }
        });

        $porProcedimento = [['x']];

        foreach($a as $k => $v){
            if(!in_array(date("Y-m-01", strtotime($v['data'])), $porProcedimento[0])){
                $porProcedimento[0][] = date("Y-m-01", strtotime($v['data']));
            }

            $achei = false;
                           
            foreach($porProcedimento as $key => $value){
                if($porProcedimento[$key][0] == $v['text']){
                    $porProcedimento[$key][array_keys($porProcedimento[0], date("Y-m-01", strtotime($v['data'])))[0]] = (empty($porProcedimento[$key][array_keys($porProcedimento[0], date("Y-m-01", strtotime($v['data'])))[0]])) ? 1 : $porProcedimento[$key][array_keys($porProcedimento[0], date("Y-m-01", strtotime($v['data'])))[0]] + 1;
                    $achei = true;
                    break;
                }
            }

            if(!$achei){
                $porProcedimento[] = [
                    0 => $v['text'],
                    array_keys($porProcedimento[0], date("Y-m-01", strtotime($v['data'])))[0] => 1
                ];

            }
        }

        $meses = count($a[0]);

        foreach($porProcedimento as $key => $value){
            for($i = 0; $i < $meses; $i++){
                if(!isset($porProcedimento[$key][$i])){
                    $porProcedimento[$key][$i] = 0;
                }
            }

            ksort($porProcedimento[$key]);
        }

        //Prestadores X Convenios X Mes
        $a = collect($convenios)
        ->filter(function ($convenio) {
            return !empty($convenio->agendamentoProcedimento[0]);
        })
        ->map(function ($convenio){
            foreach($convenio->agendamentoProcedimento as $k => $v){
                return [
                    "data" => $convenio->data,
                    "text" => $v->procedimentoInstituicaoConvenio->convenios->nome. " - ".$convenio->instituicoesAgenda->prestadores->prestador->nome,                    
                ];
            }
        });

        $porPrestador = [['x']];

        foreach($a as $k => $v){
            if(!in_array(date("Y-m-01", strtotime($v['data'])), $porPrestador[0])){
                $porPrestador[0][] = date("Y-m-01", strtotime($v['data']));
            }

            $achei = false;
                           
            foreach($porPrestador as $key => $value){
                if($porPrestador[$key][0] == $v['text']){
                    $porPrestador[$key][array_keys($porPrestador[0], date("Y-m-01", strtotime($v['data'])))[0]] = (empty($porPrestador[$key][array_keys($porPrestador[0], date("Y-m-01", strtotime($v['data'])))[0]])) ? 1 : $porPrestador[$key][array_keys($porPrestador[0], date("Y-m-01", strtotime($v['data'])))[0]] + 1;
                    $achei = true;
                    break;
                }
            }

            if(!$achei){
                $porPrestador[] = [
                    0 => $v['text'],
                    array_keys($porPrestador[0], date("Y-m-01", strtotime($v['data'])))[0] => 1
                ];

            }
        }

        $meses = count($a[0]);

        foreach($porPrestador as $key => $value){
            for($i = 0; $i < $meses; $i++){
                if(!isset($porPrestador[$key][$i])){
                    $porPrestador[$key][$i] = 0;
                }
            }

            ksort($porPrestador[$key]);
        }


        return view('instituicao.relatorios_estatisticos.convenios_result', compact('porProcedimento', 'porPrestador'));
    }

}
