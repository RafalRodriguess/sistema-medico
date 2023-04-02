<?php

namespace App\Console\Commands;

use App\Instituicao;
use App\InstituicaoAutomacaoDisparo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnviarWhatsappPrestador extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automacao-prestador:whatsapp-diario';

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

            if(!empty($instituicao['automacao_whatsapp_horario_agenda_prestador'])):
                

                //VERIFICANDO O HORÁRIO MARCADO PELA INSTITUICAO PARA ENVIO
                //pegando a hora
                $hora = date('H');
                
                $instituicao_hora_envio = explode(':', $instituicao['automacao_whatsapp_horario_agenda_prestador']);


                // if($instituicao['automacao_whatsapp_horario_agenda_prestador'] == date('H:i')):
                   if($hora === $instituicao_hora_envio[0]):

                    //verificando se já mandou
                    $enviou = DB::table('instituicoes_automacoes_disparos')
                                    ->select('id')
                                    ->where('instituicao_id', $instituicao['id'])
                                    ->where('data_execucao', date('Y-m-d'))
                                    ->where('modulo', 'agenda_diaria_prestador')
                                    ->first();

                                    // var_dump($enviou);
                                    // exit;


                    if(empty($enviou)):
                        
                        if($this->enviaAgendaDiariaPrestadores($instituicao)):

                            $registro = array(
                                'instituicao_id' => $instituicao['id'],
                                'data_execucao' => date('Y-m-d'),
                                'modulo' => 'agenda_diaria_prestador',
                                'regra' => $instituicao['automacao_whatsapp_horario_agenda_prestador'],
                            );
    
                            InstituicaoAutomacaoDisparo::create($registro);

                        endif;
                    endif;

                endif;

            endif;
                
            endforeach;

        endif;
  
        
    }


    public function enviaAgendaDiariaPrestadores($instituicao)
    {

       
       
                //CARREGANDO DB KENTRO
                $kentrodb = DB::connection('kentro')->table('autosend')->select(['id', 'queue_id'])->get();

                //VAMOS PEGAR OS AGENDAMENTOS DO DIA POSTERIOR
                $dia_posterior =  date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));
                $dia_posterior_inicio = $dia_posterior . ' 00:00:00';
                $dia_posterior_fim = $dia_posterior . ' 23:59:00';

                $agendamentos = DB::table('agendamentos')
                ->select('agendamentos.data', 'agendamentos.id', 'prestadores.nome as profissional', 'pessoas.nome as paciente', 'pessoas.telefone1', 
                'instituicoes_prestadores.id as prestador_instituicao',
                'instituicoes_prestadores.telefone as prestador_telefone')
                ->where('instituicao_id', $instituicao['id'])
                ->where('agendamentos.tipo', 'agendamento')
                ->where('agendamentos.status','!=', 'excluir')
                ->where('agendamentos.status','!=', 'cancelado')
                ->where('instituicoes_prestadores.whatsapp_receber_agenda', 1)
                ->whereBetween('agendamentos.data', [$dia_posterior_inicio, $dia_posterior_fim])
                ->join('pessoas', 'pessoas.id', '=', 'agendamentos.pessoa_id')
                ->join('instituicoes_agenda', 'instituicoes_agenda.id', '=', 'agendamentos.instituicoes_agenda_id')
                ->join('instituicoes_prestadores', 'instituicoes_prestadores.id', '=', 'instituicoes_agenda.instituicoes_prestadores_id')
                ->join('prestadores', 'prestadores.id', '=', 'instituicoes_prestadores.prestadores_id')
                ->get();


                    //  echo '<pre>';
                    // print_r($agendamentos);
                    // exit;

                //AGRUPANDO OS AGENDAMENTOS DE CADA PRESTADOR
                if(!empty($agendamentos)):

                    



                    foreach($agendamentos as $agendamento):

                        //PEGAR OS PROCEDIMENTOS
                    $procedimentos = DB::table('agendamentos_procedimentos')
                                    ->select('agendamentos_procedimentos.procedimentos_instituicoes_convenios_id', 
                                    'procedimentos.descricao as procedimento',
                                    'convenios.nome as convenio')
                                    ->where('agendamentos_id', $agendamento->id)
                                    ->join('procedimentos_instituicoes_convenios', 'procedimentos_instituicoes_convenios.id', '=', 'agendamentos_procedimentos.procedimentos_instituicoes_convenios_id')
                                    ->join('convenios', 'convenios.id', '=', 'procedimentos_instituicoes_convenios.convenios_id')
                                    ->join('procedimentos_instituicoes', 'procedimentos_instituicoes.id', '=', 'procedimentos_instituicoes_convenios.procedimentos_instituicoes_id')
                                    ->join('procedimentos', 'procedimentos.id', '=', 'procedimentos_instituicoes.procedimentos_id')
                                    ->get();

                        $agendamento->procedimentos = $procedimentos;

                        $prestador[$agendamento->prestador_instituicao][] = $agendamento;

                    endforeach;

                endif;

                // echo '<pre>';
                // print_r($prestador);
                // exit;


                //PERCORRENDO POR PRESTADOR PARA ENVIAR
                if(!empty($prestador)):

                    foreach($prestador as $prestador_enviar):


                      if(!empty($prestador_enviar[0]->prestador_telefone)):

                        $msg = "Olá ".$prestador_enviar[0]->profissional.", confira sua agenda amanhã: ".date("d/m/Y", strtotime($agendamento->data))." em ".$instituicao["nome"]."";

                        $msg.= "\n\n*Pacientes agendados:* ".sizeof($prestador_enviar)."\n\n";

                        //montando msg
                        foreach($prestador_enviar as $agenda_paciente):

                            //pegando os procedimentos
                            $proc_agenda = "";
                            foreach($agenda_paciente->procedimentos as $proc):
                                $proc_agenda.= $proc->procedimento."-".$proc->convenio.";";

                                //iniciar convenios
                                $convenio[$proc->convenio] = 0;
                                $procedimento_resumo[$proc->procedimento] = 0;

                            endforeach;

                            //somar convenios e propcedimentos
                            foreach($agenda_paciente->procedimentos as $proc):
                                $convenio[$proc->convenio] = $convenio[$proc->convenio] + 1;
                                $procedimento_resumo[$proc->procedimento] = $procedimento_resumo[$proc->procedimento] + 1;
                            endforeach;

                            $msg.= "";
                            $msg.= date("H:i", strtotime($agenda_paciente->data))." - ".$agenda_paciente->paciente."(".$proc_agenda.")"."\n";

                        endforeach;

                        /*OCULTADO POR ENQUANTO COLOCAR OPCICIONAL NA INSTITUICAO
                        $msg.= "\n\n";
                        $msg.= "*Convênios:*\n";

                        //percorrer convenios
                        foreach($convenio as $key=>$valor):

                            $msg.= $key.":".$valor."\n";

                        endforeach;


                        $msg.= "\n\n";
                        $msg.= "*Procedimentos:*\n";

                        //percorrer convenios
                        foreach($procedimento_resumo as $key=>$valor):

                            $msg.= $key.":".$valor."\n";

                        endforeach;

                        FIM OCULTADO POR ENQUANTO COLOCAR OPCICIONAL NA INSTITUICAO
                        */

                        //por fim enviando a msg

                        //TRATANDO O TELEFONE
                        $telefone = str_replace('(', '', $prestador_enviar[0]->prestador_telefone);
                        $telefone = str_replace(')', '', $telefone);
                        $telefone = str_replace(' ', '', $telefone);
                        $telefone = str_replace('-', '', $telefone);

                        $envio = array(
                            'queue_id' => $instituicao['kentro_fila_empresa'],
                            'number' => $telefone,
                            'text' =>   $msg,
                            'status' => 0,
                            //COMENTADO ATÉ O KENTRO RESOLVER
                            // 'buttons' => $buttons,
                        );


                        //REGISTRANDO NO BANCO KENTRO E ATUALIZANDO NO BANCO LOCAL COMO ENTREGUE
                        if (DB::connection('kentro')->table('autosend')->insert($envio)) :

                            // echo 'entregue';

                        endif;



                  endif;

                    endforeach;

                endif;

                return true;


    }


}
