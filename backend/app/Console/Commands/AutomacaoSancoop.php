<?php

namespace App\Console\Commands;

use App\Instituicao;
use App\Agendamentos;
use App\FaturamentoLote;
use App\FaturamentoLoteGuia;
use App\Prestador;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutomacaoSancoop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automacao:sancoop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /*

        //DESENVOLVIMENTO
        ID: "181";
        Hash: "TWVkLlNpb3MyMkAhQCM";
        URL: "http://zltecnologia.ddns.net:8902"

        //PRODUCAO
        ID: "181";
        Hash: "TWVkLlNpb3MuMjJAIUAj";
        URL: "http://websios.sancoop.com.br:9001"


        */

        
        // $instituicao = Instituicao::find($instituicao['id']);
        
        //PRIMEIRO PEGAMOS AS INSITUIÇÕES QUE ESTÃO MARCADAS PARA ENVIAR WHATSAP
        $instituicoes = Instituicao::where('possui_faturamento_sancoop', 1)
                                   ->where('sancoop_cod_instituicao', '<>', '')
                                   ->get()
                                   ->toArray();

        if(!empty($instituicoes)):

            foreach ($instituicoes as $instituicao):

                //ADICIONANDO GUIAS AO LOTE LOCAL
                $this->adicionaGuiasLoteLocal($instituicao);


                //FUNÇÃO PARA FECHAR LOTES LOCAIS ENVIAR GUIAS PARA SANCOOP E FECHAR LOTE SANCOOP E LOCAL
                //********colocamos o dia domingo para teste, depois colocar date('w') == 5 que é sexta feira e terminar o restante das regras
                /*
                if($instituicao['sancoop_automacao_envio_guias'] == 'semanalmente_sexta' && date('w') == 5):
                    // $this->transmitirGuiasFecharLote($instituicao);
                endif;
                */

                
            endforeach;

        endif;
  
        
    }


    
    //ADICIONANDO GUIAS AO LOTE LOCAL
    public function adicionaGuiasLoteLocal($instituicao)
    {

                //VAMOS PEGAR OS AGENDAMENTOS FINALIZADOS DO DIA

                $dia_buscar =  date('Y-m-d', strtotime('+0 day', strtotime(date('Y-m-d'))));
                // $dia_buscar =  date('Y-m-d', strtotime('-2 day', strtotime(date('Y-m-d'))));

                $dia_inicio = $dia_buscar . ' 00:00:00';
                $dia_fim = $dia_buscar . ' 23:59:00';

               
                $agendamentos = Agendamentos::where('agendamentos.tipo', 'agendamento')
                                        ->where('agendamentos.status', 'finalizado')
                                        ->whereBetween('agendamentos.data', [$dia_inicio, $dia_fim])
                                        ->whereHas('pessoa', function ($q) use ($instituicao) {
                                           $q->where('instituicao_id', $instituicao['id']);
                                         })
                                        ->with(['pessoa' => function ($q) use ($instituicao) {
                                           $q->where('instituicao_id', $instituicao['id']);
                                         }])
                                        ->with('carteirinha')
                                        ->with('instituicoesAgenda.prestadores.prestador')
                                        ->with('instituicoesAgenda.prestadores.procedimentosExcessoes')
                                        ->with('agendamentoProcedimento.procedimentoInstituicaoConvenio')
                                        ->with('agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios')
                                        ->with('agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento')
                                        ->get()
                                        ->toArray();



                                        // echo '<pre>';
                                        // print_r($agendamentos);
                                        // exit;



                if (!empty($agendamentos)) :

                    foreach ($agendamentos as $agendamento) {

                        //VAMOS VERIFICAR PRIMEIRO SE EXISTE PROCEDIMENTO FATURADO NO ATENDIMENTO, EX: RETORNO NAO FATURA, ETC
                        $atendimento_faturado = 0;

                        $mudar_prestador_faturado = 0;

                        foreach($agendamento['agendamento_procedimento'] as $proc_verificar_cod):
                            //CASO TENHA ALGUM PROCEDIMENTO COM CÓDIGO VINCULADO E CONVENIO SINCRONIZADO COM SANCOOP SIGNIFICA QUE É FATURADO E VAMOS VINCULAR AO LOTE
                            if(!empty($proc_verificar_cod['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['cod'])
                            && !empty($proc_verificar_cod['procedimento_instituicao_convenio']['convenios']['sancoop_cod_convenio'])):
                                $atendimento_faturado = 1;
                            endif;

                            /* AQUI VAMOS OLHAR SE O PROCEDIMENTO QEU FOI FEITO POSSUI REGRA ONDE É FATURADO NO NOME DE OUTRO PRESTADOR */
                            if(!empty($agendamento['instituicoes_agenda']['prestadores']['procedimentos_excessoes'])):

                                foreach($agendamento['instituicoes_agenda']['prestadores']['procedimentos_excessoes'] as $procedimento_excessao):

                                    if($procedimento_excessao['id'] == $proc_verificar_cod['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['id']):
                                        $mudar_prestador_faturado = $procedimento_excessao['pivot']['prestador_faturado_id'];
                                    endif;

                                endforeach;

                            endif;

                        endforeach;
                        


                      //CASO TENHA PROCEDIMENTO FATURADO VAMOS VINCULAR
                      if($atendimento_faturado == 1):

                        // echo '<pre>';
                        // print_r($agendamento['instituicoes_agenda']['prestadores']['prestador']);
                        // exit;

                                //CASO O PRSTADOR TENHA QEU MUDAR POR CONTA DO PROCEDIMENTO
                                if($mudar_prestador_faturado != 0):

                                    //PEGANDO DADOS DO PRESTADOR
                                    // $prestadorFaturado = DB::table('prestadores')->select('prestadores.*')
                                    //                     ->where('instituicoes_prestadores.id', $mudar_prestador_faturado)
                                    //                     ->join('instituicoes_prestadores', 'instituicoes_prestadores.prestadores_id', '=', 'prestadores.id')
                                    //                     ->get()
                                    //                     ->toArray();

                                                        $prestadorFaturado =      Prestador::whereHas('prestadoresInstituicoes', function ($q) use ($mudar_prestador_faturado) {
                                                            $q->where('id', $mudar_prestador_faturado);
                                                            })
                                                            ->with(['prestadoresInstituicoes' => function ($q) use ($mudar_prestador_faturado) {
                                                            $q->where('id', $mudar_prestador_faturado);
                                                            }])
                                                            ->get()
                                                            ->toArray();

                                                        

                                    $agendamento['instituicoes_agenda']['prestadores']['prestador'] = $prestadorFaturado[0];

                                endif;

                                //  echo '<pre>';
                                //         print_r($agendamento['instituicoes_agenda']['prestadores']['prestador']);
                                //         exit;

                                
                                //PRIMEIRO VAMOS VERIFICAR SE TEM PROTOCOLO DO PRESTADOR ABERTO, SE NAO TIVER ELE ABRE
                                $protocoloAberto = DB::table('faturamento_protocolos')
                                                    ->where('instituicao_id', $instituicao['id'])
                                                    ->where('status', 0)
                                                    ->where('prestadores_id', $agendamento['instituicoes_agenda']['prestadores']['prestador']['id'])
                                                    ->first();
                                
                                //CASO NÃO EXISTA PROTOCOLO IREMOS CRIAR NA API E NO LOCAL
                                if(empty($protocoloAberto) && !empty($agendamento['instituicoes_agenda']['prestadores']['prestador']['sancoop_cod_coperado'])):

                                    //CASO SUCESSO NA CRIAÇÃO DO PROTOCOLO VAMO INSERIR A GUIA LOCAL
                                    if($idProtocoloLocal = $this->criarProtocoloSancoop($instituicao['id'], $agendamento['instituicoes_agenda']['prestadores']['prestador'])):
                                        $this->inserindoGuiaProtocoloLocal($agendamento, $idProtocoloLocal);
                                    endif;


                                //CASO EXISTA VAMOS VINCULAR AS GUIAS DO PROTOCOLO EM ABERTO
                                else:
                                    $this->inserindoGuiaProtocoloLocal($agendamento, $protocoloAberto->id);
                                endif;



                      endif;
                      

                    }


                endif;

    }

    //API CRIAÇÃO DE PROTOCOLOS
    public function criarProtocoloSancoop($idInstituicao, $cooperado)
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

            //CASO CRIOU VAMOS CRIAR NO LOCAL TAMBEM
            if(!empty($return['result']->Protocolos->Protocolo)):
                // return $return['result']->Protocolos->Protocolo;

                 //CRIANDO A GUIA NO BANCO LOCAL
                 $protocolo_local = array(
                    'cod_externo' => $return['result']->Protocolos->Protocolo,
                    'descricao' => 'Prestador: '.$cooperado['sancoop_desc_prestador'].' - Período: '.date('d/m/Y'),
                    'tipo' => 2,
                    'status' => 0,
                    'instituicao_id' => $idInstituicao,
                    'prestadores_id' => $cooperado['id'],
                );

                $faturamento_protocolo = FaturamentoLote::create($protocolo_local);

                return $faturamento_protocolo->id;

            else:
                return false;
            endif;

        endif;
    }

    //INSERINDO GUIAS NO PROTOCOLO LOCAL
    public function inserindoGuiaProtocoloLocal($agendamento, $idProtocolo)
    {

        //VAMOS VERIFICAR PRIMEIRO SE NÃO INSERIU AINDA
        $guiaProtocolo = DB::table('faturamento_protocolos_guias')
                                   ->where('faturamento_protocolo_id', $idProtocolo)
                                   ->where('agendamento_id', $agendamento['id'])
                                   ->first();

        
        if(empty($guiaProtocolo )):

            //CRIANDO A GUIA NO BANCO LOCAL
            $guia_lote_local = array(
                'status' => 0,
                'faturamento_protocolo_id' => $idProtocolo,
                'agendamento_id' => $agendamento['id']
            );

            $guia = FaturamentoLoteGuia::create($guia_lote_local);

        endif;


    }

    //CONSULTANDO LOTES EM ABERTO , ENVIANDO GUIAS E FECHANDO LOTES
    public function transmitirGuiasFecharLote($instituicao)
    {

        //PEGANDO OS LOTES EM ABERTO
        $faturamentoLotes = FaturamentoLote::where('instituicao_id', $instituicao['id'])
                                            ->where('status', 0)
                                            ->with('prestador')
                                            ->with('guias')
                                            ->with('guias.agendamento_paciente')
                                            ->with('guias.agendamento_paciente.pessoa')
                                            ->with('guias.agendamento_paciente.carteirinha')
                                            ->with('guias.agendamento_paciente.agendamentoGuias')
                                            ->with('guias.agendamento_paciente.agendamentoProcedimento')
                                            ->with('guias.agendamento_paciente.agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios')
                                            ->with('guias.agendamento_paciente.agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento')
                                            ->get()
                                            ->toArray();

                                            // echo '<pre>';
                                            // print_r($faturamentoLotes);
                                            // exit;

        if(!empty($faturamentoLotes)):

            foreach($faturamentoLotes as $faturamentoLote):

                //VAMOS GARANTIR QUE O LOTE ESTÁ ABERTO NA SANCOOP
                $faturamentoLoteNaSancoop = $this->consultarLoteNaSancoop($faturamentoLote['cod_externo'], $faturamentoLote['prestador']['cpf']);

                if(!empty($faturamentoLoteNaSancoop) && $faturamentoLoteNaSancoop->Situação == 'ABERTO'):

                    //VAMOS PERCORRER OS ATENDIMENTOS PARA TRANSFERIR AS GUIAS DOS ATENDIEMNTOS

                    $numGuiafaturamentoLoteNaSancoop = 0;
                    $guias_transmitir = array();
                    $guia_transmitir_unica = array();

                    
                    //PERCORRENTO OS AGENDAMENTOS
                    foreach($faturamentoLote['guias'] as $atendimento_guia):

                        //condicao só atendimento guia em aberto
                        if($atendimento_guia['status'] == 0):

                        // echo '<pre>';
                        // print_r($atendimento_guia);
                        // exit;

                        $incremento_procedimento_guia = 1;
                        //TIPO DE PROCEDIMENTO TERMINAR ISTO DEPOIS: SE É "CONSULTA" OU "SADT" QUE É O TIPO DE ATENDIMENTO
                        $recem_nascido        = 'NÃO';
                        // $tipo_atendimento     = 'CONSULTA';
                        $material_medicamento = 'NÃO';

                        $total_procedimentos_guias = sizeof($atendimento_guia['agendamento_paciente']['agendamento_procedimento']);






                        //VAMOS PERCORRER AS GUIAS QUE FORAM CRIADAS BASEADO NAS REGRAS DE JUNÇÃO DE CONSULTA COM SADT , CARTEIRINHA E AUTORIZAÇÃO

                        //INICIANDO OS ARRAIS PARA PERCORRER ATÉ 5 POR EQUANTO
                        $arrayItensConsulta = array();
                        $arrayItensSadt = array();

                        foreach($atendimento_guia['agendamento_paciente']['agendamento_guias'] as $guia_tipo):


                            $incremento_procedimento = 1;


                            //PRECISAMOS CORRER TODOS OS PROCEDIMENTOS FEITOS E JUNTAR O QUE TE QUE JUNTAR DE ACORDO COM O TIPO
                            foreach($atendimento_guia['agendamento_paciente']['agendamento_procedimento'] as $procedimento_agenda):

                                //MONTAR GUIA DO TIPO CONSULTA
                                if($guia_tipo['tipo_guia'] == 'consulta'
                                // && $procedimento_agenda['procedimento_instituicao_convenio']['convenios']['divisao_tipo_guia'] == 2
                                && $procedimento_agenda['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['tipo_guia'] == 1):

                                    $arrayItensConsulta['CodProcedimento'.$incremento_procedimento.''] = $procedimento_agenda['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['cod'];
                                    $arrayItensConsulta['QtProcedRealizada'.$incremento_procedimento.'']  = $procedimento_agenda['qtd_procedimento'];
                                    $arrayItensConsulta['CodAutorizacao'.$incremento_procedimento.'']  = $guia_tipo['cod_aut_convenio'];

                                    $incremento_procedimento++;

                                //MONTAR GUIA DO TIPO SADT
                                elseif($guia_tipo['tipo_guia'] == 'sadt'):


                                    //REGRA PARA CONSULTA JUNTO SÓ SE O CONVENIO PERMITIR EM SADT
                                    if($procedimento_agenda['procedimento_instituicao_convenio']['convenios']['divisao_tipo_guia'] == 1
                                    && $procedimento_agenda['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['tipo_guia'] == 1):

                                        $arrayItensSadt['CodProcedimento'.$incremento_procedimento.''] = $procedimento_agenda['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['cod'];
                                        $arrayItensSadt['QtProcedRealizada'.$incremento_procedimento.'']  = $procedimento_agenda['qtd_procedimento'];
                                        $arrayItensSadt['CodAutorizacao'.$incremento_procedimento.'']  = $guia_tipo['cod_aut_convenio'];

                                        $incremento_procedimento++;

                                    //REGRA PARA EXAMES EM GERAL
                                    elseif($procedimento_agenda['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['tipo_guia'] == 2):

                                        $arrayItensSadt['CodProcedimento'.$incremento_procedimento.''] = $procedimento_agenda['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['cod'];
                                        $arrayItensSadt['QtProcedRealizada'.$incremento_procedimento.'']  = $procedimento_agenda['qtd_procedimento'];
                                        $arrayItensSadt['CodAutorizacao'.$incremento_procedimento.'']  = $guia_tipo['cod_aut_convenio'];

                                        $incremento_procedimento++;

                                    endif;

                                

                                endif;

                            endforeach;

                        endforeach;


/*OLDDDDDDDDDD
                         //MONTAMOS A PRIMEIRA GUIA CASO SEJA ABAIXO DE 5 PROCEDIMENTOS
                        //  if($total_procedimentos_guias > 5):
                        //     $guia_unica_percorrer = 5;
                        //  else:
                        //     $guia_unica_percorrer = $total_procedimentos_guias;
                        //  endif;

                        //  for ($i=0; $i < $guia_unica_percorrer; $i++) { 
                        //     $incremento_procedimento = $i + 1;
                        //     $arrayItens['CodProcedimento'.$incremento_procedimento.''] = $atendimento_guia['agendamento_paciente']['agendamento_procedimento'][$i]['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['cod'];
                        //     $arrayItens['QtProcedRealizada'.$incremento_procedimento.'']  = $atendimento_guia['agendamento_paciente']['agendamento_procedimento'][$i]['qtd_procedimento'];

                        //     //TERMINAR ISTO (O QUE ESTAMOS PEGANDO HJ É A CARTEIRINHA, PRECISAMOS DO CÓDIGO DA AUTORIZAÇÃO)
                        //     $arrayItens['CodAutorizacao'.$incremento_procedimento.'']  = $atendimento_guia['agendamento_paciente']['carteirinha']['carteirinha'];
                            
                        //  }
*/


                        //VAMOS MONTAR AS GUIAS COM OS PROCEDIMENTOS AGORA
                        foreach($atendimento_guia['agendamento_paciente']['agendamento_guias'] as $guia_tipo):

                            $numGuiafaturamentoLoteNaSancoop++;

                            $guia_transmitir_unica = [
                                'CodProtocolo'  => $faturamentoLote['cod_externo'],
                                'Guia' =>  $numGuiafaturamentoLoteNaSancoop,
                                'CodConvenio' => $atendimento_guia['agendamento_paciente']['agendamento_procedimento'][0]['procedimento_instituicao_convenio']['convenios']['sancoop_cod_convenio'],
                                'Paciente' => $atendimento_guia['agendamento_paciente']['pessoa']['nome'],
                                'RN' => $recem_nascido,
                                'DtAtendimento' => date('Y-m-d', strtotime($atendimento_guia['agendamento_paciente']['data'])),
                                'NumCarteirinha' => $atendimento_guia['agendamento_paciente']['carteirinha']['carteirinha'],
                                'Hora' => date('H:i:s', strtotime($atendimento_guia['agendamento_paciente']['data'])),
                                'MatMed' => $material_medicamento,
                                'guia_digitalizada' => '',
                                'cod_comparativo' => $atendimento_guia['id'],
                                'CodHospital' => $instituicao['sancoop_cod_instituicao'],
                                'Procedimento' => $guia_tipo['tipo_guia'],
                                
                            ];

                            if($guia_tipo['tipo_guia'] == 'consulta'):
                                $guias_transmitir[] = array_merge($guia_transmitir_unica, $arrayItensConsulta);
                            else:
                                $guias_transmitir[] = array_merge($guia_transmitir_unica, $arrayItensSadt);
                            endif;
    
                             

                        endforeach;



                         //INCREMENTE DA GUIA NO LOTE
                        //  $numGuiafaturamentoLoteNaSancoop = $numGuiafaturamentoLoteNaSancoop + 1;

                        //  $guia_transmitir_unica = [
                        //     'CodProtocolo'  => $faturamentoLote['cod_externo'],
                        //     'Guia' =>  $numGuiafaturamentoLoteNaSancoop,
                        //     'CodConvenio' => $atendimento_guia['agendamento_paciente']['agendamento_procedimento'][0]['procedimento_instituicao_convenio']['convenios']['sancoop_cod_convenio'],
                        //     'Paciente' => $atendimento_guia['agendamento_paciente']['pessoa']['nome'],
                        //     'RN' => $recem_nascido,
                        //     'DtAtendimento' => date('Y-m-d', strtotime($atendimento_guia['agendamento_paciente']['data'])),
                        //     'NumCarteirinha' => $atendimento_guia['agendamento_paciente']['carteirinha']['carteirinha'],
                        //     'Hora' => date('H:i:s', strtotime($atendimento_guia['agendamento_paciente']['data'])),
                        //     'MatMed' => $material_medicamento,
                        //     'guia_digitalizada' => '',
                        //     'cod_comparativo' => $atendimento_guia['id'],
                        //     'CodHospital' => $instituicao['sancoop_cod_instituicao'],
                        //     'Procedimento' => $tipo_atendimento,
                            
                        // ];

                        //  $guias_transmitir[] = array_merge($guia_transmitir_unica, $arrayItens);

                        //  echo '<pre>';
                        //  print_r($guia_transmitir_unica);
                        //  exit;

                        //fim condicao guia status 0
                        endif;

                    endforeach;

                        //                     echo '<pre>';
                        //  print_r($guias_transmitir);
                        //  exit;


                    $retorno_guias = $this->criarGuiasSancoop($guias_transmitir);



                else:

                    //CRIAR ARQUIVO PARA GERAR LOG DE ERRO

                endif;

            endforeach;

        endif;

    }

    //CONSULTANDO A SITUAÇÃO DO LOTE NA SANCOOP
    public function consultarLoteNaSancoop($protocolo, $cpfprestador)
    {

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
            

            if(!empty($return['result']->Protocolo)):
                return $return['result']->Protocolo[0];
            else:
                return 0;
            endif;

        endif;

    }


    //INSERINDO AS GUIAS E FECHANDO O LOTE
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

            // echo '<pre>';
            // print_r($return);
            // exit;

            if(!empty($return['result']->message && $return['result']->message == 'Guia(s) inserida(s) com sucesso')):
                return true;
            else:
                return false;
            endif;

        endif;
    }


    


}
