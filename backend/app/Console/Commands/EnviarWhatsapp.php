<?php

namespace App\Console\Commands;

use App\Instituicao;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnviarWhatsapp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automacao:whatsapp';

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
        
        // $instituicao = Instituicao::find($instituicao['id']);
        
        //PRIMEIRO PEGAMOS AS INSITUIÇÕES QUE ESTÃO MARCADAS PARA ENVIAR WHATSAP
        $instituicoes = Instituicao::where('habilitado', 1)
                                   ->where('kentro_fila_empresa', '<>', '')
                                   ->where('automacao_whatsapp', 1)
                                   ->get()
                                   ->toArray();

        if(!empty($instituicoes)):

            foreach ($instituicoes as $instituicao):

                $this->enviaConfirmacao($instituicao);
                $this->consultaConfirmacao($instituicao);

                if($instituicao['enviar_pesquisa_satisfacao_atendimentos'] == 1):
                 $this->consultaPesquisaSatisfacao($instituicao);
                endif;
                // $this->enviaAniversariantes($instituicao);
                
            endforeach;

        endif;
  
        
    }


    public function enviaConfirmacao($instituicao)
    {
       
                //CARREGANDO DB KENTRO
                $kentrodb = DB::connection('kentro')->table('autosend')->select(['id', 'queue_id'])->get();

                //VAMOS PEGAR OS AGENDAMENTOS DO DIA POSTERIOR
                $dia_posterior =  date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));
                $dia_posterior_inicio = $dia_posterior . ' 00:00:00';
                $dia_posterior_fim = $dia_posterior . ' 23:59:00';

                $agendamentos = DB::table('agendamentos')
                ->select('agendamentos.data','agendamentos.teleatendimento','agendamentos.teleatendimento_link_paciente', 'agendamentos.id', 'prestadores.nome as profissional', 'pessoas.nome as paciente', 'pessoas.telefone1')
                ->where('instituicao_id', $instituicao['id'])
                ->where('agendamentos.tipo', 'agendamento')
                ->where('agendamentos.status', 'pendente')
                ->where('agendamentos.envio_confirmacao_whatsapp', 0)
                ->where('instituicoes_prestadores.whatsapp_enviar_confirm_agenda', 1)
                ->whereBetween('agendamentos.data', [$dia_posterior_inicio, $dia_posterior_fim])
                ->join('pessoas', 'pessoas.id', '=', 'agendamentos.pessoa_id')
                ->join('instituicoes_agenda', 'instituicoes_agenda.id', '=', 'agendamentos.instituicoes_agenda_id')
                ->join('instituicoes_prestadores', 'instituicoes_prestadores.id', '=', 'instituicoes_agenda.instituicoes_prestadores_id')
                ->join('prestadores', 'prestadores.id', '=', 'instituicoes_prestadores.prestadores_id')
                ->get();


                /* CRIANDO EXCEÇÕES E ADICIONANDOS REGRAS E COMPLEMENTO OS ATENDIMENTOS CASO TENHA */

                //REGRAS DE EXCEÇOES : EX : ENVIAR DE SEGUNDA NA SEXTA E NAO DOMINGO
                $agendamentos2 = null;

                //VERIFICANDO SE POSSUI REGRA E SE HOJE É SEXTA FEIRA
                if($instituicao['automacao_whatsapp_regra_envio'] == 'segunda_enviar_sexta' && date('w') == 5):

                    $dia_posterior2 =  date('Y-m-d', strtotime('+3 day', strtotime(date('Y-m-d'))));
                    $dia_posterior2_inicio = $dia_posterior2 . ' 00:00:00';
                    $dia_posterior2_fim = $dia_posterior2 . ' 23:59:00';

                    $agendamentos2 = DB::table('agendamentos')
                    ->select('agendamentos.data','agendamentos.teleatendimento','agendamentos.teleatendimento_link_paciente', 'agendamentos.id', 'prestadores.nome as profissional', 'pessoas.nome as paciente', 'pessoas.telefone1')
                    ->where('instituicao_id', $instituicao['id'])
                    ->where('agendamentos.tipo', 'agendamento')
                    ->where('agendamentos.status', 'pendente')
                    ->where('agendamentos.envio_confirmacao_whatsapp', 0)
                    ->where('instituicoes_prestadores.whatsapp_enviar_confirm_agenda', 1)
                    ->whereBetween('agendamentos.data', [$dia_posterior2_inicio, $dia_posterior2_fim])
                    ->join('pessoas', 'pessoas.id', '=', 'agendamentos.pessoa_id')
                    ->join('instituicoes_agenda', 'instituicoes_agenda.id', '=', 'agendamentos.instituicoes_agenda_id')
                    ->join('instituicoes_prestadores', 'instituicoes_prestadores.id', '=', 'instituicoes_agenda.instituicoes_prestadores_id')
                    ->join('prestadores', 'prestadores.id', '=', 'instituicoes_prestadores.prestadores_id')
                    ->get();

                endif;



                //CASO TENHA REGRA VAMOS JUNTAR OS ARRAYS
                if(!empty($agendamentos2)):
                    $agendamentos = array_merge($agendamentos->toArray(), $agendamentos2->toArray());
                endif;




                    

                    // echo '<pre>';
                    // print_r($agendamentos);
                    // exit;



                if (!empty($agendamentos)) :

                    foreach ($agendamentos as $agendamento) {

                        if (!empty($agendamento->telefone1)) :

                            //TRATANDO O TELEFONE
                            $telefone = str_replace('(', '', $agendamento->telefone1);
                            $telefone = str_replace(')', '', $telefone);
                            $telefone = str_replace(' ', '', $telefone);
                            $telefone = str_replace('-', '', $telefone);

                            //VAMOS INSERIR NA FILA DO CLIENTE PARA DISPARO PELO KENTRO

                            //DATA EXTENSO
                            // setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                            // date_default_timezone_set('America/Sao_Paulo');
                            // $data_extenso = strftime( '%A', strtotime($agendamentos->data));

                            $msg = str_replace('{paciente}', ucwords($agendamento->paciente), $instituicao['kentro_msg_confirmacao']);
                            $msg = str_replace('{empresa}', $instituicao['nome'], $msg);
                            // $msg = str_replace('{dia_extenso}', ucwords($data_extenso), $msg);
                            $msg = str_replace('{data}', date("d/m/Y", strtotime($agendamento->data)), $msg);
                            $msg = str_replace('{hora}', date("H:i", strtotime($agendamento->data)), $msg);
                            $msg = str_replace('{profissional}', $agendamento->profissional, $msg);

                            //CASO O ATENDIMENTO SEJA VIA TELEMEDICINA, VAMOS ANEXAR O LINK
                            if($agendamento->teleatendimento == 1 && $agendamento->teleatendimento_link_paciente):
                                $msg.= "/n/n Link Teleatendimento:".$agendamento->teleatendimento_link_paciente;
                            endif;


                            $buttons = '{"title": "' . $instituicao['nome'] . '", "buttons": ["Remarcar", "Desmarcar", "Confirmar"]}';

                            if($instituicao['automacao_whatsapp_botoes'] == 1):
                                $envio = array(
                                    'queue_id' => $instituicao['kentro_fila_empresa'],
                                    'number' => $telefone,
                                    'text' => $msg,
                                    'status' => 0,
                                    'buttons' => $buttons,
                                );
                            else:
                                $envio = array(
                                    'queue_id' => $instituicao['kentro_fila_empresa'],
                                    'number' => $telefone,
                                    'text' => $msg,
                                    'status' => 0,
                                );
                            endif;


                            //REGISTRANDO NO BANCO KENTRO E ATUALIZANDO NO BANCO LOCAL COMO ENTREGUE
                            if(!empty($envio['text'])):

                                if (DB::connection('kentro')->table('autosend')->insert($envio)) :

                                    DB::table('agendamentos')
                                        ->where('id',  $agendamento->id)
                                        ->update(array(
                                            'envio_confirmacao_whatsapp' => 1,
                                            'data_hora_envio_confirmacao_whatsapp' => date('Y-m-d H:i:s')
                                        ));


                                endif;

                            endif;

                        endif;


                    }


                endif;

    }

    public function consultaConfirmacao($instituicao)
    {

        //CARREGANDO DB KENTRO
        $kentrodb = DB::connection('kentro')->table('autosend')->select(['id', 'queue_id'])->get();

        //VAMOS PEGAR OS AGENDAMENTOS DO DIA POSTERIOR

        $dia_posterior =  date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));

        $dia_posterior_inicio = $dia_posterior . ' 00:00:00';
        $dia_posterior_fim = $dia_posterior . ' 23:59:00';

        $confirmacoes_agendamentos = DB::table('agendamentos')->select('agendamentos.data', 'agendamentos.id', 'agendamentos.data_hora_envio_confirmacao_whatsapp',  'prestadores.nome as profissional', 'pessoas.nome as paciente', 'pessoas.telefone1', 'agendamentos.data_final', 'agendamentos.data_final_original', 'agendamentos.data_original')
                    ->where('instituicao_id', $instituicao['id'])
                    ->where('agendamentos.tipo', 'agendamento')
                    ->where('agendamentos.status', 'pendente')
                    ->where('agendamentos.envio_confirmacao_whatsapp', 1)
                    ->where('agendamentos.resposta_confirmacao_whatsapp', null)
                    ->whereBetween('agendamentos.data', [$dia_posterior_inicio, $dia_posterior_fim])
                    ->join('pessoas', 'pessoas.id', '=', 'agendamentos.pessoa_id')
                    ->join('instituicoes_agenda', 'instituicoes_agenda.id', '=', 'agendamentos.instituicoes_agenda_id')
                    ->join('instituicoes_prestadores', 'instituicoes_prestadores.id', '=', 'instituicoes_agenda.instituicoes_prestadores_id')
                    ->join('prestadores', 'prestadores.id', '=', 'instituicoes_prestadores.prestadores_id')
                    ->get();



        
        /* CRIANDO EXCEÇÕES E ADICIONANDOS REGRAS E COMPLEMENTO OS ATENDIMENTOS CASO TENHA */

        //REGRAS DE EXCEÇOES : EX : ENVIAR DE SEGUNDA NA SEXTA E NAO DOMINGO
        $confirmacoes_agendamentos2 = null;

        //VERIFICANDO SE POSSUI REGRA E SE HOJE É SEXTA FEIRA, DAI ENTAO PRECISAMOS VERIFICAR TANTO SEXTA QUANTO SABADO QUANTO DOMINGO
        if($instituicao['automacao_whatsapp_regra_envio'] == 'segunda_enviar_sexta' && date('w') == 5
        || $instituicao['automacao_whatsapp_regra_envio'] == 'segunda_enviar_sexta' && date('w') == 6):


            //REGRAS PARA SEXTA SABADO E DOMINGO
            if(date('w') == 5):
             $dia_posterior2 =  date('Y-m-d', strtotime('+3 day', strtotime(date('Y-m-d'))));
            elseif(date('w') == 6):
             $dia_posterior2 =  date('Y-m-d', strtotime('+2 day', strtotime(date('Y-m-d'))));
            endif;


            $dia_posterior2_inicio = $dia_posterior2 . ' 00:00:00';
            $dia_posterior2_fim = $dia_posterior2 . ' 23:59:00';

            $confirmacoes_agendamentos2 = DB::table('agendamentos')->select('agendamentos.data', 'agendamentos.id', 'agendamentos.data_hora_envio_confirmacao_whatsapp',  'prestadores.nome as profissional', 'pessoas.nome as paciente', 'pessoas.telefone1', 'agendamentos.data_final', 'agendamentos.data_final_original', 'agendamentos.data_original')
                    ->where('instituicao_id', $instituicao['id'])
                    ->where('agendamentos.tipo', 'agendamento')
                    ->where('agendamentos.status', 'pendente')
                    ->where('agendamentos.envio_confirmacao_whatsapp', 1)
                    ->where('agendamentos.resposta_confirmacao_whatsapp', null)
                    ->whereBetween('agendamentos.data', [$dia_posterior2_inicio, $dia_posterior2_fim])
                    ->join('pessoas', 'pessoas.id', '=', 'agendamentos.pessoa_id')
                    ->join('instituicoes_agenda', 'instituicoes_agenda.id', '=', 'agendamentos.instituicoes_agenda_id')
                    ->join('instituicoes_prestadores', 'instituicoes_prestadores.id', '=', 'instituicoes_agenda.instituicoes_prestadores_id')
                    ->join('prestadores', 'prestadores.id', '=', 'instituicoes_prestadores.prestadores_id')
                    ->get();

        endif;



        //CASO TENHA REGRA VAMOS JUNTAR OS ARRAYS
        if(!empty($confirmacoes_agendamentos2)):
            $confirmacoes_agendamentos = array_merge($confirmacoes_agendamentos->toArray(), $confirmacoes_agendamentos2->toArray());
        endif;

            // echo '<pre>';
            //         print_r($confirmacoes_agendamentos);
            //         exit;





                if (!empty($confirmacoes_agendamentos)) :

                    foreach ($confirmacoes_agendamentos as $agendamento) {

                        if (!empty($agendamento->telefone1) && !empty($agendamento->data_hora_envio_confirmacao_whatsapp)) :

                            //TRATANDO O TELEFONE DE ENVIO
                            $telefone_envio = str_replace('(', '', $agendamento->telefone1);
                            $telefone_envio = str_replace(')', '', $telefone_envio);
                            $telefone_envio = str_replace(' ', '', $telefone_envio);
                            $telefone_envio = str_replace('-', '', $telefone_envio);


                            //TRATANDO O TELEFONE
                            $telefone = str_replace(') 9', '', $agendamento->telefone1);
                            $telefone = str_replace('(', '', $telefone);
                            $telefone = str_replace(')', '', $telefone);
                            $telefone = str_replace(' ', '', $telefone);
                            $telefone = str_replace('-', '', $telefone);


                            //TRATANDO O TELEFONE PARA QUANDO ESQUECEU DE COLOCAR O 9
                            $telefone_sem_9 = str_replace(') ', '', $agendamento->telefone1);
                            $telefone_sem_9 = str_replace('(', '', $telefone_sem_9);
                            $telefone_sem_9 = str_replace(')', '', $telefone_sem_9);
                            $telefone_sem_9 = str_replace(' ', '', $telefone_sem_9);
                            $telefone_sem_9 = str_replace('-', '', $telefone_sem_9);


                            $chave_para_consulta = '55' . $telefone . '@s.whatsapp.net';

                            //CONSULTANDO O RETORNO DA MENSAGEM DO CLIENTE
                            $retorno = DB::connection('kentro')
                                ->table('messagehistory')->select('messagehistory.*')
                                ->where('messagehistory.clientid', $chave_para_consulta)
                                ->where('messagehistory.direction', 1)
                                ->where('messagehistory.messagetime', '>', $agendamento->data_hora_envio_confirmacao_whatsapp)
                                ->get();




                            //CASO NAO ENCNTROU O NUMERO COM 9
                            if (empty($retorno)) :

                                $chave_para_consulta = '55' . $telefone_sem_9 . '@s.whatsapp.net';

                                //CONSULTANDO O RETORNO DA MENSAGEM DO CLIENTE
                                $retorno = DB::connection('kentro')
                                    ->table('messagehistory')->select('messagehistory.*')
                                    ->where('messagehistory.clientid', $chave_para_consulta)
                                    ->where('messagehistory.direction', 1)
                                    ->where('messagehistory.messagetime', '>', $agendamento->data_hora_envio_confirmacao_whatsapp)
                                    ->get();


                            endif;


                            if (!empty($retorno)) :

                                foreach ($retorno as $resposta) {

                                    //CASO TENHA CONFIRMADO
                                    if ($resposta->message == 'Confirmar' || $resposta->message == '1') :
                                        // if ($resposta->message == '1') :

                                        if (DB::table('agendamentos')
                                            ->where('id',  $agendamento->id)
                                            ->where('resposta_confirmacao_whatsapp',  null)
                                            ->update(array(
                                                'resposta_confirmacao_whatsapp' => $resposta->message,
                                                'data_hora_resposta_confirmacao_whatsapp' => date('Y-m-d H:i:s'),
                                                'status' => 'confirmado',
                                                // 'confirmacao_horario' => date('Y-m-d H:i:s'),
                                                // 'confirmacao_profissional_id' => $CI->config->item('kentro_confirmacao_usuario')
                                            ))
                                        ) :


                                            //CASO TENHA ATUALIZADO TUDO CERTO A CONFIRMAÇÃO NO BANCO IREMOS ENVIAR MSG NO WHATSAPP
                                            $envio = array(
                                                'queue_id' => $instituicao['kentro_fila_empresa'],
                                                'number' => $telefone_envio,
                                                'text' => $instituicao['kentro_msg_resposta_confirmacao'],
                                                'status' => 0,
                                            );


                                            if(!empty($envio['text'])):
                                             DB::connection('kentro')->table('autosend')->insert($envio);
                                            endif;


                                        endif;


                                    //CASO TENHA DESMARCADO
                                    elseif ($resposta->message == 'Desmarcar' || $resposta->message == '2') :
                                        // elseif ($resposta->message == '2') :


                                        $nova_data = date('Y-m-d', strtotime($agendamento->data)).' 23:00:00';
                                        $nova_data_final = date('Y-m-d', strtotime($agendamento->data)).' 23:00:00';
                                        $dados = null;
                                        if($instituicao['ausente_agenda']){
                                            $dados = [
                                                'resposta_confirmacao_whatsapp' => $resposta->message,
                                                'data_hora_resposta_confirmacao_whatsapp' => date('Y-m-d H:i:s'),
                                                'status' => 'cancelado',
                                                'status_pagamento' => 'estornado',
                                                'motivo_cancelamento' => 'Cancelado via whatsapp',
                                                'data_original' => $agendamento->data, 
                                                'data_final_original' => $agendamento->data_final, 
                                                'data' => $nova_data, 
                                                'data_final' => $nova_data_final
                                            ];
                                        }else{
                                            $dados = [
                                                'resposta_confirmacao_whatsapp' => $resposta->message,
                                                'data_hora_resposta_confirmacao_whatsapp' => date('Y-m-d H:i:s'),
                                                'status' => 'cancelado',
                                                'status_pagamento' => 'estornado',
                                            ];
                                        }

                                        if (DB::table('agendamentos')
                                            ->where('id',  $agendamento->id)
                                            ->where('resposta_confirmacao_whatsapp',  null)
                                            ->update($dados)
                                        ) :


                                            //CASO TENHA ATUALIZADO TUDO CERTO A CONFIRMAÇÃO NO BANCO IREMOS ENVIAR MSG NO WHATSAPP
                                            $envio = array(
                                                'queue_id' => $instituicao['kentro_fila_empresa'],
                                                'number' => $telefone_envio,
                                                'text' => $instituicao['kentro_msg_resposta_desmarcado'],
                                                'status' => 0,
                                            );


                                            if(!empty($envio['text'])):
                                             DB::connection('kentro')->table('autosend')->insert($envio);
                                            endif;

                                        endif;



                                    //CASO TENHA SOLICITADO REMARCAÇÃO
                                    elseif ($resposta->message == 'Remarcar' || $resposta->message == '3') :
                                        // elseif ($resposta->message == '3') :

                                        if (DB::table('agendamentos')
                                            ->where('id',  $agendamento->id)
                                            ->where('resposta_confirmacao_whatsapp',  null)
                                            ->update(array(
                                                'resposta_confirmacao_whatsapp' => $resposta->message,
                                                'data_hora_resposta_confirmacao_whatsapp' => date('Y-m-d H:i:s'),
                                                // 'status' => 'cancelado',
                                                // 'status_pagamento' => 'estornado',
                                                // 'confirmacao_horario' => date('Y-m-d H:i:s'),
                                                // 'confirmacao_profissional_id' => $CI->config->item('kentro_confirmacao_usuario')
                                            ))
                                        ) :


                                            //CASO TENHA ATUALIZADO TUDO CERTO A CONFIRMAÇÃO NO BANCO IREMOS ENVIAR MSG NO WHATSAPP
                                            $envio = array(
                                                'queue_id' => $instituicao['kentro_fila_empresa'],
                                                'number' => $telefone_envio,
                                                'text' => $instituicao['kentro_msg_resposta_remarcacao'],
                                                'status' => 0,
                                            );


                                            if(!empty($envio['text'])):
                                             DB::connection('kentro')->table('autosend')->insert($envio);
                                            endif;

                                        endif;




                                    endif;
                                }



                            endif;


                        endif;

                        // echo '<pre>';
                        // print_r($retorno);


                    }


                endif;

    }

    public function consultaPesquisaSatisfacao($instituicao)
    {

        //CARREGANDO DB KENTRO
        $kentrodb = DB::connection('kentro')->table('autosend')->select(['id', 'queue_id'])->get();

        $agendamentosPesquisaSatisfacao = DB::table('agendamentos')
                ->select('agendamentos.data', 'agendamentos.id', 'agendamentos.data_hora_envio_pesquisa_satisfacao_whatsapp', 'pessoas.telefone1')
                ->where('instituicao_id', $instituicao['id'])
                ->where('agendamentos.tipo', 'agendamento')
                ->where('agendamentos.envio_pesquisa_satisfacao_whatsapp', 1)
                ->where('agendamentos.resposta_pesquisa_satisfacao_whatsapp', null)
                ->join('pessoas', 'pessoas.id', '=', 'agendamentos.pessoa_id')
                ->join('instituicoes_agenda', 'instituicoes_agenda.id', '=', 'agendamentos.instituicoes_agenda_id')
                ->join('instituicoes_prestadores', 'instituicoes_prestadores.id', '=', 'instituicoes_agenda.instituicoes_prestadores_id')
                ->get();


                if(!empty($agendamentosPesquisaSatisfacao)):
 
                    foreach ($agendamentosPesquisaSatisfacao as $agendamento) {
        
                        if(!empty($agendamento->telefone1) && !empty($agendamento->data_hora_envio_pesquisa_satisfacao_whatsapp)):
        
                        //TRATANDO O TELEFONE DE ENVIO
                        $telefone_envio = str_replace('(', '', $agendamento->telefone1);
                        $telefone_envio = str_replace(')', '', $telefone_envio);
                        $telefone_envio = str_replace(' ', '', $telefone_envio);
                        $telefone_envio = str_replace('-', '', $telefone_envio);
                            
        
                        //TRATANDO O TELEFONE
                        $telefone = str_replace(') 9', '', $agendamento->telefone1);
                        $telefone = str_replace('(', '', $telefone);
                        $telefone = str_replace(')', '', $telefone);
                        $telefone = str_replace(' ', '', $telefone);
                        $telefone = str_replace('-', '', $telefone);
        
        
                        $chave_para_consulta = '55'.$telefone.'@s.whatsapp.net';
        
                        //CONSULTANDO O RETORNO DA MENSAGEM DO CLIENTE
                        $retorno = DB::connection('kentro')
                        ->table('messagehistory')->select('messagehistory.*')
                        ->where('messagehistory.clientid', $chave_para_consulta)
                        ->where('messagehistory.direction', 1)
                        ->where('messagehistory.messagetime', '>', $agendamento->data_hora_envio_pesquisa_satisfacao_whatsapp)
                        ->get();
        
                            if(!empty($retorno)):
        
                                foreach ($retorno as $resposta) {
       
                                   // var_dump($resposta->message);
        
                                    //CASO TENHA RESPONDIDO
                                    if($resposta->message < 11):

                                        if (DB::table('agendamentos')
                                            ->where('id',  $agendamento->id)
                                            ->where('resposta_pesquisa_satisfacao_whatsapp',  null)
                                            ->update(array(
                                                'resposta_pesquisa_satisfacao_whatsapp' => $resposta->message,
                                            ))
                                        ) :


                                            //CASO TENHA ATUALIZADO TUDO CERTO A CONFIRMAÇÃO NO BANCO IREMOS ENVIAR MSG NO WHATSAPP
                                            $envio = array(
                                                'queue_id' => $instituicao['kentro_fila_empresa'],
                                                'number' => $telefone_envio,
                                                'text' => $instituicao['kentro_msg_resposta_pesquisa_satisfacao'],
                                                'status' => 0,
                                            );

                                            if(!empty($envio['text'])):
                                             DB::connection('kentro')->table('autosend')->insert($envio);
                                            endif;

                                        endif;
        

        
                                    endif;
                                    
                                }
        
        
        
                            endif;
        
        
                        endif;
                        
        
                    }
        
        
                endif;

    }


}
