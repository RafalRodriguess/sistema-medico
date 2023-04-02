<?php

namespace App\Http\Controllers\Instituicao;

use App\AgendamentoAtendimentoUrgencia;
use App\AgendamentoCentroCirurgico;
use App\Agendamentos;
use App\Cid;
use App\EstoqueBaixa;
use App\EstoqueEntradaProdutos;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgendamentoCentroCirurgico\AgendarCentroCirurgicoRequest;
use App\Http\Requests\AgendamentoCentroCirurgico\UpdateAgendamentoCentroCirurgicoRequest;
use App\Instituicao;
use App\Internacao;
use App\Pessoa;
use App\Produto;
use App\ProdutoBaixa;
use App\SaidaEstoque;
use App\SaidaEstoqueProduto;
use App\SalaCirurgica;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\ConverteValor;
use Illuminate\Database\Eloquent\Builder;

use function Clue\StreamFilter\fun;

class AgendamentoCentroCirurgicos extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_agendamentos_centro_cirurgico');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $centro_cirurgicos = $instituicao->centrosCirurgicos()->get();
        $estoques = $instituicao->estoques()->get();

        return view('instituicao.agendamentos_centro_cirurgicos.lista', \compact('centro_cirurgicos', 'estoques'));
    }

    public function getAgenda(Request $request)
    {
        $centro_cirurgico_id = $request->input('centro_cirurgico');
        $dados['data'] = Carbon::createFromFormat('d/m/Y', $request->input('data'))->format('Y-m-d');
        $data_selecionada = $dados['data'];
        
        if($centro_cirurgico_id != ""){

            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $centro_cirurgico = $instituicao->centrosCirurgicos()->where('id', $centro_cirurgico_id)->first();

            if(empty($centro_cirurgico)){
                return '';
            }

            $agendamentos = $centro_cirurgico->agendamentos()->search($dados)->get();

            $diaSemana = $this->retornaData(strftime("%u",strtotime($dados['data'])));
            
            $total_disponivel_dia = null;

            if($diaSemana == 'sabado' || $diaSemana == 'domingo'){
                $dia = $diaSemana.'_inicio';
                $dia_fim = $diaSemana.'_fim';
            }else{
                $dia = $diaSemana.'_feira_inicio';
                $dia_fim = $diaSemana.'_feira_fim';
            }
            $horarios = $centro_cirurgico->horarioFuncionamento()->select("{$dia} as inicio","{$dia_fim} as fim")->first();
            
            if($horarios->inicio == null){
                return false;
            }else{
                $hora_inicio = Carbon::createFromFormat('H:i', $horarios->inicio);
                $hora_fim = Carbon::createFromFormat('H:i', $horarios->fim);
                $total_disponivel_dia = gmdate('H:i', $hora_fim->diffInSeconds($hora_inicio));

                if(count($agendamentos) > 0){
    
                    $tempo_total_utilizado = null;
    
                    foreach ($agendamentos as $key => $value) {
                        $hora_inicio_a = date('H:i', strtotime($value->hora_inicio));
                        $hora_inicio_a = Carbon::createFromFormat('H:i', $hora_inicio_a)->format('H:i');
                        
                        $hora_fim_a = date('H:i', strtotime($value->hora_final));
                        $hora_fim_a = Carbon::createFromFormat('H:i', $hora_fim_a);
                        
                        if($tempo_total_utilizado == null){
    
                            $tempo_total_utilizado = gmdate('H:i', $hora_fim_a->diffInSeconds($hora_inicio_a));
                            
                        }else{
                            $diferenca = gmdate('H:i', $hora_fim_a->diffInSeconds($hora_inicio_a));
                            $inicio = Carbon::create($diferenca);
                            $hora_final = explode(':',$tempo_total_utilizado);
                            
                            $inicio->addHours($hora_final[0]);
                            $inicio->addMinute($hora_final[1]);
    
                            $tempo_total_utilizado = Carbon::parse($inicio)->format('H:i');
                        }
                    }
                    
                    $inicio = Carbon::createFromFormat('H:i', $total_disponivel_dia);
                    $hora_final = explode(':',$tempo_total_utilizado);
                    
                    $inicio->subHours($hora_final[0]);
                    $inicio->subMinute($hora_final[1]);

                    $total_disponivel_dia = Carbon::parse($inicio)->format('H:i');
                }
            }
            
            return view('instituicao.agendamentos_centro_cirurgicos.agenda', \compact('agendamentos','hora_inicio','hora_fim','total_disponivel_dia', 'data_selecionada'));
        }

        return '';
    }

    public function novaAgenda(Request $request)
    {
        $centro_cirurgico_id = $request->input('centro_cirurgico');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $centro_cirurgico = $instituicao->centrosCirurgicos()->where('id', $centro_cirurgico_id)->first();

        if(empty($centro_cirurgico)){
            return false;
        }

        $dados['data'] = Carbon::createFromFormat('d/m/Y', $request->input('data'))->format('Y-m-d');
        $diaSemana = $this->retornaData(strftime("%u",strtotime($dados['data'])));
        $agendamentos = $centro_cirurgico->agendamentos()->search($dados)->get();

        if($diaSemana == 'sabado' || $diaSemana == 'domingo'){
            $dia = $diaSemana.'_inicio';
            $dia_fim = $diaSemana.'_fim';
        }else{
            $dia = $diaSemana.'_feira_inicio';
            $dia_fim = $diaSemana.'_feira_fim';
        }
        $horarios = $centro_cirurgico->horarioFuncionamento()->select("{$dia} as inicio","{$dia_fim} as fim")->first();

        $hora_inicio = Carbon::createFromFormat('H:i', $horarios->inicio);
        $hora_final = Carbon::createFromFormat('H:i', $horarios->fim);

        $salas_cirurgicas = $centro_cirurgico->salasCirurgicas()->get();

        $prestadores = $instituicao->medicos()->get();

        return view('instituicao.agendamentos_centro_cirurgicos.modalNovaAgenda', \compact('centro_cirurgico', 'hora_inicio', 'hora_final', 'salas_cirurgicas', 'dados', 'prestadores'));
    }

    public function cirurgiasSalas(Request $request)
    {
        $sala_id = $request->input('sala_id');

        $sala = SalaCirurgica::find($sala_id);

        $cirurgias = $sala->cirurgias()->get();

        return response()->json($cirurgias);
    }

    public function salvarAgendamento(AgendarCentroCirurgicoRequest $request)
    {
        $horas_dias_diferentes = false;
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = $request->validated();

        $centro_cirurgico = $instituicao->centrosCirurgicos()->where('id', $dados['centro_cirurgico_novo_id'])->first();
        
        if(array_key_exists('interditar_novo', $dados)){
            $data = [
                'centro_cirurgico_id' => $dados['centro_cirurgico_novo_id'],
                'tipo' => 'interditado',
                'data' => Carbon::createFromFormat('d/m/Y', $dados['data_novo'])->format('Y-m-d'),
            ];

            $data['hora_inicio'] = $data['data'].' '.$dados['hora_inicio_novo'];
            $data['hora_final'] = $data['data'].' '.$dados['hora_final_novo'];
            if(strtotime($data['hora_final']) < strtotime($data['hora_inicio'])){
                $data['hora_final'] = date('Y-m-d H:i', strtotime($data['hora_final'] . ' +1 day'));
                $horas_dias_diferentes = true;
            }

        }else{
            $data = [
                'centro_cirurgico_id' => $dados['centro_cirurgico_novo_id'],
                'sala_cirurgica_id' => $dados['sala_cirurgica_novo'],
                'cirurgia_id' => $dados['cirurgia_novo'],
                'prestador_id' => $dados['cirurgiao_novo'],
                'tipo' => 'cirurgia',
                'data' => Carbon::createFromFormat('d/m/Y', $dados['data_novo'])->format('Y-m-d'),
            ];

            $data['hora_inicio'] = $data['data'].' '.$dados['hora_inicio_novo'];
        }

        $search = [
            'data' => Carbon::createFromFormat('d/m/Y', $dados['data_novo'])->format('Y-m-d')
        ];    
        
        if($data['tipo'] == 'cirurgia'){            
            $sala = $centro_cirurgico->salasCirurgicas()->where('id', $dados['sala_cirurgica_novo'])->first();
            
            ///TEMPO DURAÇÃO SALA
            $inicio = Carbon::create($sala->tempo_minimo_preparo);
            $hora_final = explode(':',$sala->tempo_minimo_utilizacao);
            
            $inicio->addHours($hora_final[0]);
            $inicio->addMinute($hora_final[1]);
            
            $tempo_total_uso = Carbon::parse($inicio)->format('H:i');
            
            //TEMPO FINAL AGENDA
            $inicio = Carbon::create($dados['hora_inicio_novo']);
            $hora_final = explode(':',$tempo_total_uso);
            
            $inicio->addHours($hora_final[0]);
            $inicio->addMinute($hora_final[1]);
            
            $data['hora_final'] = Carbon::parse($inicio)->format('H:i');

            $data['hora_final'] = $data['data'].' '.$data['hora_final'];
            if(strtotime($data['hora_final']) < strtotime($data['hora_inicio'])){
                $data['hora_final'] = date('Y-m-d H:i', strtotime($data['hora_final'] . ' +1 day'));
                $horas_dias_diferentes = true;
            }
        }

        $diaSemana = $this->retornaData(strftime("%u",strtotime($search['data'])));        
        
        if($diaSemana == 'sabado' || $diaSemana == 'domingo'){
            $dia = $diaSemana.'_inicio';
            $dia_fim = $diaSemana.'_fim';
        }else{
            $dia = $diaSemana.'_feira_inicio';
            $dia_fim = $diaSemana.'_feira_fim';
        }    

        if($horas_dias_diferentes == false){
            $horarios = $centro_cirurgico->horarioFuncionamento()->select("{$dia} as inicio","{$dia_fim} as fim")->where($dia, "<=", Carbon::parse($data['hora_inicio'])->format('H:i'))->where($dia_fim, ">=", Carbon::parse($data['hora_inicio'])->format('H:i'))->where($dia, "<=", Carbon::parse($data['hora_final'])->format('H:i'))->where($dia_fim, ">=", Carbon::parse($data['hora_final'])->format('H:i'))->first();

            if($horarios == null){
                return response()->json([
                    'tipo' => false,    
                    'header' => 'Error',
                    'text' => 'Hora da cirurgia indisponivel!',
                    'icon' => "error"
                ]);
            }

        }else{
            $horarios_inicio = $centro_cirurgico->horarioFuncionamento()->select("{$dia} as inicio","{$dia_fim} as fim")->where($dia, "<=", Carbon::parse($data['hora_inicio'])->format('H:i'))->where($dia_fim, ">=", Carbon::parse($data['hora_inicio'])->format('H:i'))->first();

            $diaSemana = $this->retornaData(strftime("%u",strtotime($data['hora_final'])));        
        
            if($diaSemana == 'sabado' || $diaSemana == 'domingo'){
                $dia = $diaSemana.'_inicio';
                $dia_fim = $diaSemana.'_fim';
            }else{
                $dia = $diaSemana.'_feira_inicio';
                $dia_fim = $diaSemana.'_feira_fim';
            }    
            
            $horarios_final = $centro_cirurgico->horarioFuncionamento()->select("{$dia} as inicio","{$dia_fim} as fim")->where($dia, "<=", Carbon::parse($data['hora_final'])->format('H:i'))->where($dia_fim, ">=", Carbon::parse($data['hora_final'])->format('H:i'))->first();

            if($horarios_inicio == null){
                return response()->json([
                    'tipo' => false,    
                    'header' => 'Error',
                    'text' => 'Hora da cirurgia indisponivel!',
                    'icon' => "error"
                ]);
            }

            if($horarios_final == null){
                return response()->json([
                    'tipo' => false,    
                    'header' => 'Error',
                    'text' => 'Hora da cirurgia indisponivel!',
                    'icon' => "error"
                ]);
            }

        }        
        
        $agendamentos = $centro_cirurgico->agendamentos()->search($search, $data['hora_inicio'], $data['hora_final'])->first();

        if($agendamentos){
            if(date('Y-m-d H:i', strtotime($data['hora_inicio'])) < date('Y-m-d H:i', strtotime($agendamentos->hora_final))){
                if(date('Y-m-d H:i', strtotime($data['hora_final'])) > date('Y-m-d H:i', strtotime($agendamentos->hora_inicio))){
                
                    return response()->json([
                        'tipo' => false,    
                        'header' => 'Error',
                        'text' => 'Hora da cirurgia indisponivel!',
                        'icon' => "error"
                    ]);
                }
            }else if(date('Y-m-d H:i', strtotime($data['hora_final'])) > date('Y-m-d H:i', strtotime($agendamentos->hora_inicio))){
                if(date('Y-m-d H:i', strtotime($data['hora_inicio'])) < date('Y-m-d H:i', strtotime($agendamentos->hora_final))){
                    
                    return response()->json([
                        'tipo' => false,    
                        'header' => 'Error',
                        'text' => 'Hora da cirurgia indisponivel!',
                        'icon' => "error"
                    ]);
                }
            }

        }
        
        DB::transaction(function() use($data, $request, $instituicao){
            $usuario_logado = $request->user('instituicao');

            $agendamento = AgendamentoCentroCirurgico::create($data);

            $agendamento->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return response()->json(['tipo' => true]);
    }

    public function excluirAgendamento(Request $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao_id, 404);

        DB::transaction(function() use($agendamento, $instituicao_id, $request){
            $agendamento->delete();

            $usuario_logado = $request->user('instituicao');
            $agendamento->criarLogExclusao($usuario_logado, $instituicao_id);
        });

        return response()->json([
            'tipo' => false,    
            'header' => 'Sucesso',
            'text' => 'Agendamento excluido com sucesso!',
            'icon' => "success"
        ]);
    }

    public function editarAgenda(Request $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao->id, 404);

        // $atendimento = Agendamentos::where('status', 'agendado')->whereHas('instituicoesAgenda.prestadores.instituicao', function($q) use($instituicao){
        //     $q->where('id', $instituicao->id);
        // })->get();

        $acomodacoes = $instituicao->acomodacoes()->get();
        $unidades_internacoes = $instituicao->unidadesInternacoes()->where('ativo', 1)->get();
        $vias_acesso = $instituicao->ViasAcesso()->get();
        $anestesias = $instituicao->tipoAnestesia()->get();

        $prestadores = $instituicao->prestadores()->select('prestadores_id')->where('anestesista', 1)->groupBy('prestadores_id')->get();
        // $cids = Cid::get();

        return view('instituicao.agendamentos_centro_cirurgicos.modaleditarAgenda', \compact('agendamento', 'acomodacoes', 'unidades_internacoes', 'vias_acesso', 'anestesias','prestadores'));
    }

    public function equipamentosCaixasCirurgicos(Request $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao->id, 404);

        $equipamentos = $instituicao->Equipamentos()->get();
        $caixas_cirurgicos = $instituicao->caixasCirurgicos()->get();

        return view('instituicao.agendamentos_centro_cirurgicos.dados_equipamentos_caixas_cirurgicos_agenda', \compact('agendamento', 'equipamentos', 'caixas_cirurgicos'));
    }
    
    public function outrasCirurgias(Request $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao->id, 404);

        $cirurgias = $instituicao->cirurgias()->get();
        $vias_acesso = $instituicao->ViasAcesso()->get();
        $medicos = $instituicao->medicos()->get();
        $convenios = $instituicao->convenios()->get();

        return view('instituicao.agendamentos_centro_cirurgicos.dados_outras_cirurgias', \compact('agendamento', 'cirurgias', 'vias_acesso', 'medicos', 'convenios'));
    }
    
    public function sanguesDerivados(Request $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao->id, 404);

        $sangues_derivados = $instituicao->sanguesDerivados()->get();

        return view('instituicao.agendamentos_centro_cirurgicos.dados_sangues_derivados', \compact('agendamento', 'sangues_derivados'));
    }
    
    public function produtos(Request $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao->id, 404);

        // $produtos = $instituicao->produtos()->get();
        // $fornecedores = $instituicao->instituicaoPessoas()->where('tipo', 3)->get();

        return view('instituicao.agendamentos_centro_cirurgicos.dados_produtos', \compact('agendamento'));
    }

    public function updateAgenda(UpdateAgendamentoCentroCirurgicoRequest $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao->id, 404);
            
        $dados = [
            'paciente_id' => $request->paciente_id_editar,
            'ambulatorio_id' => $request->ambulatorio_id_editar,
            'urgencia_id' => $request->urgencia_id_editar,
            'internacao_id' => $request->internacao_id_editar,
            'acomodacao_id' => $request->acomodacao_editar,
            'unidade_internacao_id' => $request->unidade_internacao_editar,
            'via_acesso_id' => $request->via_acesso_editar,
            'anestesista_id' => $request->anestesista_editar,
            'tipo_anestesia_id' => $request->tipo_anestesia_editar,
            'pacote' => $request->boolean('pacote_editar'),
            'cid_id' => $request->cid_editar,
            'obs' => $request->obs_editar,
            'sala_cirurgica_entrada' => $request->sala_cirurgica_entrada,
            'sala_cirurgica_saida' => $request->sala_cirurgica_saida,
            'anestesia_inicio' => $request->anestesia_inicio,
            'anestesia_fim' => $request->anestesia_fim,
            'cirurgia_inicio' => $request->cirurgia_inicio,
            'cirurgia_fim' => $request->cirurgia_fim,
            'limpeza_inicio' => $request->limpeza_inicio,
            'limpeza_fim' => $request->limpeza_fim,
        ];

        if($request->sala_cirurgica_entrada == ""){
            unset($dados['sala_cirurgica_entrada']);
        }
        if($request->sala_cirurgica_saida == ""){
            unset($dados['sala_cirurgica_saida']);
        }
        if($request->anestesia_inicio == ""){
            unset($dados['anestesia_inicio']);
        }
        if($request->anestesia_fim == ""){
            unset($dados['anestesia_fim']);
        }
        if($request->cirurgia_inicio == ""){
            unset($dados['cirurgia_inicio']);
        }
        if($request->cirurgia_fim == ""){
            unset($dados['cirurgia_fim']);
        }
        if($request->limpeza_inicio == ""){
            unset($dados['limpeza_inicio']);
        }
        if($request->limpeza_fim == ""){
            unset($dados['limpeza_fim']);
        }
    
        $equipamentoCaixaCirurgicoIn = $request->boolean('in_page_equipamentos_caixas_cirurgicas');
        $equipamentos = [];
        if(array_key_exists('equipamentos', $request->validated())){
            $equipamentos = collect($request->validated()['equipamentos'])
                ->filter(function ($equipamento){
                    return !is_null($equipamento['equipamento']);
                })
                ->map(function ($equipamento){
                    return [
                        'equipamento_id' => $equipamento['equipamento'],
                        'quantidade' => $equipamento['quantidade'],
                    ];
                });
        }

        $caixas_cirurgicos = [];        
        if(array_key_exists('caixas_cirurgicas', $request->validated())){
            $caixas_cirurgicos = collect($request->validated()['caixas_cirurgicas'])
                ->filter(function ($caixa_cirurgica){
                    return !is_null($caixa_cirurgica['caixa_cirurgica']);
                })
                ->map(function ($caixa_cirurgica){
                    return [
                        'caixa_cirurgica_id' => $caixa_cirurgica['caixa_cirurgica'],
                        'quantidade' => $caixa_cirurgica['quantidade'],
                    ];
                });
        }
        
        $outrasCirurgiasIn = $request->boolean('in_page_outras_cirurgias');
        $outras_cirurgias = [];        
        if(array_key_exists('outras_cirurgias', $request->validated())){
            $outras_cirurgias = collect($request->validated()['outras_cirurgias'])
                ->filter(function ($outra_cirurgia){
                    return !is_null($outra_cirurgia['cirurgia']);
                })
                ->map(function ($outra_cirurgia){
                    return [
                        'cirurgia_id' => $outra_cirurgia['cirurgia'],
                        'via_acesso_id' => $outra_cirurgia['via_acesso'],
                        'convenio_id' => $outra_cirurgia['convenio'],
                        'cirurgiao_id' => $outra_cirurgia['medico'],
                        'pacote' => $outra_cirurgia['pacote'],
                        'tempo' => $outra_cirurgia['tempo'],
                    ];
                });
        }
        
        $sangueDerivadosIn = $request->boolean('in_page_sangues_derivados');
        $sangues_derivados = [];        
        if(array_key_exists('sangues_derivados', $request->validated())){
            $sangues_derivados = collect($request->validated()['sangues_derivados'])
                ->filter(function ($sangue_derivado){
                    return !is_null($sangue_derivado['sangue_derivado']);
                })
                ->map(function ($sangue_derivado){
                    return [
                        'sangue_derivado_id' => $sangue_derivado['sangue_derivado'],
                        'quantidade' => $sangue_derivado['quantidade'],
                    ];
                });
        }
        
        $produtosIn = $request->boolean('in_page_produtos');
        
        $produtos = [];        
        if(array_key_exists('produtos', $request->validated())){
            $produtos = collect($request->validated()['produtos'])->filter(function ($produto_baixa) use ($instituicao) {
                    // Filtrando por instituicao
                    $entrada = EstoqueEntradaProdutos::where('id', $produto_baixa['id_entrada_produto'] ?? 0)
                        ->whereHas('entrada', function (Builder $query) use ($instituicao) {
                            $query->where('estoque_entradas.instituicao_id', $instituicao->id);
                        })->first();

                    // Filtrando por quantidade válida e calculando valor total
                    return !empty($entrada) && ($entrada->quantidade_estoque ?? 0) >= ($produto_baixa['quantidade'] ?? 0);
                })->map(function ($produto){
                    return [
                        'id_entrada_produto' => $produto['id_entrada_produto'],
                        'quantidade' => $produto['quantidade'],
                        'obs' => $produto['obs']
                    ];
                });
        }        
        
        DB::transaction(function() use($dados, $instituicao, $agendamento, $request, $equipamentos, $equipamentoCaixaCirurgicoIn, $caixas_cirurgicos, $outras_cirurgias, $outrasCirurgiasIn, $sangueDerivadosIn, $sangues_derivados, $produtosIn, $produtos){
            $usuario_logado = $request->user('instituicao');
            $agendamento->update($dados);

            $agendamento->criarLogEdicao($usuario_logado, $instituicao->id);
            
            //ADD EQUIPAMENTOS
            $this->relacaoAgendamento($agendamento, 'equipamentos', $equipamentos, $equipamentoCaixaCirurgicoIn);
            
            //ADD CAIXAS CIRÚRGICAS
            $this->relacaoAgendamento($agendamento, 'caixasCirurgicas', $caixas_cirurgicos, $equipamentoCaixaCirurgicoIn);
            
            //ADD OUTRAS CIRURGIAS
            $this->relacaoAgendamento($agendamento, 'outrasCirurgias', $outras_cirurgias, $outrasCirurgiasIn);
            
            //ADD SANGUE DERIVADOS
            $this->relacaoAgendamento($agendamento, 'sangueDerivados', $sangues_derivados, $sangueDerivadosIn);
            //ADD PRODUTOS
            $this->relacaoAgendamento($agendamento, 'produtos', $produtos, $produtosIn);
        });

        return response()->json(['sucesso' => 'success']);
    }

    public function relacaoAgendamento(AgendamentoCentroCirurgico $agendamento, $relacao, $dados, $existe)
    {
        if($existe == true){
            $agendamento->$relacao()->detach();
            if(count($dados) > 0){
                $agendamento->$relacao()->attach($dados);
            }
        }
    }

    public function retornaData($dia)
    {
        if($dia == 1){
            return 'segunda';
        }
        if($dia == 2){
            return 'terca';
        }
        if($dia == 3){
            return 'quarta';
        }
        if($dia == 4){
            return 'quinta';
        }
        if($dia == 5){
            return 'sexta';
        }
        if($dia == 6){
            return 'sabado';
        }
        if($dia == 7){
            return 'domingo';
        }
    }

    public function getPacientes(Request $request)
    {
        // $atendimento = Agendamentos::where('status', 'agendado')->whereHas('instituicoesAgenda.prestadores.instituicao', function($q) use($instituicao){
        //     $q->where('id', $instituicao->id);
        // })->get();

        if ($request->ajax()) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            // dd($request->page);
            $atendimento = Agendamentos::getCentroCirurgicoAgendamentos($nome, $instituicao->id)->simplePaginate(100);
            // dd($atendimento->toArray());
            $morePages = true;
            if (empty($atendimento->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $atendimento->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($atendimento->per_page());
            return response()->json($results);
        }
    }

    public function getPacientesUrgencia(Request $request)
    {
        // $atendimento = Agendamentos::where('status', 'agendado')->whereHas('instituicoesAgenda.prestadores.instituicao', function($q) use($instituicao){
        //     $q->where('id', $instituicao->id);
        // })->get();

        if ($request->ajax()) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            // dd($request->page);
            $atendimento = AgendamentoAtendimentoUrgencia::getCentroCirurgicoUrgencia($nome, $instituicao->id)->simplePaginate(100);
            // dd($atendimento->toArray());
            $morePages = true;
            if (empty($atendimento->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $atendimento->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($atendimento->per_page());
            return response()->json($results);
        }
    }

    public function getPacientesInternacao(Request $request)
    {
        // $atendimento = Agendamentos::where('status', 'agendado')->whereHas('instituicoesAgenda.prestadores.instituicao', function($q) use($instituicao){
        //     $q->where('id', $instituicao->id);
        // })->get();

        if ($request->ajax()) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            // dd($request->page);
            $atendimento = Internacao::getCentroCirurgicoInternacao($nome, $instituicao->id)->simplePaginate(100);
            // dd($atendimento->toArray());
            $morePages = true;
            if (empty($atendimento->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $atendimento->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($atendimento->per_page());
            return response()->json($results);
        }
    }
    
    public function getCids(Request $request)
    {
        // $atendimento = Agendamentos::where('status', 'agendado')->whereHas('instituicoesAgenda.prestadores.instituicao', function($q) use($instituicao){
        //     $q->where('id', $instituicao->id);
        // })->get();

        if ($request->ajax()) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            // dd($request->page);
            $cids = Cid::search($nome, $instituicao->id)->simplePaginate(100);
            // dd($cids->toArray());
            $morePages = true;
            if (empty($cids->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $cids->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($cids->per_page());
            return response()->json($results);
        }
    }
    
    public function getProdutos(Request $request)
    {
        if ($request->ajax()) {
            $nome = ($request->input('q')) ? $request->input('q') : '';
            $entradas = EstoqueEntradaProdutos::buscarProdutosEmEstoque($nome)
                ->with('produto.especie')    
                ->simplePaginate(30);

            $results = array(
                "results" => $entradas->items(),
                "pagination" => array(
                    "more" => !empty($entradas->nextPageUrl()),
                )
            );

            return response()->json($results);
        }
    }

    public function getFornecedores(Request $request, Produto $produto)
    {
        abort_unless($request->session()->get('instituicao') === $produto->instituicao_id, 404);
        $fornecedores = Pessoa::getFornecedoresEntrada($request->session()->get('instituicao'), $produto->id)->get();
        
        foreach ($fornecedores as $key => $value) {
            if(count($value->estoqueEntradas) > 0){
                foreach ($value->estoqueEntradas as $keyE => $entradas) {
                    if(count($entradas->estoqueEntradaProdutos) > 0){
                        foreach ($entradas->estoqueEntradaProdutos as $keyL => $lote) {
                            $loteSaida = $produto->estoqueSaidas()->where('lote',$lote->lote)->sum('quantidade');
                            if($lote->quantidade <= $loteSaida){
                                unset($entradas->estoqueEntradaProdutos[$keyL]);
                            }else{
                                $lote->quantidade_atual = $lote->quantidade - $loteSaida;
                            }
                        }
                        if(count($entradas->estoqueEntradaProdutos) == 0){
                            unset($value->estoqueEntradas[$keyE]);
                        }
                    }else{
                        unset($value->estoqueEntradas[$keyE]);
                    }
                }
                if(count($value->estoqueEntradas) == 0){
                    unset($fornecedores[$key]);
                }
            }else{
                unset($fornecedores[$key]);
            }
        }

        return response()->json($fornecedores);  
    }
    
    public function getLotesFornecedores(Request $request, Produto $produto, Pessoa $fornecedor)
    {
        abort_unless($request->session()->get('instituicao') === $produto->instituicao_id, 404);
        abort_unless($request->session()->get('instituicao') === $fornecedor->instituicao_id, 404);
        $lotes = $produto->estoqueEntradas()->whereHas('estoque_entrada', function($q) use($fornecedor){
            $q->where('id_fornecedor', $fornecedor->id);
        })->get();
        
        foreach ($lotes as $keyL => $lote) {
            $loteSaida = $produto->estoqueSaidas()->where('lote',$lote->lote)->sum('quantidade');
            if($lote->quantidade <= $loteSaida){
                unset($lotes[$keyL]);
            }else{
                $lote->quantidade_atual = $lote->quantidade - $loteSaida;
            }
        }

        return response()->json($lotes);  
    }

    public function dadosComplementares(Request $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao->id, 404);

        return view('instituicao.agendamentos_centro_cirurgicos.dados_complementares', \compact('agendamento'));
    }

    public function fichaCirurgica(Request $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao->id, 404);

        $idade = null;
        if($agendamento->pessoa->nascimento){
            $idade = ConverteValor::calcularIdade($agendamento->pessoa->nascimento);
        }

        return view('instituicao.agendamentos_centro_cirurgicos.ficha_cirurgica', \compact('agendamento', 'instituicao', 'idade'));
    }
    
    public function folhaSala(Request $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao->id, 404);

        return view('instituicao.agendamentos_centro_cirurgicos.folha_sala', \compact('agendamento', 'instituicao'));
    }

    public function mudarStatusAgendamento(Request $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao->id, 404);

        DB::transaction(function() use($agendamento, $instituicao, $request){
            $status = AgendamentoCentroCirurgico::retornaStatus($agendamento->status);
            $agendamento->update(['status' => $status]);
            $agendamento->criarLogEdicao($request->user('instituicao'), $instituicao->id);
        });

        return response()->json([
            'tipo' => false,    
            'header' => 'Sucesso',
            'text' => 'Agendamento excluido com sucesso!',
            'icon' => "success"
        ]);
    }

    public function gerarEstoque(Request $request, AgendamentoCentroCirurgico $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->centroCirurgico->instituicao_id === $instituicao->id, 404);

        DB::transaction(function() use($agendamento, $request, $instituicao){
            $usuario_logado = $request->user('instituicao');

            if($agendamento->saida_estoque_id == null){
                // Criando baixa de estoque
                $baixa_estoque = EstoqueBaixa::create([
                    'estoque_id' => $request->input('estoque_id'),
                    'motivo_baixa_id' => SaidaEstoque::buscarMotivoSaidaEstoque()->id,
                    'data_emissao' => \date('Y-m-d'),
                    'data_hora_baixa' => \date('H:i:s'),
                    'usuario_id' => $usuario_logado->id,
                    'instituicao_id' => $instituicao->id
                ]);

                $saida_estoque = SaidaEstoque::create([
                    'estoques_id' => $request->input('estoque_id'),
                    'estoque_baixa_id' => $baixa_estoque->id,
                    'usuarios_id' => $usuario_logado->id,
                    'instituicoes_id' => $instituicao->id
                ]);

                $saida_estoque->criarLogInstituicaoCadastro($usuario_logado, $instituicao->id);
                $baixa_estoque->criarLogCadastro($usuario_logado);

                $agendamento->update(['saida_estoque_id' => $saida_estoque->id]);
                $agendamento->criarLogEdicao($usuario_logado, $instituicao->id);

                $produtos_baixa = [];
                $produtos_selecionados = [];
                $produtos = $agendamento->produtos()->get();

                if(count($produtos) > 0){
                    foreach ($produtos as $key => $item) {  
                        if($item->pivot->lote_id){
                            $produtos_baixa[] = [
                                'produto_id' => $item['id'],
                                'lote' => EstoqueEntradaProdutos::find($item['pivot']['lote_id'])->lote,
                                'quantidade' => $item['pivot']['quantidade'],
                                'baixa_id' => $baixa_estoque->id
                            ];
                            
                            $produtos_selecionados[] = [
                                'saida_estoque_id' => $saida_estoque->id,
                                'codigo_de_barras' =>  null,
                            ];   
                        }
                    }

                    $keys_produtos_selecionados = array_keys($produtos_selecionados);
                    $baixa_estoque->overwrite($baixa_estoque->estoqueBaixaProdutos(), $produtos_baixa, function ($new, $key) use (&$produtos_selecionados, $keys_produtos_selecionados) {
                        $produtos_selecionados[$keys_produtos_selecionados[$key]]['estoque_baixa_produtos_id'] = $new->id;
                    });

                    $saida_estoque->overwrite($saida_estoque->produtosSaida(), $produtos_selecionados);

                    foreach($produtos as $item){
                        $saida = SaidaEstoqueProduto::whereHas('baixaProduto', function($q) use($item){
                            $q->where('produto_id', $item->id)
                            ->where('lote', $item->pivot->lote_id);
                        })
                        ->where('saida_estoque_id', $saida_estoque->id)
                        ->first();

                        if(!empty($saida)){
                            DB::table('agendamentos_centro_cirurgico_has_produtos')
                                ->where('produto_id', $item->id)
                                ->where('agendamento_centro_cirurgico_id', $agendamento->id)
                                ->where('lote_id', $item->pivot->lote_id)
                                ->update(['saida_estoque_produto_id' => $saida->id]);
                        }
                    }
                }

            }else{

                $saida_estoque = SaidaEstoque::find($agendamento->saida_estoque_id);
                $baixa_estoque = EstoqueBaixa::find($saida_estoque->estoque_baixa_id);

                $produtos_baixa = [];
                $produtos_selecionados = [];
                $produtos = $agendamento->produtos()->get();

                if(count($produtos) > 0){
                    foreach ($produtos as $key => $item) {  
                        if($item->pivot->lote_id){
                            $produtos_baixa[] = [
                                'produto_id' => $item['id'],
                                'lote' => EstoqueEntradaProdutos::find($item['pivot']['lote_id'])->lote,
                                'quantidade' => $item['pivot']['quantidade'],
                                'baixa_id' => $baixa_estoque->id
                            ];
                            
                            $produtos_selecionados[] = [
                                'saida_estoque_id' => $saida_estoque->id,
                                'codigo_de_barras' =>  null,
                            ];   
                        }
                    }

                    $keys_produtos_selecionados = array_keys($produtos_selecionados);
                    $baixa_estoque->overwrite($baixa_estoque->estoqueBaixaProdutos(), $produtos_baixa, function ($new, $key) use (&$produtos_selecionados, $keys_produtos_selecionados) {
                        $produtos_selecionados[$keys_produtos_selecionados[$key]]['estoque_baixa_produtos_id'] = $new->id;
                    });

                    $saida_estoque->overwrite($saida_estoque->produtosSaida(), $produtos_selecionados);

                    $saida_estoque_produto_ids = "";
                    foreach($produtos as $item){
                        if($item->saida_estoque_produto_id == null){
                            $saida = SaidaEstoqueProduto::whereHas('baixaProduto', function($q) use($item){
                                $q->where('produto_id', $item->id)
                                ->where('lote', EstoqueEntradaProdutos::find($item['pivot']['lote_id'])->lote);
                            })
                            ->where('saida_estoque_id', $saida_estoque->id)
                            ->first();
    
                            if(!empty($saida)){
                                DB::table('agendamentos_centro_cirurgico_has_produtos')
                                    ->where('produto_id', $item->id)
                                    ->where('agendamento_centro_cirurgico_id', $agendamento->id)
                                    ->where('lote_id', $item->pivot->lote_id)
                                    ->update(['saida_estoque_produto_id' => $saida->id]);
                            }
                        }else{
                            if($saida_estoque_produto_ids != ""){
                                $saida_estoque_produto_ids += ','.$item->saida_estoque_id;
                            }else{
                                $saida_estoque_produto_ids = $item->saida_estoque_id;
                            }
                        }
                    }

                    if($saida_estoque_produto_ids != ""){
                        $saida_estoque_produto = SaidaEstoqueProduto::whereNotIn($saida_estoque_produto_ids)->get();
                        if(count($saida_estoque_produto) > 0){
                            foreach ($saida_estoque_produto as $key => $value) {
                                $estoque_baixa_produto = ProdutoBaixa::find($value->estoque_baixa_produtos_id)->delete();
                                $value->delete();
                            }
                        }
                    }
                }
            }
        });
    }
}
