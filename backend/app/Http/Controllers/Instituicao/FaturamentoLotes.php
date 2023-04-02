<?php

namespace App\Http\Controllers\instituicao;

use App\Agendamentos;
use App\FaturamentoLote;
use App\FaturamentoLoteGuia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Faturamento\CriarLotesRequest;
use App\Http\Requests\Faturamento\PesquisaFiltrosRequest;
use App\Instituicao;
use App\Prestador;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaturamentoLotes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_lotes');
        return view('instituicao.faturamento_lotes.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $prestadores = $instituicao->medicos()->get();
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_lotes');
        return view('instituicao.faturamento_lotes.criar', \compact('prestadores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarLotesRequest $request, FaturamentoLote $faturamentoLote)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_lotes');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));


        DB::transaction(function() use ($request, $instituicao){

            $usuario_logado = $request->user('instituicao');

            $dados = $request->validated();

            $prestador = Prestador::find($dados['prestadores_id'])->toArray();

        //    echo '<pre>';
        //    print_r($prestador['sancoop_cod_coperado']);
        //     exit;
            

            //CASO TENHA SIDO SANCOOP, IREMOS CRIAR O PROTOCOLO NA API E ATUALIZAR NO BANCO LOCAL
            if($dados['tipo'] == 2 && !empty($prestador['sancoop_cod_coperado'])):

                //PRIMEIRO VAMOS VERIFICAR SE TEM PROTOCOLO DO PRESTADOR ABERTO, SE NAO TIVER ELE ABRE
                $protocoloAberto = DB::table('faturamento_protocolos')
                    ->where('instituicao_id', $instituicao->id)
                    ->where('status', 0)
                    ->where('prestadores_id', $prestador->id)
                    ->first();

               if(empty($protocoloAberto)):

                    $result = $instituicao->faturamento_lotes()->create($dados);

                    $retorno_protocolo = $this->criarProtocoloSancoop($prestador);

                    //SE DER ERRO NÃO VAMOS NEM CRIAR
                    if(empty($retorno_protocolo)):
                        return false;
                    else:
                        //CASO TENHA CRIADO O PROTOCOLO NA SANCOOP VAMOS ATUALIZAR NO BANCO LOCAL
                        $atualizar_cod_protocolo['cod_externo'] = $retorno_protocolo;
                        $result->update($atualizar_cod_protocolo);
                    endif;
                    

                endif;

            else:

                $result = $instituicao->faturamento_lotes()->create($dados);

            endif;

            $result->criarLogCadastro($usuario_logado, $instituicao->id);

        });



        return redirect()->route('instituicao.faturamento.lotes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Lote criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, FaturamentoLote $faturamentoLote)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_lotes');
        $instituicao = $request->session()->get("instituicao");

        return view('instituicao.faturamento_lotes.editar', [
            'dado' => $faturamentoLote
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarLotesRequest $request, FaturamentoLote $faturamentoLote)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_contas');
        $instituicao = $request->session()->get("instituicao");
       
        DB::transaction(function() use ($request, $instituicao, $faturamentoLote){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $faturamentoLote->update($dados);
            $faturamentoLote->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.faturamento.lotes.edit', [$faturamentoLote])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Lote alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FaturamentoLote $faturamentoLote)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_lotes');

        $instituicao = $request->session()->get("instituicao");
        
        DB::transaction(function () use ($faturamentoLote, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $faturamentoLote->delete();
            $faturamentoLote->criarLogExclusao($usuario_logado, $instituicao);

            return $faturamentoLote;
        });

        return redirect()->route('instituicao.faturamento.lotes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Lote excluído com sucesso!'
        ]);
    }

    //GUIAS FATURAMENTO MANUAL
    public function guias(Request $request, FaturamentoLote $faturamento)
    {
        // dd($faturamento->toArray());
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_lotes');
        return view('instituicao.faturamento_lotes.guias', \compact('faturamento'));
    }

    //GUIAS FATURAMENTO SANCOOP
    public function guiasSancoop(Request $request, FaturamentoLote $faturamento)
    {

        // dd($faturamento->toArray());
        // exit;

        //CONSULTAR AS GUIDAS DO PROTOCOLO **********TERMINAR ISSO JOGAR NA VIEW PRA TER CERTEZA DAS GUIAS QUE SERÃO TRANSFERIDAS
        // $this->consultarNumGuiaLoteNaSancoop($faturamento->cod_externo);

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_lotes');
        return view('instituicao.faturamento_lotes.guias_sancoop', \compact('faturamento'));
    }

    public function tabelaFiltros(PesquisaFiltrosRequest $request)
    {
        $dados = $request->validated();

        // dd($dados);
//testa
        $agendamentos = Agendamentos::doesntHave('faturamentoLoteGuia')->GetAgendamentosFinalizadosGuias($dados)
                                                                    ->with('pessoa')
                                                                    ->get();

        // dd($agendamentos->toArray());

        return view('instituicao.faturamento_lotes.tabela_filtros', \compact('agendamentos'));
    }

    public function adicionarGuias(Request $request){

        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        $faturamentoLote = FaturamentoLote::where('id', $_POST['idlote'])
                                         ->with('prestador')
                                         ->first()
                                         ->toArray();

        $faturamentoLoteNaSancoop = $this->consultarLoteNaSancoop($faturamentoLote['cod_externo'], $faturamentoLote['prestador']['cpf']);

        //SÓ PODE ADICIONAR SE TIVER COM O STATUS ABERTO
        if($faturamentoLoteNaSancoop->status == 'ABERTO'):


        $numGuiafaturamentoLoteNaSancoop = $this->consultarNumGuiaLoteNaSancoop($faturamentoLote->cod_externo);



        //SEPARANDO OS IDS VINDOS DOS AGENDAMENTOS
        $idsAgendamentos = explode(';', $_POST['idsagendamentos']);
        
        $cont = count($idsAgendamentos);


        for ($j = 0; $j < $cont; $j++) {

            //VAMOS INSERIR NA SANCOOP

            $agendamento = Agendamentos::where('id', $idsAgendamentos[$j])
                                         ->with('pessoa')
                                         ->with('pessoa.carteirinha')
                                         ->with('instituicoesAgenda.prestadores.prestador')
                                         ->with('agendamentoProcedimento.procedimentoInstituicaoConvenio')
                                         ->with('agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios')
                                         ->first()
                                         ->toArray();



            if($agendamento):

                //VAMOS PERCORRER OS PROCEDIMENTOS DO AGENDAMENTO
                if(!empty($agendamento['agendamento_procedimento'])):

                    $incremento_procedimento_guia = 1;
                    //TIPO DE PROCEDIMENTO TERMINAR ISTO DEPOIS: SE É "CONSULTA" OU "SADT" QUE É O TIPO DE ATENDIMENTO
                    $recem_nascido        = 'NÃO';
                    $tipo_atendimento     = 'CONSULTA';
                    $material_medicamento = 'NÃO';

                    $total_procedimentos_guias = sizeof($agendamento['agendamento_procedimento']);


                        //MONTAMOS A PRIMEIRA GUIA CASO SEJA ABAIXO DE 5 PROCEDIMENTOS
                        if($total_procedimentos_guias > 5):
                            $guia_unica_percorrer = 5;
                        else:
                            $guia_unica_percorrer = $total_procedimentos_guias;
                        endif;

                        // $arrayItens =  array();

                        for ($i=0; $i < $guia_unica_percorrer; $i++) { 
                            $incremento_procedimento = $i + 1;
                            $arrayItens['CodProcedimento'.$incremento_procedimento.''] = $agendamento['agendamento_procedimento'][$i]['procedimento_instituicao_convenio']['sancoop_cod_procedimento'];
                            //TERMINAR ISTO
                            $arrayItens['CodAutorizacao'.$incremento_procedimento.'']  = 0;
                            $arrayItens['QtProcedRealizada'.$incremento_procedimento.'']  = 1;
                        }



                        //CRIANDO A GUIA NO BANCO LOCAL
                        $guia_lote_local = array(
                            'cod_externo' => $numGuiafaturamentoLoteNaSancoop + 1,
                            'status' => 0,
                            'faturamento_protocolo_id' => $_POST['idlote'],
                            'agendamento_id' => $idsAgendamentos[$j]
                        );

                        $guia = FaturamentoLoteGuia::create($guia_lote_local);
                        

                        $guia_transmitir_unica = [
                            'CodProtocolo'  => $faturamentoLote->cod_externo,
                            'Guia' =>  $numGuiafaturamentoLoteNaSancoop + 1,
                            'CodConvenio' => $agendamento['agendamento_procedimento'][0]['procedimento_instituicao_convenio']['convenios']['sancoop_cod_convenio'],
                            'Paciente' => $agendamento['pessoa']['nome'],
                            'RN' => $recem_nascido,
                            'DtAtendimento' => date('Y-m-d', strtotime($agendamento['data'])),
                            'NumCarteirinha' => $agendamento['pessoa']['carteirinha'][0]['carteirinha'],
                            'Hora' => date('H:i:s', strtotime($agendamento['data'])),
                            'MatMed' => $material_medicamento,
                            'guia_digitalizada' => '',
                            'cod_comparativo' => $guia->id,
                            'CodHospital' => $instituicao->sancoop_cod_instituicao,
                            'Procedimento' => $tipo_atendimento,
                            
                        ];

                        $guia_transmitir[0] = array_merge($guia_transmitir_unica, $arrayItens);

                        $total_guias_transferir = 1;


                      



                        //**************FINALIZAR ISTO ESTÁ DANDO DUPLICIDADE NO ARRAY */
                        //*****CASO A QUANTIDADE DE PROCEDIMENTOS ULTRAPASSE 5 TEMOS QUE CRIAR NOVAS GUIAS
                        if($total_procedimentos_guias > 5):

                            $incremento_procedimento = 1;
                            $percorrendo_num = 1;
                            $total_guias_transferir = 2;
                            $percorrer_total_de = $total_procedimentos_guias - 5;

                            // echo $total_procedimentos_guias;
                            // exit;

                            for ($i=5; $i < $total_procedimentos_guias; $i++) { 


                                $arrayItens[$total_guias_transferir]['CodProcedimento'.$incremento_procedimento.''] = $agendamento['agendamento_procedimento'][$i]['procedimento_instituicao_convenio']['sancoop_cod_procedimento'];
                                //TERMINAR ISTO
                                $arrayItens[$total_guias_transferir]['CodAutorizacao'.$incremento_procedimento.'']  = 0;
                                $arrayItens[$total_guias_transferir]['QtProcedRealizada'.$incremento_procedimento.'']  = 1;

                               
                                $incremento_procedimento++;


                                if($percorrendo_num % 5 == 0):


                                        $guia_transmitir_varias[$total_guias_transferir] = [
                                            'CodProtocolo'  => $faturamentoLote->cod_externo,
                                            'Guia' =>  $total_guias_transferir,
                                            'CodConvenio' => $agendamento['agendamento_procedimento'][0]['procedimento_instituicao_convenio']['convenios']['sancoop_cod_convenio'],
                                            'Paciente' => $agendamento['pessoa']['nome'],
                                            'RN' => $recem_nascido,
                                            'DtAtendimento' => date('Y-m-d', strtotime($agendamento['data'])),
                                            'NumCarteirinha' => $agendamento['pessoa']['carteirinha'][0]['carteirinha'],
                                            'Hora' => date('H:i:s', strtotime($agendamento['data'])),
                                            'MatMed' => $material_medicamento,
                                            'guia_digitalizada' => '',
                                            'CodHospital' => $instituicao->sancoop_cod_instituicao,
                                            'Procedimento' => $tipo_atendimento,
                                            
                                        ];

                                        $guia_transmitir[] = array_merge($guia_transmitir_varias[$total_guias_transferir], $arrayItens[$total_guias_transferir]);

                                        //nomes dos arrays a da merge incrimentando
                                        $total_guias_transferir + 1;

                                        //incremento do procedimento volta pra 1 de novo
                                        $incremento_procedimento = 1;

                                endif;


                                
                                //VAMOS FINALIZAR COM O ULTIMO ARRAY DO QUE SOBROU QUE NAO É MULTIPLO DE 5
                                if($percorrendo_num == $percorrer_total_de):

                                    $guia_transmitir_varias[$total_guias_transferir] = [
                                        'CodProtocolo'  => $faturamentoLote->cod_externo,
                                        'Guia' =>  $total_guias_transferir,
                                        'CodConvenio' => $agendamento['agendamento_procedimento'][0]['procedimento_instituicao_convenio']['convenios']['sancoop_cod_convenio'],
                                        'Paciente' => $agendamento['pessoa']['nome'],
                                        'RN' => $recem_nascido,
                                        'DtAtendimento' => date('Y-m-d', strtotime($agendamento['data'])),
                                        'NumCarteirinha' => $agendamento['pessoa']['carteirinha'][0]['carteirinha'],
                                        'Hora' => date('H:i:s', strtotime($agendamento['data'])),
                                        'MatMed' => $material_medicamento,
                                        // 'guia_digitalizada' => '',
                                        'CodHospital' => $instituicao->sancoop_cod_instituicao,
                                        'Procedimento' => $tipo_atendimento,
                                        
                                    ];

                                    


                                    $guia_transmitir[] = array_merge($guia_transmitir_varias[$total_guias_transferir], $arrayItens[$total_guias_transferir]);

                                endif;


                                
                                $percorrendo_num++;

                                
                               


                              
                            }

                        endif;



                        $retorno_guias = $this->criarGuiasSancoop($guia_transmitir);

                        return $retorno_guias;

                    //     echo '<pre>';
                    //     print_r($guia_transmitir);
                    //  exit;


                        

                        // foreach ($agendamento['agendamento_procedimento'] as $agendamento_guia) {

                        //    $incremento_guia++;
                        
                        //  }


                endif;



            endif;
            



        }

        echo true;

    //CASO ESTEJA TENTANDO INSERIR EM  UM PROTOLOCO FECHADO COLOCAR REGRA
    else:

        echo 'ERRO. PROTOCOLO NÃO DISPONÍVEL PARA INSERÇÃO DE GUIAS.';

    endif;


    }


    public function criarProtocoloSancoop($cooperado)
    {
        //OBTENDO TOKEN DE AUTORIZAÇÃO
        $parameters['ID'] = 181;
        $parameters['Hash'] = 'TWVkLlNpb3MuMjJAIUAj';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/Token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
    
        $headers = [
            "Content-Type: application/json"
        ];
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        /* CASO TENHA AUTENTICADO */
        if(!empty($return['result']->token)):

            //PARAMETROS A ENVIAR
            $parametersData['CodCooperado'] = $cooperado['sancoop_cod_coperado'];
            $parametersData['user_nome'] = $cooperado['sancoop_user_coperado'];
            
            //CRIANDO O PROTOCOLO NA SANCOP
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/Protocolo');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parametersData));
        
            $headers = [
                "Content-Type: application/json",
                "Authorization: Bearer {$return['result']->token}"
            ];
        
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
            $return['result'] = json_decode(curl_exec($ch));
            $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);
            // echo '<pre>';
            // print_r($return);
            // exit;

            if(!empty($return['result']->Protocolos->Protocolo)):
                return $return['result']->Protocolos->Protocolo;
            else:
                return false;
            endif;

        endif;
    }


    public function criarGuiasSancoop($guias)
    {
        //OBTENDO TOKEN DE AUTORIZAÇÃO
        $parameters['ID'] = 181;
        $parameters['Hash'] = 'TWVkLlNpb3MuMjJAIUAj';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/Token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
    
        $headers = [
            "Content-Type: application/json"
        ];
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        /* CASO TENHA AUTENTICADO */
        if(!empty($return['result']->token)):

            //PARAMETROS A ENVIAR
            $parametersData['Guias'] = $guias;

            //             echo '<pre>';
            // print_r($parametersData);
            // exit;
            
            //CRIANDO O PROTOCOLO NA SANCOP
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/ProtocoloGuia');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parametersData));
        
            $headers = [
                "Content-Type: application/json",
                "Authorization: Bearer {$return['result']->token}"
            ];
        
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
            $return['result'] = json_decode(curl_exec($ch));
            $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);
            echo '<pre>';
            print_r($return);
            exit;

            if(!empty($return['result']->Protocolos->Protocolo)):
                return $return['result']->Protocolos->Protocolo;
            else:
                return false;
            endif;

        endif;
    }


    public function consultarLoteNaSancoop($protocolo, $cpfprestador)
    {

        // echo $protocolo;
        // exit;


        //OBTENDO TOKEN DE AUTORIZAÇÃO
        // CODIGOS INSTITUICOES NA SANCOOP - 79 angios - 3623 santa casa
        $parameters['ID'] = 181;
        $parameters['Hash'] = 'TWVkLlNpb3MuMjJAIUAj';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/Token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
    
        $headers = [
            "Content-Type: application/json"
        ];
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        /* CASO TENHA AUTENTICADO */
        if(!empty($return['result']->token)):

            //CONSULTANDO API SANCOOP PARA VERIFICAR SE EXISTE O COOPERADO
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/Protocolo?CodProtocolo='.$protocolo.'&CPF='.$cpfprestador.'');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        
            $headers = [
                "Content-Type: application/json",
                "Authorization: Bearer {$return['result']->token}"
            ];
        
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
            $return['result'] = json_decode(curl_exec($ch));
            $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);
            
            
            echo '<pre>';
            print_r($return);
            exit;

            if(!empty($return['result']->Guias)):
                $total_guias = sizeof($return['result']->Guias) - 1;
                return $return['result']->Guias[$total_guias]->Guia;
            else:
                return 0;
            endif;

        endif;

    }


    public function consultarNumGuiaLoteNaSancoop($protocolo)
    {

        // echo $protocolo;
        // exit;


        //OBTENDO TOKEN DE AUTORIZAÇÃO
        // CODIGOS INSTITUICOES NA SANCOOP - 79 angios - 3623 santa casa
        $parameters['ID'] = 181;
        $parameters['Hash'] = 'TWVkLlNpb3MuMjJAIUAj';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/Token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
    
        $headers = [
            "Content-Type: application/json"
        ];
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        /* CASO TENHA AUTENTICADO */
        if(!empty($return['result']->token)):

            //CONSULTANDO API SANCOOP PARA VERIFICAR SE EXISTE O COOPERADO
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/Guias?CodProtocolo='.$protocolo.'');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        
            $headers = [
                "Content-Type: application/json",
                "Authorization: Bearer {$return['result']->token}"
            ];
        
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
            $return['result'] = json_decode(curl_exec($ch));
            $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);
            
            
            echo '<pre>';
            print_r($return);
            exit;

            if(!empty($return['result']->Guias)):
                $total_guias = sizeof($return['result']->Guias) - 1;
                return $return['result']->Guias[$total_guias]->Guia;
            else:
                return 0;
            endif;

        endif;

    }

    public function removerGuiasLote(Request $request, FaturamentoLote $faturamento)
    {

    //    dd($faturamento->toArray());
        if($_POST):

            DB::table('faturamento_protocolos_guias')
                    ->where('faturamento_protocolo_id',  $_POST['faturamento_protocolo_id'])
                    ->where('agendamento_id',  $_POST['agendamento_id'])
                    ->update(array(
                        'status' => 4, //STATUS DE GUIA REMOVIDA DO LOTE
                    ));

            return redirect()->route('instituicao.faturamento.lotesGuiasSancoop', ['faturamento' => $faturamento] )->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Atendimento removido com sucesso!'
            ]);

        endif;

    }

    public function addGuiasPendenteLote(Request $request, FaturamentoLote $faturamento)
    {

        //primeiro mudamos o status do antigo protocolo
        DB::table('faturamento_protocolos_guias')
                    ->where('faturamento_protocolo_id',  $_POST['faturamento_protocolo_id_old'])
                    ->where('agendamento_id',  $_POST['agendamento_id'])
                    ->update(array(
                        'status' => 5, //STATUS DE GUIA REMOVIDA DO LOTE E ADICIONADO A UM NOVO LOTE
                    ));
        //CRIANDO A GUIA NO BANCO LOCAL
        $guia_lote_local = array(
            'status' => 0,
            'faturamento_protocolo_id' => $_POST['faturamento_protocolo_id'],
            'agendamento_id' => $_POST['agendamento_id']
        );
        
        FaturamentoLoteGuia::create($guia_lote_local);

        return redirect()->route('instituicao.faturamento.lotesGuiasSancoop', ['faturamento' => $faturamento] )->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atendimento adicionado com sucesso!'
        ]);

    }

}
