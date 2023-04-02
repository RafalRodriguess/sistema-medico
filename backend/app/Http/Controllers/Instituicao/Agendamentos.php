<?php

namespace App\Http\Controllers\Instituicao;

use App\AgendamentoAtendimento;
use App\AgendamentoGuia;
use App\AgendamentoListaEspera;
use App\AgendamentoProcedimento;
use App\Http\Controllers\Controller;
use App\Instituicao;
use App\InstituicoesAgenda;
use App\Agendamentos as Agendamento;
use App\AuditoriaAgendamento;
use App\Carteirinha;
use App\ContaPagar;
use App\ContaReceber;
use App\Convenio;
use App\ConveniosProcedimentos;
use App\Especialidade;
use App\FaturamentoLoteGuia;
use App\GruposProcedimentos;
use App\Http\Requests\Agendamentos\EditarAgendamentoBackendRequest;
use App\Http\Requests\Agendamentos\PagamentoAgendamentoRequest;
use App\Http\Requests\Agendamentos\SalvarAgendamentoBackendRequest;
use App\Http\Requests\Agendamentos\VerificaProximoHorarioRequest;
use App\InstituicoesPrestadores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\PagarMe;
use App\Log;
use App\Pessoa;
use App\Prestador;
use App\PrestadorSolicitante;
use App\Procedimento;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Support\ConverteValor;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

use function Clue\StreamFilter\fun;

