<?php

namespace App\Integracoes;

use App\Instituicao;
use Illuminate\Support\Facades\Http;
use App\InstituicaoPacienteAtendimento;
use App\InstituicaoPacienteDocumento;
use App\InstituicaoPacienteSincronizacao;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SantaCasa implements Integracao {
    
    public function getPaciente(array $dados): array
    {
        // $dados = [
        //     'cpf' => '49277944668',
        //     'cartao' => '700305921388337',
        // ];

        return Http::asForm()
            ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/prontuario', $dados)
            ->json();
    }

    public function getDados($usuario)
    {
        

        //PEGANDO O CD_PACIENTE DA SANTA CASA
        $dadosVinculacao = DB::table('instituicao_has_pacientes')->where('instituicao_id', 1)
                                                                ->where('usuario_id', $usuario->id)
                                                                ->get(); 


        
        //CASO NÃO EXISTA JÁ RETORNAMOS FALSO
        if(empty($dadosVinculacao[0]->id_externo)){
            return false;
        }else{
            $cdPaciente = $dadosVinculacao[0]->id_externo;
        }

        // return 'adsf';


        // return $dadosVinculacao;

        //ALINE MAURICIO
        //$cdPaciente = 5820;
        //MAURICIO
       // $cdPaciente = 183581;


        //PRIMEIRO VERIFICAMOS A ULTIMA DATA DE SINCRONIZAÇÃO
        $instituicao = Instituicao::where('chave_unica', 'santacasa')->first();
        
        $instituicaoPaciente = $instituicao->instituicaoPaciente()
                                            ->wherePivot('id_externo', $cdPaciente)
                                            ->first();
                                            
        $sincronizacao = InstituicaoPacienteSincronizacao::where('instituicao_has_pacientes_id', $instituicaoPaciente->pivot->id)
                                                          ->orderByDesc('id')
                                                          ->first();
        

        if(!empty($sincronizacao)){
            $data_ultima_sincronizacao = $sincronizacao->data_hora->format('d/m/Y');
        }else{
            $data_ultima_sincronizacao = '01/01/1900';
        }

        // $data_ultima_sincronizacao = '01/01/1900';


        //return $data_ultima_sincronizacao;


        //BUSCANDO OS DADOS NA API DA SANTA CASA
        $dados = Http::asForm()
            ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/dados_prontuario', [
                'prontuario' => $cdPaciente,
                'dti' => $data_ultima_sincronizacao,
                'dtf' => date('d/m/Y')
            ])
            ->json(); 



            // http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabela_exame

            // $dados_new = Http::asForm()
            // ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabela_exame', [
            //     'prontuario' => $cdPaciente,
            //     'dti' => $data_ultima_sincronizacao,
            //     'dtf' => date('d/m/Y')
            // ])
            // ->json(); 

            // return $dados_new;

    
            
            
        /* ARRAYS
        ['RETORNO'][1] = prontuario
        ['RETORNO'][3] = atendimento
        ['RETORNO'][5] = exame
        ['RETORNO'][7] = receita
        ['RETORNO'][9] = atestado
        FIM ARRAYS*/ 
        /*
        Resultado de Exame da Imagem
        http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/arquivo_imagem
        Parâmetros= { chave (obrigatório), caminho (obrigatório) }
        */
        // return $dados['RETORNO'][5];

        //SINCRONIZANDO ATENDIMENTOS
        if(!empty($dados['RETORNO'][3])){           
            
            foreach($dados['RETORNO'][3] as $atendimento){


                //VAMOS VERIFICAR SE JÁ NAO EXISTE PARA NAO DUPLICAR
                $existeAtendimento = DB::table('instituicao_has_pacientes_atendimentos')->where('id_externo', $atendimento['CD_ATENDIMENTO'])
                                                                                        ->count(); 
                      
                if($existeAtendimento == 0):

                    $array_atendimento[] = array(
                        'id_externo' => $atendimento['CD_ATENDIMENTO'],
                        'data' => Carbon::createFromFormat('d/m/Y H:i', $atendimento['DT_ATENDIMENTO']),
                        //'data' => $atendimento['DT_ATENDIMENTO'],
                        'tipo_atendimento' => $atendimento['TP_ATENDIMENTO'],
                        'nome_prestador' => $atendimento['NM_PRESTADOR'],
                        'nome_convenio' => $atendimento['NM_CONVENIO'],
                        'especialidade_atendimento' => $atendimento['NM_ESPECIALIDADE'],
                        'origem_atendimento' => $atendimento['NM_ORIGEM'],
                        'anamnese_cid' => $atendimento['CD_CID'],
                        'anamnese_descricao_cid' => $atendimento['DS_CID'],
                        'anamnese_qp' => $atendimento['DS_QUEIXA_PRINCIPAL'],
                        'procedimento' => $atendimento['PROCEDIMENTO'],
                        'instituicao_has_pacientes_id' => $instituicaoPaciente->pivot->id
                    );

                endif;

            }

            if(!empty($array_atendimento)):

              DB::table('instituicao_has_pacientes_atendimentos')->insert($array_atendimento);

            endif;
        

        }



        //SINCRONIZANDO EXAMES
        if(!empty($dados['RETORNO'][5])){           
            
            foreach($dados['RETORNO'][5] as $exame){


                //VAMOS VERIFICAR SE JÁ NAO EXISTE PARA NAO DUPLICAR
                $existeExame = DB::table('instituicao_has_pacientes_documentos')->where('id_externo', $exame['COD_IT_PEDIDO'])
                                                                                ->where('codigo_pedido', $exame['COD_PED'])
                                                                                ->where('grupo', 'exame')
                                                                                ->count(); 
                      
                if($existeExame == 0):

                    $array_exame[] = array(
                        'id_externo' => $exame['COD_IT_PEDIDO'],
                        'grupo' => 'exame',
                        'tipo' => $exame['TIPO'],
                        'codigo_pedido' => $exame['COD_PED'],
                        'data_pedido' => Carbon::createFromFormat('d/m/Y', $exame['DT_PEDIDO']),
                        'codigo_atendimento' => $exame['CD_ATENDIMENTO'],
                        'nome_convenio' => $exame['NM_CONVENIO'],
                        'nome_prestador' => $exame['NM_PRESTADOR'],
                        'codigo_exame' => $exame['COD_EXAME'],
                        'descricao' => $exame['NM_EXAME'],
                        'url' => $exame['CAMINHO'],
                        'instituicao_has_pacientes_id' => $instituicaoPaciente->pivot->id
                    );

                endif;

            }

            if(!empty($array_exame)):

              DB::table('instituicao_has_pacientes_documentos')->insert($array_exame);

            endif;
        

        }


        //SINCRONIZANDO RECEITUÁRIOS
        if(!empty($dados['RETORNO'][7])){           
            
            foreach($dados['RETORNO'][7] as $receituario){

                //VAMOS VERIFICAR O TIPO DE RECEITA: 1 - RECEITA ANTIGA, 2 - RECEITA NOVA
                if($receituario['TIPO'] == 2):

                    $medicacao = explode(';', $receituario['MEDICACAO']);
                    $tipo = 'novo';
                    $id_externo = null;
                    $codigo_pedido = $receituario['CD_RECEITA'];
                    $codigo_exame = $medicacao[0];
                    $descricao = $medicacao[1];
                    $qtd_medicacao = $receituario['QTDE'];
                    $posologia_medicacao = $receituario['POSOLOGIA'];
                    
                    //VERIFICANDO SE EXISTE RCEITA TIPO 2
                    $existeItemReceita = DB::table('instituicao_has_pacientes_documentos')->where('codigo_pedido', $receituario['CD_RECEITA'])
                                                                                          ->where('codigo_atendimento', $receituario['CD_ATENDIMENTO'])
                                                                                          ->where('codigo_exame', $codigo_exame)
                                                                                          ->where('descricao', $descricao)
                                                                                          ->count(); 
                
                else:

                    $tipo = 'antigo';
                    $id_externo = $receituario['CD_RECEITA'];
                    $codigo_pedido = null;
                    $codigo_exame = null;
                    $descricao = $receituario['MEDICACAO'];
                    $qtd_medicacao = null;
                    $posologia_medicacao = null;

                    //VERIFICANDO SE EXISTE RCEITA TIPO 1
                    $existeItemReceita = DB::table('instituicao_has_pacientes_documentos')->where('id_externo', $receituario['CD_RECEITA'])
                                                                                          ->where('codigo_atendimento', $receituario['CD_ATENDIMENTO'])
                                                                                          ->count(); 

                endif;


                      
                if($existeItemReceita == 0):

                    $array_receituario[] = array(
                        'id_externo' => $id_externo,
                        'grupo' => 'receita',
                        'tipo' => $tipo,
                        'codigo_pedido' => $codigo_pedido,
                        'data_pedido' => Carbon::createFromFormat('d/m/Y H:i', $receituario['DT_REGISTRO']),
                        'codigo_atendimento' => $receituario['CD_ATENDIMENTO'],
                        //'nome_convenio' => $receituario['NM_CONVENIO'],
                        'nome_prestador' => $receituario['NM_PRESTADOR'],
                        'codigo_exame' => $codigo_exame,
                        'descricao' => $descricao,
                        'instituicao_has_pacientes_id' => $instituicaoPaciente->pivot->id,
                        'medicacao_qtd' => $qtd_medicacao,
                        'medicacao_posologia' => $posologia_medicacao
                    );

                endif;

            }

            if(!empty($array_receituario)):

              DB::table('instituicao_has_pacientes_documentos')->insert($array_receituario);

            endif;
        

        }

         //SINCRONIZANDO ATESTADOS
         if(!empty($dados['RETORNO'][9])){           
            
            foreach($dados['RETORNO'][9] as $atestado){


                //VAMOS VERIFICAR SE JÁ NAO EXISTE PARA NAO DUPLICAR
                $existeAtestado = DB::table('instituicao_has_pacientes_documentos')->where('id_externo', $atestado['CD_ATESTADO'])
                                                                                   ->where('grupo', 'atestado')
                                                                                   ->count(); 
                      
                if($existeAtestado == 0):

                    $array_atestado[] = array(
                        'id_externo' => $atestado['CD_ATESTADO'],
                        'grupo' => 'atestado',
                        //'tipo' => $atestado['TIPO'],
                        //'codigo_pedido' => $atestado['COD_PED'],
                        'data_pedido' => Carbon::createFromFormat('d/m/Y H:i', $atestado['DT_REGISTRO']),
                        'codigo_atendimento' => $atestado['CD_ATENDIMENTO'],
                        //'nome_convenio' => $atestado['NM_CONVENIO'],
                        'nome_prestador' => $atestado['NM_PRESTADOR'],
                        'codigo_exame' => $atestado['CD_DOCUMENTO'],
                        'descricao' => $atestado['ATESTADO'],
                        'instituicao_has_pacientes_id' => $instituicaoPaciente->pivot->id
                    );

                endif;

            }

            if(!empty($array_atestado)):

              DB::table('instituicao_has_pacientes_documentos')->insert($array_atestado);

            endif;
        

        }
        



        /*APÓS OS DADOS INSERIDOS IREMOS SALVAR NA BASE DE DADOS A SINCRONIZAÇÃO EFETUADA*/
        if(!empty($dados['RETORNO'][3]) 
        || !empty($dados['RETORNO'][5]) 
        || !empty($dados['RETORNO'][7]) 
        || !empty($dados['RETORNO'][9])):
            
            $sincronizacao = array(
                'data_hora' => Carbon::createFromFormat('Y-m-d H:i', date('Y-m-d H:i')),
                'instituicao_has_pacientes_id' => $instituicaoPaciente->pivot->id,
            );

            DB::table('instituicao_has_pacientes_sincronizacao')->insert($sincronizacao);

        endif;
        
        /* FIM SALVANDO SINCRONIZAÇÃO */




        return response()->json('sincronizacao_efetuada');

       //return $dados;
    }

}