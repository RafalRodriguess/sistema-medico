<?php

namespace App\Http\Controllers\Instituicao;

use App\AgendamentoProcedimento;
use App\Agendamentos;
use App\ConveniosProcedimentos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Instituicao\Agendamentos as InstituicaoAgendamentos;
use App\Instituicao;
use App\InstituicaoProcedimentos;
use App\Prestador;
use App\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PainelInicial extends Controller
{
    public function index(Request $request)
    {
       //VERIFICANDO SE TEM QUE SINCRONIZAR AGENDA
    //    $this->sincronizar_agenda($request);

       //RETORNAREMOS PARA A DASHBOARD DE AMBULATÓRIO PRIMEIRO, MONTAR TODAS DASHBOARDS POSTERIOR
       $user = $request->user('instituicao');

       $prestador = $user->prestadorMedico()->first();

       if(!empty($prestador)){
           $agendamentos = new InstituicaoAgendamentos;
           return $agendamentos->index($request);
       }

       return view('instituicao.home');
    }
    
    public function dashboard(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_dashboard');
        return view('instituicao.home');
    }

    public function sincronizar_agenda($instituicao){

        /* VERIFICANDO SE INSTITUIÇÃO POSSUI SINCRONIZAÇÃO AUTOMÁTICA */
        $instituicao = Instituicao::find($instituicao->session()->get('instituicao'));

        
        if($instituicao->sincronizacao_agenda == 1):

            if($instituicao->chave_unica == 'santacasa'):

                /* COLOCAR ALGUMAS FUNÇÕES PARA RODAR EM UM CRON, SÃO ATULIZADAS POUCO FREQUENTES */

                //PACIENTES *NAO USAR POR ENQUANTO*
                // $this->sincronizar_agenda_santa_casa_pacientes($instituicao);

                //CONVENIOS
                // $this->sincronizar_agenda_santa_casa_convenios($instituicao);
                //TABELA DE PROCEDIMENTOS
                // $this->sincronizar_agenda_santa_casa_procedimentos($instituicao);
                //PRESTADORES
                // $this->sincronizar_agenda_santa_casa_prestadores($instituicao);
                //SETORES
                // $this->sincronizar_agenda_santa_casa_setores($instituicao);
                //SERVIÇOS (GRUPOS E ESPECIALIDADES)
                // $this->sincronizar_agenda_santa_casa_servicos($instituicao);
                //PRESTADORES ESPECIALIDADES, DIAS E HORÁRIOS DE ATENDIMENTOS
                $this->sincronizar_agenda_santa_casa_prestadores_atendimentos($instituicao);



                //OLD
                // $this->sincronizar_agenda_santa_casa($instituicao);
            endif;

        endif;

    }


    /*********************************************************  SINCRONIZAÇÃO SANTA CASA ***********************************************/

    /**************  SINCRONIZAÇÃO CONVENIOS **************/
    public function sincronizar_agenda_santa_casa_convenios($instituicao){

        //buscando dados na api
        $convenios = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabelas_agenda', [
            'chave' => '295bf3771c4790e7As5fd681',
            'tipo' => 'CONV',
        ])
        ->json(); 

       
    if(!empty($convenios['RETORNO'])){ 

        foreach($convenios['RETORNO'] as $convenio){

            /*VAMOS VERIFICAR SE EXISTE NOVO CONVENIO A VINCULAR E NOTIFICAR A ADMINISTRAÇÃO*/
            $existeConvenioSincronizacao = DB::table('convenios_instituicoes_sincronizacao')
                                            ->where('id_externo', $convenio['CD_CONVENIO'])
                                            ->where('instituicoes_id', $instituicao->id)
                                            ->count(); 

            //SE NAO ENCONTRAR É PORQUE EXISTE NOVO CONVENIO, NOTIFICAR A ADMIN HEALTHBOOK
            if($existeConvenioSincronizacao == 0):
                
                    //VERIFICANDO SE JÁ EXISTE A NOTIFICAÇÃO
                    $existeNotificacao = DB::table('notificacoes_admin')
                                            ->where('modulo', 'sincronizacao_convenios')
                                            ->where('chave_instituicao', $instituicao->chave_unica)
                                            ->where('id_externo', $convenio['CD_CONVENIO'])     
                                            ->count(); 

                 if($existeNotificacao == 0):
                
                    $notificao = array(
                        'modulo' => 'sincronizacao_convenios',
                        'chave_instituicao' => $instituicao->chave_unica,
                        'id_externo' => $convenio['CD_CONVENIO'],
                        'descricao' => 'Novo convenio encontrado, Convênio:'.$convenio['NM_CONVENIO'].', aguarda vinculação para parâmetrização.',
                    );

                    DB::table('notificacoes_admin')->insert($notificao);

                 endif;



            endif;

            /*FIM VERIFICAÇÃO */

            /* COMENTANDO, SOMENTE A PRIMEIRA VEZ QUE SUBIR O SISTEMA */
            // $array_convenio = array(
            //     'nome' => $convenio['NM_CONVENIO'],
            //     'descricao' => $convenio['NM_CONVENIO'],
            // );

            // $new_convenio = Convenio::create($array_convenio);

            // if($new_convenio):

            //     $array_convenio_sincronizacao = array(
            //         'id_externo' => $convenio['CD_CONVENIO'],
            //         'convenios_id' => $new_convenio->id,
            //         'instituicoes_id' => $instituicao->id,
            //     );

            //     DB::table('convenios_instituicoes_sincronizacao')->insert($array_convenio_sincronizacao);

            // endif;



        }

     }
        
    }
    /************** FIM SINCRONIZAÇÃO CONVENIOS **************/

    /**************  SINCRONIZAÇÃO PRESTADORES **************/
    public function sincronizar_agenda_santa_casa_prestadores($instituicao){

        //buscando os dados na api
        $prestadores = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabelas_agenda', [
            'chave' => '295bf3771c4790e7As5fd681',
            'tipo' => 'PREST',
        ])
        ->json(); 


        if(!empty($prestadores['RETORNO'])){

            foreach($prestadores['RETORNO'] as $prestador){

                $cpfFormatado = substr($prestador['NR_CPF_CGC'], 0, 3) . '.' .
                                    substr($prestador['NR_CPF_CGC'], 3, 3) . '.' .
                                    substr($prestador['NR_CPF_CGC'], 6, 3) . '-' .
                                    substr($prestador['NR_CPF_CGC'], 9, 2);


                /*VAMOS VERIFICAR SE JÁ EXISTE PRESTADOR*/
                $existePrestador                = DB::table('prestadores')
                                                ->where('cpf', $cpfFormatado)
                                                ->count(); 

                /*SE NAO EXISTE VAMOS CRIAR E VINCULAR A INSTITUIÇÃO*/
                if($existePrestador == 0):

                    $array_prestador = array(
                        'nome' => $prestador['NM_PRESTADOR'],
                        'cpf' => $cpfFormatado,
                        'crm' => $prestador['CONSELHO'].'-'.$prestador['DS_CODIGO_CONSELHO'],
                    );

                    $new_prestador = Prestador::create($array_prestador);

                    if($new_prestador):

                        $array_prestador_sincronizacao = array(
                            'id_externo' => $prestador['CD_PRESTADOR'],
                            'prestadores_id' => $new_prestador->id,
                            'instituicoes_id' => $instituicao->id,
                        );

                        DB::table('prestadores_instituicoes_sincronizacao')->insert($array_prestador_sincronizacao);

                    endif;

                endif;



                }

            }

    }
    /**************  FIM SINCRONIZAÇÃO PRESTADORES **************/


    /**************  SINCRONIZAÇÃO SETORES **************/
    public function sincronizar_agenda_santa_casa_setores($instituicao){

        $setores = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabelas_agenda', [
            'chave' => '295bf3771c4790e7As5fd681',
            'tipo' => 'SET',
        ])
        ->json(); 


        if(!empty($setores['RETORNO'])){

            foreach($setores['RETORNO'] as $setor){

            /*VAMOS VERIFICAR SE EXISTE NOVO SETOR A CRIAR*/
            $existeSetorSincronizacao = DB::table('setores_instituicoes_sincronizacao')
                ->where('id_externo', $setor['CD_SETOR'])
                ->where('instituicoes_id', $instituicao->id)
                ->count(); 

            /*SE NAO EXISTE, VAMOS CRIAR UM NOVO*/
            if($existeSetorSincronizacao == 0):
            
                $array_setor = array(
                    'id_externo' => $setor['CD_SETOR'],
                    'instituicoes_id' => $instituicao->id,
                    'descricao' => $setor['NM_SETOR'],
                );

                DB::table('setores_instituicoes_sincronizacao')->insert($array_setor);

            endif;


            }
        }

    }
    /************** FIM SINCRONIZAÇÃO SETORES **************/


    /**************  SINCRONIZAÇÃO SERVIÇOS (GRUPOS E ESPECIALIDADES) **************/
    public function sincronizar_agenda_santa_casa_servicos($instituicao){

        $servicos_grupos_especialidades = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabelas_agenda', [
            'chave' => '295bf3771c4790e7As5fd681',
            'tipo' => 'SERV',
        ])
        ->json(); 

        // dd($servicos_grupos_especialidades);

        if(!empty($servicos_grupos_especialidades['RETORNO'])){ 

            foreach($servicos_grupos_especialidades['RETORNO'] as $grupo_especialidade){


            /*VAMOS VERIFICAR SE EXISTE NOVO SERVIÇO/GRUPO/ESPECIALIDADE A VINCULAR E NOTIFICAR A ADMINISTRAÇÃO*/
                $existeEspecialidadeSincronizacao = DB::table('especialidades_sincronizacao')
                ->where('id_externo', $grupo_especialidade['CD_SER_DIS'])
                ->where('instituicoes_id', $instituicao->id)
                ->count(); 

                $existeGrupoSincronizacao = DB::table('grupos_procedimentos_sincronizacao')
                ->where('id_externo', $grupo_especialidade['CD_SER_DIS'])
                ->where('instituicoes_id', $instituicao->id)
                ->count(); 

                //SE NAO ENCONTRAR É PORQUE EXISTE NOVO CONVENIO, NOTIFICAR A ADMIN HEALTHBOOK
                if($existeEspecialidadeSincronizacao == 0 && $existeGrupoSincronizacao == 0):

                //VERIFICANDO SE JÁ EXISTE A NOTIFICAÇÃO
                $existeNotificacaoEspecialidadeGrupo = DB::table('notificacoes_admin')
                            ->where('modulo', 'sincronizacao_especialidades_grupos')
                            ->where('chave_instituicao', $instituicao->chave_unica)
                            ->where('id_externo', $grupo_especialidade['CD_SER_DIS'])     
                            ->count(); 
                

                if($existeNotificacaoEspecialidadeGrupo == 0):

                    $notificaoEspecialidade = array(
                        'modulo' => 'sincronizacao_especialidades_grupos',
                        'chave_instituicao' => $instituicao->chave_unica,
                        'id_externo' => $grupo_especialidade['CD_SER_DIS'],
                        'descricao' => 'Novo serviço/especialiade/grupo encontrado, Serviço:'.$grupo_especialidade['DS_SER_DIS'].', aguarda vinculação para parâmetrização.',
                    );

                    DB::table('notificacoes_admin')->insert($notificaoEspecialidade);

                 endif;



            endif;


        /* COMENTANDO, SOMENTE A PRIMEIRA VEZ QUE SUBIR O SISTEMA */
            // $array_grupo_especialidade = array(
            //     'nome' => $grupo_especialidade['DS_SER_DIS'],
            // );

            // $new_especialidade = Especialidade::create($array_grupo_especialidade);

            // $new_grupo_procedimento = GruposProcedimentos::create($array_grupo_especialidade);

            // if($new_especialidade && $new_grupo_procedimento):

            //     $array_especialidade_sincronizacao = array(
            //         'id_externo' => $grupo_especialidade['CD_SER_DIS'],
            //         'especialidades_id' => $new_especialidade->id,
            //         'instituicoes_id' => $instituicao->id,
            //     );

            //     $array_grupo_sincronizacao = array(
            //         'id_externo' => $grupo_especialidade['CD_SER_DIS'],
            //         'grupos_procedimentos_id' => $new_grupo_procedimento->id,
            //         'instituicoes_id' => $instituicao->id,
            //     );

            //     DB::table('especialidades_sincronizacao')->insert($array_especialidade_sincronizacao);
            //     DB::table('grupos_procedimentos_sincronizacao')->insert($array_grupo_sincronizacao);

            // endif;


        }
    }

    }
    /**************  FIM SINCRONIZAÇÃO SERVIÇOS **************/


    /************** VINCULAÇÃO DE PRESTADORES E AGENDA DE PRESTADORES **************/
    public function sincronizar_agenda_santa_casa_prestadores_atendimentos($instituicao){


        /* SETORES:
        47 -> MEDICINA NUCLEAR
        95 -> MAMOGRAFIA
        98 -> TOMOGRAFIA
        99 -> ULTRASSONOGRAFIA
        114 -> LABORATÓRIO
        119 -> SANTA CASA CORAÇÃO
        121 -> ENDOSCOPIA
        143 -> SANTA CASA CORAÇÃO CENTRO
        153 -> CENTRO MÉDICO MAJOR PRATES
        243 -> TOMOGRAFIA RADIALIS
        246 -> RESSONÂNCIA MAGNÉTICA
        258 -> CENTRAL DE CONSULTAS
        276 -> CHECK UP
        */

        $config_agendas = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/agendas', [
            'chave' => '295bf3771c4790e7As5fd681',
            // 'setor' => '47,95,98,99,114,119,121,143,153,243,246,258,276',
            'setor' => '47',
        ])
        ->json(); 

        $result_agendas = collect($config_agendas['RETORNO']);

        //array de prestadores e seus dias de atendimeno inseridos
        $result_agrupado = $result_agendas->groupBy('CD_PRESTADOR');

        //temos que aumentar o tempo de execução
        ini_set('max_execution_time', 36000);

        if(!empty($result_agrupado)):



            /* PERCORRENDO OS PRESTADORES*/
            foreach($result_agrupado as $agenda_prestador):

                    //percorrendo os dias de atendimento dos prestadores
                    foreach($agenda_prestador as $agenda_prestador_dia):
                        
                        //pegando id do prestador na base healthbook
                        $prestador_dados  = DB::table('prestadores_instituicoes_sincronizacao')
                                                ->where('id_externo', $agenda_prestador_dia['CD_PRESTADOR'])
                                                ->where('instituicoes_id', $instituicao->id)
                                                ->first(); 

                        //pegando especialidade do prestador na base healthbook caso tenha

                           //primeiro verificamos se veio vazio o serviço que é especialidade na base local, se sim, setamos como especialidade padrão
                           if(!empty($agenda_prestador_dia['CD_SER_DIS'])):
                            
                             $especialidade_prestador = DB::table('especialidades_sincronizacao')
                                            ->where('id_externo', $agenda_prestador_dia['CD_SER_DIS'])
                                            ->where('instituicoes_id', $instituicao->id)
                                            ->first();

                             $especialidade_prestador_serv = $especialidade_prestador->especialidades_id;

                           else:
                            //especialidade procedimentos gerais 
                            $especialidade_prestador_serv = 1;

                           endif;
                    
                    //se existir prestador e especialidade da agenda vamos criar o dia da agenda
                     if(!empty($prestador_dados) && !empty($especialidade_prestador_serv)):

                        //verificando se já possui especialidade vinculada
                        $possuiVinculoEspecialidade  = DB::table('instituicoes_prestadores')
                                                    ->where('prestadores_id', $prestador_dados->prestadores_id)
                                                    ->where('instituicoes_id', $instituicao->id)
                                                    ->where('especialidades_id', $especialidade_prestador_serv)
                                                    ->count(); 

                        //se nao existe vamos vincular
                        if($possuiVinculoEspecialidade == 0):
                        
                            $array_vinculo_especialidade = array(
                                'prestadores_id' => $prestador_dados->prestadores_id,
                                'instituicoes_id' => $instituicao->id,
                                'especialidades_id' => $especialidade_prestador_serv,
                            );

                            DB::table('instituicoes_prestadores')->insert($array_vinculo_especialidade);

                        endif;

                    
                        //precisamos pegar as configurações da agenda do dia
                        $dia_da_agenda = explode(' ',$agenda_prestador_dia['DATA']);

                        $config_dia_agenda = Http::asForm()
                        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/dados_agenda', [
                                'chave' => '295bf3771c4790e7As5fd681',
                                'setor' => $agenda_prestador_dia['CD_SETOR'],
                                'prestador' => $agenda_prestador_dia['CD_PRESTADOR'],
                                'dti' => $dia_da_agenda[0],
                                'dtf' => $dia_da_agenda[0],
                        ])
                        ->json(); 

                        //como sistema MV não possui relações de pacientes -> procedimentos -> grupos -> convenios, temos que fazer a verificação a partir dos agendamentos dia a dia
                        if(!empty($config_dia_agenda['RETORNO'][0]['paciente_agendados'])):
                           $this->configura_convenios_procedimentos_prestadores_santa_casa($config_dia_agenda['RETORNO'][0], $instituicao);
                           $this->configura_pacientes_agenda_santa_casa($config_dia_agenda['RETORNO'][0], $instituicao);
                        endif;


                        // VAMOS INSERIR NO BANCO DE DADOS

                        $dia_hora_inicio = explode(' ', $config_dia_agenda['RETORNO'][0]['dados_agenda']['HR_INICIO']);
                        $dia_hora_fim = explode(' ', $config_dia_agenda['RETORNO'][0]['dados_agenda']['HR_FIM']);

                        //***********TERMINAR ESSE ARRAY VERIFICAR A DURAÇÃO DO ATENDIMENTO E INTERVALOS


                        //TERMINAR ISSO COLOQUEI 11 PRA GENTE DESCOBRIR ONDE É
                        if(empty($config_dia_agenda['RETORNO'][0]['dados_agenda']['TEMPO_ITEM_AGENDA'])):
                            $duracao_atendimento =  "00:21";
                        else:
                            $duracao_atendimento = $config_dia_agenda['RETORNO'][0]['dados_agenda']['TEMPO_ITEM_AGENDA'];
                        endif;


                        //verificando se veio o serviço = especialidade
                        if(!empty($config_dia_agenda['RETORNO'][0]['dados_agenda']['CD_SER_DIS'])):
                            $servico_especialidade_grupo = $config_dia_agenda['RETORNO'][0]['dados_agenda']['CD_SER_DIS'];
                        else:
                            $servico_especialidade_grupo = 1;
                        endif;

                        $arrayDiaHorario[$servico_especialidade_grupo][] = 
                            [
                                  "date" => $dia_hora_inicio[0], 
                                  "hora_fim" => $dia_hora_fim[1], 
                                  "selected" => false, 
                                  "hora_inicio" => $dia_hora_inicio[1], 
                                  "especialidade" => $servico_especialidade_grupo, 
                                  "hora_intervalo" => $dia_hora_fim[1], 
                                  "duracao_intervalo" => "00:00", 
                                  "duracao_atendimento" => $duracao_atendimento,
                                  "setor" => $config_dia_agenda['RETORNO'][0]['dados_agenda']['CD_SETOR']
                               ]
                         ; 

      


                    endif;
        

                    endforeach;

                    /*O ARRAY PERCORRIO MONTA O JSON ENTÃO TEMOS QUE ATUALIZAR O JSON DO PRESTADOR DE ACORDO COM ESPECIALIDADE OU CRIAR UM NOVO */
                    if(!empty($arrayDiaHorario)):

                        // echo '<pre>';

                        // echo 'DADOS PRESTADOR:';
                        // print_r($prestador_dados);

                        // echo 'ESPECIALIDADE PRESTADOR:';
                        // // dd($especialidade_prestador);
                        // print_r($especialidade_prestador_serv);

                        // echo 'ARRAY DIA HORARIO:';
                        // print_r($arrayDiaHorario);


                        // exit;


                        foreach ($arrayDiaHorario as $key => $value) {

                     

                            //pegando id do prestador na base healthbook
                            $prestador_dados  = DB::table('prestadores_instituicoes_sincronizacao')
                                ->where('id_externo', $agenda_prestador[0]['CD_PRESTADOR'])
                                ->where('instituicoes_id', $instituicao->id)
                                ->first(); 

                           //pegando especialidade do prestador na base healthbook

                           //regra do serviço que vem vazia = grupo / especialidade
                           if($key != 1):
                           
                            $especialidade_prestador = DB::table('especialidades_sincronizacao')
                                ->where('id_externo', $key)
                                ->where('instituicoes_id', $instituicao->id)
                                ->first();

                           $especialidade_prestador_serv = $especialidade_prestador->especialidades_id;

                           else:

                            $especialidade_prestador_serv = 1;

                           endif;

                                //SIMULANDO ERRO CASO NAO TENHA PROFISSIONAL
                                if(empty($prestador_dados)):
                                        dd($agenda_prestador[0]);
                                endif;

                                // dd($especialidade_prestador_serv);
                            
                            //pegando o vinculo com a instituicao
                            $prestadorEspecialidadeInstituicao  = DB::table('instituicoes_prestadores')
                                                    ->where('prestadores_id', $prestador_dados->prestadores_id)
                                                    ->where('instituicoes_id', $instituicao->id)
                                                    ->where('especialidades_id', $especialidade_prestador_serv)
                                                    ->first(); 

                                                    // dd($prestadorEspecialidadeInstituicao);

                           //se existir o vinculo com a instituicao
                            if(!empty($prestadorEspecialidadeInstituicao)):


                                //array da agenda
                                $agenda_unica_prestador = array(
                                    'referente' => 'prestador',
                                    'tipo' => 'unico',
                                    'dias_unicos' => json_encode($value),
                                    'instituicoes_prestadores_id' => $prestadorEspecialidadeInstituicao->id,
                                );

                                // dd($agenda_unica_prestador['dias_unicos']);
                                // exit;

                                //verificando se já existe a agenda para a especialidade
                                $prestadorAgendaEspecialidade  = DB::table('instituicoes_agenda')
                                                    ->where('instituicoes_prestadores_id', $prestadorEspecialidadeInstituicao->id)
                                                    ->first(); 
                            
                               if(empty($prestadorAgendaEspecialidade)):
                                //CRIANDO REGISTRO
                                  DB::table('instituicoes_agenda')->insert($agenda_unica_prestador);
                               else:
                                //ATUALIZANDO REGISTRO

                                // dd($prestadorEspecialidadeInstituicao->id);
                                // dd($especialidade_prestador->especialidades_id);
                                // dd($value);
                                // dd(json_decode($prestadorAgendaEspecialidade->dias_unicos));
                                // exit;

                                
                                /***  PEGAMOS AS DATAS RETORNADAS DA API, PERCORREMOS O JSON E VERIFICAMOS SE NAO TEM O DIA DA AGENDA E INSERMOS O DIA NO JSON ***/
                                $array_atualizar_agenda_adicionando = json_decode($prestadorAgendaEspecialidade->dias_unicos);

                                foreach ($value as $dia_agenda_profissional) {
                                    
                                    $dia_agenda_encontrado = DB::table('instituicoes_agenda')->where('instituicoes_prestadores_id', $prestadorEspecialidadeInstituicao->id)
                                                                    ->whereJsonContains("dias_unicos", ['date' => $dia_agenda_profissional['date']])
                                                                    ->whereJsonContains("dias_unicos", ['especialidade' => $dia_agenda_profissional['especialidade']])
                                                                    ->whereJsonContains("dias_unicos", ['setor' => $dia_agenda_profissional['setor']])
                                                                    ->first();

                                                                    // dd($dia_agenda_encontrado);
                                                                    // exit;

                                    if(empty($dia_agenda_encontrado)):
                                        array_push($array_atualizar_agenda_adicionando, $dia_agenda_profissional);
                                    endif;

                                }

                                //TEMOS QUE ATUALIZAR TODA VEZ, MESMO QUE NAO TENHA NOVOS REGISTROS
                                DB::table('instituicoes_agenda')->where('instituicoes_prestadores_id', $prestadorEspecialidadeInstituicao->id)
                                                                  ->update(['dias_unicos' => json_encode($array_atualizar_agenda_adicionando)]);



                                  /*** AGORA PRECISAMOS FAZER O INVERSO, PEGAR O ARRAY LOCAL, PESQUISAR NA API PARA VERIFICAR SE ESSE DIA NAO FOI MODIFICADO ALGUM HORÁRIO OU REMOVIDO ***/
                                  $dias_existentes = DB::table('instituicoes_agenda')->where('instituicoes_prestadores_id', $prestadorEspecialidadeInstituicao->id)
                                                                    ->first();


                                  $array_atualizar_agenda_removendo = array();

                                  $array_dias_atendimento = json_decode($dias_existentes->dias_unicos);

                                //   dd($array_dias_atendimento);


                                  foreach ($array_dias_atendimento as $dia_atendimento) {
                                    
                                    //se o dia for maior teremos que percorrer a api para ver se nao foi removido algum dia de atendimento
                                    if(strtotime($dia_atendimento->date) >= strtotime(date('d/m/Y'))):

                                        $dia_atendimento_removido = 1;
                                        //percorrendo os dias da api retornada
                                        foreach ($value as $dia_api_agenda) {

                                         if(strtotime($dia_api_agenda['date']) >= strtotime(date('d/m/Y'))):

                                            if(strtotime($dia_api_agenda['date']) == strtotime($dia_atendimento->date)
                                                  && $dia_api_agenda['especialidade'] == $dia_atendimento->especialidade
                                                  && $dia_api_agenda['setor'] == $dia_atendimento->setor
                                              ):

                                              $dia_atendimento_removido = 0;

                                            endif;
                                          endif;
                                            
                                        }

                                      //verificando se nao encontrou registro do dia na api, se nao, tem que remover do array
                                      if($dia_atendimento_removido == 0):

                                        array_push($array_atualizar_agenda_removendo, $dia_atendimento);

                                      endif;
                                        
                                        
                                    //se nao for maior, precisamos inserir o array da mesma forma
                                    else:

                                        array_push($array_atualizar_agenda_removendo, $dia_atendimento);

                                    endif;
                             
                                    

                                  }

                                //   dd($array_atualizar_agenda_removendo);

                                //TEMOS QUE ATUALIZAR TODA VEZ, MESMO QUE NAO TENHA REMOVIDO REGISTROS
                                DB::table('instituicoes_agenda')->where('instituicoes_prestadores_id', $prestadorEspecialidadeInstituicao->id)
                                                                  ->update(['dias_unicos' => json_encode($array_atualizar_agenda_removendo)]);

                                                      

                                                                    

                                                                

                               endif;

                                

                            endif;

                        }


                    endif;

                    //  dd($arrayDiaHorario);
                        //  exit;

            endforeach;

        endif;

    }

    /************** FIM VINCULAÇÃO DE PRESTADORES E AGENDA DE PRESTADORES **************/

    /* OLD*/
    public function sincronizar_agenda_santa_casa($instituicao){


        /**************  SINCRONIZAÇÃO AGENDA **************/

        //  $agendas = Http::asForm()
        //  ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/dados_agenda', [
        //         'chave' => '295bf3771c4790e7As5fd681',
        //         'setor' => '153',
        //         'prestador' => '3886',
        //         'dti' => '24/03/2021',
        //         'dtf' => '24/03/2021',
        //  ])
        // ->json(); 

        // dd($agendas);

        /**************  FIM SINCRONIZAÇÃO AGENDA **************/


        /* VINCULANDO PROFISSIOAIS A INSTITUIÇÃ COM DEVIDAS ESPECIALIDADES */

         /*
        API Tabelas da Agenda:
        Link: http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabelas_agenda
        parâmetros: { tipo , cod }
        tipos: 
        __ SERV : Lista os serviços
        __ SET : Lista os setores
        __ PREST : Lista os prestadores


        API Dados da Agenda:
        Link: http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/dados_agenda
        parâmetros: { dti *, dtf *, setor *, prestador, servico  }
        * Campos Obrigatórios

        258 - CENTRAL DE CONSULTAS

        tipos:  
        __ CONV : Lista os convenios

        */

        //BUSCANDO TABELAS DE CADASTROS DE AGENDAS
        // $dados = Http::asForm()
        // ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabelas_agenda', [
        //     'chave' => '295bf3771c4790e7As5fd681',
        //     'tipo' => 'PREST',
        // ])
        // ->json(); 

        // return $dados;



        //BUSCANDO AGENDAS
        // $dados = Http::asForm()
        // ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/dados_agenda', [
        //     'chave' => '295bf3771c4790e7As5fd681',
        //     'setor' => '258',
        //     'dti' => '21/01/2021',
        //     'dtf' => '22/01/2021',
        // ])
        // ->json(); 

        // return $dados;

    }
    /* FIM OLD */

    /************** VINCULAÇÃO DE PROCEDIMENTOS **************/
    public function sincronizar_agenda_santa_casa_procedimentos($instituicao){

        //buscando dados na api
        $procedimentos = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabelas_agenda', [
            'chave' => '295bf3771c4790e7As5fd681',
            'tipo' => 'IT_AGEN',
        ])
        ->json(); 

        /*
        A - PROCENDIMENTO AMBULATORIAL
        I - EXAME IMAGEM
        L - LABORATORIO
        */
 
     if(!empty($procedimentos['RETORNO'])){ 
 
         foreach($procedimentos['RETORNO'] as $procedimento){
 
             /*VAMOS VERIFICAR SE EXISTE NOVO PROCEDIMENTO A VINCULAR E NOTIFICAR A ADMINISTRAÇÃO*/
             $existeProcedimentoSincronizacao = DB::table('procedimentos_sincronizacao')
                                             ->where('id_externo', $procedimento['CD_ITEM_AGENDAMENTO'])
                                             ->where('instituicoes_id', $instituicao->id)
                                             ->count(); 
 
             //SE NAO ENCONTRAR É PORQUE EXISTE NOVO CONVENIO, NOTIFICAR A ADMIN HEALTHBOOK
             if($existeProcedimentoSincronizacao == 0):
                 
                     //VERIFICANDO SE JÁ EXISTE A NOTIFICAÇÃO
                     $existeNotificacao = DB::table('notificacoes_admin')
                                             ->where('modulo', 'sincronizacao_procedimentos')
                                             ->where('chave_instituicao', $instituicao->chave_unica)
                                             ->where('id_externo', $procedimento['CD_ITEM_AGENDAMENTO'])     
                                             ->count(); 
 
                  if($existeNotificacao == 0):
                 
                     $notificao = array(
                         'modulo' => 'sincronizacao_convenios',
                         'chave_instituicao' => $instituicao->chave_unica,
                         'id_externo' => $procedimento['CD_ITEM_AGENDAMENTO'],
                         'descricao' => 'Novo procedimento encontrado, Procedimento:'.$procedimento['DS_ITEM_AGENDAMENTO'].', aguarda vinculação para parâmetrização.',
                     );
 
                     DB::table('notificacoes_admin')->insert($notificao);
 
                  endif;
 
 
 
             endif;
 
             /*FIM VERIFICAÇÃO */
 
             /* COMENTANDO, SOMENTE A PRIMEIRA VEZ QUE SUBIR O SISTEMA 
             if($procedimento['TP_ITEM'] == 'A'):
                $tipo = 'consulta';
             else:
                $tipo = 'exame';
             endif;

             $array_procedimento = array(
                 'descricao' => $procedimento['DS_ITEM_AGENDAMENTO'],
                 'tipo' => $tipo,
             );
 
             $new_procedimento = Procedimento::create($array_procedimento);
 
             if($new_procedimento):
 
                 $array_procedimento_sincronizacao = array(
                     'id_externo' => $procedimento['CD_ITEM_AGENDAMENTO'],
                     'procedimentos_id' => $new_procedimento->id,
                     'instituicoes_id' => $instituicao->id,
                 );
 
                 DB::table('procedimentos_sincronizacao')->insert($array_procedimento_sincronizacao);
 
             endif;
             */
 
 
         }
 
      }

    }
    /************** FIM VINCULAÇÃO DE PROCEDIMENTOS **************/


    /************** SINCRONIZAÇÃO DE PACIENTES TERMINAR **************/
    public function sincronizar_agenda_santa_casa_pacientes($instituicao){

        $dados = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabelas_agenda', [
            'chave' => '295bf3771c4790e7As5fd681',
            'tipo' => 'PAC',
            'cod' => null,
            'inicio'=> 1,
            'fim'=> 2000,
            'nome'=> null
        ])
        ->json(); 

        dd($dados);

    }
    /************** FIM SINCRONIZAÇÃO DE PACIENTES TERMINAR **************/


    /************** CONFIGURANDO PROCEDIMENTOS INSTITUIÇÕES, PRESTADORS PROCEDIEMNTOS CONVENIOS **************/
    public function configura_convenios_procedimentos_prestadores_santa_casa($dados, $instituicao){
       
  
        foreach ($dados['paciente_agendados'] as $agendamento) {


                 //PRIMEIRO VERIFICAMOS SE EXISTE O VINCULO DO PROCEDIMENTO LOCAL -> PROCEDIMENTO EXTERNO, SE NAO EXISTIR TEM QUE VINCULAR NA ADMIN PRIMEIRO
                $procedimentoLocalExterno  = DB::table('procedimentos_sincronizacao')
                        ->where('id_externo', $agendamento['CD_ITEM_AGENDAMENTO'])
                        ->where('instituicoes_id', $instituicao->id)
                        ->first(); 


              //caso nao venha o codigo do serivço, tem que setar como grupo ou especialidade padrão 1
               if(!empty($dados['dados_agenda']['CD_SER_DIS'])):

               $grupoLocalExterno  = DB::table('grupos_procedimentos_sincronizacao')
                        ->where('id_externo',  $dados['dados_agenda']['CD_SER_DIS'])
                        ->where('instituicoes_id', $instituicao->id)
                        ->first(); 

               $grupoLocalExternoServ = $grupoLocalExterno->grupos_procedimentos_id;

               $especialidadeLocalExterno  = DB::table('especialidades_sincronizacao')
                        ->where('id_externo',  $dados['dados_agenda']['CD_SER_DIS'])
                        ->where('instituicoes_id', $instituicao->id)
                        ->first(); 

               $especialidadeLocalExternoServ = $especialidadeLocalExterno->especialidades_id;

               else:

                $grupoLocalExternoServ = 1 ;
                $especialidadeLocalExternoServ = 1 ;

               endif;

               $convenioLocalExterno  = DB::table('convenios_instituicoes_sincronizacao')
                        ->where('id_externo',  $agendamento['CD_CONVENIO'])
                        ->where('instituicoes_id', $instituicao->id)
                        ->first(); 

               $instituicaoPrestadorExterno  = DB::table('prestadores_instituicoes_sincronizacao')
                        ->where('id_externo',  $dados['dados_agenda']['CD_PRESTADOR'])
                        ->where('instituicoes_id', $instituicao->id)
                        ->first();

                if(!empty($instituicaoPrestadorExterno) && !empty($grupoLocalExternoServ)):

                    $instituicaoPrestador  = DB::table('instituicoes_prestadores')
                        ->where('prestadores_id',  $instituicaoPrestadorExterno->prestadores_id)
                        ->where('especialidades_id',  $especialidadeLocalExternoServ)
                        ->where('instituicoes_id', $instituicao->id)
                        ->first();

                endif;

        
               
                if(!empty($procedimentoLocalExterno) && !empty($grupoLocalExternoServ) && !empty($convenioLocalExterno) && !empty($instituicaoPrestador)):

                //AGORA VERIFICAMOS SE O PROCEIDMENTO ESTÁ VINCULADO A INSTITUIÇÃO
                $procedimentoInstituicao  = DB::table('procedimentos_instituicoes')
                        ->where('procedimentos_id', $procedimentoLocalExterno->procedimentos_id)
                        ->where('instituicoes_id', $instituicao->id)
                        ->first(); 

                        //SE NAO ENCONTRAR TEMOS QUE CRIAR O REGISTRO DA VINCULAÇÃO ENTRE PROCEDIMENTO -> INSTITUIÇÃO
                        if(empty($procedimentoInstituicao)):

                            $array_procedimento_instituicao = array(
                                'procedimentos_id' => $procedimentoLocalExterno->procedimentos_id,
                                'instituicoes_id' => $instituicao->id,
                                'grupo_id' => $grupoLocalExternoServ,
                            );


                            $new_procedimento_instituicao = InstituicaoProcedimentos::create($array_procedimento_instituicao);

                            //SE O PROCEDIMENTO NAO FOR VINCULADO, SIGNIFICA QUE PARA ESTE CONVENIO EM QUESTÃO NAO EXISTE A VINCULAÇÃO, IREMOS VINCULAR
                            $array_procedimento_instituicao_convenio = array(
                                'procedimentos_instituicoes_id' => $new_procedimento_instituicao->id,
                                'convenios_id' => $convenioLocalExterno->convenios_id,
                                'valor' => '0.00',
                            );
   

                            $new_procedimento_instituicao_convenio = ConveniosProcedimentos::create($array_procedimento_instituicao_convenio);


                            //TAMBÉM IREMOS VINCULAR O PROCEDIMENTO AO PRESTADOR
                            $array_procedimento_instituicao_convenio_prestador = array(
                                'instituicoes_prestadores_id' => $instituicaoPrestador->id,
                                'procedimentos_convenios_id' => $new_procedimento_instituicao_convenio->id,
                                'procedimentos_id' => $procedimentoLocalExterno->procedimentos_id,
                            );

                            DB::table('procedimentos_convenios_instituicoes_prestadores')->insert($array_procedimento_instituicao_convenio_prestador);


                        //SE ENCONTRAR IREMOS VERIFICAR SE JÁ NAO EXISTE O VINCULO DAQUELE PROCEDIMENTO NA INSTIUIÇÃO E NAQUELE CONVEIO
                        else:
                        
                        $procedimentoInstituicaoConvenio  = DB::table('procedimentos_instituicoes_convenios')
                            ->where('procedimentos_instituicoes_id', $procedimentoInstituicao->id)
                            ->where('convenios_id', $convenioLocalExterno->convenios_id)
                            ->count();
                            

                        //SE NAO ENCONTRAR VINCULAMOS O PROCEDIMENTO DA INSTITUIÇÃO AO CONVENIO
                        if($procedimentoInstituicaoConvenio == 0):

                            $array_procedimento_instituicao_convenio = array(
                                'procedimentos_instituicoes_id' => $procedimentoInstituicao->id,
                                'convenios_id' => $convenioLocalExterno->convenios_id,
                                'valor' => '0.00',
                            );

                           $new_procedimento_instituicao_convenio = ConveniosProcedimentos::create($array_procedimento_instituicao_convenio);

                           //TAMBÉM IREMOS VINCULAR O PROCEDIMENTO AO PRESTADOR
                                $array_procedimento_instituicao_convenio_prestador = array(
                                    'instituicoes_prestadores_id' => $instituicaoPrestador->id,
                                    'procedimentos_convenios_id' => $new_procedimento_instituicao_convenio->id,
                                    'procedimentos_id' => $procedimentoLocalExterno->procedimentos_id,
                                );

                            DB::table('procedimentos_convenios_instituicoes_prestadores')->insert($array_procedimento_instituicao_convenio_prestador);


                        endif;



                        endif;

                    

                endif;

        }

        

        
        
        
    }
    /************** FIM CONFIGURAÇÃO **************/

    /************** CONFIGURANDO PACIENTES E AGENDA DOS PACIENTES **************/
    public function configura_pacientes_agenda_santa_casa($dados, $instituicao){

        


        foreach ($dados['paciente_agendados'] as $agendamento) {

            // dd($agendamento);
            // exit;


            //SÓ PODEMOS EXECUTAR SE EXISTIR O PACIENTE NA BASE EXTERNA
            if(!empty($agendamento['CD_PACIENTE'])):

            //PRIMEIRO VERIFICAMOS SE O PACIENTE EXISTE PELO CÓDIGO DA INSTIUIÇÃO
            $usuarioInstituicao  = DB::table('instituicao_has_pacientes')
                        ->where('id_externo', $agendamento['CD_PACIENTE'])
                        ->where('instituicao_id', $instituicao->id)
                        ->first(); 
            
            //CASO NÃO EXISTA PELO CÓDIGO VERICAREMOS PELO CPF
            if(empty($usuarioInstituicao)):

                $cpfFormatado = substr($agendamento['NR_CPF'], 0, 3) . '.' .
                                    substr($agendamento['NR_CPF'], 3, 3) . '.' .
                                    substr($agendamento['NR_CPF'], 6, 3) . '-' .
                                    substr($agendamento['NR_CPF'], 9, 2);

                $usuario  = DB::table('usuarios')
                        ->where('cpf', $agendamento['CD_PACIENTE'])
                        ->first(); 

                        


                //CASO NÃO EXISTA PELO CPF TEREMOS QUE CRIAR
                if(empty($usuario)):

                    if(!empty($agendamento['DT_NASCIMENTO'])):
                        $nascimento = Carbon::createFromFormat('d/m/Y H:i', $agendamento['DT_NASCIMENTO'])->format('Y/m/d');
                    else:
                        $nascimento = null;
                    endif;

                    if(!empty($agendamento['NR_FONE'])):
                        $telefone = $this->formatar_telefone($agendamento['NR_FONE']);
                    else:
                        $telefone = null;
                    endif;

                    $array_usuario = array(
                        'nome' => $agendamento['NM_PACIENTE'],
                        'data_nascimento' => $nascimento,
                        'cpf' => $cpfFormatado,
                        'telefone' => $telefone,
                        'nome_mae' => $agendamento['NM_MAE'],
                    );

                    // if(empty($array_usuario['telefone']) || empty($array_usuario['data_nascimento'])):
                    //     dd($agendamento);
                    //     exit;
                    // endif;


                    $new_usuario = Usuario::create($array_usuario);

                    //APÓS CRIAR IREMOS VINCULAR A INSTITUIÇÃO
                    $instituicao_paciente = array(
                        'instituicao_id' => $instituicao->id,
                        'usuario_id' => $new_usuario->id,
                        'id_externo' => $agendamento['CD_PACIENTE'],
                    );

                    DB::table('instituicao_has_pacientes')->insert($instituicao_paciente);

                    $id_usuario_agenda = $new_usuario->id;

                else:
                    $id_usuario_agenda = $usuario->id;

                endif;

            else:
                $id_usuario_agenda = $usuarioInstituicao->usuario_id;
            endif;


            /* vamos pegar a agenda do profissional */

            //pegando id do prestador na base healthbook
            $prestador_dados  = DB::table('prestadores_instituicoes_sincronizacao')
            ->where('id_externo', $dados['dados_agenda']['CD_PRESTADOR'])
            ->where('instituicoes_id', $instituicao->id)
            ->first(); 

            //pegando especialidade do prestador na base healthbook
            if(!empty($dados['dados_agenda']['CD_SER_DIS'])):

            $especialidade_prestador = DB::table('especialidades_sincronizacao')
            ->where('id_externo',  $dados['dados_agenda']['CD_SER_DIS'])
            ->where('instituicoes_id', $instituicao->id)
            ->first();

            $especialidade_prestador_serv = $especialidade_prestador->especialidades_id;

            else:

                $especialidade_prestador_serv = 1;

            endif;

            //pegando vinculo do prestador especialidade na base healthbook
            $possuiVinculoEspecialidade  = DB::table('instituicoes_prestadores')
                                                    ->where('prestadores_id', $prestador_dados->prestadores_id)
                                                    ->where('instituicoes_id', $instituicao->id)
                                                    ->where('especialidades_id', $especialidade_prestador_serv)
                                                    ->first(); 
            
           //pegando configuração da agenda do prestador
           $instituicaoAgendaPrestador  = DB::table('instituicoes_agenda')
                                    ->where('instituicoes_prestadores_id', $possuiVinculoEspecialidade->id)
                                    ->first(); 
           


           if(!empty($instituicaoAgendaPrestador)):

                        //APÓS REGRAS E VERIFICAÇÕES DE USUÁRIOS VAMOS CRIAR O AGENDAMENTO
                        $agendamento_paciente = array(
                            'tipo' => 'agendamento',
                            'data' => Carbon::createFromFormat('d/m/Y H:i', $agendamento['HR_AGENDA'])->format('Y/m/d H:i:s'),
                            'status' => 'pendente',
                            'valor_total' => '0.00',
                            'instituicoes_agenda_id' => $instituicaoAgendaPrestador->id,
                            'usuario_id' => $id_usuario_agenda,
                            'forma_pagamento' => 'dinheiro',
                        );

                        //VERIFICANDO SE JÁ EXISTE O AGENDAMENTO, SE NA IREMOS CRIAR
                        $agenda_inserida  = DB::table('agendamentos')
                                                    ->where('data', Carbon::createFromFormat('d/m/Y H:i', $agendamento['HR_AGENDA'])->format('Y/m/d H:i:s'))
                                                    ->where('instituicoes_agenda_id', $instituicaoAgendaPrestador->id)
                                                    ->where('usuario_id', $id_usuario_agenda)
                                                    ->count(); 
                        if($agenda_inserida == 0):


                            //INSERIMOS A AGENDA
                            $new_agenda = Agendamentos::create($agendamento_paciente);

                            if(!empty($agendamento['CD_ITEM_AGENDAMENTO'])):

                                    //INSERIMOS O ITEM DA AGENDA
                                    $procedimentoLocalExterno  = DB::table('procedimentos_sincronizacao')
                                        ->where('id_externo', $agendamento['CD_ITEM_AGENDAMENTO'])
                                        ->where('instituicoes_id', $instituicao->id)
                                        ->first(); 

                                        // dd($agendamento);
                                        // exit;
                                    
                              if(!empty($procedimentoLocalExterno)):
                                //AGORA VERIFICAMOS SE O PROCEIDMENTO ESTÁ VINCULADO A INSTITUIÇÃO
                                    $procedimentoInstituicao  = DB::table('procedimentos_instituicoes')
                                        ->where('procedimentos_id', $procedimentoLocalExterno->procedimentos_id)
                                        ->where('instituicoes_id', $instituicao->id)
                                        ->first(); 

                                    //PEGANDO O CONVENIO LOCAL EXTERNO
                                    $convenioLocalExterno  = DB::table('convenios_instituicoes_sincronizacao')
                                        ->where('id_externo',  $agendamento['CD_CONVENIO'])
                                        ->where('instituicoes_id', $instituicao->id)
                                        ->first(); 


                                        if(!empty($procedimentoInstituicao) && !empty($convenioLocalExterno)):

                                            //PEGANDO O PROCEDIMENTO INSTITUICAO ONVENIOA
                                            $procedimentoInstituicaoConvenio  = DB::table('procedimentos_instituicoes_convenios')
                                                ->where('procedimentos_instituicoes_id', $procedimentoInstituicao->id)
                                                ->where('convenios_id', $convenioLocalExterno->convenios_id)
                                                ->first();

                                                $agendamento_paciente_procedimento = array(
                                                    'agendamentos_id' => $new_agenda->id,
                                                    'procedimentos_instituicoes_convenios_id' => $procedimentoInstituicaoConvenio->id,
                                                    'valor_atual' => '0.00',
                                                    'estornado' => 0,
                                                );


                                                $new_agendamento_procedimento = AgendamentoProcedimento::create($agendamento_paciente_procedimento);

                                       endif;

                                  endif;

                            endif;


                            

                            


                        endif;

// dd($agendamento_paciente);
// exit;

           endif;



           endif;
        }

    }
    /************** FIM CONFIGURAÇÃO **************/




    /* FUNÇÕES FORMATAÇÃO */

    public function formatar_telefone($telefone_db){

        $telefone_sem_simbolos = preg_replace('/[^0-9]/', '', $telefone_db);
            
            if (strlen($telefone_sem_simbolos) < 8) {
                // Telefone não tem quantidade de números suficiente
                return null;
            }
            
            if ((12 === strlen($telefone_sem_simbolos) || 11 === strlen($telefone_sem_simbolos)) && '0' === $telefone_sem_simbolos[0]) {
                // Telefone com 0 no DDD
                $telefone_formatado = 12 === strlen($telefone_sem_simbolos)
                    ? preg_replace('/^0([0-9]{2})9([0-9]{4})([0-9]{4})$/', '($1) 9 $2-$3', $telefone_sem_simbolos)
                    : preg_replace('/^0([0-9]{2})([0-9]{4})([0-9]{4})$/', '($1) $2-$3', $telefone_sem_simbolos);
            } else if (10 === strlen($telefone_sem_simbolos) || 11 === strlen($telefone_sem_simbolos)) {
                // Telefone já tem DDD
                $telefone_formatado = 11 === strlen($telefone_sem_simbolos)
                    ? preg_replace('/^([0-9]{2})9([0-9]{4})([0-9]{4})$/', '($1) 9 $2-$3', $telefone_sem_simbolos)
                    : preg_replace('/^([0-9]{2})([0-9]{4})([0-9]{4})$/', '($1) $2-$3', $telefone_sem_simbolos);
            } else {
                // Telefone sem DDD
                $telefone_formatado = 9 === strlen($telefone_sem_simbolos)
                    ? preg_replace('/^9([0-9]{4})([0-9]{4})$/', '(68) 9 $1-$2', $telefone_sem_simbolos)
                    : preg_replace('/^([0-9]{4})([0-9]{4})$/', '(68) $1-$2', $telefone_sem_simbolos);
            }
            
            return $telefone_formatado;

    }

}