class Agendamentos extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_agendamentos');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        /*KENTRO*/
        //REGRA MUDOU, NÃO PRECISA DE HABILIDADE MAIS
        //OCULTADO ATÉ VITOR RESPONDER SOBRE API E WEBHOOK, DEIXADO APENAS NO CRON
        if ($instituicao->automacao_whatsapp === 5555) :

            try{
                $kentrodb = DB::connection('kentro')->table('autosend')->select(['id', 'queue_id'])->get();

                //VAMOS PEGAR OS AGENDAMENTOS DO DIA POSTERIOR


                $dia_posterior =  date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));

                $dia_posterior_inicio = $dia_posterior . ' 00:00:00';
                $dia_posterior_fim = $dia_posterior . ' 23:59:00';

                $agendamentos = DB::table('agendamentos')
                    ->select('agendamentos.data', 'agendamentos.id', 'prestadores.nome as profissional', 'pessoas.nome as paciente', 'pessoas.telefone1')
                    ->where('instituicao_id', $request->session()->get('instituicao'))
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

                            $msg = str_replace('{paciente}', ucwords($agendamento->paciente), $instituicao->kentro_msg_confirmacao);
                            $msg = str_replace('{empresa}', $instituicao->nome, $msg);
                            // $msg = str_replace('{dia_extenso}', ucwords($data_extenso), $msg);
                            $msg = str_replace('{data}', date("d/m/Y", strtotime($agendamento->data)), $msg);
                            $msg = str_replace('{hora}', date("H:i", strtotime($agendamento->data)), $msg);
                            $msg = str_replace('{profissional}', $agendamento->profissional, $msg);

                            $buttons = '{"title": "' . $instituicao->nome . '", "buttons": ["Remarcar", "Desmarcar", "Confirmar"]}';

                            $envio = array(
                                'queue_id' => $instituicao->kentro_fila_empresa,
                                'number' => $telefone,
                                'text' => $msg,
                                'status' => 0,
                                'buttons' => $buttons,
                            );

                            //REGISTRANDO NO BANCO KENTRO E ATUALIZANDO NO BANCO LOCAL COMO ENTREGUE
                            if (DB::connection('kentro')->table('autosend')->insert($envio)) :

                                DB::table('agendamentos')
                                    ->where('id',  $agendamento->id)
                                    ->update(array(
                                        'envio_confirmacao_whatsapp' => 1,
                                        'data_hora_envio_confirmacao_whatsapp' => date('Y-m-d H:i:s')
                                    ));


                            endif;

                        endif;

                        // echo '<pre>';
                        // print_r($envio);


                    }


                endif;


                //VAMOS CONSULTAR AGORA AS RESPOSTAS

                $confirmacoes_agendamentos = DB::table('agendamentos')->select('agendamentos.data', 'agendamentos.id', 'agendamentos.data_hora_envio_confirmacao_whatsapp',  'prestadores.nome as profissional', 'pessoas.nome as paciente', 'pessoas.telefone1')
                    ->where('instituicao_id', $request->session()->get('instituicao'))
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
                                    if ($resposta->message == 'Confirmar') :

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
                                                'queue_id' => $instituicao->kentro_fila_empresa,
                                                'number' => $telefone_envio,
                                                'text' => $instituicao->kentro_msg_resposta_confirmacao,
                                                'status' => 0,
                                            );



                                            DB::connection('kentro')->table('autosend')->insert($envio);


                                        endif;


                                    //CASO TENHA DESMARCADO
                                    elseif ($resposta->message == 'Desmarcar') :

                                        $nova_data = date('Y-m-d', strtotime($agendamento->data)).' 23:00:00';
                                        $nova_data_final = date('Y-m-d', strtotime($agendamento->data)).' 23:00:00';
                                        $dados = null;
                                        if($instituicao->ausente_agenda){
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
                                            // ->update(array(
                                            //     'resposta_confirmacao_whatsapp' => $resposta->message,
                                            //     'data_hora_resposta_confirmacao_whatsapp' => date('Y-m-d H:i:s'),
                                            //     'status' => 'cancelado',
                                            //     'status_pagamento' => 'estornado',
                                            //     // 'confirmacao_horario' => date('Y-m-d H:i:s'),
                                            //     // 'confirmacao_profissional_id' => $CI->config->item('kentro_confirmacao_usuario')
                                            // ))
                                        ) :


                                            //CASO TENHA ATUALIZADO TUDO CERTO A CONFIRMAÇÃO NO BANCO IREMOS ENVIAR MSG NO WHATSAPP
                                            $envio = array(
                                                'queue_id' => $instituicao->kentro_fila_empresa,
                                                'number' => $telefone_envio,
                                                'text' => $instituicao->kentro_msg_resposta_desmarcado,
                                                'status' => 0,
                                            );



                                            DB::connection('kentro')->table('autosend')->insert($envio);


                                        endif;



                                    //CASO TENHA SOLICITADO REMARCAÇÃO
                                    elseif ($resposta->message == 'Remarcar') :

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
                                                'queue_id' => $instituicao->kentro_fila_empresa,
                                                'number' => $telefone_envio,
                                                'text' => $instituicao->kentro_msg_resposta_remarcacao,
                                                'status' => 0,
                                            );



                                            DB::connection('kentro')->table('autosend')->insert($envio);


                                        endif;




                                    endif;
                                }



                            endif;


                        endif;

                        // echo '<pre>';
                        // print_r($retorno);


                    }


                endif;

            }catch(Exception $e){
                $erro = [
                    "arquivo" => $e->getFile(),
                    "linha" => $e->GetLine(),
                    "erro" => $e->getMessage(),
                ];

                $instituicao = Instituicao::find($request->session()->get('instituicao'));
                $instituicao->criarLog($request->user('instituicao'), "Erro na conexão com o bando de dados do kentro, mensagens não enviadas", $erro);
            }

            //CONSULTAR PESQUISA DE SATISFAÇÃO
            if($instituicao->enviar_pesquisa_satisfacao_atendimentos == 1):

                $agendamentosPesquisaSatisfacao = DB::table('agendamentos')
                ->select('agendamentos.data', 'agendamentos.id', 'agendamentos.data_hora_envio_pesquisa_satisfacao_whatsapp', 'pessoas.telefone1')
                ->where('instituicao_id', $request->session()->get('instituicao'))
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
                                                'queue_id' => $instituicao->kentro_fila_empresa,
                                                'number' => $telefone_envio,
                                                'text' => $instituicao->kentro_msg_resposta_pesquisa_satisfacao,
                                                'status' => 0,
                                            );

                                            DB::connection('kentro')->table('autosend')->insert($envio);


                                        endif;



                                    endif;

                                }



                            endif;


                        endif;

                        // echo '<pre>';
                        // print_r($retorno);


                    }


                endif;



            endif;

        endif;


        /*FIM KENTRO*/

        // $agendaAusente = $instituicao->agendasAusente()->get();
        // dd($agendaAusente);
        $usuario_logado = $request->user('instituicao');

        $usuario_prestador = $usuario_logado->prestadorMedico()->get();
        if(count($usuario_prestador) > 0 && $usuario_prestador[0]->tipo == 2){
            $prestador_especialidade_id = $usuario_prestador[0]->id;
        }

        $instituicao_logada = $usuario_logado->instituicao->where('id', $request->session()->get('instituicao'))->first();
        $prestadoresIds = explode(',', $instituicao_logada->pivot->visualizar_prestador);

        $profissionaisHome = Especialidade::
        whereHas('prestadoresInstituicao', function($q) use($usuario_prestador, $usuario_logado, $instituicao, $prestadoresIds){
            $q->where('ativo', 1);
            if(!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_agenda_prestador')){
                if(count($usuario_prestador) > 0 && $usuario_prestador[0]->tipo == 2){
                    $q->where('instituicao_usuario_id', $usuario_logado->id);
                }
            }else{
                if($prestadoresIds != null){
                    if(!in_array('', $prestadoresIds)){
                        $q->whereIn('id', $prestadoresIds);
                    }
                }
            }
            $q->where('instituicoes_id',$instituicao->id);
        })
        ->with([
            'prestadoresInstituicao' => function($q) use($instituicao, $usuario_logado, $usuario_prestador, $prestadoresIds){
                $q->where('ativo', 1);
                $q->where('instituicoes_id',$instituicao->id);
                if(!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_agenda_prestador')){
                    if(count($usuario_prestador) > 0 && $usuario_prestador[0]->tipo == 2){
                        $q->where('instituicao_usuario_id', $usuario_logado->id);
                    }
                }else{
                    if($prestadoresIds != null){
                        if(!in_array('', $prestadoresIds)){
                            $q->whereIn('id', $prestadoresIds);
                        }
                    }
                }
            },
            'prestadoresInstituicao.prestador' => function($q){
                $q->select('id','nome');
            }
        ])->get();

        return view('instituicao.agendamentos/lista', \compact('profissionaisHome'));
    }

    public function cancelar_agendamento(Request $request)
    {

        $agendamento = Agendamento::find($request->id);

        $pagarMe = new PagarMe();
        $title = '';
        $msg = '';
        $msg1 = '';
        $url = '';
        try {
            // $estorno = $pagarMe->estornarTransacaoInstituicao($agendamento );
            // if($estorno->status=='refunded'){
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            if($instituicao->ausente_agenda){
                $nova_data = date('Y-m-d', strtotime($agendamento->data)).' 23:00:00';
                $nova_data_final = date('Y-m-d', strtotime($agendamento->data)).' 23:00:00';
                $agendamento->update(['motivo_cancelamento' => $request->motivo, 'status' => 'cancelado', 'status_pagamento' => 'estornado', 'data_original' => $agendamento->data, 'data_final_original' => $agendamento->data_final, 'data' => $nova_data, 'data_final' => $nova_data_final]);
            }else{
                $agendamento->update(['motivo_cancelamento' => $request->motivo, 'status' => 'cancelado', 'status_pagamento' => 'estornado']);
            }

            $agendamento->criarLogEdicao($request->user('instituicao'), $request->session()->get('instituicao'));
            AuditoriaAgendamento::logAgendamento($request->id, 'cancelado', $request->user('instituicao')->id, 'cancelar_agendamento', $request->motivo);
            // if($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='consulta'){
            //     $title = 'Consulta #'.$agendamento->id;
            //     $msg = 'A clínica cancelou a sua consulta.';
            //     $url = 'tabs/tab1/agendamentos/consulta/'.$agendamento->id;
            //     if($request->motivo){
            //         $msg1 = 'Motivo: '.$request->motivo;
            //     }
            // }elseif($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='exame'){
            //     $title = 'Exame #'.$agendamento->id;
            //     $msg = 'A clínica cancelou o seu exame. ';
            //     $url = 'tabs/tab1/agendamentos/exame/'.$agendamento->id;
            //     if($request->motivo){
            //         $msg1 = 'Motivo: '.$request->motivo;
            //     }
            // }
            // event(new \App\Events\NotificacaoFCM(
            //     $title,
            //     $msg.$msg1,
            //     $url,
            //     $agendamento->usuario_id
            // ));
            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso',
                'text' => 'Agendamento cancelado com sucesso!',
                'data' => date('d/m/Y', strtotime($agendamento->data))
            ]);
            // }
        } catch (\Throwable $th) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Houve um erro ao estornar o pagamento!'
            ]);
        }
    }

    public function finalizar_agendamento(Request $request)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = Agendamento::find($request->id);

        // $pagarMe = new PagarMe();
        // $title ='';
        // $msg ='';
        // $url ='';
        try {


            $agendamento->update(['status' => 'finalizado']);
            $agendamento->atendimento()->update(['status' => '0']);
            $agendamento->criarLogEdicao($request->user('instituicao'), $request->session()->get('instituicao'));

            AuditoriaAgendamento::logAgendamento($agendamento->id, 'finalizado', $request->user('instituicao')->id, 'finalizar_agendamento');
            // if($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='consulta'){
            //     $title = 'Consulta #'.$agendamento->id;
            //     $msg = 'A clínica encerrou a sua consulta. Data:'.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->data)->format('d/m/Y H:i');
            //     $url = 'tabs/tab1/agendamentos/consulta/'.$agendamento->id;

            // }elseif($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='exame'){
            //     $title = 'Exame #'.$agendamento->id;
            //     $msg = 'A clínica encerrou o seu exame. Data:'.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->data)->format('d/m/Y H:i');
            //     $url = 'tabs/tab1/agendamentos/exame/'.$agendamento->id;

            // }
            // event(new \App\Events\NotificacaoFCM(
            //     $title,
            //     $msg,
            //     $url,
            //     $agendamento->usuario_id
            // ));

            
            /****  ENVIANDO PESQUISA DE SATISFAÇÃO WHATSAPP ******/

            //REGRA SE A INSTITUIÇÃO DESEJA ENVIAR
            if($instituicao->enviar_pesquisa_satisfacao_atendimentos == 1):

                //REGRA SE O PROCEIMENTO PERMITE ENVIAR
                $procedimentosAgendamento = Agendamento::where('id', $request->id)
                    ->with('agendamentoProcedimento')
                    ->with('agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento')
                    ->first()
                    ->toArray();

                //vamos percorrer os procedimentos para ver se é para enviar a pesquisa
                $enviar_pesquisa = 1;

                if(!empty($procedimentosAgendamento['agendamento_procedimento'])):

                    foreach ($procedimentosAgendamento['agendamento_procedimento'] as $proc_agendamento) {
                        if($proc_agendamento['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['pesquisa_satisfacao'] == 1):
                            $enviar_pesquisa = 0;
                        endif;
                    }

                endif;


                if($enviar_pesquisa == 1):
                    $this->enviar_pesquisa_satisfacao($request, $agendamento);
                endif;

            endif;

            /***** FIM ENVIAR PESQUISA DE SATISFAÇÃO ****/

            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso',
                'text' => 'Agendamento finalizado com sucesso!',
                'data' => date('d/m/Y', strtotime($agendamento->data))
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Houve um erro ao finalizar o agendamento!'
            ]);
        }
    }

    public function finalizar_atendimento(Request $request)
    {

        $agendamento = Agendamento::find($request->id);

        // $agendamento = Agendamento::where('id', $request->id)->with('usuario')->first();

        // dd($request->id, $agendamento);

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        // $pagarMe = new PagarMe();
        // $title ='';
        // $msg ='';
        // $url ='';
        try {
            $status = ($instituicao->finalizar_consultorio == 1) ? 'finalizado' : 'finalizado_medico';
            $agendamento->update(['status' => $status]);
            $agendamento->criarLogEdicao($request->user('instituicao'), $request->session()->get('instituicao'));
            $agendamento->atendimento()->update(['status' => '0']);
            AuditoriaAgendamento::logAgendamento($agendamento->id, $status, $request->user('instituicao')->id, 'finalizar_atendimento');
            // if($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='consulta'){
            //     $title = 'Consulta #'.$agendamento->id;
            //     $msg = 'A clínica encerrou a sua consulta. Data:'.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->data)->format('d/m/Y H:i');
            //     $url = 'tabs/tab1/agendamentos/consulta/'.$agendamento->id;

            // }elseif($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo=='exame'){
            //     $title = 'Exame #'.$agendamento->id;
            //     $msg = 'A clínica encerrou o seu exame. Data:'.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->data)->format('d/m/Y H:i');
            //     $url = 'tabs/tab1/agendamentos/exame/'.$agendamento->id;

            // }
            // event(new \App\Events\NotificacaoFCM(
            //     $title,
            //     $msg,
            //     $url,
            //     $agendamento->usuario_id
            // ));

            /****  ENVIANDO PESQUISA DE SATISFAÇÃO WHATSAPP ******/

            //REGRA SE A INSTITUIÇÃO DESEJA ENVIAR
            if($instituicao->enviar_pesquisa_satisfacao_atendimentos == 1):

                //REGRA SE O PROCEIMENTO PERMITE ENVIAR
                $procedimentosAgendamento = Agendamento::where('id', $request->id)
                    ->with('agendamentoProcedimento')
                    ->with('agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento')
                    ->first()
                    ->toArray();

                //vamos percorrer os procedimentos para ver se é para enviar a pesquisa
                $enviar_pesquisa = 1;

                if(!empty($procedimentosAgendamento['agendamento_procedimento'])):

                    foreach ($procedimentosAgendamento['agendamento_procedimento'] as $proc_agendamento) {
                        if($proc_agendamento['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['pesquisa_satisfacao'] == 1):
                            $enviar_pesquisa = 0;
                        endif;
                    }

                endif;


                if($enviar_pesquisa == 1):
                    $this->enviar_pesquisa_satisfacao($request, $agendamento);
                endif;

            endif;

            /***** FIM ENVIAR PESQUISA DE SATISFAÇÃO ****/



            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso',
                'text' => 'Atendimento finalizado com sucesso!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Houve um erro ao finalizar o agendamento!'
            ]);
        }
    }

    /* ENVIANDO PESQUISA DE SATISFAÇÃO WHATSAPP*/
    public function enviar_pesquisa_satisfacao(Request $request, $agendamento)
    {
        $paciente = Pessoa::where(['id' => $agendamento->pessoa_id])->first();

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        if(!empty($paciente->telefone1) && $agendamento->envio_pesquisa_satisfacao_whatsapp == 0):


            //TRATANDO O TELEFONE
                $telefone = str_replace('(', '', $paciente->telefone1);
                $telefone = str_replace(')', '', $telefone);
                $telefone = str_replace(' ', '', $telefone);
                $telefone = str_replace('-', '', $telefone);

            //VAMOS INSERIR NA FILA DO CLIENTE PARA DISPARO PELO KENTRO

            $msg = str_replace('{paciente}', ucwords($paciente->nome), $instituicao->kentro_msg_pesquisa_satisfacao);
            $msg = str_replace('{empresa}', $instituicao->nome, $msg);

            $envio = array(
                'queue_id' => $instituicao->kentro_fila_empresa,
                'number' => $telefone,
                'text' => $msg,
                'status' => 0,
            );

            // echo '<pre>';
            // print_r($paciente);
            // exit;

            //REGISTRANDO NO BANCO KENTRO E ATUALIZANDO NO BANCO LOCAL COMO ENTREGUE
            if (DB::connection('kentro')->table('autosend')->insert($envio)) :


                DB::table('agendamentos')
                            ->where('id',  $agendamento->id)
                            ->update(array(
                                'envio_pesquisa_satisfacao_whatsapp' => 1,
                                'data_hora_envio_pesquisa_satisfacao_whatsapp' => date('Y-m-d H:i:s')
                ));

                endif;

            endif;
    }



    public function confirmar_agendamento(Request $request)
    {

        $agendamento = Agendamento::find($request->id);

        $pagarMe = new PagarMe();
        $title = '';
        $msg = '';
        $url = '';
        try {


            if($agendamento->status == "ausente"){
                $instituicao = Instituicao::find($request->session()->get('instituicao'));
                if($instituicao->ausente_agenda){
                    if($agendamento->data_original){
                        $agendamento->update(['data_original' => null, 'data_final_original' => null, 'data' => $agendamento->data_original, 'data_final' => $agendamento->data_final_original]);
                    }
                }
            }
            $agendamento->update(['status' => 'confirmado']);
            $agendamento->criarLogEdicao($request->user('instituicao'), $request->session()->get('instituicao'));
            AuditoriaAgendamento::logAgendamento($agendamento->id, 'confirmado', $request->user('instituicao')->id, 'confirmar_agendamento');
            if ($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo == 'consulta') {
                // $title = 'Consulta #'.$agendamento->id;
                // $msg = 'A clínica agendou a sua consulta. Data:'.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->data)->format('d/m/Y H:i');
                // $url = 'tabs/tab1/agendamentos/consulta/'.$agendamento->id;

            } elseif ($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo == 'exame') {
                // $title = 'Exame #'.$agendamento->id;
                // $msg = 'A clínica agendou o seu exame. Data:'.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->data)->format('d/m/Y H:i');
                // $url = 'tabs/tab1/agendamentos/exame/'.$agendamento->id;

            }
            // event(new \App\Events\NotificacaoFCM(
            //     $title,
            //     $msg,
            //     $url,
            //     $agendamento->usuario_id
            // ));
            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso',
                'text' => 'Agendamento confirmado com sucesso!',
                'data' => date('d/m/Y', strtotime($agendamento->data))
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Houve um erro ao confirmar o agendamento!'
            ]);
        }
    }

    public function iniciar_atendimento(Request $request)
    {
        $agendamento = Agendamento::find($request->id);

        $valor_atual = $agendamento->agendamentoProcedimento->sum('valor_atual');

        if ($valor_atual > 0) {
            if (empty($agendamento->contaReceber()->first())) {
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Adicione uma forma de pagamento!'
                ]);
            }
        }

        $dados = [
            'agendamento_id' => $agendamento->id,
            'pessoa_id' => $agendamento->pessoa->id,
            'data_hora' => date('Y-m-d H:i'),
            'tipo' => 1,
            'status' => 1,
        ];

        DB::transaction(function () use ($dados, $request, $agendamento) {
            if($agendamento->status == "ausente"){
                $instituicao = Instituicao::find($request->session()->get('instituicao'));
                if($instituicao->ausente_agenda){
                    if($agendamento->data_original){
                        $agendamento->update(['data_original' => null, 'data_final_original' => null, 'data' => $agendamento->data_original, 'data_final' => $agendamento->data_final_original]);
                    }
                }
            }

            $agendamento->update([
                'status' => 'agendado',
                'carteirinha_id' => $request->input('carteirinha_id'),
                'tipo_guia' => $request->input("tipo_guia"),
                'num_guia_convenio' => $request->input("num_guia_convenio"),
                'cod_aut_convenio' => $request->input("cod_aut_convenio"),
            ]);
            $atendimento = AgendamentoAtendimento::create($dados);
            $agendamento->criarLogEdicao($request->user('instituicao'),
            $request->session()->get('instituicao'));

            $atendimento->criarLogCadastro(
                $request->user('instituicao'),
                $request->session()->get('instituicao')
            );
            AuditoriaAgendamento::logAgendamento($agendamento->id, 'agendado', $request->user('instituicao')->id, 'iniciar_atendimento');
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Atendimento confirmado com sucesso!',
            'data' => date('d/m/Y', strtotime($agendamento->data))
        ]);
    }

    public function reativar_agendamento(Request $request)
    {
        $agendamento = Agendamento::find($request->id);

        DB::transaction(function () use ($agendamento, $request) {
            if($agendamento->status == "cancelado"){
                $instituicao = Instituicao::find($request->session()->get('instituicao'));
                if($instituicao->ausente_agenda){
                    if($agendamento->data_original){
                        $agendamento->update(['data_original' => null, 'data_final_original' => null, 'data' => $agendamento->data_original, 'data_final' => $agendamento->data_final_original]);
                    }
                }
            }

            $agendamento->update(['status' => 'pendente']);

            $agendamento->criarLogEdicao($request->user('instituicao'), $request->session()->get('instituicao'));
            AuditoriaAgendamento::logAgendamento($agendamento->id, 'agendado', $request->user('instituicao')->id, 'reativar_agendamento');
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Agendamento reativado confirmado com sucesso!',
            'data' => date('d/m/Y', strtotime($agendamento->data))
        ]);
    }

    public function remover_agendamento(Request $request)
    {
        $agendamento = Agendamento::find($request->id);

        DB::transaction(function () use ($agendamento, $request) {
            $agendamento->update(['status' => 'excluir']);

            $agendamento->criarLogEdicao($request->user('instituicao'), $request->session()->get('instituicao'));
            AuditoriaAgendamento::logAgendamento($agendamento->id, 'excluir', $request->user('instituicao')->id, 'remover_agendamento');

            $referentes = $agendamento->agendamentosReferentes()->get();
            if(count($referentes) > 0){
                foreach ($referentes as $key => $value) {
                    $value->delete();
                    $value->criarLogExclusao($request->user('instituicao'), $request->session()->get('instituicao'));
                }
            }
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Agendamento reativado confirmado com sucesso!',
            'data' => date('d/m/Y', strtotime($agendamento->data))
        ]);
    }

    public function ausente_agendamento(Request $request)
    {
        $agendamento = Agendamento::find($request->id);

        DB::transaction(function () use ($agendamento, $request) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            if($instituicao->ausente_agenda){
                $nova_data = date('Y-m-d', strtotime($agendamento->data)).' 23:00:00';
                $nova_data_final = date('Y-m-d', strtotime($agendamento->data)).' 23:00:00';
                $agendamento->update(['status' => 'ausente', 'status_motivo' => $request->input('motivo'), 'data_original' => $agendamento->data, 'data_final_original' => $agendamento->data_final, 'data' => $nova_data, 'data_final' => $nova_data_final]);
            }else{
                $agendamento->update(['status' => 'ausente', 'status_motivo' => $request->input('motivo')]);
            }

            $agendamento->criarLogEdicao($request->user('instituicao'), $request->session()->get('instituicao'));
            AuditoriaAgendamento::logAgendamento($agendamento->id, 'ausente', $request->user('instituicao')->id, 'ausente_agendamento');
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Agendamento reativado confirmado com sucesso!',
            'data' => date('d/m/Y', strtotime($agendamento->data))
        ]);
    }

    public function alterar_horario(Request $request) {
        
        $agendamento = Agendamento::find($request->id);
        $title ='';
        $msg ='';
        $url ='';
        $usuario_logado = $request->user('instituicao');
        $instituicao_id = $request->session()->get('instituicao');

        if($agendamento->instituicoesAgenda->instituicoes_prestadores_id == $request->input('prestador_id')){
            try {
                //PEGA A DURAÇÃO PARA SETAR A DATA FINAL
                $data_atual = date('d/m/Y', strtotime($request->data));
                $dia_semana = explode("-", \Carbon\Carbon::createFromFormat('d/m/Y', $data_atual)->dayName)[0];
                $data = \Carbon\Carbon::createFromFormat('d/m/Y', $data_atual)->format('d/m/Y');

                $agenda = InstituicoesAgenda::whereRaw("json_contains(`dias_unicos`, '".json_encode(['date' => $data])."')")
                    ->where('instituicoes_prestadores_id', $request->input('prestador_id'))
                    ->first();
                
                if ($agenda) {
                    foreach (json_decode($agenda->dias_unicos) as $valueJson) {
                        if ($valueJson->date == date('d/m/Y', strtotime($request->data))) {
                            $duracao = explode(':', $valueJson->duracao_atendimento);
                            $duracao_tempo = $duracao[0]*60+$duracao[1];
                        }
                    }
                } else {
                    $agenda = InstituicoesAgenda::where(function ($q) use ($request) {
                            $q->where('instituicoes_prestadores_id', $request->input('prestador_id'));
                        })
                        // ->where('hora_inicio', '<=', date('H:i:s', strtotime($request->data)))
                        // ->where('hora_fim', '>=', date('H:i:s', strtotime($request->data)))
                        ->where('dias_continuos', $dia_semana)
                        ->where('id', $request->idAgenda)
                        ->first();
                        
                    $duracao = explode(':', $agenda->duracao_atendimento);
                    $duracao_tempo = $duracao[0]*60+$duracao[1];
                }


                //VERIFICA SE EXISTE ALGUM AGENDAMENTO OCUPANDO ESSE HORARIO
                $horarioCancelado = Agendamento::whereNotNull('id_referente')->where('data', $request->data)->where('instituicoes_agenda_id', $agenda->id)->first();

                if(!empty($horarioCancelado)){
                    $agendamentoReferente = Agendamento::find($horarioCancelado->id_referente);
                    $data_inicio = $agendamentoReferente->data;
                    $data_final = date('Y-m-d H:i:s', strtotime(" +".$duracao_tempo." minutes", strtotime($data_inicio)));

                    $data_original = $request->data;
                    $data_final_original = date('Y-m-d H:i:s', strtotime(" +".$duracao_tempo." minutes", strtotime($request->data)));

                    $agendamento->update(['status'=>'pendente', 'data' => $data_inicio, 'data_final' => $data_final, 'data_original' => $data_original,'data_final_original' => $data_final_original, 'instituicoes_agenda_id' => $agenda->id, 'tipo_agenda' => "encaixe"]);
                    $agendamento->criarLogEdicao($usuario_logado, $instituicao_id);

                    if(count($agendamento->atendimento) > 0){
                        foreach ($agendamento->atendimento as $key => $value) {
                            $value->delete();
                        }
                    }

                    AuditoriaAgendamento::logAgendamento($agendamento->id, 'pendente', $request->user('instituicao')->id, 'alterar_horario', "Alterado de: [{$agendamento->data}] para: [{$request->data}]");

                }else{
                    $horarioExiste = Agendamento::whereNull('id_referente')->where('data', $request->data)->where('instituicoes_agenda_id', $agenda->id)->first();
                    $tipo_agenda = "normal";
                    if(!empty($horarioExiste)){
                        $tipo_agenda = "encaixe";
                    }

                    $data_final = date('Y-m-d H:i:s', strtotime(" +".$duracao_tempo." minutes", strtotime($request->data)));

                    $agendamento->update(['status'=>'pendente', 'data' => $request->data, 'data_final' => $data_final, 'instituicoes_agenda_id' => $agenda->id, 'tipo_agenda' => $tipo_agenda]);
                    $agendamento->criarLogEdicao($usuario_logado, $instituicao_id);

                    if(count($agendamento->atendimento) > 0){
                        foreach ($agendamento->atendimento as $key => $value) {
                            $value->delete();
                        }
                    }

                    AuditoriaAgendamento::logAgendamento($agendamento->id, 'pendente', $request->user('instituicao')->id, 'alterar_horario', "Alterado de: [{$agendamento->data}] para: [{$request->data}]");
                }

                $agendamento->agendamentosReferentes()->delete();

                return response()->json([
                        'icon' => 'success',
                        'title' => 'Sucesso',
                        'text' => 'Horário alterado com sucesso!',
                        'data' => date('d/m/Y', strtotime($agendamento->data))
                    ]);

            // } catch (\Throwable $th) {
            //     return response()->json([
            //         'icon' => 'error',
            //         'title' => 'Erro',
            //         'text' => 'Houve um erro ao alterar o horário!'
            //     ]);
            // }
            }catch(Exception $e){

            }
        }else{
            $dia_semana = explode("-",\Carbon\Carbon::parse($request->data)->dayName)[0];
            $data = \Carbon\Carbon::parse($request->data)->format('d/m/Y');

            $agenda = InstituicoesAgenda::where('instituicoes_prestadores_id',$request->prestador_id)
            // ->whereJsonContains("dias_unicos", ['date' => $data])
            ->whereRaw("json_contains(`dias_unicos`, '".json_encode(['date' => $data])."')")
            ->first();

            if(empty($agenda)){
                $agenda = InstituicoesAgenda::where('instituicoes_prestadores_id',$request->prestador_id)
                ->where('dias_continuos',$dia_semana)
                ->where('id', $request->idAgenda)
                ->first();
            }

            if(!empty($agenda)){
                if($agenda->dias_unicos){
                    foreach (json_decode($agenda->dias_unicos) as $valueJson) {
                        if ($valueJson->date == date('d/m/Y', strtotime($request->data))) {
                            $duracao = explode(':', $valueJson->duracao_atendimento);
                            $duracao_tempo = $duracao[0]*60+$duracao[1];
                        }
                    }
                }else{
                    $duracao = explode(':', $agenda->duracao_atendimento);
                    $duracao_tempo = $duracao[0]*60+$duracao[1];
                }
            }

            if(empty($agenda)){
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Erro',
                    'text' => 'Houve um erro ao alterar o horário, agenda prestador não existe!'
                ]);
            }else{
                try {

                    //VERIFICA SE EXISTE ALGUM AGENDAMENTO OCUPANDO ESSE HORARIO
                    $horarioCancelado = Agendamento::whereNotNull('id_referente')->where('data', $request->data)->where('instituicoes_agenda_id', $agenda->id)->first();

                    $data_inicio = $request->data;
                    $data_final = date('Y-m-d H:i:s', strtotime(" +".$duracao_tempo." minutes", strtotime($data_inicio)));

                    $data_original = null;
                    $data_final_original = null;

                    if(!empty($horarioCancelado)){
                        $agendamentoReferente = Agendamento::find($horarioCancelado->id_referente);
                        $data_inicio = $agendamentoReferente->data;
                        $data_final = date('Y-m-d H:i:s', strtotime(" +".$duracao_tempo." minutes", strtotime($data_inicio)));

                        $data_original = $request->data;
                        $data_final_original = date('Y-m-d H:i:s', strtotime(" +".$duracao_tempo." minutes", strtotime($request->data)));
                    }


                    $prestadorAntigo = $agendamento->instituicoesAgenda->prestadores->prestador->nome;
                    $agendamento->update(['status'=>'pendente', 'data' => $data_inicio, 'data_final' => $data_final, 'data_original' => $data_original,'data_final_original' => $data_final_original, 'instituicoes_agenda_id' => $agenda->id]);
                    $prestadorNovo = $agendamento->instituicoesAgenda->prestadores->prestador->nome;
                    $agendamento->criarLogEdicao($usuario_logado, $instituicao_id);
                    AuditoriaAgendamento::logAgendamento($agendamento->id, 'pendente', $request->user('instituicao')->id, 'alterar_horario', "Alterado de: [{$agendamento->data}] para: [{$request->data}], prestador: [{$prestadorAntigo}] para: [{$prestadorNovo}]");

                    $agendamento->agendamentosReferentes()->delete();

                    return response()->json([
                            'icon' => 'success',
                            'title' => 'Sucesso',
                            'text' => 'Horário alterado com sucesso!'
                        ]);

                } catch (\Throwable $th) {
                    return response()->json([
                        'icon' => 'error',
                        'title' => 'Erro',
                        'text' => 'Houve um erro ao alterar o horário!'
                    ]);
                }
            }
        }

    }

    public function cancelar_horario(Request $request)
    {
        // dd($request->all());
        $usuario_logado = $request->user('instituicao');
        $instituicao_id = $request->session()->get('instituicao');
        try {
            $agendamento = Agendamento::create([
                'instituicoes_agenda_id' => $request->agenda,
                'data' => $request->horario,
                'free_parcelas' => '1',
                'status' => 'cancelado',
                'motivo_cancelamento' => $request->motivo,
            ]);

            AuditoriaAgendamento::logAgendamento($agendamento->id, 'cancelado', $request->user('instituicao')->id, 'cancelar_horario', $request->motivo);
            $agendamento->criarLogEdicao($usuario_logado, $instituicao_id);
            return response()->json([
                    'icon' => 'success',
                    'title' => 'Sucesso',
                    'text' => 'Horário cancelado com sucesso!',
                    'data' => date('d/m/Y', strtotime($agendamento->data))
                ]);

        } catch (\Throwable $th) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Houve um erro ao cancelar o horário!'
            ]);
        }
    }

    public function reativarHorario(Request $request, Agendamento $agendamento){
        // dd($agendamento, $request->all());
        $usuario_logado = $request->user('instituicao');
        $instituicao_id = $request->session()->get('instituicao');
        try {
            $agendamento->delete();
            $agendamento->criarLogEdicao($usuario_logado, $instituicao_id);

            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso',
                'text' => 'Horário reativado com sucesso!',
                'data' => date('d/m/Y', strtotime($agendamento->data))
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Houve um erro ao reativar o horário!'
            ]);
        }

    }

    public function modalRemarcar(Request $request)
    {
        $dia_semana = explode("-", \Carbon\Carbon::createFromFormat('d/m/Y', $request->data)->dayName)[0];
        $data = \Carbon\Carbon::createFromFormat('d/m/Y', $request->data)->format('d/m/Y');

        $agendDiaUnico = InstituicoesAgenda::when($request->prestador_especialidade_id != null, function ($q) use ($request) {
                $q->where('instituicoes_prestadores_id', $request->prestador_especialidade_id);
            })
            ->when($request->procedimento_instituicao_id != null, function ($q) use ($request) {
                $q->where('procedimentos_instituicoes_id', $request->procedimento_instituicao_id);
            })
            // where(function($q) use ($request){
            //     $q->where('instituicoes_prestadores_id',$request->prestador_especialidade_id)
            //     ->orWhere('procedimentos_instituicoes_id', $request->procedimento_instituicao_id);
            // })
            ->whereRaw("json_contains(`dias_unicos`, '".json_encode(['date' => $data])."')")
            // ->whereJsonContains("dias_unicos", ['date' => $data])
            ->first();
        // dd($agenda);
        if ($agendDiaUnico) {
            $dados = array_filter(
                JSON_DECODE($agendDiaUnico->dias_unicos),
                function ($e) use ($data) {
                    return $e->date == $data;
                }
            );
            usort($dados, function($a, $b) {
                $datetime1 = strtotime($a->hora_inicio);
                $datetime2 = strtotime($b->hora_inicio);
                return $datetime1 - $datetime2;
            });

            foreach ($dados as $key => $value) {
                $agendaUnica[] = [
                    'id' => $agendDiaUnico->id,
                    'hora_inicio' => $value->hora_inicio.':00',
                    'hora_fim' => $value->hora_fim.':00',
                    'hora_intervalo' => $value->hora_intervalo.':00',
                    'duracao_intervalo' => $value->duracao_intervalo.':00',
                    'duracao_atendimento' => $value->duracao_atendimento.':00',
                ];
            }

            $agenda = !empty($agendaUnica) ? $agendaUnica : [];
            // $agenda->hora_inicio = $dados[0]->hora_inicio . ':00';
            // $agenda->hora_fim = $dados[0]->hora_fim . ':00';
            // $agenda->hora_intervalo = $dados[0]->hora_intervalo . ':00';
            // $agenda->duracao_intervalo = $dados[0]->duracao_intervalo . ':00';
            // $agenda->duracao_atendimento = $dados[0]->duracao_atendimento . ':00';

        } else {
            $agendaDiaContinuo = InstituicoesAgenda::where(function ($q) use ($request) {
                    $q->where('instituicoes_prestadores_id', $request->prestador_especialidade_id);
                    // ->orWhere('procedimentos_instituicoes_id', $request->procedimento_instituicao_id);
                })
                ->where('dias_continuos', $dia_semana)
                ->get();

            foreach ($agendaDiaContinuo as $key => $value) {
                $agendaUnica[] = [
                    'id' => $value->id,
                    'hora_inicio' => $value->hora_inicio,
                    'hora_fim' => $value->hora_fim,
                    'hora_intervalo' => $value->hora_intervalo,
                    'duracao_intervalo' => $value->duracao_intervalo,
                    'duracao_atendimento' => $value->duracao_atendimento,
                ];
            }

            $agenda = !empty($agendaUnica) ? $agendaUnica : [];
        }
        // dd($agenda);
        // $agenda = InstituicoesAgenda::
        // where('instituicoes_prestadores_id',$request->prestador_especialidade_id)
        // ->where('dias_continuos',$dia_semana)
        // ->first();

        $agendamentos = Agendamento::whereHas('instituicoesAgenda', function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->whereHas('prestadores', function ($q) use ($request) {
                    $q->where('instituicoes_prestadores_id', $request->prestador_especialidade_id);
                });
            })
                ->orWhere(function ($q) use ($request) {
                    $q->whereHas('procedimentos', function ($q) use ($request) {
                        $q->where('id', $request->procedimento_instituicao_id);
                    });
                });
        })
        ->whereDate('data',\Carbon\Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d'))
        ->whereNotIn('status', ['excluir', 'cancelado'])
        ->with(['agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimento','agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios'])->get();

        return view("instituicao.agendamentos/modalRemarcar", compact('agenda', 'agendamentos', 'data'))->render();
    }

    public function modalInserirAgenda(Request $request)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $desconto_maximo = $instituicao->instituicaoUsuarios()->where('usuario_id', $request->user('instituicao')->id)->first()->pivot->desconto_maximo;
        $tipo_inserir = ($request->input('tipo')) ? $request->input('tipo') : 'normal';
        $paciente_lista = ($request->input('paciente_lista')) ? $request->input('paciente_lista') : null;
        $lista_id = ($request->input('lista_id')) ? $request->input('lista_id') : null;
        $data = $request->input('data');
        $dataSemana = Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
        setlocale(LC_TIME, 'pt-br');
        $diaSemana = $this->retornaData(strftime("%u", strtotime($dataSemana)));
        $hora = $request->input('horario');
        // $pacientes = $instituicao->instituicaoPessoas()->get();
        $campos_obg = json_decode($instituicao->config);
        $campos_obg = (!empty($campos_obg->pessoas)) ? $campos_obg->pessoas : null;

        $convenios = [];

        $paciente = null;
        if($paciente_lista){
            $paciente = Pessoa::where('id', $paciente_lista)->where('instituicao_id', $instituicao->id)->first();
        }
        
        $agendamentos = Agendamento::where('data',date("Y-m-d H:i:00", strtotime(str_replace("/", "-", $data." ".$hora))))
            ->whereHas('instituicoesAgendaGeral', function($q) use($request){
            // $q->when($this->prestador_especialidade_id, function($q){
                $q->whereHas('prestadores',function($q) use($request){
                    $q->where('instituicoes_prestadores_id', $request->input('prestador_especialidade_id'));
                });
            })
            ->where('status', 'cancelado')
            ->whereNull('pessoa_id')
            ->get();

        if($agendamentos->count() > 0 && $tipo_inserir != 'avulso'){
            return response()->json([
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Impossivel inserir agenda para este horario. o horario foi cancelado'
            ]);
        }
        $verifica_agenda = 'normal';
        
        if($tipo_inserir != 'avulso'){
            $inst_prestador = InstituicoesPrestadores::where('id', $request->input('prestador_especialidade_id'))->first();
            $agendaInserir = InstituicoesAgenda::where('id', $request->input('id'))->with(['convenios' => function ($query) {
                $query->where('ativo', 1);
                $query->orderBy('nome', 'ASC');
            }])->first();
        }else{
            
            $inst_prestador = InstituicoesPrestadores::where('id', $request->input('prestador_especialidade_id'))->with(['agenda' => function ($query) use ($data) {
                // $query->whereJsonContains('dias_unicos', ['date' => $data]);
                $query->whereRaw("json_contains(`dias_unicos`, '".json_encode(['date' => $data])."')");
            }])->first();
            
            if(sizeof($inst_prestador->agenda) > 0){
                $agendaInserir = $inst_prestador->agenda[0];
                $verifica_agenda = "unica";
            }

            if(sizeof($inst_prestador->agenda) == 0){
                // dd($hora, $diaSemana);
                $inst_prestador = InstituicoesPrestadores::where('id', $request->input('prestador_especialidade_id'))->with(['agenda' => function ($query) use ($diaSemana, $hora) {
                    // $query->where('hora_inicio', '<=', $hora);
                    // $query->where('hora_fim', '>=', $hora);
                    $query->where('dias_continuos', $diaSemana);
                    $query->with(['convenios' => function ($query) {
                        $query->where('ativo', 1);
                        $query->orderBy('nome', 'ASC');
                    }]);
                }])->first();

                $agendaInserir = $inst_prestador->agenda[0];
                // dd($inst_prestador->toArray());
            }
        }
        
        $hora_fim = null;
        $duracao[0] = 0;
        $duracao[1] = 20;



        if($agendaInserir->instituicoes_prestadores_id != $inst_prestador->id){
            return response()->json([
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Agenda prestador não existe!'
            ]);
        }

        if (!empty($agendaInserir) && $agendaInserir->dias_unicos != null) {
            // foreach ($agendaInserir->toArray() as $value) {
                foreach (json_decode($agendaInserir->toArray()['dias_unicos']) as $valueJson) {
                    if (isset($valueJson->convenio_id_unico) & $valueJson->date == $data) {
                        $convenios = Convenio::whereIn('id', $valueJson->convenio_id_unico)->where('ativo', 1)->orderBy('nome', 'ASC')->get();
                        $duracao = explode(":",$valueJson->duracao_atendimento);
                    }
                }
            // }
        }

        if (!empty($agendaInserir) && $agendaInserir->dias_unicos == null) {
            // $inst_prestador = InstituicoesPrestadores::where('id', $request->input('prestador_especialidade_id'))->with(['agenda' => function ($query) use ($diaSemana) {
            //     $query->where('dias_continuos', $diaSemana);
                // $query->with(['convenios' => function ($query) {
                //     $query->where('ativo', 1);
                //     $query->orderBy('nome', 'ASC');
                // }]);
            // }])->first();

            $convenios = $agendaInserir->convenios;
            $duracao = explode(":",$agendaInserir->duracao_atendimento);
        }
        $total_minutos = 0;
        if($duracao[0] > 0){
            $total_minutos = $duracao[0]*60+$duracao[1];
        }else{
            $total_minutos = $duracao[1];
        }

        $hora_fim = date('H:i', strtotime(" +".$total_minutos." minutes", strtotime($hora)));

        $referencia_relacoes = Pessoa::getRelacoesParentescos();
        $compromissos = $instituicao->compromissos()->get();

        return view("instituicao.agendamentos/modalInserirAgenda", \compact('inst_prestador','data','hora', 'convenios', 'referencia_relacoes', 'campos_obg', 'compromissos', 'hora_fim', 'total_minutos', 'tipo_inserir', 'agendaInserir', 'verifica_agenda', 'instituicao', 'paciente', 'lista_id', 'desconto_maximo'));
    }

    public function retornaData($dia)
    {
        if ($dia == 1) {
            return 'segunda';
        }
        if ($dia == 2) {
            return 'terca';
        }
        if ($dia == 3) {
            return 'quarta';
        }
        if ($dia == 4) {
            return 'quinta';
        }
        if ($dia == 5) {
            return 'sexta';
        }
        if ($dia == 6) {
            return 'sabado';
        }
        if ($dia == 7) {
            return 'domingo';
        }
    }

    public function getPacientes(Request $request)
    {
        if ($request->ajax()) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            $pacientes = $instituicao->instituicaoPessoas()->getPacientes($nome)->simplePaginate(100);

            $morePages = true;
            if (empty($pacientes->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $pacientes->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            return response()->json($results);
        }
    }

    public function getCarteirinhas(Request $request)
    {
        if ($request->ajax()) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';

            $pessoa = Pessoa::find($request->input('pessoa'));
            abort_unless($pessoa->instituicao_id === $instituicao->id, 403);

            $pacientes = $pessoa->carteirinha()->getCarteirinhas($nome)->simplePaginate(100);

            $morePages = true;
            if (empty($pacientes->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $pacientes->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($pacientes->per_page());
            return response()->json($results);
        }
    }

    public function editarCarteirinha(Request $request, Agendamento $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $pessoa = Pessoa::find($agendamento->pessoa_id);
        abort_unless($pessoa->instituicao_id === $instituicao->id, 403);

        $dados = $request->all();
        $carteirinha = $pessoa->carteirinha()->where('id', $request->input('carteirinha_id'))->first();

        if(!empty($carteirinha) || empty($dados['carteirinha_id'])){
            $dados_guia = $dados['guia'];
            
            $agendamento_guia = $agendamento->agendamentoGuias()->get();
            // dd($agendamento_guia);

            if($agendamento_guia->count() > 0){
                AgendamentoGuia::where('agendamento_id', $agendamento->id)->delete();
            }
            foreach($dados_guia as $values){
                // dd(collect($dados_guia));
                AgendamentoGuia::create($values);
            }

            $agendamento->update(['carteirinha_id' => $dados['carteirinha_id']]);
            $agendamento->criarLogEdicao($request->user('instituicao'), $instituicao->id);


            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso',
                'text' => 'Carteirinha alterada com sucesso!'
            ]);
            
        }else{
            return response()->json([
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Carteirinha não pertence ao paciente!'
            ]);
        }

    }

    public function getSolicitantes(Request $request)
    {
        if ($request->ajax())
        {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            // dd($request->page);
            $solicitantes = $instituicao->solicitantes()->where("nome", "like", "%{$nome}%")->simplePaginate(100);

            $morePages=true;
            if (empty($solicitantes->nextPageUrl())){
                $morePages=false;
            }

            $results = array(
                "results" => $solicitantes->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($pacientes->per_page());
            return response()->json($results);
        }
    }

    public function getPaciente(Request $request, Pessoa $pessoa)
    {
        abort_unless($pessoa->instituicao_id === $request->session()->get('instituicao'), 403);

        return response()->json($pessoa);
    }

    public function getConvenio(Request $request, Pessoa $pessoa)
    {

        abort_unless($pessoa->instituicao_id === $request->session()->get('instituicao'), 403);
        $carteirinha = Carteirinha::where('pessoa_id', $pessoa->id)->first();

        return response()->json($carteirinha);
    }

    public function getProcedimentos(Request $request, Convenio $convenio, Prestador $prestador)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $procedimentos = $instituicao->procedimentosInstituicoes()->whereHas('instituicaoProcedimentosConvenios', function ($query) use ($convenio) {
            $query->where('convenios_id', $convenio->id);
        })->with(['procedimento', 'instituicaoProcedimentosConvenios' => function ($query) use ($convenio) {
            $query->where('convenios_id', $convenio->id);
        }])
        ->get();

        foreach ($procedimentos as $k => $v) {
            $dataWhere = [
                'procedimento_instituicao_convenio_id' => $v->instituicaoProcedimentosConvenios[0]->pivot->id,
                'prestador_id' => $prestador->id
            ];

            $procConv = DB::table('procedimentos_convenios_has_repasse_medico')
                ->where($dataWhere)
                ->first();

            if (empty($procConv)) {
                unset($procedimentos[$k]);
            } else {
                if (!empty((float) $procConv->valor_cobrado)) {
                    $procedimentos[$k]->instituicaoProcedimentosConvenios[0]->pivot->valor =  $procConv->valor_cobrado;
                }
            }
        }

        // $procedimentoConvenio_id = $procedimentos[0]->instituicaoProcedimentosConvenios[0]->pivot->id;

        // $procConvenio = DB::table('procedimentos_convenios_has_repasse_medico')
        //     ->where('procedimento_instituicao_convenio_id', $procedimentoConvenio_id)
        //     ->where('prestador_id', $request->input('prestador'))
        //     ->first();

        // if(!empty($procConvenio) && !empty((float) $procConvenio->valor_cobrado)){
        //     $procedimentos[0]->instituicaoProcedimentosConvenios[0]->pivot->valor = $procConvenio->valor_cobrado;
        // }

        return response()->json($procedimentos);
    }

    public function salvarProcedimentoPaciente(SalvarAgendamentoBackendRequest $request)
    {
        $dados = $request->validated();
        $dados['proximo_horario_existe'] = $request->boolean('proximo_horario_existe');
        $instituicao_id = $request->session()->get('instituicao');

        $data_hora = $dados['data_agenda'] . ' ' . $dados['hora_agenda'];
        $data_hora_final = $dados['data_agenda'] . ' ' . $dados['hora_agenda_final'];

        $total_procedimentos = str_replace('.', '', $dados['total_procedimentos_agendar']);
        $total_procedimentos = str_replace(',', '.', $total_procedimentos);
        // Carbon::createFromFormat('d/m/Y H:i', $data_hora_final)->format('Y/m/d H:i:s')
        $agendamento_paciente = array(
            'tipo' => 'agendamento',
            'data' => Carbon::createFromFormat('d/m/Y H:i', $data_hora)->format('Y/m/d H:i:s'),
            'data_final' => Carbon::createFromFormat('d/m/Y H:i', $data_hora_final)->format('Y/m/d H:i:s'),
            'status' => 'pendente',
            'valor_total' => $total_procedimentos,
            'instituicoes_agenda_id' => $dados['inst_prest_id'],
            'pessoa_id' => $dados['paciente_agenda'], //criar paciente_id == pessoas
            'forma_pagamento' => 'dinheiro',
            'acompanhante' => (!empty($dados['acompanhante'])) ? 1 : 0,
            'acompanhante_relacao' => (!empty($dados['acompanhante'])) ? $dados['acompanhante_relacao'] : null,
            'acompanhante_nome' => (!empty($dados['acompanhante'])) ? $dados['acompanhante_nome'] : null,
            'acompanhante_telefone' => (!empty($dados['acompanhante'])) ? $dados['acompanhante_telefone'] : null,
            'cpf_acompanhante' => (!empty($dados['acompanhante'])) ? $dados['cpf_acompanhante'] : null,
            'obs' => $dados['obs'],
            'carteirinha_id' => (array_key_exists('carteirinha_id', $dados)) ? $dados['carteirinha_id'] : null,
            'compromisso_id' => (array_key_exists('compromisso_id', $dados)) ? $dados['compromisso_id'] : null,
            'tipo_agenda' => ($dados['tipo_inserir']) ? $dados['tipo_inserir'] : 'normal',
            'teleatendimento' => (!empty($dados['teleatendimento'])) ? $dados['teleatendimento'] : null,
        );


        $convenios = collect($dados['convenio'])
            ->filter(function ($convenio) {
                return !empty($convenio['procedimento_agenda']);
            })
            ->map(function ($convenio) {
                $desconto = 0;
                if (array_key_exists('desconto', $convenio)) {
                    $desconto = str_replace('.', '', $convenio['desconto']);
                    $desconto = str_replace(',', '.', $desconto);
                }
                return [
                    "valor" => $convenio['valor'],
                    "qtd_procedimento" => $convenio['qtd_procedimento'],
                    "procedimento_agenda" => $convenio['procedimento_agenda'],
                    'desconto' => $desconto
                ];
            });

        if ($dados['tipo_inserir'] == "avulso") {
            $dataSemana = Carbon::createFromFormat('d/m/Y', $dados['data_agenda'])->format('Y-m-d');
            setlocale(LC_TIME, 'pt-br');
            $diaSemana = $this->retornaData(strftime("%u", strtotime($dataSemana)));

            $agenda = InstituicoesAgenda::where('hora_inicio', '<=', date('H:i:s', strtotime($agendamento_paciente['data'])))
            ->where('hora_fim', '>=', date('H:i:s', strtotime($agendamento_paciente['data'])))
            ->where('dias_continuos', $diaSemana)
            ->where('instituicoes_prestadores_id', $dados['inst_prestador_id'])
            ->first();

            if(empty($agenda)){
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Horário inválido.',
                    'text' => "Selecione um horario válido!"
                ]);
            }
        } else {
            $agenda = InstituicoesAgenda::where('id', $dados['inst_prest_id'])->first();
        }
        
        $duracao = null;
        $value_cadastro_intervalo = false;
        //INICIO VERIFICAÇÃO DE HORARIO ESTRAPOLANDO
        if($agenda->dias_unicos){
            $hora = date('H:i', strtotime($agendamento_paciente['data']));
            foreach (json_decode($agenda->dias_unicos) as $valueJson) {
                if (isset($valueJson->convenio_id_unico) & $valueJson->date == date('d/m/Y', strtotime($agendamento_paciente['data'])) & $valueJson->hora_inicio <= $hora & $valueJson->hora_fim >= $hora) {
                    $duracao = explode(':', $valueJson->duracao_atendimento);
                    $duracao_tempo = $duracao[0]*60+$duracao[1];
                    $duracao_agenda = date('H:i', strtotime(" +".$duracao_tempo." minutes", strtotime($dados['hora_agenda'])));
                    $duracao_agenda_final = date('H:i', strtotime($dados['hora_agenda_final']));

                    $ultimo_horario_agenda = date('H:i', strtotime($valueJson->hora_fim));

                    $intervalo = explode(':', $valueJson->duracao_intervalo);
                    $duracao_intervalo = $intervalo[0]*60+$intervalo[1];
                    $duracao_intervalo_agenda = date('H:i', strtotime(" +".$duracao_intervalo." minutes", strtotime($valueJson->hora_intervalo)));

                    if($valueJson->hora_intervalo <= date('H:i:s', strtotime($agendamento_paciente['data'])) && $duracao_intervalo_agenda >= date('H:i:s', strtotime($agendamento_paciente['data']))){
                        $value_cadastro_intervalo = true;
                    }
                }
            }

        }else{
            //VERIFICA DIA CONTINUO
            $duracao = explode(':', $agenda->duracao_atendimento);
            $duracao_tempo = $duracao[0]*60+$duracao[1];
            $duracao_agenda = date('H:i', strtotime(" +".$duracao_tempo." minutes", strtotime($dados['hora_agenda'])));
            $duracao_agenda_final = date('H:i', strtotime($dados['hora_agenda_final']));

            $ultimo_horario_agenda = date('H:i', strtotime($agenda->hora_fim));

            $intervalo = explode(':', $agenda->duracao_intervalo);
            $duracao_intervalo = $intervalo[0]*60+$intervalo[1];
            $duracao_intervalo_agenda = date('H:i', strtotime(" +".$duracao_intervalo." minutes", strtotime($agenda->hora_intervalo)));

            if($agenda->hora_intervalo <= date('H:i:s', strtotime($agendamento_paciente['data'])) && $duracao_intervalo_agenda >= date('H:i:s', strtotime($agendamento_paciente['data']))){
                $value_cadastro_intervalo = true;
            }
        }


        if(empty($agenda) || $duracao == null){
            return response()->json([
                'icon' => 'error',
                'title' => 'Horário inválido.',
                'text' => "Selecione um horario válido!"
            ]);
        }

        $i = DB::transaction(function() use($dados, $agendamento_paciente, $request, $instituicao_id, $convenios, $data_hora_final, $duracao_agenda_final, $duracao_agenda, $ultimo_horario_agenda, $agenda, $duracao_tempo, $value_cadastro_intervalo, $duracao_intervalo_agenda, $duracao) {
            $usuario_logado = $request->user('instituicao');

            if(array_key_exists('lista_paciente', $dados)){
                if($dados['lista_paciente']){
                    $listaEspera = AgendamentoListaEspera::where('id', $dados['lista_paciente'])->where('instituicao_id', $instituicao_id)->first();
                    $listaEspera->update(['status' => 1]);
                    $listaEspera->criarLogEdicao($usuario_logado, $instituicao_id);
                }
            }

            if (!preg_match('/^\d+$/', $dados['paciente_agenda'])) {
                $instituicao = Instituicao::find($instituicao_id);

                $data = [
                    'nome' => $dados['paciente_agenda'],
                    'telefone1' => $dados['telefone_paciente_agenda'],
                    'nascimento' => $dados['data_paciente_agenda'],
                    'personalidade' => 1,
                    'tipo' => 2,
                    'cep' => $dados['cep'],
                    'estado' => $dados['estado'],
                    'cidade' => $dados['cidade'],
                    'bairro' => $dados['bairro'],
                    'rua' => $dados['rua'],
                    'numero' => $dados['numero'],
                    'complemento' => $dados['complemento'],
                    "cpf" => $dados['cpf'],
                    "telefone2" => $dados['telefone2'],
                    "telefone3" => $dados['telefone3'],
                    "sexo" => $dados['sexo'],
                ];

                if(!empty($data['cpf'])){
                    $paciente = Pessoa::where(['cpf' => $data['cpf'], 'instituicao_id' => $instituicao_id])->first();

                    if(!empty($paciente)){
                        return [
                            'icon' => 'error',
                            'title' => 'Falha.',
                            'text' => "CPF encontra-se cadastrado para paciente #{$paciente->id} {$paciente->nome}!"
                        ];
                    }
                }

                $confg_paciente = (!empty(json_decode($instituicao->config)->pessoas)) ? json_decode($instituicao->config)->pessoas : null;
                if(!empty($confg_paciente)){
                    foreach($confg_paciente as $campo => $ativo){
                        if($campo == "endereco"){
                            if(empty($data['cep']) || empty($data['estado']) || empty($data['cidade']) || empty($data['bairro']) || empty($data['rua']) || empty($data['numero']))
                            return [
                                'icon' => 'error',
                                'title' => 'Falha.',
                                'text' => 'Campos referente ao endereço devem ser preenchidos'
                            ];
                        }else if(empty($data[$campo])){
                            return [
                                'icon' => 'error',
                                'title' => 'Falha.',
                                'text' => "Campo {$campo} deve ser preenchido!"
                            ];
                        }
                    }
                }

                $novo_paciente = $instituicao->pessoa()->create($data);

                $novo_paciente->criarLog($usuario_logado, 'Novo paciente via agendamento', $data, $instituicao_id);

                $agendamento_paciente['pessoa_id'] = $novo_paciente->id;
            } else {
                $paciente = Pessoa::find($agendamento_paciente['pessoa_id']);
                $instituicao = Instituicao::find($instituicao_id);
                $data = [
                    'telefone1' => $dados['telefone_paciente_agenda'],
                    'nascimento' => $dados['data_paciente_agenda'],
                    'cep' => $dados['cep'],
                    'estado' => $dados['estado'],
                    'cidade' => $dados['cidade'],
                    'bairro' => $dados['bairro'],
                    'rua' => $dados['rua'],
                    'numero' => $dados['numero'],
                    'complemento' => $dados['complemento'],
                    "cpf" => $dados['cpf'],
                    "telefone2" => $dados['telefone2'],
                    "telefone3" => $dados['telefone3'],
                    "sexo" => $dados['sexo'],
                ];

                $confg_paciente = (!empty(json_decode($instituicao->config)->pessoas)) ? json_decode($instituicao->config)->pessoas : null;
                if(!empty($confg_paciente)){
                    foreach($confg_paciente as $campo => $ativo){
                        if($campo == "endereco"){
                            if(empty($data['cep']) || empty($data['estado']) || empty($data['cidade']) || empty($data['bairro']) || empty($data['rua']) || empty($data['numero']))
                            return [
                                'icon' => 'error',
                                'title' => 'Falha.',
                                'text' => 'Campos referente ao endereço devem ser preenchidos'
                            ];
                        }else if(empty($data[$campo])){
                            return [
                                'icon' => 'error',
                                'title' => 'Falha.',
                                'text' => "Campo {$campo} deve ser preenchido!"
                            ];
                        }
                    }
                }

                $paciente->update($data);
                $paciente->criarLogEdicao($usuario_logado, $instituicao_id);
            }

            if(!empty($dados['solicitante_agenda'])){
                if(!preg_match('/^\d+$/', $dados['solicitante_agenda'])){
                    $instituicao = Instituicao::find($instituicao_id);

                    $data = [
                        'nome' => $dados['solicitante_agenda'],
                    ];

                    $novo_solicitante = $instituicao->solicitantes()->create($data);

                    $novo_solicitante->criarLog($usuario_logado, 'Novo solicitante via agendamento', $data, $instituicao_id);

                    $agendamento_paciente['solicitante_id'] = $novo_solicitante->id;

                }else{
                    $agendamento_paciente['solicitante_id'] = $dados['solicitante_agenda'];
                }
            }

            // VERIFICA SE EXISTE AGENDAMENTO E COLOCA O AGENDAMENTO COMO ENCAIXE
            if($agendamento_paciente['tipo_agenda'] == 'avulso'){
                // if(!empty(Agendamento::where('instituicoes_agenda_id', $agendamento_paciente['instituicoes_agenda_id'])->where('data', $agendamento_paciente['data'])->first())){
                    $agendamento_paciente['tipo_agenda'] = "encaixe";
                // }
            }

            $agendamento = Agendamento::create($agendamento_paciente);

            $agendamento->criarLogCadastro($usuario_logado, $instituicao_id);
            AuditoriaAgendamento::logAgendamento($agendamento->id, $agendamento_paciente['status'], $usuario_logado->id, 'salvarProcedimentoPaciente', $agendamento_paciente['obs']);

            foreach ($convenios as $key => $value) {
                //ESSE PROCEDIMENTOS
                $valor = str_replace(['.',','],['','.'],$value['valor']);

                $procedimentoRepasse = ConveniosProcedimentos::find($value['procedimento_agenda']);

                $repasse = $procedimentoRepasse->repasseMedicoId($agendamento->instituicoesAgenda->prestadores->prestador->id)->first();

                $agendamento_paciente_procedimento = array(
                    'agendamentos_id' => $agendamento->id,
                    'procedimentos_instituicoes_convenios_id' => $value['procedimento_agenda'],
                    'qtd_procedimento' => $value['qtd_procedimento'],
                    'valor_atual' => $valor,
                    'valor_convenio' => $procedimentoRepasse->valor_convenio*$value['qtd_procedimento'],
                    'estornado' => 0,
                    'desconto' => $value['desconto']
                );

                if (!empty($repasse)) {
                    $agendamento_paciente_procedimento['tipo'] = $repasse->pivot->tipo;
                    if($repasse->pivot->tipo == "porcentagem"){
                        $agendamento_paciente_procedimento['valor_repasse'] = $repasse->pivot->valor_repasse;
                    }else{
                        $agendamento_paciente_procedimento['valor_repasse'] = $repasse->pivot->valor_repasse*$value['qtd_procedimento'];
                    }
                    
                    $agendamento_paciente_procedimento['tipo_cartao'] = $repasse->pivot->tipo_cartao;
                    if($repasse->pivot->tipo_cartao == "porcentagem"){
                        $agendamento_paciente_procedimento['valor_repasse_cartao'] = $repasse->pivot->valor_repasse_cartao;
                    }else{
                        $agendamento_paciente_procedimento['valor_repasse_cartao'] = $repasse->pivot->valor_repasse_cartao*$value['qtd_procedimento'];
                    }
                }

                $procedimento = AgendamentoProcedimento::create($agendamento_paciente_procedimento);

                $procedimento->criarLogCadastro($usuario_logado, $instituicao_id);
            }

            //VERIFICA SE PODE CANCELAR O PROXIMO HORARIO
            if(!$value_cadastro_intervalo){

                //VERIFICA SE PASSA
                if($duracao_agenda <= $duracao_agenda_final){

                    //CRIA AGENDAMENTO CANCELANDO O HORARIO
                    while ($duracao_agenda < $duracao_agenda_final) {
                        if($duracao_agenda < $ultimo_horario_agenda){
                            $dia_cancelar = $dados['data_agenda'] . ' ' . $duracao_agenda;
                            $horario_cancelar = Carbon::createFromFormat('d/m/Y H:i', $dia_cancelar)->format('Y-m-d H:i:s');
                            $horaro_cancelar_final =  Carbon::createFromFormat('d/m/Y H:i', $dados['data_agenda'] . ' ' .date('H:i', strtotime(" +".$duracao_tempo." minutes", strtotime($duracao_agenda))))->format('Y-m-d H:i:s');

                            //VERIFICA SE É PARA ALTERAR DATA
                            if($dados['proximo_horario_existe']){
                                $agendamentosMultiplos = Agendamento::where('instituicoes_agenda_id', $agendamento->instituicoes_agenda_id)->where('data', '>=', $horario_cancelar)->where('data', '<', $horaro_cancelar_final)->whereNotIn('status', ['excluir', 'cancelado'])->get();
                                // dd($horaro_cancelar_final, $horario_cancelar, $agendamentosMultiplos->toArray());
                                //VERIFICA SE TEM MAIS DE UM AGENDAMENTO NO HORARIO
                                if(count($agendamentosMultiplos) > 1){
                                    //MUDA O HORARIO DE TODOS QUE EXISTEM NO HORARIO
                                        foreach ($agendamentosMultiplos as $key => $value) {
                                            //VERIFICA SE JA ESTA MUDANDO PELA SEGUNDA VEZ
                                            if ($value->data_original) {
                                                $value->update(['data' => $agendamento->data, 'data_final' => Carbon::createFromFormat('d/m/Y H:i', $data_hora_final)->format('Y-m-d H:i:s')]);
                                            }else{
                                                $value->update(['data' => $agendamento->data, 'data_original' => $value->data, 'data_final' => Carbon::createFromFormat('d/m/Y H:i', $data_hora_final)->format('Y-m-d H:i:s'), 'data_final_original' => $value->data_final]);
                                            }

                                            AuditoriaAgendamento::logAgendamento($value->id, $value->status, $usuario_logado->id, 'alterar_horario', "Agendamento {$value->id}, horario alterado");
                                            $value->criarLogEdicao($usuario_logado, $instituicao_id);
                                        }
                                //VERIFICA SE TEM MAIS DE UM
                                }else if(count($agendamentosMultiplos) > 0){
                                    // $agendamentoProximoHorario = Agendamento::where('instituicoes_agenda_id', $agendamento->instituicoes_agenda_id)->where('data', $horario_cancelar)->whereNotIn('status', ['excluir', 'cancelado'])->first();
                                    //MUDA O HORARIO DE O UNICO QUE EXISTE NO HORARIO
                                    if(!empty($agendamentosMultiplos[0])){

                                        if ($agendamentosMultiplos[0]->data_original) {
                                            $agendamentosMultiplos[0]->update(['data' => $agendamento->data, 'data_final' => Carbon::createFromFormat('d/m/Y H:i', $data_hora_final)->format('Y-m-d H:i:s')]);
                                        }else{
                                            $agendamentosMultiplos[0]->update(['data' => $agendamento->data, 'data_original' => $agendamentosMultiplos[0]->data, 'data_final' => Carbon::createFromFormat('d/m/Y H:i', $data_hora_final)->format('Y-m-d H:i:s'), 'data_final_original' => $agendamentosMultiplos[0]->data_final]);
                                        }

                                        AuditoriaAgendamento::logAgendamento($agendamentosMultiplos[0]->id, $agendamentosMultiplos[0]->status, $usuario_logado->id, 'alterar_horario', "Agendamento {$agendamentosMultiplos[0]->id}, horario alterado");
                                        $agendamentosMultiplos[0]->criarLogEdicao($usuario_logado, $instituicao_id);
                                    }
                                }
                            }

                            //CANCELA HORARIO PARA SEREM REMOVIDOS DE VISUALIZAÇÃO
                            $agendamento_cancelar = Agendamento::create([
                                'instituicoes_agenda_id' => $agenda->id,
                                'data' => $horario_cancelar,
                                'free_parcelas' => '1',
                                'status' => 'cancelado',
                                'motivo_cancelamento' => "Agendamento {$agendamento->id}, ocupando horario",
                                'id_referente' => $agendamento->id
                            ]);

                            AuditoriaAgendamento::logAgendamento($agendamento_cancelar->id, 'cancelado', $usuario_logado->id, 'cancelar_horario', "Agendamento {$agendamento->id}, ocupando horario");
                            $agendamento_cancelar->criarLogCadastro($usuario_logado, $instituicao_id);

                        }

                        $duracao_agenda = date('H:i', strtotime(" +".$duracao_tempo." minutes", strtotime($duracao_agenda)));
                    }
                }
            }else{
                //REMOVE AGENDAMENTO DO HORARIO DE ALMOÇO E COLOCA NO PRIMEIRO
                $dia_intervalo = $dados['data_agenda'] . ' ' . $duracao_intervalo_agenda;
                $duracao_agenda_intervalo = $duracao[0].':'.$duracao[1];
                $horario_intervalo = Carbon::createFromFormat('d/m/Y H:i', $dia_intervalo)->format('Y-m-d H:i:s');
                $horario_intervalo_carbon = Carbon::createFromFormat('d/m/Y H:i', $dia_intervalo);
                $agendamento->update(['data' => $horario_intervalo, 'data_original' => $agendamento->data, 'data_final' => $horario_intervalo_carbon->add(\Carbon\CarbonInterval::createFromFormat('H:i', $duracao_agenda_intervalo))->format('Y-m-d H:i:s'), 'data_final_original' => $agendamento->data_final]);
            }

            /*TELEATENDIMENTO EVIDA*/
            if($agendamento_paciente['teleatendimento'] == 1):
                if($links = $this->cria_agenda_teleatendimento($agendamento_paciente['instituicoes_agenda_id'], $agendamento_paciente['pessoa_id'], $agendamento->id)):

                    $pessoa = Pessoa::where(['id' => $agendamento_paciente['pessoa_id']])->first();

                    foreach ($links as $link) {
                        if($link->id == $pessoa->cpf):
                            $link_paciente = $link->url;
                        else:
                            $link_medico = $link->url;
                        endif;
                    }

                    DB::table('agendamentos')
                    ->where('id', $agendamento->id)
                    ->update(array(
                        'teleatendimento_link_prestador' => $link_medico,
                        'teleatendimento_link_paciente' =>  $link_paciente
                    ));
                endif;
            endif;
            /*FIM TELEATENDIMENTO EVIDA*/


            return [
                'sucesso' => true,
                'data' => $dados['data_agenda']
            ];

        });



        return response()->json($i);

    }

    public function modalDescricao(Request $request)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario = $request->user('instituicao');
        $desconto_maximo_descricao = $usuario->instituicao()->where('instituicao_id', $instituicao->id)->first()->pivot->desconto_maximo;
        $medico = false;
        if ($usuario->prestadorMedico()->first()) {
            $medico = true;
        }
        // dd($instituicao->id);

        $agendamento = Agendamento::whereHas('instituicoesAgenda', function ($q) use ($request, $instituicao) {
            $q->where(function ($q) use ($request, $instituicao) {
                $q->where(function ($q) use ($request, $instituicao) {
                    $q->whereHas('prestadores', function ($q) use ($request, $instituicao) {
                        $q->where('instituicoes_id', $instituicao->id);
                    });
                })
                    ->orWhere(function ($q) use ($request, $instituicao) {
                        $q->whereHas('procedimentos', function ($q) use ($request, $instituicao) {
                            $q->where('instituicoes_id', $instituicao->id);
                        });
                    })
                    ->orWhere(function ($q) use ($request, $instituicao) {
                        $q->whereHas('grupos', function ($q) use ($request, $instituicao) {
                            $q->where('instituicao_id', $instituicao->id);
                        });
                    });
            });
        })
            ->with([
                'pessoa',
                'instituicoesAgenda',
                'instituicoesAgenda.prestadores',
                'instituicoesAgenda.prestadores.especialidade',
                'instituicoesAgenda.prestadores.prestador',
                'agendamentoProcedimento',
                'agendamentoProcedimento.procedimentoInstituicaoConvenioTrashed',
                'agendamentoProcedimento.procedimentoInstituicaoConvenioTrashed.convenios' => function ($q) {
                    $q->withTrashed();
                },
                'agendamentoProcedimento.procedimentoInstituicaoConvenioTrashed.procedimentoInstituicao' => function ($q) {
                    $q->withTrashed();
                },
                'agendamentoProcedimento.procedimentoInstituicaoConvenioTrashed.procedimentoInstituicao.procedimento',
                'agendamentoGuias',
                'atendimentoPaciente'
            ])
            ->find($request->agendamento_id);

        // dd($agendamento)


        // $outrosAgendamentos = Agendamento::whereHas('instituicoesAgenda',function($q) use ($request, $instituicao){
        //     $q->where(function($q) use ($request, $instituicao){
        //         $q->where(function($q) use ($request, $instituicao){
        //             $q->whereHas('prestadores',function($q) use ($request, $instituicao){
        //                 $q->where('instituicoes_id',$instituicao->id);
        //             });
        //         })
        //         ->orWhere(function($q) use ($request, $instituicao){
        //             $q->whereHas('procedimentos',function($q) use ($request, $instituicao){
        //                 $q->where('instituicoes_id',$instituicao->id);
        //             });
        //         })
        //         ->orWhere(function($q) use ($request, $instituicao){
        //             $q->whereHas('grupos',function($q) use ($request, $instituicao){
        //                 $q->where('instituicao_id',$instituicao->id);
        //             });
        //         });
        //     });
        // })
        // ->where('codigo_transacao',$agendamento->codigo_transacao)
        // ->where('id','<>',$agendamento->id)
        // ->get();

        // dd("aqui");
        $usuarioAgendamentos = [];
        if(!empty($agendamento->pessoa->id)){

            $usuarioAgendamentos = Agendamento::where('pessoa_id', $agendamento->pessoa->id)->with([
                'pessoa',
                'instituicoesAgenda',
                'instituicoesAgenda.prestadores',
                'instituicoesAgenda.prestadores.especialidade',
                'instituicoesAgenda.prestadores.prestador',
                'agendamentoProcedimento',
                'agendamentoProcedimento.procedimentoInstituicaoConvenioTrashed',
                'agendamentoProcedimento.procedimentoInstituicaoConvenioTrashed.convenios' => function ($q) {
                    $q->withTrashed();
                },
                'agendamentoProcedimento.procedimentoInstituicaoConvenioTrashed.procedimentoInstituicao' => function ($q) {
                    $q->withTrashed();
                },
                'agendamentoProcedimento.procedimentoInstituicaoConvenioTrashed.procedimentoInstituicao.procedimento',
            ])->whereNotNull('instituicoes_agenda_id')->orderBy('data', 'desc')->get();
        
        }

        $convenios = [];

        if ($agendamento->instituicoesAgenda->dias_unicos != null) {
            foreach (json_decode($agendamento->instituicoesAgenda->dias_unicos) as $valueJson) {
                if (isset($valueJson->convenio_id_unico) & $valueJson->date == date('d/m/Y', strtotime($agendamento->data))) {
                    $convenios = Convenio::whereIn('id', $valueJson->convenio_id_unico)->where('ativo', 1)->orderBy('nome', 'ASC')->get();
                    $duracao_atendimento = $valueJson->duracao_atendimento;
                    $duracao_atendimento = $duracao_atendimento.':00';
                }
            }
        } else {
            $convenios = $agendamento->instituicoesAgenda->convenios()->where('ativo', 1)->orderBy('nome', 'ASC')->get();
            $duracao_atendimento = $agendamento->instituicoesAgenda->duracao_atendimento;
        }

        $formaPagamento = ContaPagar::formas_pagamento();

        $contas = $usuario->contasInstituicao()->get();
        $planosConta = $instituicao->planosContas()->where('padrao', 0)->get();
        $referencia_relacoes = Pessoa::getRelacoesParentescos();

        $solicitante = PrestadorSolicitante::find($agendamento->solicitante_id);

        $maquinas_cartao = $instituicao->maquinasCartao()->get();
        $compromissos = $instituicao->compromissos()->get();

        $tipo_guia_dados = collect($agendamento->agendamentoProcedimento)
        ->filter(function ($procedimento) {
            return !empty($procedimento);
        })
        ->map(function ($procedimento){

            // dd($procedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo);
            return [
                "divisao_tipo_guia" => $procedimento->procedimentoInstituicaoConvenioTrashed->convenios->divisao_tipo_guia,
                "tipo_guia" => $procedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->tipo_guia,
                'carteirinha_obg' => $procedimento->procedimentoInstituicaoConvenioTrashed->utiliza_parametro_convenio ? (int)  $procedimento->procedimentoInstituicaoConvenioTrashed->convenios->carteirinha_obg : $procedimento->procedimentoInstituicaoConvenioTrashed->carteirinha_obrigatoria,
                'aut_obg' => $procedimento->procedimentoInstituicaoConvenioTrashed->utiliza_parametro_convenio ? $procedimento->procedimentoInstituicaoConvenioTrashed->convenios->aut_obrigatoria : $procedimento->procedimentoInstituicaoConvenioTrashed->aut_obrigatoria,
                'tipo' => $procedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->tipo
            ];

        });

        $tipo_guia = array_unique(array_column($tipo_guia_dados->toArray(), "tipo_guia"));
        $divisao_guia = in_array(2, array_unique(array_column($tipo_guia_dados->toArray(), "divisao_tipo_guia"))) ? 'separado' : 'junto';

        $carteirinha = array_unique(array_column($tipo_guia_dados->toArray(), "carteirinha_obg"));
        $aut = array_unique(array_column($tipo_guia_dados->toArray(), "aut_obg"));
        rsort($tipo_guia);
        $tipo =  array_unique(array_column($tipo_guia_dados->toArray(), "tipo"));
        $contaReceberCriada = 0;
        if(!empty($agendamento->contaReceber()->first())){
            $contaReceberCriada = 1;
        }
        // dd($tipo_guia_dados, $tipo_guia, $divisao_guia, $carteirinha, $tipo);
        return view("instituicao.agendamentos/modalDescricao", compact("agendamento",'usuarioAgendamentos', 'convenios', 'planosConta', 'contas', 'formaPagamento', 'referencia_relacoes', 'medico','solicitante', 'maquinas_cartao', 'compromissos', 'divisao_guia', 'tipo_guia', 'carteirinha', 'aut', 'duracao_atendimento', 'tipo', 'contaReceberCriada', 'instituicao', 'desconto_maximo_descricao'))->render();
    }

    public function editarAgendamento(EditarAgendamentoBackendRequest $request, Agendamento $agendamento)
    {
        $dados = $request->validated();

        $instituicao_id = $request->session()->get('instituicao');

        $total_procedimentos = str_replace('.', '', $dados['total_procedimentos_descricao']);
        $total_procedimentos = str_replace(',', '.', $total_procedimentos);

        $agendamento_paciente = array(
            'valor_total' => $total_procedimentos
        );

        $convenios = null;
        if (array_key_exists("convenio", $dados)) {
            $convenios = collect($dados['convenio'])
                ->filter(function ($convenio) {
                    return !empty($convenio['procedimento_agenda']);
                })
                ->map(function ($convenio) {
                    $desconto = 0;
                    if(array_key_exists('desconto', $convenio)){
                        $desconto = str_replace('.', '', $convenio['desconto']);
                        $desconto = str_replace(',', '.', $desconto);
                    }
                    return [
                        "valor" => $convenio['valor'],
                        "procedimento_agenda" => $convenio['procedimento_agenda'],
                        "qtd_procedimento" => $convenio['qtd_procedimento'],
                        "desconto" => $desconto,
                    ];
                });
        }
        // dd($convenios);
        DB::transaction(function () use ($dados, $agendamento_paciente, $agendamento, $request, $instituicao_id, $convenios) {
            $usuario_logado = $request->user('instituicao');

            $agendamento->update($agendamento_paciente);

            $agendamento->criarLogEdicao($usuario_logado, $instituicao_id);
            AuditoriaAgendamento::logAgendamento($agendamento->id, $agendamento['status'], $usuario_logado->id, 'editarAgendamento');
            if ($convenios != null) {
                AgendamentoProcedimento::where('agendamentos_id', $agendamento->id)->delete();
                foreach ($convenios as $key => $value) {

                    //ESSE PROCEDIMENTOS
                    $convenioExiste = AgendamentoProcedimento::where('agendamentos_id', $agendamento->id)->where('procedimentos_instituicoes_convenios_id', $value['procedimento_agenda'])->withTrashed()->first();

                    if(empty($convenioExiste)){
                        $valor = str_replace(['.',','],['','.'],$value['valor']);
                        $procedimentoRepasse = ConveniosProcedimentos::find($value['procedimento_agenda']);

                        $agendamento_paciente_procedimento = array(
                            'agendamentos_id' => $agendamento->id,
                            'procedimentos_instituicoes_convenios_id' => $value['procedimento_agenda'],
                            'qtd_procedimento' => $value['qtd_procedimento'],
                            'valor_atual' => $valor,
                            'valor_convenio' => $procedimentoRepasse->valor_convenio*$value['qtd_procedimento'],
                            'desconto' => $value['desconto'],
                            'estornado' => 0,
                        );

                        $repasse = $procedimentoRepasse->repasseMedicoId($agendamento->instituicoesAgenda->prestadores->prestador->id)->first();

                        if (!empty($repasse)) {
                            $agendamento_paciente_procedimento['tipo'] = $repasse->pivot->tipo;
                            if($repasse->pivot->tipo == "porcentagem"){
                                $agendamento_paciente_procedimento['valor_repasse'] = $repasse->pivot->valor_repasse;
                            }else{
                                $agendamento_paciente_procedimento['valor_repasse'] = $repasse->pivot->valor_repasse*$value['qtd_procedimento'];
                            }
                           
                            $agendamento_paciente_procedimento['tipo_cartao'] = $repasse->pivot->tipo_cartao;
                            if($repasse->pivot->tipo_cartao == "porcentagem"){
                                $agendamento_paciente_procedimento['valor_repasse_cartao'] = $repasse->pivot->valor_repasse_cartao;
                            }else{
                                $agendamento_paciente_procedimento['valor_repasse_cartao'] = $repasse->pivot->valor_repasse_cartao*$value['qtd_procedimento'];
                            }
                        }

                        $procedimento = AgendamentoProcedimento::create($agendamento_paciente_procedimento);

                        $procedimento->criarLogCadastro($usuario_logado, $instituicao_id);
                    } else {
                        // dd(empty($convenioExiste->deleted_at));
                        if (!empty($convenioExiste->deleted_at)) {
                            $convenioExiste->restore();

                            $valor = str_replace(['.',','],['','.'],$value['valor']);
                            $procedimentoRepasse = ConveniosProcedimentos::find($value['procedimento_agenda']);

                            $agendamento_paciente_procedimento = array(
                                'procedimentos_instituicoes_convenios_id' => $value['procedimento_agenda'],
                                'qtd_procedimento' => $value['qtd_procedimento'],
                                'valor_atual' => $valor,
                                'valor_convenio' => $procedimentoRepasse->valor_convenio*$value['qtd_procedimento'],
                                'desconto' => $value['desconto'],
                                'estornado' => 0,
                            );

                            $repasse = $procedimentoRepasse->repasseMedicoId($agendamento->instituicoesAgenda->prestadores->prestador->id)->first();

                            if (!empty($repasse)) {
                                $agendamento_paciente_procedimento['tipo'] = $repasse->pivot->tipo;
                                if($repasse->pivot->tipo == "porcentagem"){
                                    $agendamento_paciente_procedimento['valor_repasse'] = $repasse->pivot->valor_repasse;
                                }else{
                                    $agendamento_paciente_procedimento['valor_repasse'] = $repasse->pivot->valor_repasse*$value['qtd_procedimento'];
                                }
                                
                                $agendamento_paciente_procedimento['tipo_cartao'] = $repasse->pivot->tipo_cartao;
                                if($repasse->pivot->tipo_cartao == "porcentagem"){
                                    $agendamento_paciente_procedimento['valor_repasse_cartao'] = $repasse->pivot->valor_repasse_cartao;
                                }else{
                                    $agendamento_paciente_procedimento['valor_repasse_cartao'] = $repasse->pivot->valor_repasse_cartao*$value['qtd_procedimento'];
                                }
                            }


                            $convenioExiste->update($agendamento_paciente_procedimento);
                        }
                    }
                }
            }
        });

        $this->atualiza_guia($agendamento->id);

        /* SANCOOP - VERIFICANDO SE ADICIONOU/REMOVEU PROCEDIMENTOS QUE DEVEM SER FATURADOS NA EDIÇÃO */

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        if($instituicao->possui_faturamento_sancoop == 1 
        && $agendamento->status != 'ausente'
        && $agendamento->status != 'desistencia'
        && $agendamento->status != 'cancelado'):


            //TEMOS QUE FAZER AS REGRAS DE ENVIO PARA NAO ATUALIZAR AGENDAMENTOS DO DIA DO ENVIO DAS GUIAS, UMA VEZ QUE ESTES VAO EM OUTROS LOTES
            //#############TERMINAR AS OUTRAS REGRAS QUE NÃO SAO SEXTA

            //VAMOS PEGAR O DIA ATUAL PQ NAO PODE EDITAR OS DAGENDAMENTOS DO DIA POIS SAO PROCESSADOS A NOITE
            // $dia_agenda = date('w', strtotime($agendamento->data));
            // $hoje = date('w');

            /*VAMOS APLICAR A REGRA AGORA BASESADO NO ENVIO DA INSTITUICAO*/

            //SÓ PODEMOS MEXER NOS AGENDAMENTOS PASSADOS
            if(strtotime($agendamento->data) < strtotime(date('Y-m-d 00:00:00'))):
                $atualizar_dados_guia = 1;
                //****TERMINAR O RESTANTE AQUIIII */
            else:
                $atualizar_dados_guia = 0; 
            endif;


            //CASO POSSA ATUALIZAR OS DADOS
            if($atualizar_dados_guia == 1):
                $this->atualiza_dados_guia_protocolo_sancoop($instituicao->id, $agendamento->id);
            endif;


        endif;

        /* FIM SANCOOP */

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Agendamento editado com sucesso!'
        ]);
    }

    public function estornarParcialmente(Request $request)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = Agendamento::whereHas('instituicoesAgenda', function ($q) use ($request, $instituicao) {
            $q->where(function ($q) use ($request, $instituicao) {
                $q->where(function ($q) use ($request, $instituicao) {
                    $q->whereHas('prestadores', function ($q) use ($request, $instituicao) {
                        $q->where('instituicoes_id', $instituicao->id);
                    });
                })
                    ->orWhere(function ($q) use ($request, $instituicao) {
                        $q->whereHas('procedimentos', function ($q) use ($request, $instituicao) {
                            $q->where('instituicoes_id', $instituicao->id);
                        });
                    })
                    ->orWhere(function ($q) use ($request, $instituicao) {
                        $q->whereHas('grupos', function ($q) use ($request, $instituicao) {
                            $q->where('instituicao_id', $instituicao->id);
                        });
                    });
            });
        })->find($request->agendamento);
        if (!$request->procedimentos) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Selecione ao menos um procedimento!'
            ]);
        }
        $pagarMe = new PagarMe();
        $soma_agendamentoProcedimento = $agendamento->agendamentoProcedimento()->whereIn('id', $request->procedimentos)->sum('valor_atual');

        try {
            $estorno = $pagarMe->estornarParcialmenteTransacaoInstituicao($agendamento, $soma_agendamentoProcedimento);
            if ($estorno->status == 'refunded') {
                $agendamento->update(['status' => 'cancelado', 'status_pagamento' => 'estornado']);
                if ($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo == 'consulta') {
                    $title = 'Consulta #' . $agendamento->id;
                    $msg = 'A clínica cancelou a sua consulta.';
                    $url = 'tabs/tab1/agendamentos/consulta/' . $agendamento->id;
                } elseif ($agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo == 'exame') {
                    $title = 'Exame #' . $agendamento->id;
                    $msg = 'A clínica cancelou o seu exame. ';
                    $url = 'tabs/tab1/agendamentos/exame/' . $agendamento->id;
                }
                // event(new \App\Events\NotificacaoFCM(
                //     $title,
                //     $msg.$msg1,
                //     $url,
                //     $agendamento->usuario_id
                // ));
                return response()->json([
                    'icon' => 'success',
                    'title' => 'Sucesso',
                    'text' => 'Agendamento cancelado com sucesso!'
                ]);
            }

            $agendamento->update(['status' => 'cancelado', 'status_pagamento' => 'estornado parcialmente']);
            $agendamento->agendamentoProcedimento()->whereIn('id', $request->procedimentos)->update(['estornado' => '1']);

            event(new \App\Events\NotificacaoFCM(
                'Cancelamento parcial',
                'A clínica cancelou parcialmente sua consulta/exame',
                'tabs/tab1/agendamentos/exame/' . $agendamento->id,
                $agendamento->usuario_id
            ));

            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso',
                'text' => 'Agendamento estornado parcialmente com sucesso!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Houve um erro ao estornar o pagamento!'
            ]);
        }
    }

    public function buscarAgendamentos(Request $request)
    {
        $search = $request->get('search');
        $atendimento = $request->get('agendamento_atendimentos_id');
        if (empty($atendimento) && empty($search)) {
            return response()->json([]);
        }

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $query = AgendamentoAtendimento::buscarAgendamentoPorPacientes($instituicao)
            ->with([
                'agendamento.agendamentoProcedimento',
                'agendamento.agendamentoProcedimento.procedimentoInstituicaoConvenio',
                'agendamento.agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios',
                'agendamento.agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao',
                'agendamento.agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento'
            ])
            ->orderBy('data_hora', 'desc');

        $status = $request->get('status', false);
        if($status) {
            $query->where('agendamento_atendimentos.status', $status);
        }

        if (!empty($atendimento)) {
            $query->where('agendamento_atendimentos.id', $atendimento);
        } else {
            $query->where(function($query) use ($search) {
                $query->whereHas('pessoa', function(Builder $query) use ($search) {
                    $query->where('pessoas.nome', 'like', "%$search%");
                })
                ->orWhereHas('agendamento.instituicoesPrestadores.prestador', function(Builder $query) use ($search) {
                    $query->where('prestadores.nome', 'like', "%$search%");
                });
            });
        }
        $results = $query->simplePaginate(30);
        return response()->json([
            "results" => $results->items(),
            "pagination" => array(
                "more" => !empty($results->nextPageUrl()),
            )
        ]);
    }

    public function salvarPagamento(PagamentoAgendamentoRequest $request, Agendamento $agendamento)
    {
        $instituicao_id = $request->session()->get('instituicao');
        $dados = $request->validated();

        unset($dados['pagamento']);

        $pagamentos = collect($request->pagamento)
            ->filter(function ($pagamento) {
                $valor = ConverteValor::converteDecimal($pagamento['valor']);
                return $valor > 0;
            })
            ->map(function ($pagamento) {
                return [
                    'valor_parcela' => ConverteValor::converteDecimal($pagamento['valor']),
                    'data_vencimento' => $pagamento['data'],
                    'conta_id' => $pagamento['conta_id'],
                    'plano_conta_id' => $pagamento['plano_conta_id'],
                    'forma_pagamento' => $pagamento['forma_pagamento'],
                    'valor_pago' => (array_key_exists('recebido', $pagamento)) ? ConverteValor::converteDecimal($pagamento['valor']) : null,
                    'data_pago' => (array_key_exists('recebido', $pagamento)) ? $pagamento['data'] : null,
                    'status' => (array_key_exists('recebido', $pagamento)) ? 1: 0,
                    'num_parcelas' => $pagamento['num_parcelas'],
                    'maquina_id' => (!empty($pagamento['maquina_id'])) ? $pagamento['maquina_id'] : null,
                    'taxa_cartao' => (!empty($pagamento['taxa'])) ? $pagamento['taxa'] : null,
                    'cod_aut' => (!empty($pagamento['cod_aut'])) ? $pagamento['cod_aut'] : null,
                ];
            });

        // dd($pagamentos);

        DB::transaction(function () use ($request, $instituicao_id, $agendamento, $pagamentos, $dados) {
            $usuario_logado = $request->user('instituicao');
            if (array_key_exists('desconto', $dados)) {
                $dados['desconto'] = ConverteValor::converteDecimal($dados['desconto']);
                $agendamento->update(['desconto' => $dados['desconto'], 'carteirinha_id' => $dados['carteirinha_id_pagamento']]);
                $agendamento->criarLogEdicao($usuario_logado, $instituicao_id);
            } else {
                $agendamento->update([['desconto' => 0.0], 'carteirinha_id' => $dados['carteirinha_id_pagamento']]);
                $agendamento->criarLogEdicao($usuario_logado, $instituicao_id);
            }

            foreach ($pagamentos as $key => $value) {
                $data = $value;

                //////CARTAO DE CREDITO
                if($data['forma_pagamento'] == 'cartao_credito' OR $data['forma_pagamento'] == 'boleto_cobranca'){
                    $idPai = 0;

                    $valor_parcela = $data['valor_parcela']/$data['num_parcelas'];

                    $valor_parcela = number_format($valor_parcela, 2, '.', '');

                    for ($i=0; $i < $data['num_parcelas']; $i++) {
                        $valor_parcela_utilizar = 0;
                        if($i == 0){
                            $total_parcelas = $valor_parcela*$data['num_parcelas'];

                            if($total_parcelas == $data['valor_parcela']){
                                $valor_parcela_utilizar = $valor_parcela;
                            }else if($total_parcelas > $data['valor_parcela']){
                                $valor_parcela_utilizar = $total_parcelas - $data['valor_parcela'];
                                $valor_parcela_utilizar = number_format($valor_parcela_utilizar, 2, '.', '');

                                $valor_parcela_utilizar = $valor_parcela - $valor_parcela_utilizar;
                            }else{
                                $valor_parcela_utilizar = $data['valor_parcela'] - $total_parcelas;
                                $valor_parcela_utilizar = number_format($valor_parcela_utilizar, 2, '.', '');
                                $valor_parcela_utilizar = $valor_parcela + $valor_parcela_utilizar;
                            }

                            $data_vencimento =  $data['data_vencimento'];


                        }else{
                            $valor_parcela_utilizar = $valor_parcela;
                            $data_vencimento = date('Y-m-d', strtotime($data_vencimento.' +1 month'));
                        }

                        $dadosCartaoCredito = [
                            'valor_parcela' => $valor_parcela_utilizar,
                            'data_vencimento' => $data_vencimento,
                            'conta_id' => $data['conta_id'],
                            'plano_conta_id' => $data['plano_conta_id'],
                            'forma_pagamento' => $data['forma_pagamento'],
                            'num_parcela' => $i + 1,
                            'descricao' => "Parcela de agendamento",
                            'pessoa_id' => $agendamento->pessoa_id,
                            'instituicao_id' => $instituicao_id,
                            'num_parcelas' => $data['num_parcelas'],
                            'valor_total' => $total_parcelas,
                            "data_pago" => $data['data_pago'],
                            "valor_pago" => $valor_parcela_utilizar,
                            'tipo_parcelamento' => 'mensal',
                            'status' => $data['status'],
                            'maquina_id' => (!empty($data['maquina_id'])) ? $data['maquina_id'] : null,
                            'taxa_cartao' => (!empty($data['taxa_cartao'])) ? number_format($data['taxa_cartao'] / $data['num_parcelas'], 2) : null,
                            'cod_aut' => (!empty($data['cod_aut'])) ? $data['cod_aut'] : null,
                        ];

                        $contaReceber = $agendamento->contaReceber()->create($dadosCartaoCredito);
                        $contaReceber->criarLogCadastro($usuario_logado, $instituicao_id);

                        if($idPai == 0){
                            $idPai = $contaReceber->id;
                            $contaReceber->update(['conta_pai' => $idPai]);
                        }else{
                            $contaReceber->update(['conta_pai' => $idPai]);
                        }
                    }

                }else{

                    $data['pessoa_id'] = $agendamento->pessoa_id;
                    $data['descricao'] = "Parcela de agendamento";
                    $data['instituicao_id'] = $instituicao_id;
                    $data['num_parcela'] = $key;
                    $data['valor_total'] = $data['valor_parcela'];
                    $data['usuario_baixou_id'] = (!empty($data['status'])) ? $usuario_logado->id : null;

                    $contaReceber = $agendamento->contaReceber()->create($data);
                    $contaReceber->criarLogCadastro($usuario_logado, $instituicao_id);
                }
            }
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Agendamento, pagamento efeutado com sucesso!'
        ]);
    }

    public function salvarPagamentoStatus(PagamentoAgendamentoRequest $request, Agendamento $agendamento)
    {
        $instituicao_id = $request->session()->get('instituicao');
        $dados = $request->validated();
        unset($dados['pagamento']);

        $pagamentos = collect($request->pagamento)
            ->filter(function ($pagamento) {
                $valor = ConverteValor::converteDecimal($pagamento['valor']);
                return $valor > 0;
            })
            ->map(function ($pagamento) {
                return [
                    'valor_parcela' => ConverteValor::converteDecimal($pagamento['valor']),
                    'data_vencimento' => $pagamento['data'],
                    'conta_id' => $pagamento['conta_id'],
                    'plano_conta_id' => $pagamento['plano_conta_id'],
                    'forma_pagamento' => $pagamento['forma_pagamento'],
                    'valor_pago' => (array_key_exists('recebido', $pagamento)) ? ConverteValor::converteDecimal($pagamento['valor']) : null,
                    'data_pago' => (array_key_exists('recebido', $pagamento)) ? $pagamento['data'] : null,
                    'status' => (array_key_exists('recebido', $pagamento)) ? 1 : 0,
                    'num_parcelas' => $pagamento['num_parcelas'],
                    'maquina_id' => (!empty($pagamento['maquina_id'])) ? $pagamento['maquina_id'] : null,
                    'taxa_cartao' => (!empty($pagamento['taxa'])) ? $pagamento['taxa'] : null,
                    'cod_aut' => (!empty($pagamento['cod_aut'])) ? $pagamento['cod_aut'] : null,
                ];
            });


        DB::transaction(function () use ($request, $instituicao_id, $agendamento, $pagamentos, $dados) {
            $usuario_logado = $request->user('instituicao');
            $data = [
                'agendamento_id' => $agendamento->id,
                'pessoa_id' => $agendamento->pessoa->id,
                'data_hora' => date('Y-m-d H:i'),
                'tipo' => 1,
                'status' => 1,
            ];

            if (array_key_exists('desconto', $dados)) {
                $dados['desconto'] = ConverteValor::converteDecimal($dados['desconto']);
                $agendamento->update([
                    'desconto' => $dados['desconto'],
                    'status' => 'agendado',
                    'carteirinha_id' => (!empty($dados['carteirinha_id_pagamento'])) ? $dados['carteirinha_id_pagamento'] : null,
                    'tipo_guia' => (!empty($dados['tipo_guia_pagamento'])) ? $dados['tipo_guia_pagamento'] : null,
                    'num_guia_convenio' => (!empty($dados['num_guia_convenio_pagamento'])) ? $dados['num_guia_convenio_pagamento'] : null,
                    'cod_aut_convenio' => (!empty($dados['cod_aut_convenio_pagamento'])) ? $dados['cod_aut_convenio_pagamento'] : null,
                ]);
                $agendamento->criarLogEdicao($usuario_logado, $instituicao_id);
            } else {
                $agendamento->update([
                    'desconto' => 0.0,
                    'status' => 'agendado',
                    'carteirinha_id' => (!empty($dados['carteirinha_id_pagamento'])) ? $dados['carteirinha_id_pagamento'] : null,
                    'tipo_guia' => (!empty($dados['tipo_guia_pagamento'])) ? $dados['tipo_guia_pagamento'] : null,
                    'num_guia_convenio' => (!empty($dados['num_guia_convenio_pagamento'])) ? $dados['num_guia_convenio_pagamento'] : null,
                    'cod_aut_convenio' => (!empty($dados['cod_aut_convenio_pagamento'])) ? $dados['cod_aut_convenio_pagamento'] : null,
                ]);
                $agendamento->criarLogEdicao($usuario_logado, $instituicao_id);
            }

            $atendimento = AgendamentoAtendimento::create($data);

            $atendimento->criarLogCadastro(
                $request->user('instituicao'),
                $instituicao_id
            );
            AuditoriaAgendamento::logAgendamento($agendamento->id, 'agendado', $request->user('instituicao')->id, 'iniciar_atendimento');

            foreach ($pagamentos as $key => $value) {
                $idPai = 0;
                $data = $value;

                //////CARTAO DE CREDITO
                if($data['forma_pagamento'] == 'cartao_credito' OR $data['forma_pagamento'] == 'boleto_cobranca'){

                    $valor_parcela = $data['valor_parcela']/$data['num_parcelas'];

                    $valor_parcela = number_format($valor_parcela, 2, '.', '');

                    for ($i=0; $i < $data['num_parcelas']; $i++) {
                        $valor_parcela_utilizar = 0;
                        if($i == 0){
                            $total_parcelas = $valor_parcela*$data['num_parcelas'];

                            if($total_parcelas == $data['valor_parcela']){
                                $valor_parcela_utilizar = $valor_parcela;
                            }else if($total_parcelas > $data['valor_parcela']){
                                $valor_parcela_utilizar = $total_parcelas - $data['valor_parcela'];
                                $valor_parcela_utilizar = number_format($valor_parcela_utilizar, 2, '.', '');

                                $valor_parcela_utilizar = $valor_parcela - $valor_parcela_utilizar;
                            }else{
                                $valor_parcela_utilizar = $data['valor_parcela'] - $total_parcelas;
                                $valor_parcela_utilizar = number_format($valor_parcela_utilizar, 2, '.', '');
                                $valor_parcela_utilizar = $valor_parcela + $valor_parcela_utilizar;
                            }

                            $data_vencimento =  $data['data_vencimento'];

                        }else{
                            $valor_parcela_utilizar = $valor_parcela;
                            $data_vencimento = date('Y-m-d', strtotime($data_vencimento.' +1 month'));
                        }

                        $dadosCartaoCredito = [
                            'valor_parcela' => $valor_parcela_utilizar,
                            'data_vencimento' => $data_vencimento,
                            'conta_id' => $data['conta_id'],
                            'plano_conta_id' => $data['plano_conta_id'],
                            'forma_pagamento' => $data['forma_pagamento'],
                            'num_parcela' => $i + 1,
                            'descricao' => "Parcela de agendamento",
                            'pessoa_id' => $agendamento->pessoa_id,
                            'instituicao_id' => $instituicao_id,'num_parcelas' => $data['num_parcelas'],
                            'valor_total' => $total_parcelas,
                            "data_pago" => $data['data_pago'],
                            "valor_pago" => $valor_parcela_utilizar,
                            'tipo_parcelamento' => 'mensal',
                            'status' => $data['status'],
                            'maquina_id' => (!empty($data['maquina_id'])) ? $data['maquina_id'] : null,
                            'taxa_cartao' => (!empty($data['taxa_cartao'])) ? number_format($data['taxa_cartao'] / $data['num_parcelas'], 2) : null,
                            'cod_aut' => (!empty($data['cod_aut'])) ? $data['cod_aut'] : null,
                        ];

                        $contaReceber = $agendamento->contaReceber()->create($dadosCartaoCredito);
                        $contaReceber->criarLogCadastro($usuario_logado, $instituicao_id);

                        if($idPai == 0){
                            $idPai = $contaReceber->id;
                            $contaReceber->update(['conta_pai' => $idPai]);
                        }else{
                            $contaReceber->update(['conta_pai' => $idPai]);
                        }
                    }

                }else{

                    $data['pessoa_id'] = $agendamento->pessoa_id;
                    $data['descricao'] = "Parcela de agendamento";
                    $data['instituicao_id'] = $instituicao_id;
                    $data['num_parcela'] = $key;
                    $data['valor_total'] = $data['valor_parcela'];
                    $data['usuario_baixou_id'] = (!empty($data['status'])) ? $usuario_logado->id : null;

                    $contaReceber = $agendamento->contaReceber()->create($data);
                    $contaReceber->criarLogCadastro($usuario_logado, $instituicao_id);
                }
            }
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Agendamento, pagamento efeutado com sucesso!'
        ]);
    }

    public function cancelarParcelaPagamento(Request $request, Agendamento $agendamento, ContaReceber $parcela)
    {
        abort_unless($agendamento->id === $parcela->agendamento_id, 403);

        DB::transaction(function () use ($agendamento, $parcela, $request) {
            $instituicao_id = $request->session()->get('instituicao');
            $usuario_logado = $request->user('instituicao');

            $parcela->update(['cancelar_parcela' => 1]);
            $parcela->criarLogEdicao($usuario_logado, $instituicao_id);
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Agendamento, parcela cancelada com sucesso!'
        ]);
    }



    /*KENTRO*/
    // public function enviar_confirmacoes_atendimentos(Request $request){



    // }

    /*FIM KENTRO*/
    public function getDiasPrestador(Request $request)
    {
        $agenda = InstituicoesAgenda::where('instituicoes_prestadores_id', $request->input('prestador_id'))->get();
        $dias = ['dias_semana' => [], 'dias_unicos' => []];

        foreach ($agenda as $key => $values) {
            if ($values['tipo'] == 'continuo') {
                $dias['dias_semana'][] = ['dia' => $values['dias_continuos'], 'obs' => $values['obs']];
            } else if ($values['tipo'] == 'unico') {
                $a = json_decode($values['dias_unicos'], true);
                $dias['dias_unicos'] = $a;
            }
        }

        return response()->json($dias);
    }

    public function getAuditoria(Request $request, Agendamento $agendamento)
    {
        $instituicao = $request->session()->get('instituicao');
        $lista = AuditoriaAgendamento::where('agendamento_id', $agendamento->id)
            ->orderBy('data', 'desc')
            ->with(['usuarios' => function($q) use($instituicao){
                $q->whereHas('instituicaoTrashed', function($q1) use($instituicao){
                    $q1->where('id', $instituicao);
                });
            }])
            ->get();

        // dd($lista);

        return view('instituicao.agendamentos/auditoria', compact('lista'));
    }

    public function salvaObsConsultorio(Request $request)
    {
        $paciente = Pessoa::find($request->input('id'));

        DB::transaction(function () use ($paciente, $request) {
            $instituicao_id = $request->session()->get('instituicao');
            $usuario_logado = $request->user('instituicao');

            $paciente->update(['obs_consultorio' => $request->input('obs_consultorio')]);
            $paciente->criarLogEdicao($usuario_logado, $instituicao_id);
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Obs salva com sucesso!'
        ]);
    }

    public function getAgendamentos(Request $request, Pessoa $pessoa)
    {

        $usuarioAgendamentos = Agendamento::where('pessoa_id', $pessoa->id)->whereNotNull('instituicoes_agenda_id')->with('agendamentoProcedimentoTashed.procedimentoInstituicaoConvenioTrashed')->orderBy('data', 'desc')->get();

        return view("instituicao.agendamentos/agendamentos", compact('usuarioAgendamentos'));
    }

    public function setToobar(Request $request)
    {

        $dados = Json_decode($request->input('dados'));

        $status = [
            'pendente' => 0,
            'agendado' => 0,
            'confirmado' => 0,
            'em_atendimento' => 0,
            'finalizado' => 0,
            'ausente' => 0,
        ];

        foreach ($dados as $k => $v) {
            switch ($v) {
                case 'pendente':
                    $status['pendente']++;
                    break;
                case 'finalizado':
                    $status['finalizado']++;
                    break;
                case 'confirmado':
                    $status['confirmado']++;
                    break;
                case 'agendado':
                    $status['em_atendimento']++;
                    break;
                case 'em_atendimento':
                    $status['em_atendimento']++;
                    break;
                case 'ausente':
                    $status['ausente']++;
                    break;
            }
        }

        $status['agendado'] = count($dados);

        // dd($dados, $status);

        return view("instituicao.agendamentos/toobar", compact('status'));
    }

    public function imprimeAgendamento(Request $request, Agendamento $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view("instituicao.agendamentos/imprime_agendamento", compact('agendamento', 'instituicao'));
    }

    public function getProfissionaisDia(Request $request)
    {
        $diasemana = array('domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado');

        $agendaConsinuos = InstituicoesAgenda::where('referente', 'prestador')
            ->where('dias_continuos', $diasemana[date("w", strtotime(str_replace("/", "-", $request->input('data'))))])
            ->when($request->input('setor_id'), function ($q) use ($request) {
                $q->where('setor_id', $request->input('setor_id'));
            })
            ->with(['prestadores' => function ($q) use ($request) {
                $q->where('instituicoes_id', $request->session()->get('instituicao'));
                $q->where('ativo', 1);
            }])
            ->get();

        $agendaUnicos = InstituicoesAgenda::where('referente', 'prestador')
            // ->whereJsonContains("dias_unicos", ['date' => $request->input('data')])
            ->whereRaw("json_contains(`dias_unicos`, '".json_encode(['date' => $request->input('data')])."')")
            ->when($request->input('setor_id'), function ($q) use ($request) {
                $q->whereJsonContains("dias_unicos", ['setor_id' => $request->input('setor_id')]);
            })
            ->with(['prestadores' => function ($q) use ($request) {
                $q->where('instituicoes_id', $request->session()->get('instituicao'));
                $q->where('ativo', 1);
            }])
            ->get();

        $profissionais = [];
        if ($agendaConsinuos) {
            foreach ($agendaConsinuos as $key => $values) {
                if (!empty($values->prestadores['prestador'])) {
                    $profissionais[] = [
                        'prestador_especialidade_id' => $values->instituicoes_prestadores_id,
                        'profissional' => $values->prestadores['prestador']['nome'],
                        'inicio' => $values->hora_inicio,
                        'fim' => $values->hora_fim,
                    ];
                }
            }
        }

        if ($agendaUnicos) {
            foreach ($agendaUnicos as $key => $values) {
                if (!empty($values->prestadores['prestador'])) {
                    $diaUnico = array_filter(
                        JSON_DECODE($values->dias_unicos),
                        function ($e) use ($request) {
                            return $e->date == $request->input('data');
                        }
                    );

                    // dd( $diaUnico, $values->prestadores );

                    foreach ($diaUnico as $v) {
                        $profissionais[] = [
                            'prestador_especialidade_id' => $values->instituicoes_prestadores_id,
                            'profissional' => $values->prestadores['prestador']['nome'],
                            'inicio' => $v->hora_inicio,
                            'fim' => $v->hora_fim,
                        ];
                    }
                }
            }
        }

        return view("instituicao.agendamentos/profissionais", ['profissionais' => array_filter($profissionais)]);
    }

    public function salvaObs(Request $request, Agendamento $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        if((!empty($request->input('teleatendimento')))){
            if($instituicao->telemedicina_integrado == 1 && $agendamento->instituicoesAgenda->prestadores->telemedicina_integrado == 1){
                
                
                if(
                    empty($agendamento->pessoa->cpf) OR
                    empty($agendamento->pessoa->telefone1) OR 
                    empty($agendamento->pessoa->email) OR 
                    empty($agendamento->pessoa->sexo) OR
                    empty($agendamento->pessoa->nascimento) 
                ){
                    return response()->json([
                        'icon' => 'error',
                        'title' => 'Flaha',
                        'text' => 'Faltam dados no cadastro do paciente que são obrigatórios para o tele atendimento, gentileza conferir a ficha do paciente os seguintes dados estão preenchidos: CPF, Dt Nascimento, Telefone 1, E-mail e Sexo!'
                    ]);
                }
            }
        }

       DB::transaction(function () use ($agendamento, $request, $instituicao) {
            $instituicao_id = $request->session()->get('instituicao');
            $usuario_logado = $request->user('instituicao');

            $dados = [
                "obs" => $request->input('obs'),
                'acompanhante' => (!empty($request->input('acompanhante'))) ? 1 : 0,
                "acompanhante_relacao" => (!empty($request->input('acompanhante'))) ? $request->input('acompanhante_relacao') : "",
                "acompanhante_nome" => (!empty($request->input('acompanhante'))) ? $request->input('acompanhante_nome') : "",
                "acompanhante_telefone" => (!empty($request->input('acompanhante'))) ? $request->input('acompanhante_telefone') : "",
                "cpf_acompanhante" => (!empty($request->input('acompanhante'))) ? $request->input('cpf_acompanhante') : "",
                'solicitante_id' => $request->input('solicitante_id'),
                "compromisso_id" => (!empty($request->input('compromisso_id'))) ? $request->input('compromisso_id') : null,
                "teleatendimento" => (!empty($request->input('teleatendimento'))) ? $request->input('teleatendimento') : null,
            ];

            if(!empty($dados['solicitante_id'])){
                if(!preg_match('/^\d+$/', $dados['solicitante_id'])){
                    $instituicao = Instituicao::find($instituicao_id);

                    $data = [
                        'nome' => $dados['solicitante_id'],
                    ];

                    $novo_solicitante = $instituicao->solicitantes()->create($data);

                    $novo_solicitante->criarLog($usuario_logado, 'Novo solicitante via agendamento', $data, $instituicao_id);

                    $dados['solicitante_id'] = $novo_solicitante->id;

                }
            }

            $agendamento->update($dados);
            $agendamento->criarLogEdicao($usuario_logado, $instituicao_id);
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Obs salva com sucesso!'
        ]);
    }

    public function getSelectProcedimentos(Request $request, Convenio $convenio, Prestador $prestador)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $procedimentos = $instituicao->procedimentosInstituicoes()->whereHas('instituicaoProcedimentosConvenios', function ($query) use ($convenio) {
            $query->where('convenios_id', $convenio->id);
        })->with(['procedimento', 'instituicaoProcedimentosConvenios' => function ($query) use ($convenio) {
            $query->where('convenios_id', $convenio->id);
        }])

            ->get();

        foreach ($procedimentos as $k => $v) {
            $dataWhere = [
                'procedimento_instituicao_convenio_id' => $v->instituicaoProcedimentosConvenios[0]->pivot->id,
                'prestador_id' => $prestador->id
            ];

            $procConv = DB::table('procedimentos_convenios_has_repasse_medico')
                ->where($dataWhere)
                ->first();

            if (empty($procConv)) {
                unset($procedimentos[$k]);
            } else {
                if (!empty((float) $procConv->valor_cobrado)) {
                    $procedimentos[$k]->instituicaoProcedimentosConvenios[0]->pivot->valor =  $procConv->valor_cobrado;
                }
            }
        }

        $pacotes = $instituicao->pacoteProcedimentos()->with('procedimentoVinculo')->get();

        // $procedimentoConvenio_id = $procedimentos[0]->instituicaoProcedimentosConvenios[0]->pivot->id;

        // $procConvenio = DB::table('procedimentos_convenios_has_repasse_medico')
        //     ->where('procedimento_instituicao_convenio_id', $procedimentoConvenio_id)
        //     ->where('prestador_id', $request->input('prestador'))
        //     ->first();

        // if(!empty($procConvenio) && !empty((float) $procConvenio->valor_cobrado)){
        //     $procedimentos[0]->instituicaoProcedimentosConvenios[0]->pivot->valor = $procConvenio->valor_cobrado;
        // }

        return view("instituicao.agendamentos/pacotes_procedimentos", compact('procedimentos', 'pacotes'));
    }

    public function getProcedimentoPesquisa(Request $request)
    {
        if ($request->ajax())
        {
            $instituicao = $request->session()->get('instituicao');
            $nome = ($request->input('q')) ? $request->input('q') : '';

            $procedimentos = Procedimento::getProcedimentoPesquisaModel($nome, $instituicao)->simplePaginate(100);

            $morePages=true;
            if (empty($procedimentos->nextPageUrl())){
                $morePages=false;
            }

            $results = array(
                "results" => $procedimentos->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($pacientes->per_page());
            return response()->json($results);
        }
    }

    public function getDiasAtendimentoPrestador(Request $request)
    {
        $agenda_semana =  InstituicoesAgenda::where('instituicoes_prestadores_id',$request->input('prestador_id'))
            ->where('tipo', 'continuo')
            ->get();

        $datasEscolhidas = [];
        $datasAtendimentos = [];
        $arraySemana = [];
        $dateStart = date('Y-m-d 00:00:00');
        if(!empty($agenda_semana)){
            while($dateStart < date('Y-m-d 00:00:00', strtotime('+60 days'))){

                $diaSemana = $this->retornaData(strftime("%u",strtotime($dateStart)));

                if(array_key_exists($diaSemana, $arraySemana)){
                    if($arraySemana[$diaSemana] == 1){
                        $datasEscolhidas[$dateStart] = $dateStart;
                        $datasAtendimentos[] = $dateStart;
                    }
                }else{
                    $existe = false;
                    foreach ($agenda_semana as $key => $value) {
                        if($value->dias_continuos == $diaSemana){
                            $existe = true;
                            $arraySemana[$diaSemana] = 1;
                            $datasEscolhidas[$dateStart] = $dateStart;
                            $datasAtendimentos[] = $dateStart;
                        }
                    }

                    if($existe == false){
                        $arraySemana[$diaSemana] = 0;
                    }
                }

                $dateStart = date('Y-m-d 00:00:00', strtotime($dateStart.' +1 days'));
            };
        }

        $agenda_unica = InstituicoesAgenda::where('instituicoes_prestadores_id',$request->input('prestador_id'))
        ->where('tipo', 'unico')
        ->first();

        // $arrayUnica = [];
        $dateStart = date('Y-m-d 00:00:00');
        $dateEnd = date('Y-m-d 00:00:00', strtotime('+60 days'));

        if($agenda_unica){
            // $dados = array_map(
            //     JSON_DECODE($agenda_unica->dias_unicos),
            //     function ($e) use ($dateStart){
            //         $data_unica = date('Y-m-d 00:00:00', strtotime($e->date));

            //         if($data_unica >= $dateStart){
            //             return $e;
            //         }
            //     }
            // );
            // $dados = array_map(function ($e) use ($dateStart){
            //     $data_unica = date('Y-m-d 00:00:00', strtotime($e->date));
            //     if($data_unica <= $dateStart){
            //         if(!array_key_exists($data_unica, $this->datasAtendimentos)){
            //             // $this->datasAtendimentos[$data_unica] = $data_unica;
            //             return $e;
            //         }
            //     }
            // }, JSON_DECODE($agenda_unica->dias_unicos));
            $dados = collect(JSON_DECODE($agenda_unica->dias_unicos))
            ->filter(function ($e) use($dateStart, $dateEnd, $datasEscolhidas) {
                // $data_unica = date('Y-m-d 00:00:00', strtotime($e->date));
                $data_unica = \Carbon\Carbon::createFromFormat('d/m/Y', $e->date)->format('Y-m-d H:i:s');
                // dd(\Carbon\Carbon::createFromFormat('d/m/Y', $e->date)->format('Y-m-d H:i:s'), $e->date, $data_unica,$dateStart,$dateEnd);
                if($data_unica >= $dateStart){
                    if($data_unica <= $dateEnd){
                        if(!array_key_exists($data_unica, $datasEscolhidas)){
                            // $this->datasAtendimentos[$data_unica] = $data_unica;
                            return $data_unica;
                        }
                    }
                }
            })
            ->map(function ($e){
                // $data_unica = date('Y-m-d 00:00:00', strtotime($e->date));
                //
                return [
                    'data' => $e->date,
                ];
            });
            // dd($dados);
            if($dados){
                // while($dateStart < date('Y-m-d 00:00:00', strtotime('+30 days'))){

                    // $diaSemana = $this->retornaData(strftime("%u",strtotime($dateStart)));

                    // $existe = false;
                    foreach ($dados as $key => $value) {

                        if(!array_key_exists($value['data'], $datasEscolhidas)){
                            $dataExplode = explode('/', $value['data']);

                            $data_unica = date('Y-m-d 00:00:00', strtotime($dataExplode[2].'-'.$dataExplode[1].'-'.$dataExplode[0]));
                            // if($data_unica <= $dateStart){
                                // if($data_unica == $diaSemana){
                                    // $existe = true;
                                    // $arrayUnica[$diaSemana] = 1;
                                    $datasEscolhidas[$data_unica] = $data_unica;
                                    $datasAtendimentos[] = $data_unica;
                                // }
                            // }
                        }

                        // if($existe == false){
                        //     $arrayUnica[$diaSemana] = 0;
                        // }
                    }

                    // $dateStart = date('Y-m-d 00:00:00', strtotime($dateStart.' +1 days'));
                // };
            }
        }

        return response()->json($datasAtendimentos);
    }

    public function getRegistroPesquisa(Request $request){
        $dados['pesquisa'] = $request->input('pesquisa');
        return view('instituicao.agendamentos/registro_pesquisa', \compact('dados'));
    }

    public function uploadGuia(Request $request, Agendamento $agendamento){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        if(!empty($request->file('arquivo_guia_convenio'))){
            $arquivo_nome = $agendamento->id;
            $arquivo_venda = $arquivo_nome.".".$request->arquivo_guia_convenio->getClientOriginalExtension();

            $path = Storage::disk('public')->putFileAs(
                'upload_guias/', $request->file('arquivo_guia_convenio'), $arquivo_venda
            );

           $agendamento->update(['arquivo_guia_convenio' => $path]);
        }
    }

    public function verificaProximoHorarioAgenda(VerificaProximoHorarioRequest $request)
    {
        $instituicao_id = $request->session()->get('instituicao');
        $dados = $request->validated();
        $inst_pres_agenda = InstituicoesAgenda::find($dados['inst_prest_id']);

        abort_unless($instituicao_id === $inst_pres_agenda->prestadores->instituicoes_id, 403);

        //VARIAVEL DE VERIFICAÇÃO
        $verifica = false;

        $agenda = InstituicoesAgenda::where('id', $dados['inst_prest_id'])->first();
        //INICIO VERIFICAÇÃO DE HORARIO ESTRAPOLANDO
        if($agenda->dias_unicos){
            foreach (json_decode($agenda->dias_unicos) as $valueJson) {
                if ($valueJson->date == $dados['data_agenda']) {
                    $duracao = explode(':', $valueJson->duracao_atendimento);
                    $duracao_tempo = $duracao[0]*60+$duracao[1];
                    $duracao_agenda = date('H:i', strtotime(" +".$duracao_tempo." minutes", strtotime($dados['hora_agenda'])));
                    $duracao_agenda_final = date('H:i', strtotime($dados['hora_agenda_final']));

                    $ultimo_horario_agenda = date('H:i', strtotime($valueJson->hora_fim));
                }
            }

        }else{
            //VERIFICA DIA CONTINUO
            $duracao = explode(':', $agenda->duracao_atendimento);
            $duracao_tempo = $duracao[0]*60+$duracao[1];
            $duracao_agenda = date('H:i', strtotime(" +".$duracao_tempo." minutes", strtotime($dados['hora_agenda'])));
            $duracao_agenda_final = date('H:i', strtotime($dados['hora_agenda_final']));

            $ultimo_horario_agenda = date('H:i', strtotime($agenda->hora_fim));

            // dd($duracao_agenda);
        }

        //VERIFICA SE PASSA
        if($duracao_agenda <= $duracao_agenda_final){

            //VERIFICA SE EXISTE UM HORARIO
            while ($duracao_agenda < $duracao_agenda_final) {
                if($duracao_agenda < $ultimo_horario_agenda){
                    $dia_cancelar = $dados['data_agenda'] . ' ' . $duracao_agenda;
                    $proximo_horario = Carbon::createFromFormat('d/m/Y H:i', $dia_cancelar)->format('Y-m-d H:i:s');
                    $proximo_horario_final = Carbon::createFromFormat('d/m/Y H:i', $dados['data_agenda'] . ' ' .date('H:i', strtotime(" +".$duracao_tempo." minutes", strtotime($duracao_agenda))))->format('Y-m-d H:i:s');

                    $agendamento = Agendamento::where('instituicoes_agenda_id', $inst_pres_agenda->id)->where('data', '>=', $proximo_horario)->where('data', '<', $proximo_horario_final)->whereNotIn('status', ['excluir', 'cancelado'])->first();

                    if(!empty($agendamento)){
                        $verifica = true;
                    }
                }

                $duracao_agenda = date('H:i', strtotime(" +".$duracao_tempo." minutes", strtotime($duracao_agenda)));
                // dd($duracao_agenda, $ultimo_horario_agenda, $duracao_agenda_final);
            }
        }

        if($verifica == true){
            return response()->json([
                'icon' => 'warning',
                'title' => 'Alerta',
                'text' => 'Há um proximo atendimento marcado e o atual excede o limite de atendimento'
            ]);
        }else{
            return response()->json([
                'icon' => 'success',
            ]);
        }
    }

    public function getAgendaSemanal(Request $request)
    {
        $data['inicio'] = date('Y-m-d', strtotime(\Carbon\Carbon::createFromFormat('d/m/Y', $request->input('data'))->format('Y-m-d')));
        $data['fim'] = date('Y-m-d', strtotime($data['inicio'].' +1 week'));
        $data['fim'] = date('Y-m-d', strtotime($data['fim'].' -1 day'));

        $usuario_logado = $request->user('instituicao');

        $usuario_prestador = $usuario_logado->prestadorMedico()->get();
        if(count($usuario_prestador) > 0 && $usuario_prestador[0]->tipo == 2){
            $prestador_especialidade_id = $usuario_prestador[0]->id;
        }

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        //PEGA TODOS OS PRESTADORES
        $prestadores = Especialidade::
        whereHas('prestadoresInstituicao', function($q) use($usuario_prestador, $instituicao, $usuario_logado){
            $q->where('ativo', 1);
            if(!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_agenda_prestador')){
                if(count($usuario_prestador) > 0 && $usuario_prestador[0]->tipo == 2){
                    $q->where('instituicao_usuario_id', $usuario_logado->id);
                }
            }
            $q->where('instituicoes_id',$instituicao->id);
        })
        ->with([
            'prestadoresInstituicao' => function($q) use($usuario_prestador, $instituicao, $usuario_logado){
                $q->where('ativo', 1);
                $q->where('instituicoes_id',$instituicao->id);
                if(!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_agenda_prestador')){
                    if(count($usuario_prestador) > 0 && $usuario_prestador[0]->tipo == 2){
                        $q->where('instituicao_usuario_id', $usuario_logado->id);
                    }
                }
            },
            'prestadoresInstituicao.prestador' => function($q){
                $q->select('id','nome');
            }
        ])->get();


        // $horarioAgenda = response()->json($horarioAgenda);
        // dd($horarioAgenda, 'aqui1');
        return view('instituicao.agendamentos.agenda_semanal', \compact('prestadores', 'data'));
    }

    public function getDadosSemanal(Request $request)
    {
        $data['inicio'] = date('Y-m-d', strtotime(\Carbon\Carbon::createFromFormat('d/m/Y', $request->input('data'))->format('Y-m-d')));
        $data['fim'] = date('Y-m-d', strtotime($data['inicio'].' +1 week'));
        $data['fim'] = date('Y-m-d', strtotime($data['fim'].' -1 day'));

        //PEGA OS AGENDAMENTOS EXISTENTES
        $agendamentos = Agendamento::where('status', '<>', 'excluir')->whereNotNull('instituicoes_agenda_id')
            // ->whereNotNull('pessoa_id')
            ->whereHas('instituicoesAgendaGeral', function($q) use($request){
            // $q->when($this->prestador_especialidade_id, function($q){
                $q->whereHas('prestadores',function($q) use($request){
                    $q->where('instituicoes_prestadores_id', $request->input('prestador'));
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
        ->where('data','>=',$data['inicio'].' 00:00:00')
        ->where('data','<=',$data['fim'].' 23:59:59')
        // ->with([
        //     'agendamentoProcedimento',
        //     'agendamentoProcedimento.procedimentoInstituicaoConvenio' => function($q){
        //         $q->withTrashed();
        //     },
        //     'agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios',
        //     'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao' => function($q){
        //         $q->withTrashed();
        //     },
        //     'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento',
        //     'atendimento',
        //     'instituicoesAgenda',
        //     'instituicoesAgenda.prestadores',
        //     'instituicoesAgenda.prestadores.prestador',
        //     'pessoa',
        //     'usuario',
        //     'agendamentoGuias',
        // ])
        ->orderBy(DB::raw('IF(`data_original` IS NOT NULL, `data_original`, `data`)'), "ASC")->get();
        // dd($agendamentos->toArray());
        $data_inicial = $data['inicio'];

        $horarioAgenda = [];
        $intervalos = [];
        $duracao = [];
        // for ($i=\Carbon\Carbon::createFromFormat('Y-m-d', $data_inicial); $i < \Carbon\Carbon::createFromFormat('Y-m-d', $data['fim']); $i->addDay()) {
        //     $var[] = $i;
        //     // dd($i);
        // }
        while ($data_inicial <= $data['fim']) {

            $dia = \Carbon\Carbon::createFromFormat('Y-m-d', $data_inicial)->format('d/m/Y');
            // $dia_semana = explode("-",$i->dayName)[0];
            // $dia = $i->format('d/m/Y');
            $dia_semana = explode("-",\Carbon\Carbon::createFromFormat('Y-m-d', $data_inicial)->dayName)[0];

            //VERIFICA SE EXISTE DIA UNICO
            $agenda = InstituicoesAgenda::where('instituicoes_prestadores_id',$request->input('prestador'))
            ->whereRaw("json_contains(`dias_unicos`, '".json_encode(['date' => $dia])."')")
            ->first();

            if($agenda){

                try {
                    $horarioAgendaRetorno = $this->montaHorario($agenda, 'unico', $dia, $agendamentos);

                    if(count($horarioAgendaRetorno['intervalos']) > 0){
                        $intervalos[] = $horarioAgendaRetorno['intervalos'];
                    }
                    $duracao[] = $horarioAgendaRetorno['duracao'];

                    if(count($horarioAgendaRetorno['horarios']) > 0){
                        $horarioAgenda[] = $horarioAgendaRetorno['horarios'];
                    }
                } catch (Exception $e) {

                    return response()->json([
                        'icon' => 'error',
                        'title' => 'Erro',
                        'text' => 'Houve um erro ao gerar agendamentos!'
                    ]);
                }

            }else{

                //PEGA DIA CONTINUO SE N EXISTIR DIA UNICO
                $agenda = InstituicoesAgenda::where('instituicoes_prestadores_id',$request->input('prestador'))
                ->where('dias_continuos',$dia_semana)
                ->orderBy('hora_inicio', 'ASC')
                ->get();

                if(count($agenda) > 0){

                    try {
                        $horarioAgendaRetorno = $this->montaHorario($agenda, 'continuo', $dia, $agendamentos);

                        if(count($horarioAgendaRetorno['intervalos']) > 0){
                            $intervalos[] = $horarioAgendaRetorno['intervalos'];
                        }
                        $duracao[] = $horarioAgendaRetorno['duracao'];

                        if(count($horarioAgendaRetorno['horarios']) > 0){
                            $horarioAgenda[] = $horarioAgendaRetorno['horarios'];
                        }
                        // dd($horarioAgenda);
                    } catch (Exception $e) {


                        return response()->json([
                            'icon' => 'error',
                            'title' => 'Erro',
                            'text' => $e->getMessage()
                        ]);
                    }
                }

            }

            $data_inicial = date('Y-m-d', strtotime($data_inicial.' +1 day'));
        }

        $horariosFinal = [];
        $horarioAgendaFinal = [];

        //VERIFICA AGENDA AUSENTE
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $prestador = InstituicoesPrestadores::find($request->input('prestador'));
        $prestador_id = $prestador->prestadores_id;

        $agendaAusente = $instituicao->agendasAusente()
            ->where('data','>=',$data['inicio'])
            ->where('data','<=',$data['fim'])
            ->whereHas('instituicoesPrestadores',function($q) use($prestador_id, $instituicao){
                $q->where('prestador_id', $prestador_id);
                $q->where('instituicao_id', $instituicao->id);
            })
        ->get();

        //MONTA HORARIOS COM INTERVALOS
        if(count($horarioAgenda) > 0){
            for($i = 0; $i < count($horarioAgenda); $i++){
                if(!empty($horarioAgenda[$i])){
                    for ($x=0; $x < count($horarioAgenda[$i]); $x++) {
                        if(!empty($horarioAgenda[$i][$x])){
                            for ($y=0; $y < count($horarioAgenda[$i][$x]); $y++) {
                                if(!empty($horarioAgenda[$i][$x][$y])){
                                    $existeAusente = false;
                                    $motivo = "";
                                    if(count($agendaAusente) > 0){
                                        foreach ($agendaAusente as $key => $value) {
                                            if($value->dia_todo){
                                                if(( date('Y-m-d H:i:s', strtotime($horarioAgenda[$i][$x][$y]['start'])) >= date('Y-m-d H:i:s', strtotime($value->data.' 00:00:00'))) && (date('Y-m-d H:i:s', strtotime($horarioAgenda[$i][$x][$y]['start'])) <= date('Y-m-d H:i:s', strtotime($value->data.' 23:59:59')))){
                                                    $existeAusente = true;
                                                    $motivo = $value->motivo;
                                                }
                                            }else{
                                                if((date('Y-m-d H:i:s', strtotime($horarioAgenda[$i][$x][$y]['start'])) >= date('Y-m-d H:i:s', strtotime($value->data.' '.$value->hora_inicio))) && (date('Y-m-d H:i:s', strtotime($horarioAgenda[$i][$x][$y]['start'])) <= date('Y-m-d H:i:s', strtotime($value->data.' '.$value->hora_fim)))){
                                                    $existeAusente = true;
                                                    $motivo = $value->motivo;
                                                }
                                            }
                                        }

                                        if($existeAusente){
                                            $horarioAgendaFinal[]= [
                                                'id' => 'null',
                                                'title' => 'Horário Ausente',
                                                'start' => $horarioAgenda[$i][$x][$y]['start'], // a property!
                                                'end' => $horarioAgenda[$i][$x][$y]['end'],
                                                'color' => '#e9e7e7',
                                                'textColor' => "#000 !important",
                                                'horarioAgendamento' => date('H:i', strtotime($horarioAgenda[$i][$x][$y]['start'])).' - '.date('H:i', strtotime($horarioAgenda[$i][$x][$y]['end'])),
                                                'status' => 'ausente',
                                                'borderColor' => '#fff',
                                                'texto' => 'Motivo horário ausente: '.$motivo
                                            ];
                                        }else{
                                            $horarioAgendaFinal[] = $horarioAgenda[$i][$x][$y];
                                        }
                                    }else{
                                        $horarioAgendaFinal[] = $horarioAgenda[$i][$x][$y];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        // if(count($intervalos) > 0){
        //     for($i = 0; $i < count($intervalos); $i++){
        //         for ($x=0; $x < count($intervalos[$i]); $x++) {
        //             $horariosFinal[] = $intervalos[$i][$x];
        //         }
        //     }
        // }

        //MONTA DURAÇÃO COM O MENOR INTERVALO
        $inicio_horario = null;
        $fim_horario = null;
        $tempo_duracao = null;

        // dd($duracao);
        if(count($duracao) > 0){
            for($i = 0; $i < count($duracao); $i++){
                for ($x=0; $x < count($duracao[$i]); $x++) {
                    if($inicio_horario == null){
                        $inicio_horario = $duracao[$i][$x]['inicio'];
                        $fim_horario = $duracao[$i][$x]['fim'];
                        $tempo_duracao = $duracao[$i][$x]['duracao'];
                    }else{
                        if(date('H:i:s', strtotime($duracao[$i][$x]['inicio'])) < date('H:i:s', strtotime($inicio_horario))){
                            $inicio_horario = $duracao[$i][$x]['inicio'];
                        }
                        if(date('H:i:s', strtotime($duracao[$i][$x]['fim'])) > date('H:i:s', strtotime($fim_horario))){
                            $fim_horario = $duracao[$i][$x]['fim'];
                        }
                        if(date('H:i:s', strtotime($duracao[$i][$x]['duracao'])) < date('H:i:s', strtotime($tempo_duracao))){
                            $tempo_duracao = $duracao[$i][$x]['duracao'];
                        }
                    }
                }
            }
        }else{
            $inicio_horario = '07:00:00';
            $fim_horario =  '22:00:00';
            $tempo_duracao = '01:00:00';
        }

        if(date('H:i', strtotime($fim_horario)) < "23:00"){
            $fim_horario = date("H:i:s", strtotime($fim_horario." +1 hour"));
        }else{
            $fim_horario = date("H:i:s", strtotime($fim_horario));
        }
        // dd($horarioAgendaFinal, $intervalos, $horariosFinal, $inicio_horario, $fim_horario, $tempo_duracao, $duracao);
        return response()->json(['horarios' => $horarioAgendaFinal, 'inicio_horario' => date("H:i:s", strtotime($inicio_horario)), 'fim_horario' => $fim_horario, 'tempo_duracao' => date('H:i:s', strtotime($tempo_duracao)), 'data_inicio' => $data['inicio']]);
    }

    public function montaHorario($agenda, $tipo, $data_inicial, $agendamentos)
    {
        if($tipo == "unico"){
            $dados = array_filter(
                JSON_DECODE($agenda->dias_unicos),
                function ($e) use ($data_inicial){
                    return $e->date==$data_inicial;
                }
            );

            foreach ($dados as $key => $value) {
                $agendaTipo[] = [
                    'hora_inicio' => $value->hora_inicio.':00',
                    'hora_fim' => $value->hora_fim.':00',
                    'hora_intervalo' => $value->hora_intervalo.':00',
                    'duracao_intervalo' => $value->duracao_intervalo.':00',
                    'duracao_atendimento' => $value->duracao_atendimento.':00',
                ];
            }
        }else{
            $agendaTipo = $agenda;
        }
        $montaHorarios = [];
        $intervalos = [];
        $duracao = [];
        $removeHorarios = [];
        //MONTA HORARIOS
        foreach ($agendaTipo as $item){

            $x = \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_inicio']);
            $removeHorarios[] = date('Y-m-d H:i:s', strtotime($x));
            for ( $i=\Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_inicio']); $i < \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_intervalo']); $i->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_atendimento'])) ){
                $existe_horario = false;
                // if(\Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($i)->format('Y-m-d H:i:s') == '2022-11-01 11:00:00'){
                //     dd('aqui', $existe_horario);
                // }
                $x = $x->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_atendimento']));
                $montaHorarios[] = $this->montaHorarioAtendimento($item, $agendamentos, $data_inicial, $i, $item['hora_inicio'], $existe_horario);
                $removeHorarios[] = date('Y-m-d H:i:s', strtotime($x));
            }

            if(\Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_intervalo'])->format('Y-m-d H:i:s') < \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_intervalo'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_intervalo']))->format('Y-m-d H:i:s')){
                $intervalos[] = [
                    'title' => 'Intervalo',
                    'start' => \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_intervalo'])->format('Y-m-d H:i:s'),
                    'end' => \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_intervalo'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_intervalo']))->format('Y-m-d H:i:s'),
                ];

            }

            $duracao[] = [
                'inicio' => \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_inicio'])->format('Y-m-d H:i:s'),
                'fim' => \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_fim'])->format('Y-m-d H:i:s'),
                'duracao' => \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['duracao_atendimento'])->format('Y-m-d H:i:s'),
            ];

            $x = "";
            $x = \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_intervalo'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_intervalo']));
            $removeHorarios[] = date('Y-m-d H:i:s', strtotime($x));

            for ( $i=\Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_intervalo'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_intervalo'])); $i < \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($item['hora_fim']); $i->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_atendimento'])) ){
                $existe_horario = false;
                $x = $x->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_atendimento']));
                $montaHorarios[] = $this->montaHorarioAtendimento($item, $agendamentos, $data_inicial, $i, $item['hora_intervalo'], $existe_horario);
                $removeHorarios[] = date('Y-m-d H:i:s', strtotime($x));
            }
        }
        // dd($montaHorarios);
        return ['horarios' => $montaHorarios, 'intervalos' => $intervalos, 'duracao' => $duracao, 'removeHorarios' => $removeHorarios];
    }

    public function montaHorarioAtendimento($item, $agendamentos, $data_inicial, $i, $hora, $existe_horario)
    {
        // if ($tipo == "unico") {
        //     $duracao = $item['duracao_atendimento'].":00";
        // }else{
        //     $duracao = $item['duracao_atendimento'];
        // }

        //VERIFICA SE EXISTE AGENDAMENTO NO HORARIO

        $usados = [];
        // if(count($agendamentos) > 0){
        //     for ($x=0; $x < count($agendamentos); $x++) {
        //         // if(array_key_exists($x, $agendamentos)){
        //             // dd($agendamentos[$x]->toArray()) ;

        //         // }
        //     }
        // }
        $montaHorariosAtendimento = [];

        foreach ($agendamentos as $keyA => $agendamento) {
            if( \Carbon\Carbon::parse($agendamento['data']) < $i || (\Carbon\Carbon::parse($agendamento['data']) >= $i &&  \Carbon\Carbon::parse($agendamento['data']) < \Carbon\Carbon::parse($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_atendimento']))) ){

                // if(\Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($i)->format('Y-m-d H:i:s') == '2022-11-01 11:00:00'){
                //     dd($i->format("Y-m-d H:i:s"), $agendamento['data'], \Carbon\Carbon::parse($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_atendimento']))->format('Y-m-d H:i:s'));
                //     dd('aqui', $existe_horario, $montaHorariosAtendimento);
                // }
                $existe_horario = true;
                if ($agendamento->data_final_original){
                    $horario_final = ($agendamento['data_final_original']) ? \Carbon\Carbon::parse($agendamento['data_final_original'])->format('Y-m-d H:i:s') : \Carbon\Carbon::parse($agendamento['data'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_atendimento']))->format('Y-m-d H:i:s');
                    if(date('Y-m-d', strtotime($agendamento->data)) != date('Y-m-d', strtotime($horario_final))){
                        $horario_final = \Carbon\Carbon::parse($agendamento['data'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_atendimento']))->format('Y-m-d H:i:s');
                    }
                } else {
                    $horario_final = ($agendamento['data_final']) ? \Carbon\Carbon::parse($agendamento['data_final'])->format('Y-m-d H:i:s') : \Carbon\Carbon::parse($agendamento['data'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_atendimento']))->format('Y-m-d H:i:s');

                    if(date('Y-m-d', strtotime($agendamento->data)) != date('Y-m-d', strtotime($horario_final))){
                        $horario_final = \Carbon\Carbon::parse($agendamento['data'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_atendimento']))->format('Y-m-d H:i:s');
                    }
                }

                if ($agendamento->data_original){
                    $horario_original = \Carbon\Carbon::parse($agendamento['data_original'])->format('H:i');
                }else{
                    $horario_original = \Carbon\Carbon::parse($agendamento['data'])->format('H:i');
                }

                $cor = (count($agendamento->atendimento) > 0 && $agendamento->atendimento[0]->status == 1) ? Agendamento::status_para_cor('em_consultorio') : Agendamento::status_para_cor($agendamento->status);
                $texto_cor = (count($agendamento->atendimento) > 0 && $agendamento->atendimento[0]->status == 1) ? Agendamento::status_para_cor_texto('em_consultorio') : Agendamento::status_para_cor_texto($agendamento->status);
                $etiqueta = 'black';
                if($agendamento->compromisso_id){
                    $etiqueta = $agendamento->compromisso->cor;
                }

                if($agendamento->status == "cancelado"){
                    if($agendamento->id_referente == null){
                        $montaHorariosAtendimento[] = [
                            'id' => $agendamento->id,
                            'title' => 'Horário cancelado',
                            'start' => $agendamento['data'], // a property!
                            'end' => $horario_final,
                            'color' => $cor,
                            'textColor' => $texto_cor.' !important',
                            'horarioAgendamento' => $horario_original.' - '.date('H:i', strtotime($horario_final)),
                            'status' => $agendamento->status,
                            'borderColor' => $etiqueta,
                            'texto' => 'Horário cancelado'
                        ];
                    // }else{
                    //     $montaHorariosAtendimento[] = [
                    //         'title' => 'nao_exibir',
                    //         'start' => $agendamento['data'], // a property!
                    //         'end' => $horario_final
                    //     ];
                    }

                }else{

                    $exige_card_aut = false;
                    foreach ($agendamento->agendamentoProcedimento as $k => $v) {
                        if($v->procedimentoInstituicaoConvenio->utiliza_parametro_convenio == 0){
                            if($v->procedimentoInstituicaoConvenio->carteirinha_obrigatoria || $v->procedimentoInstituicaoConvenio->aut_obrigatoria){
                                $exige_card_aut = true;
                                break;
                            }
                        }else if($v->procedimentoInstituicaoConvenio->convenios->carteirinha_obg || $v->procedimentoInstituicaoConvenio->convenios->aut_obrigatoria){
                            $exige_card_aut = true;
                            break;
                        }
                    }

                    if ($exige_card_aut && !$agendamento->agendamentoGuias->count()) {
                    }else{
                        $exige_card_aut = false;
                    }

                    $texto = "";
                    if ($agendamento->usuario){
                        $texto = $agendamento->usuario->nome." ";
                    }else{
                        $texto = $agendamento->pessoa->nome." ";
                    }

                    if ($agendamento->usuario){
                        if($agendamento->usuario->telefone){
                            $texto = $texto.' '.$agendamento->usuario->telefone." ";
                        }
                    }else{
                        if($agendamento->pessoa->telefone1){
                            $texto = $texto.' '.$agendamento->pessoa->telefone1." ";
                        }
                    }

                    $texto = $texto.' '.' - '.$agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome;
                    if (count($agendamento->agendamentoProcedimento) == 1){
                        $texto = $texto.' '.' ('.$agendamento->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao.')';
                    }else{
                        $texto .= '(';
                        foreach ($agendamento->agendamentoProcedimento as $key => $value) {
                            $texto .=  $value->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao.", ";
                        }
                        $texto = substr($texto, 0, -2);
                        $texto .= ")";
                    }

                    $montaHorariosAtendimento[] = [
                        'id' => $agendamento->id,
                        'title' => ($exige_card_aut) ? '! '.$agendamento->pessoa->nome : $agendamento->pessoa->nome,
                        'start' => $agendamento['data'], // a property!
                        'end' => $horario_final,
                        'color' => $cor,
                        'textColor' => $texto_cor.' !important',
                        'horarioAgendamento' => $horario_original.' - '.date('H:i', strtotime($horario_final)),
                        'status' => $agendamento->status,
                        'borderColor' => $etiqueta,
                        'texto' => $texto
                    ];
                }
                unset($agendamentos[$keyA]);
                // dd($montaHorariosAtendimento);
                // unset($agendamento);
            }
        }

        // if(count($usados) > 0){

        //     foreach ($usados as $key => $value) {
        //         unset($agendamentos[$value]);
        //     }

        // }

        // if(\Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($i)->format('Y-m-d H:i:s') == '2022-11-01 11:00:00'){
        //     dd('aqui', $existe_horario, $montaHorariosAtendimento);
        // }
        if($existe_horario == false && date('Y-m-d') <= $i->format('Y-m-d')){
            $start = \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($i)->format('Y-m-d H:i:s');
            $end = \Carbon\Carbon::createFromFormat('d/m/Y', $data_inicial)->setTimeFromTimeString($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $item['duracao_atendimento']))->format('Y-m-d H:i:s');
            $montaHorariosAtendimento[] = [
                'id' => 'null',
                'title' => 'Horário disponível',
                'start' => $start, // a property!
                'end' => $end,
                'color' => '#9f9f9f',
                'textColor' => "#000 !important",
                'horarioAgendamento' => date('H:i', strtotime($start)).' - '.date('H:i', strtotime($end)),
                'status' => 'livre',
                'borderColor' => 'black',
                'texto' => 'Horário disponível'
            ];
            // dd('$montaHorariosAtendimento', $montaHorariosAtendimento, $data_inicial, $item['duracao_atendimento']);
        }
        // dd($montaHorariosAtendimento);
        if(!empty($montaHorariosAtendimento)){
            return $montaHorariosAtendimento;
        }
    }

    public function getDadosSemanalDia(Request $request)
    {
        $data['inicio'] = date('Y-m-d', strtotime(\Carbon\Carbon::createFromFormat('d/m/Y', $request->input('data'))->format('Y-m-d')));

        //PEGA OS AGENDAMENTOS EXISTENTES
        $agendamentos = Agendamento::where('status', '<>', 'excluir')->whereNotNull('instituicoes_agenda_id')
        ->whereHas('instituicoesAgendaGeral', function($q) use($request){
            $q->whereHas('prestadores',function($q) use($request){
                $q->where('instituicoes_prestadores_id', $request->input('prestador'));
            });
        })
        ->where('data', '>=',$data['inicio'].' 00:00:00')
        ->where('data', '<=',$data['inicio'].' 23:59:00')
        ->orderBy(DB::raw('IF(`data_original` IS NOT NULL, `data_original`, `data`)'), "ASC")->get();

        $data_inicial = $data['inicio'];

        $horarioAgenda = [];
        $intervalos = [];
        $duracao = [];
        $removeHorarios = [];

        $dia = \Carbon\Carbon::createFromFormat('Y-m-d', $data_inicial)->format('d/m/Y');
        $dia_semana = explode("-",\Carbon\Carbon::createFromFormat('Y-m-d', $data_inicial)->dayName)[0];

        //VERIFICA SE EXISTE DIA UNICO
        $agenda = InstituicoesAgenda::where('instituicoes_prestadores_id',$request->input('prestador'))
        ->whereRaw("json_contains(`dias_unicos`, '".json_encode(['date' => $dia])."')")
        ->first();

        if($agenda){
            try {
                $horarioAgendaRetorno = $this->montaHorario($agenda, 'unico', $dia, $agendamentos);

                $removeHorarios = $horarioAgendaRetorno['removeHorarios'];

                if(count($horarioAgendaRetorno['intervalos']) > 0){
                    $intervalos[] = $horarioAgendaRetorno['intervalos'];
                }
                $duracao[] = $horarioAgendaRetorno['duracao'];

                if(count($horarioAgendaRetorno['horarios']) > 0){
                    $horarioAgenda[] = $horarioAgendaRetorno['horarios'];
                }
            } catch (Exception $e) {
                // dd($e);
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Erro',
                    'text' => 'Houve um erro ao gerar agendamentos!'
                ]);
            }

        }else{

            //PEGA DIA CONTINUO SE N EXISTIR DIA UNICO
            $agenda = InstituicoesAgenda::where('instituicoes_prestadores_id',$request->input('prestador'))
            ->where('dias_continuos',$dia_semana)
            ->orderBy('hora_inicio', 'ASC')
            ->get();

            if(count($agenda) > 0){
                try {
                    $horarioAgendaRetorno = $this->montaHorario($agenda, 'continuo', $dia, $agendamentos);

                    $removeHorarios = $horarioAgendaRetorno['removeHorarios'];

                    if(count($horarioAgendaRetorno['intervalos']) > 0){
                        $intervalos[] = $horarioAgendaRetorno['intervalos'];
                    }
                    $duracao[] = $horarioAgendaRetorno['duracao'];

                    if(count($horarioAgendaRetorno['horarios']) > 0){
                        $horarioAgenda[] = $horarioAgendaRetorno['horarios'];
                    }
                    // dd($horarioAgenda);
                } catch (Exception $e) {

                    // dd($e);
                    return response()->json([
                        'icon' => 'error',
                        'title' => 'Erro',
                        'text' => $e->getMessage()
                    ]);
                }
            }

        }

        $data_inicial = date('Y-m-d', strtotime($data_inicial.' +1 day'));

        $horariosFinal = [];
        $horarioAgendaFinal = [];
        $horarioRemove = [];

        //VERIFICA AGENDA AUSENTE
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $prestador = InstituicoesPrestadores::find($request->input('prestador'));
        $prestador_id = $prestador->prestadores_id;

        $agendaAusente = $instituicao->agendasAusente()
            ->where('data',$data['inicio'])
            ->whereHas('instituicoesPrestadores',function($q) use($prestador_id, $instituicao){
                $q->where('prestador_id', $prestador_id);
                $q->where('instituicao_id', $instituicao->id);
            })
        ->get();

        // dd($horarioAgenda);
        //MONTA HORARIOS COM INTERVALOS
        if(count($horarioAgenda) > 0){
            for($i = 0; $i < count($horarioAgenda); $i++){
                if(!empty($horarioAgenda[$i])){
                    for ($x=0; $x < count($horarioAgenda[$i]); $x++) {
                        if(!empty($horarioAgenda[$i][$x])){
                            for ($y=0; $y < count($horarioAgenda[$i][$x]); $y++) {
                                if(!empty($horarioAgenda[$i][$x][$y])){
                                    $existeAusente = false;
                                    $motivo = "";
                                    if(count($agendaAusente) > 0){
                                        foreach ($agendaAusente as $key => $value) {
                                            if($value->dia_todo){
                                                if(( date('Y-m-d H:i:s', strtotime($horarioAgenda[$i][$x][$y]['start'])) >= date('Y-m-d H:i:s', strtotime($value->data.' 00:00:00'))) && (date('Y-m-d H:i:s', strtotime($horarioAgenda[$i][$x][$y]['start'])) <= date('Y-m-d H:i:s', strtotime($value->data.' 23:59:59')))){
                                                    $existeAusente = true;
                                                    $motivo = $value->motivo;
                                                }
                                            }else{
                                                if((date('Y-m-d H:i:s', strtotime($horarioAgenda[$i][$x][$y]['start'])) >= date('Y-m-d H:i:s', strtotime($value->data.' '.$value->hora_inicio))) && (date('Y-m-d H:i:s', strtotime($horarioAgenda[$i][$x][$y]['start'])) <= date('Y-m-d H:i:s', strtotime($value->data.' '.$value->hora_fim)))){
                                                    $existeAusente = true;
                                                    $motivo = $value->motivo;
                                                }
                                            }
                                        }

                                        if($existeAusente){
                                            $horarioAgendaFinal[]= [
                                                'id' => 'null',
                                                'title' => 'Horário Ausente',
                                                'start' => $horarioAgenda[$i][$x][$y]['start'], // a property!
                                                'end' => $horarioAgenda[$i][$x][$y]['end'],
                                                'color' => '#e9e7e7',
                                                'textColor' => "black",
                                                'horarioAgendamento' => date('H:i', strtotime($horarioAgenda[$i][$x][$y]['start'])).' - '.date('H:i', strtotime($horarioAgenda[$i][$x][$y]['end'])),
                                                'status' => 'ausente',
                                                'borderColor' => 'black',
                                                'texto' => 'Motivo horário ausente: '.$motivo
                                            ];
                                        }else{
                                            $horarioAgendaFinal[] = $horarioAgenda[$i][$x][$y];
                                        }
                                    }else{
                                        $horarioAgendaFinal[] = $horarioAgenda[$i][$x][$y];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // if(count($intervalos) > 0){
        //     for($i = 0; $i < count($intervalos); $i++){
        //         for ($x=0; $x < count($intervalos[$i]); $x++) {
        //             $horariosFinal[] = $intervalos[$i][$x];
        //         }
        //     }
        // }

        if(count($removeHorarios) > 0){
            for($i = 0; $i < count($removeHorarios); $i++){
                // for ($x=0; $x < count($removeHorarios[$i]); $x++) {
                    $horarioRemove[] = $removeHorarios[$i];
                // }
            }
        }

        //MONTA DURAÇÃO COM O MENOR INTERVALO
        $inicio_horario = null;
        $fim_horario = null;
        $tempo_duracao = null;

        // dd($duracao);
        if(count($duracao) > 0){
            for($i = 0; $i < count($duracao); $i++){
                for ($x=0; $x < count($duracao[$i]); $x++) {
                    if($inicio_horario == null){
                        $inicio_horario = $duracao[$i][$x]['inicio'];
                        $fim_horario = $duracao[$i][$x]['fim'];
                        $tempo_duracao = $duracao[$i][$x]['duracao'];
                    }else{
                        if(date('H:i:s', strtotime($duracao[$i][$x]['inicio'])) < date('H:i:s', strtotime($inicio_horario))){
                            $inicio_horario = $duracao[$i][$x]['inicio'];
                        }
                        if(date('H:i:s', strtotime($duracao[$i][$x]['fim'])) > date('H:i:s', strtotime($fim_horario))){
                            $fim_horario = $duracao[$i][$x]['fim'];
                        }
                        if(date('H:i:s', strtotime($duracao[$i][$x]['duracao'])) < date('H:i:s', strtotime($tempo_duracao))){
                            $tempo_duracao = $duracao[$i][$x]['duracao'];
                        }
                    }
                }
            }
        }
        // dd($horarioRemove);
        if(date('H:i', strtotime($fim_horario)) < "23:00"){
            $fim_horario = date("H:i:s", strtotime($fim_horario." +1 hour"));
        }else{
            $fim_horario = date("H:i:s", strtotime($fim_horario));
        }
        // dd($horarioAgendaFinal, $inicio_horario, $fim_horario, $tempo_duracao,  $horarioRemove, $data['inicio']);
        return response()->json(['removes' => $horarioRemove,'horarios' => $horarioAgendaFinal, 'inicio_horario' => date("H:i:s", strtotime($inicio_horario)), 'fim_horario' => $fim_horario, 'tempo_duracao' => date('H:i:s', strtotime($tempo_duracao)), 'data_inicio' => $data['inicio']]);
    }

    public function geraBoelto(Request $request, Agendamento $agendamento){
        $contas_rec = ContaReceber::
            where('agendamento_id', $agendamento->id)
            ->where('forma_pagamento', 'boleto_cobranca')
            ->where('cancelar_parcela', 0)
        ->get();

        if($request->input('acompanhante')){
           $agendamento->update(['boleto_acompanhante' => 1]);
        }

        $contasReceber = new ContasReceber;

        $boletos = "";

        $i = "";

        foreach($contas_rec as $item){
            $i = $contasReceber->geraBoleto($request, $item);
            if(is_array($i)){
                break;
            }else{
                $boletos .= $i;
            }
        }

        if(is_array($i)){
            return $i;
        }else{
            return $boletos;
        }
    }

    public function retornaPendente(Request $request, Agendamento $agendamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'retorno_agendamento_pendente');
        abort_unless($agendamento->pessoa->instituicao_id === $request->session()->get('instituicao'), 403);

        DB::transaction(function() use($agendamento, $request){
            $usuario_logado = $request->user('instituicao');
            if($agendamento->status == "ausente" || $agendamento->status == "cancelado"){
                $instituicao = Instituicao::find($request->session()->get('instituicao'));
                if($instituicao->ausente_agenda){
                    if($agendamento->data_original){
                        $agendamento->update(['data_original' => null, 'data_final_original' => null, 'data' => $agendamento->data_original, 'data_final' => $agendamento->data_final_original]);
                    }
                }
            }

            $status_anterior = Agendamento::status_para_texto($agendamento->status);
            $agendamento->update(['status' => 'pendente']);
            $agendamento->criarLogEdicao($usuario_logado, $request->session()->get('instituicao'));

            if(count($agendamento->atendimento) > 0){
                foreach ($agendamento->atendimento as $key => $value) {
                    $value->delete();
                }
            }

            AuditoriaAgendamento::logAgendamento($agendamento->id, 'pendente', $usuario_logado->id, 'retorno_pendente', "Alterado de: [{$status_anterior}] para: [pendente]");
        });

        return response()->json(['data' => date('d/m/Y', strtotime($agendamento->data))]);
    }

    public function setDesistencia(Request $request, Agendamento $agendamento)
    {
        // $this->authorize('habilidade_instituicao_sessao', 'retorno_agendamento_pendente');
        abort_unless($agendamento->pessoa->instituicao_id === $request->session()->get('instituicao'), 403);

        DB::transaction(function() use($agendamento, $request){
            $usuario_logado = $request->user('instituicao');
            if($agendamento->status == "agendado" | $agendamento->status == "em_consultorio"){
                $instituicao = Instituicao::find($request->session()->get('instituicao'));

                $status_anterior = Agendamento::status_para_texto($agendamento->status);
                $agendamento->update(['status' => 'desistencia', 'motivo_desistencia' => $request->input('motivo_desistencia')]);
                $agendamento->criarLogEdicao($usuario_logado, $request->session()->get('instituicao'));

                AuditoriaAgendamento::logAgendamento($agendamento->id, 'desistencia', $usuario_logado->id, 'desistencia', "Alterado de: [{$status_anterior}] para: [desistencia]");


                $contasReceber = $agendamento->contaReceber()->get();
                foreach($contasReceber AS $item){
                    $item->update(['cancelar_parcela' => 1]);
                    $item->criarLogEdicao($usuario_logado, $request->session()->get('instituicao'));
                }

            }            
        });

        return response()->json(['data' => date('d/m/Y', strtotime($agendamento->data))]);
    }

    public function getGias(Request $request, Agendamento $agendamento){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $agendamento = Agendamento::whereHas('instituicoesAgenda', function ($q) use ($request, $instituicao) {
            $q->where(function ($q) use ($request, $instituicao) {
                $q->where(function ($q) use ($request, $instituicao) {
                    $q->whereHas('prestadores', function ($q) use ($request, $instituicao) {
                        $q->where('instituicoes_id', $instituicao->id);
                    });
                })
                    ->orWhere(function ($q) use ($request, $instituicao) {
                        $q->whereHas('procedimentos', function ($q) use ($request, $instituicao) {
                            $q->where('instituicoes_id', $instituicao->id);
                        });
                    })
                    ->orWhere(function ($q) use ($request, $instituicao) {
                        $q->whereHas('grupos', function ($q) use ($request, $instituicao) {
                            $q->where('instituicao_id', $instituicao->id);
                        });
                    });
            });
        })->with([
            'pessoa',
            'instituicoesAgenda',
            'instituicoesAgenda.prestadores',
            'instituicoesAgenda.prestadores.especialidade',
            'instituicoesAgenda.prestadores.prestador',
            'agendamentoProcedimento',
            'agendamentoProcedimento.procedimentoInstituicaoConvenio',
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios' => function ($q) {
                $q->withTrashed();
            },
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao' => function ($q) {
                $q->withTrashed();
            },
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento',
            'agendamentoGuias',
        ])
        ->find($agendamento->id);

        $tipo_guia_dados = collect($agendamento->agendamentoProcedimento)
        ->filter(function ($procedimento) {
            return !empty($procedimento);
        })
        ->map(function ($procedimento){
            // dd($procedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo);
            return [
                "divisao_tipo_guia" => $procedimento->procedimentoInstituicaoConvenioTrashed->convenios->divisao_tipo_guia,
                "tipo_guia" => $procedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->tipo_guia,
                'carteirinha_obg' => $procedimento->procedimentoInstituicaoConvenioTrashed->utiliza_parametro_convenio ? (int)  $procedimento->procedimentoInstituicaoConvenioTrashed->convenios->carteirinha_obg : $procedimento->procedimentoInstituicaoConvenioTrashed->carteirinha_obrigatoria,
                'aut_obg' => $procedimento->procedimentoInstituicaoConvenioTrashed->utiliza_parametro_convenio ? $procedimento->procedimentoInstituicaoConvenioTrashed->convenios->aut_obrigatoria : $procedimento->procedimentoInstituicaoConvenioTrashed->aut_obrigatoria,
                'tipo' => $procedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->tipo
            ];

        });

        $tipo_guia = array_unique(array_column($tipo_guia_dados->toArray(), "tipo_guia"));
        $divisao_guia = in_array(2, array_unique(array_column($tipo_guia_dados->toArray(), "divisao_tipo_guia"))) ? 'separado' : 'junto';

        $carteirinha = array_unique(array_column($tipo_guia_dados->toArray(), "carteirinha_obg"));
        $aut = array_unique(array_column($tipo_guia_dados->toArray(), "aut_obg"));
        rsort($tipo_guia);
        $tipo =  array_unique(array_column($tipo_guia_dados->toArray(), "tipo"));
        

        return view("instituicao.agendamentos/guias_sancoop", compact("agendamento", 'divisao_guia', 'tipo_guia', 'carteirinha', 'aut', 'tipo'))->render();
    }

    public function atualiza_guia ($agendamento_id){
        $agendamento = Agendamento::with([
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios' => function ($q) {
                $q->withTrashed();
            },
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao' => function ($q) {
                $q->withTrashed();
                $q->with('procedimento');
            },
            'agendamentoGuias',
        ])
        ->find($agendamento_id);

        $tipo_guia_dados = collect($agendamento->agendamentoProcedimento)
        ->filter(function ($procedimento) {
            return !empty($procedimento);
        })
        ->map(function ($procedimento){
            // dd($procedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo);
            return [
                "divisao_tipo_guia" => $procedimento->procedimentoInstituicaoConvenioTrashed->convenios->divisao_tipo_guia,
                "tipo_guia" => $procedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->tipo_guia,
                'carteirinha_obg' => $procedimento->procedimentoInstituicaoConvenioTrashed->utiliza_parametro_convenio ? (int)  $procedimento->procedimentoInstituicaoConvenioTrashed->convenios->carteirinha_obg : $procedimento->procedimentoInstituicaoConvenioTrashed->carteirinha_obrigatoria,
                'aut_obg' => $procedimento->procedimentoInstituicaoConvenioTrashed->utiliza_parametro_convenio ? $procedimento->procedimentoInstituicaoConvenioTrashed->convenios->aut_obrigatoria : $procedimento->procedimentoInstituicaoConvenioTrashed->aut_obrigatoria,
                'tipo' => $procedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->tipo
            ];

        });

        $tipo_guia = array_unique(array_column($tipo_guia_dados->toArray(), "tipo_guia"));
        $divisao_guia = in_array(2, array_unique(array_column($tipo_guia_dados->toArray(), "divisao_tipo_guia"))) ? 'separado' : 'junto';

        $carteirinha = array_unique(array_column($tipo_guia_dados->toArray(), "carteirinha_obg"));
        $aut = array_unique(array_column($tipo_guia_dados->toArray(), "aut_obg"));
        rsort($tipo_guia);
        $tipo =  array_unique(array_column($tipo_guia_dados->toArray(), "tipo"));

        if($divisao_guia == "junto" && sizeof($agendamento->agendamentoGuias) > 0 && count($tipo_guia) > 0){
            if($tipo_guia[0] == 2 && $agendamento->agendamentoGuias[0]->tipo_guia == 'consulta'){
                $agendamento->agendamentoGuias[0]->update(['tipo_guia' => 'sadt']);
            }else if($tipo_guia[0] == 1 && $agendamento->agendamentoGuias[0]->tipo_guia == 'sadt'){
                $agendamento->agendamentoGuias[0]->update(['tipo_guia' => 'consulta']);
            }
        }
    }

    /*TELEATENDIMENTO EVIDA*/

    function cria_agenda_teleatendimento($agenda_prestador, $pessoa_id, $agendamento_id)
    {

        $pessoa = Pessoa::where(['id' => $pessoa_id])->first();

        $prestador_agenda = InstituicoesAgenda::where('id', $agenda_prestador)
                    ->with('prestadores')
                    ->with('prestadores.prestador')
                    ->first();

        $agendamento = Agendamento::where('id', $agendamento_id)
                    // ->with('prestadores')
                    // ->with('prestadores.prestador')
                    ->first();

                    // dd($agendamento);

        //VERIFICANDO SE JÁ EXISTE A PESSOA CRIADA NA API NO BANCO LOCAL
        if(!empty($pessoa->teleatendimento_id_pessoa)):
            $paciente_atendido = $pessoa->teleatendimento_id_pessoa;
        else:

            //CONFERINDO SE JÁ EXISTE VINCULO NO EVIDA/BUSCARE
            $pessoa_evida = $this->consultaPessoaEvida($pessoa->cpf);

            //SE EXISTIR VAMOS APENAS ATUALIZAR
            if(!empty($pessoa_evida)):
                $atualizar_pessoa['teleatendimento_id_pessoa'] = $pessoa_evida;
                $paciente_atendido = $pessoa_evida;
            //SE NÃO TEMOS QUE CRIAR
            else:
                if($novo_pessoa_id = $this->criarPessoaEvida($pessoa)):
                    $atualizar_pessoa['teleatendimento_id_pessoa'] = $novo_pessoa_id;
                    $paciente_atendido = $novo_pessoa_id;
                endif;
            endif;

            //POR FIM ATUALIZAMOS NO BANCO LOCAL
            if(!empty($atualizar_pessoa)):
                DB::table('pessoas')
                ->where('id', $pessoa->id)
                ->update(array(
                    'teleatendimento_id_pessoa' => $atualizar_pessoa['teleatendimento_id_pessoa']
                ));
            endif;

        endif;

        //VERIFICANDO SE JÁ EXISTE O PRESTADOR CRIADO NA API
        if(!empty($prestador_agenda->prestadores->prestador->teleatendimento_id_prestador)):
            $prestador_atendimento = $prestador_agenda->prestadores->prestador->teleatendimento_id_prestador;
        else:

            //CONFERINDO SE JÁ EXISTE VINCULO NO EVIDA/BUSCARE
            $prestador_evida = $this->consultaPrestadorEvida($prestador_agenda->prestadores->prestador->cpf);

            //SE EXISTIR VAMOS APENAS ATUALIZAR
            if(!empty($prestador_evida)):
                $atualizar_prestador['teleatendimento_id_prestador'] = $prestador_evida;
                $prestador_atendimento = $prestador_evida;
            //SE NÃO TEMOS QUE CRIAR
            else:
                if($novo_prestador_id = $this->criarPrestadorEvida($prestador_agenda->prestadores->prestador,$prestador_agenda->prestadores)):
                    $atualizar_prestador['teleatendimento_id_prestador'] = $novo_prestador_id;
                    $prestador_atendimento = $novo_prestador_id;
                endif;
            endif;

            //POR FIM ATUALIZAMOS NO BANCO LOCAL
            if(!empty($atualizar_prestador)):
                DB::table('prestadores')
                ->where('id', $prestador_agenda->prestadores->prestador->id)
                ->update(array(
                    'teleatendimento_id_prestador' => $atualizar_prestador['teleatendimento_id_prestador']
                ));
            endif;

        endif;

        //AGORA VAMOS INSERIR O REGISTRO NA API
        if(!empty($paciente_atendido) && !empty($prestador_atendimento)):

            //SETANDO A HORA COMO MT-03 SAO PAULO
            $dt_agenda = strtotime($agendamento->data);
            $data_inicio_agenda = date("Y-m-d H:i:s", strtotime('+3 hours', $dt_agenda));

            $dt_fim_agenda = strtotime($agendamento->data_final);
            $data_fim_agenda = date("Y-m-d H:i:s", strtotime('+3 hours', $dt_fim_agenda));

            // $data_inicio_agenda =  date($agendamento->data, strtotime('+3 hours'));
            // $data_fim_agenda =  date($agendamento->data_final, strtotime('+3 hours', strtotime($agendamento->data_final)));

            // $now = strtotime($agendamento->data);
            // $new_time = date("Y-m-d H:i:s", strtotime('+3 hours', $now));

            // echo $new_time;
            // exit;

            $inicio = explode(' ', $data_inicio_agenda);
            $inicio = $inicio[0].'T'.$inicio[1].'.000Z';

            $fim = explode(' ', $data_fim_agenda);
            $fim = $fim[0].'T'.$fim[1].'.000Z';

            $params = 

                [  
                    "title" => "Teleatendimento",
                    "start_date_time" => $inicio,
                    "end_date_time" => $fim,
                    "participants" => [
                        [
                            "id" => $prestador_atendimento,
                            "role" => "MMD",
                        ],
                        [
                            "id" => $paciente_atendido,
                            "role" => "PAT",
                        ]
                    ],
               ];


        //         echo '<pre>';
        // print_r($params);
        // exit;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.v2.doutoraovivo.com.br/appointment/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    
        $headers = [
            "Content-Type: application/json",
            "x-api-key: cCuWVa12Xo2ZOml2JMFuT4o3DwvKB3Ke3O79L2zH"
        ];
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // echo '<pre>';
        // print_r($return);
        // exit;
    
        curl_close($ch);


            if(!empty($return['result']->participants)):

             return $return['result']->participants;
    
            else:
    
            return false;
    
            endif;


        endif;


    }


    //APIS PESSOA
    public function consultaPessoaEvida($cpf)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.v2.doutoraovivo.com.br/person/'.$cpf.'');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        $headers = [
            "Content-Type: application/json",
            "x-api-key: cCuWVa12Xo2ZOml2JMFuT4o3DwvKB3Ke3O79L2zH"
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        // echo '<pre>';
        // print_r($return);
        // exit;

        //47ad4603-7334-4126-8d2a-d546f5e4da92

       if(!empty($return['result']) && !empty($return['result']->id)):

        return $return['result']->id;

       else:

        return false;

       endif;


    }

    public function criarPessoaEvida($pessoa)
    {

        // echo '<pre>';
        // print_r($pessoa);
        // exit;

        //FORMATANDO CPF
        $cpf = str_replace('.','',$pessoa->cpf);
        $cpf = str_replace('-','',$cpf);

        //FORMATANDO TELEFONE
        $telefone = str_replace('(','',$pessoa->telefone1);
        $telefone = str_replace(')','',$telefone);
        $telefone = str_replace(' ','',$telefone);
        $telefone = str_replace('-','',$telefone);

        //SEXO
        if($pessoa->sexo == 'm'):
            $sexo = 'M';
        else:
            $sexo = 'F';
        endif;


        $params['id'] = $pessoa->cpf;
        $params['name'] = $pessoa->nome;

        if(!empty($pessoa->email)):
		 $params['email'] = $pessoa->email;
        endif;

		$params['cpf'] = $cpf ;
		$params['registration'] = 'asa'.$pessoa->cpf;
		$params['birth_date'] = $pessoa->nascimento;
		$params['gender'] = $sexo;
		$params['cell_phone'] = $telefone;


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.v2.doutoraovivo.com.br/person/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    
        $headers = [
            "Content-Type: application/json",
            "x-api-key: cCuWVa12Xo2ZOml2JMFuT4o3DwvKB3Ke3O79L2zH"
        ];
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // echo '<pre>';
        // print_r($return);
        // exit;
    
        curl_close($ch);

        if(!empty($return['result']) && !empty($return['result']->id)):

        return $return['result']->id;

        else:

        return false;

        endif;

    }

    //APIS PRESTADORES
    public function consultaPrestadorEvida($cpf)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.v2.doutoraovivo.com.br/professional/'.$cpf.'');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        $headers = [
            "Content-Type: application/json",
            "x-api-key: cCuWVa12Xo2ZOml2JMFuT4o3DwvKB3Ke3O79L2zH"
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        // echo '<pre>';
        // print_r($return);
        // exit;

        //47ad4603-7334-4126-8d2a-d546f5e4da92

       if(!empty($return['result']) && !empty($return['result']->id)):

        return $return['result']->id;

       else:

        return false;

       endif;


    }

    /* APIS EVIDA / BUSCARE */

    public function criarPrestadorEvida($prestador,$prestador_instituicao)
    {

        // echo '<pre>';
        // print_r($prestador);
        // exit;

        //FORMATANDO CPF
        $cpf = str_replace('.','',$prestador->cpf);
        $cpf = str_replace('-','',$cpf);

        //FORMATANDO TELEFONE
        $telefone = str_replace('(','',$prestador_instituicao[0]->telefone);
        $telefone = str_replace(')','',$telefone);
        $telefone = str_replace(' ','',$telefone);
        $telefone = str_replace('-','',$telefone);

        //CONSELHO ***************VERIFICAR COM BUSCARE SE ACEITA TODOS DO ASA
        if($prestador_instituicao[0]->tipo_conselho_id == 1):
            $conselho = 'CRM';
        endif;

        //SEXO
        if($prestador->sexo == 1):
            $sexo = 'M';
        else:
            $sexo = 'F';
        endif;


        $params['id'] = $prestador->cpf;
        $params['name'] = $prestador->nome;
		$params['email'] = $prestador->email;
		$params['cpf'] = $cpf ;
		$params['license_number'] = $prestador_instituicao[0]->crm;
		$params['license_council'] = $conselho;
		$params['license_region'] = $prestador_instituicao[0]->conselho_uf;
		$params['birth_date'] = $prestador->nascimento;
		$params['gender'] = $sexo;
		$params['cell_phone'] = $telefone;


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.v2.doutoraovivo.com.br/professional/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    
        $headers = [
            "Content-Type: application/json",
            "x-api-key: cCuWVa12Xo2ZOml2JMFuT4o3DwvKB3Ke3O79L2zH"
        ];
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        curl_close($ch);

        if(!empty($return['result']) && !empty($return['result']->id)):

        return $return['result']->id;

        else:

        return false;

        endif;

    }



    /*FIM TELEATENDIMENTO EVIDA*/

    /* INTEGRAÇÃO ASAPLAN */

    public function getInfoAsaplan(Request $request, Pessoa $pessoa)
    {

        echo $pessoa->asaplan_situacao_plano;

    }

    /* FIM INTEGRAÇÃO ASAPLAN */


    /* FUNÇÕES SANCOOP */

    public function atualiza_dados_guia_protocolo_sancoop($instituicao, $agenda)
    {

        $agendamento = Agendamento::where('id', $agenda)
                                        ->with('carteirinha')
                                        ->with('instituicoesAgenda.prestadores.prestador')
                                        ->with('instituicoesAgenda.prestadores.procedimentosExcessoes')
                                        ->with('agendamentoProcedimento.procedimentoInstituicaoConvenio')
                                        ->with('agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios')
                                        ->with('agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento')
                                        ->get()
                                        ->toArray();


        //VAMOS VERIFICAR PRIMEIRO SE EXISTE PROCEDIMENTO FATURADO NO ATENDIMENTO, EX: RETORNO NAO FATURA, ETC
        $atendimento_faturado = 0;

        $mudar_prestador_faturado = 0;

        foreach($agendamento[0]['agendamento_procedimento'] as $proc_verificar_cod):
            //CASO TENHA ALGUM PROCEDIMENTO COM CÓDIGO VINCULADO E CONVENIO SINCRONIZADO COM SANCOOP SIGNIFICA QUE É FATURADO E VAMOS VINCULAR AO LOTE
            if(!empty($proc_verificar_cod['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['cod'])
            && !empty($proc_verificar_cod['procedimento_instituicao_convenio']['convenios']['sancoop_cod_convenio'])):
                $atendimento_faturado = 1;
            endif;

            /* AQUI VAMOS OLHAR SE O PROCEDIMENTO QEU FOI FEITO POSSUI REGRA ONDE É FATURADO NO NOME DE OUTRO PRESTADOR */
            if(!empty($agendamento[0]['instituicoes_agenda']['prestadores']['procedimentos_excessoes'])):

                foreach($agendamento[0]['instituicoes_agenda']['prestadores']['procedimentos_excessoes'] as $procedimento_excessao):

                    if($procedimento_excessao['id'] == $proc_verificar_cod['procedimento_instituicao_convenio']['procedimento_instituicao']['procedimento']['id']):
                        $mudar_prestador_faturado = $procedimento_excessao['pivot']['prestador_faturado_id'];
                    endif;

                endforeach;

            endif;

        endforeach;

        // echo  $atendimento_faturado;
        // exit;

        //VAMOS ELIMINAR AS GUIAS E DEPOIS ADICIONAR NOVAMENTE CASO POSSUA
        DB::table('faturamento_protocolos_guias')
            ->where('agendamento_id',  $agendamento[0]['id'])
            ->where('status', 0)
            ->delete();




            //CASO TENHA PROCEDIMENTO FATURADO VAMOS VINCULAR
            if($atendimento_faturado == 1):

                        //CASO O PRSTADOR TENHA QEU MUDAR POR CONTA DO PROCEDIMENTO
                        if($mudar_prestador_faturado != 0):

                                                $prestadorFaturado =      Prestador::whereHas('prestadoresInstituicoes', function ($q) use ($mudar_prestador_faturado) {
                                                    $q->where('id', $mudar_prestador_faturado);
                                                    })
                                                    ->with(['prestadoresInstituicoes' => function ($q) use ($mudar_prestador_faturado) {
                                                    $q->where('id', $mudar_prestador_faturado);
                                                    }])
                                                    ->get()
                                                    ->toArray();

                                                

                            $agendamento[0]['instituicoes_agenda']['prestadores']['prestador'] = $prestadorFaturado[0];

                        endif;

                        
                        //PRIMEIRO VERIFICAMOS SE POSSUI LOTE DO PRESTADOR EM ABERTO, CASO NÃO , NÃO A NADA A SER FETO
                        $protocoloAberto = DB::table('faturamento_protocolos')
                                ->where('instituicao_id', $instituicao)
                                ->where('status', 0)
                                ->where('prestadores_id', $agendamento[0]['instituicoes_agenda']['prestadores']['prestador']['id'])
                                ->first();

                                // dd($protocoloAberto);

                        if(!empty($protocoloAberto)):
                                
                                //APÓS APAGAR AGORA VAMOS INSERIR NOVAMENTE A EDIÇÃO
                                $guia_lote_local = array(
                                    'status' => 0,
                                    'faturamento_protocolo_id' => $protocoloAberto->id,
                                    'agendamento_id' => $agendamento[0]['id']
                                );

                                FaturamentoLoteGuia::create($guia_lote_local);
                            
                    
                                
                    
                        endif;



                endif;

        







                                        // echo '<pre>';
                                        // print_r($agendamento);
                                        // exit;

    }

    /* FIM SANCOOP */


}


