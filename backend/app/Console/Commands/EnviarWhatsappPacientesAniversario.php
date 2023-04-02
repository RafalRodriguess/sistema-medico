<?php

namespace App\Console\Commands;

use App\Instituicao;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnviarWhatsappPacientesAniversario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automacao-pacientes:whatsapp-aniversario';

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
                                   ->where('automacao_whatsapp_aniversario', 1)
                                   ->get()
                                   ->toArray();

        if(!empty($instituicoes)):

            foreach ($instituicoes as $instituicao):
                $this->enviaAniversariantes($instituicao);
            endforeach;

        endif;
  
        
    }


    public function enviaAniversariantes($instituicao)
    {
       

                $pacientes = DB::table('pessoas')
                ->select('pessoas.id','pessoas.nome as paciente', 'pessoas.telefone1', 'pessoas.envio_mensagem_whatsapp_aniversario', 'pessoas.nascimento')
                ->where('instituicao_id', $instituicao['id'])
                ->where('nascimento', 'like', '%'.date('m-d').'%')
                ->get();

                // dd($pacientes->toArray());
                // exit;

                if (!empty($pacientes)) :

                    foreach ($pacientes as $paciente) {

                        if (!empty($paciente->telefone1) && !empty($paciente->nascimento) && $paciente->envio_mensagem_whatsapp_aniversario != date('Y-m-d')):

                            $dia_mes = explode('-', $paciente->nascimento);

                          if(date('m-d') == $dia_mes[1].'-'.$dia_mes[2]):

                            //TRATANDO O TELEFONE
                            $telefone = str_replace('(', '', $paciente->telefone1);
                            $telefone = str_replace(')', '', $telefone);
                            $telefone = str_replace(' ', '', $telefone);
                            $telefone = str_replace('-', '', $telefone);

                            //VAMOS INSERIR NA FILA DO CLIENTE PARA DISPARO PELO KENTRO

                            //DATA EXTENSO
                            // setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                            // date_default_timezone_set('America/Sao_Paulo');
                            // $data_extenso = strftime( '%A', strtotime($agendamentos->data));

                            $msg = str_replace('{paciente}', ucwords($paciente->paciente), $instituicao['kentro_msg_aniversario']);
                            $msg = str_replace('{empresa}', $instituicao['nome'], $msg);


                            $envio = array(
                                'queue_id' => $instituicao['kentro_fila_empresa'],
                                'number' => $telefone,
                                'text' => $msg,
                                'status' => 0
                            );

                            // echo '<pre>';
                            // print_r($envio);
                            // exit;


                            //REGISTRANDO NO BANCO KENTRO E ATUALIZANDO NO BANCO LOCAL COMO ENTREGUE
                            if (DB::connection('kentro')->table('autosend')->insert($envio)) :

                                DB::table('pessoas')
                                    ->where('id',  $paciente->id)
                                    ->update(array(
                                        'envio_mensagem_whatsapp_aniversario' => date('Y-m-d')
                                    ));

                            endif;

                          endif;

                        endif;

                    }

                endif;



    }

 

}
