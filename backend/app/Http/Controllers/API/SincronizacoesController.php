<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Instituicao;
use App\InstituicaoPaciente;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SincronizacoesController extends Controller
{
    public function instituicoes(Request $request)
    {
        $instituicoes = Instituicao::query()
            ->where('permite_historico', 1)
            ->get();

       return response()->json($instituicoes);
    }

    public function instituicoesVerificarUsuario(Request $request)
    {

        $usuario = $request->user('sanctum');

        $retorno = InstituicaoPaciente::query()
            ->where('instituicao_id', $request->dados)
            ->where('usuario_id', $usuario->id)
            ->count();

       return response()->json($retorno);
    }

    public function instituicoesDetalhes(Request $request)
    {

        $instituicao = Instituicao::query()
            ->where('id', $request->dados)
            ->get();


       return response()->json($instituicao);
    }

    public function instituicoesValidar(Request $request)
    {

        if($request->instituicao == 1){
            return $this->instituicoesValidarSantaCasa($request);
        }else{
            return response()->json('instiuicao_nao_homologada');
        }

    }


    public function instituicoesPaciente(Request $request)
    {
        // return $request;

        if($request->dados == 1){
            return $this->instituicoesPacienteSantaCasa($request);
        }else{
            return response()->json('instiuicao_nao_homologada');
        }

    }

    public function instituicoesPacienteManual(Request $request)
    {
        // return $request;

        if($request->instituicao == 1){
            return $this->instituicoesPacienteSantaCasaManual($request);
        }else{
            return response()->json('instiuicao_nao_homologada');
        }

    }

    public function instituicoesPacienteSantaCasaManual($request)
    {

        //PARAMETRIZANDO DADOS USUÁRIO LOGADO
        $usuario = $request->user('sanctum');
        $cpf_user = str_replace('.','',$usuario->cpf);
        $cpf_user = str_replace('-','',$cpf_user);
        $nascimento_user = $usuario->data_nascimento->format('d/m/Y');


        //PARAMETROS PARA A API
        $dados = [
            'cpf' => $cpf_user
        ];

        //REQUISIÇÃO API
        $dados_api = Http::asForm()
            ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/prontuario', $dados)
            ->json();  


        //VERIFICANDO SE FOI ENCONTRADO OS DADOS NA API
        if(isset($dados_api['RETORNO'])){

            //PARAMETRIZANDO DADOS USUÁRIO RETORNADO DA API
            $nascimento_api = explode(' ', $dados_api['RETORNO'][0]['DT_NASCIMENTO']);
            $nascimento_api = $nascimento_api[0];
            $cpf_api = $dados_api['RETORNO'][0]['NR_CPF'];
            $cod_prontuario_api = $dados_api['RETORNO'][0]['CD_PACIENTE'];

            $nome_mae = explode(' ', $dados_api['RETORNO'][0]['NM_MAE']);
            //ELIMINANDO 'DE' 'DA' 'DOS'
            if(strlen($nome_mae[1]) > 3){
                $sobrenome_correto = $nome_mae[1];
            }else{
                $sobrenome_correto = $nome_mae[2];
            }


            /*VERIFICANDO SE OS DADOS BATEM COM OS DADOS INFORMADOS PELO USUÁRIO LOGADO*/

            //SE OS DADOS FOREM VERDADEIROS IREMOS CONFERIR O CÓDGIDO DO PRONTUÁRIO DIGITADO
            if($cpf_user == $cpf_api 
            && $nascimento_user == $nascimento_api
            && $request->dados == $sobrenome_correto){

                
                //POR FIM IREMOS VINCULAR A INSTITUIÇÃO AO USUÁRIO

                //VAMOS VERIFICAR SE JÁ NAO EXISTE PARA NAO DUPLICAR
                $existeVinculacao = DB::table('instituicao_has_pacientes')->where('instituicao_id', $request->instituicao)
                                                                                ->where('usuario_id', $usuario->id)
                                                                                ->count(); 
                
                if($existeVinculacao == 0):

                    $array_vinculacao = array(
                        'instituicao_id' => $request->instituicao,
                        'usuario_id' => $usuario->id,
                        'id_externo' => $cod_prontuario_api
                    );

                   return response()->json(DB::table('instituicao_has_pacientes')->insert($array_vinculacao));

                endif;
                


            }else{
                return response()->json('api_dados_divergentes');
            }

            /* FIM VERIFICAÇAO DE DADOS */


        }else{
            return response()->json('api_usuario_inexistente');
        }

            

    }

    public function instituicoesPacienteSantaCasa($request)
    {

        //PARAMETRIZANDO DADOS USUÁRIO LOGADO
        $usuario = $request->user('sanctum');
        $cpf_user = str_replace('.','',$usuario->cpf);
        $cpf_user = str_replace('-','',$cpf_user);
        $nascimento_user = $usuario->data_nascimento->format('d/m/Y');


        //PARAMETROS PARA A API
        $dados = [
            'cpf' => $cpf_user
        ];

        //REQUISIÇÃO API
        $dados_api = Http::asForm()
            ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/prontuario', $dados)
            ->json();  

        if(!empty($dados_api)){
            $mae_rand = explode(' ', $dados_api['RETORNO'][0]['NM_MAE']);

            //VAMOS ELIMINAR OS NOMES 'DE' 'DO'
            for ($i=0; $i < sizeof($mae_rand) ; $i++) { 
               if(strlen($mae_rand[$i]) > 3){
                   $array_nomes[] = $mae_rand[$i];
               }
            }

            $mae_rand = $this->rand_array($array_nomes);

            return response()->json($mae_rand);
        }

            

    }

    function rand_array($array)
        {

            $count = count($array);
            $indi = range(0,$count-1);
            shuffle($indi);
            $newarray = array($count);
            $i = 0;

            // echo '<pre>';
            // print_r($array);
            // exit;
            foreach ($indi as $index)
            {
                  $newarray[$i] = $array[$index];
            
                $i++;

            }

            return $newarray;
        }

    public function instituicoesValidarSantaCasa($request)
    {

        //PARAMETRIZANDO DADOS USUÁRIO LOGADO
        $usuario = $request->user('sanctum');
        $cpf_user = str_replace('.','',$usuario->cpf);
        $cpf_user = str_replace('-','',$cpf_user);
        $nascimento_user = $usuario->data_nascimento->format('d/m/Y');


        //PARAMETROS PARA A API
        $dados = [
            'cpf' => $cpf_user
        ];

        //REQUISIÇÃO API
        $dados_api = Http::asForm()
            ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/prontuario', $dados)
            ->json();  

            // return $dados_api;

        
        //VERIFICANDO SE FOI ENCONTRADO OS DADOS NA API
        if(isset($dados_api['RETORNO'])){

            //PARAMETRIZANDO DADOS USUÁRIO RETORNADO DA API
            $nascimento_api = explode(' ', $dados_api['RETORNO'][0]['DT_NASCIMENTO']);
            $nascimento_api = $nascimento_api[0];
            $cpf_api = $dados_api['RETORNO'][0]['NR_CPF'];
            $cod_prontuario_api = $dados_api['RETORNO'][0]['CD_PACIENTE'];


            /*VERIFICANDO SE OS DADOS BATEM COM OS DADOS INFORMADOS PELO USUÁRIO LOGADO*/

            //SE OS DADOS FOREM VERDADEIROS IREMOS CONFERIR O CÓDGIDO DO PRONTUÁRIO DIGITADO
            if($cpf_user == $cpf_api 
            && $nascimento_user == $nascimento_api
            && $request->dados == $cod_prontuario_api){

                
                //POR FIM IREMOS VINCULAR A INSTITUIÇÃO AO USUÁRIO

                //VAMOS VERIFICAR SE JÁ NAO EXISTE PARA NAO DUPLICAR
                $existeVinculacao = DB::table('instituicao_has_pacientes')->where('instituicao_id', $request->instituicao)
                                                                                ->where('usuario_id', $usuario->id)
                                                                                ->count(); 
                
                if($existeVinculacao == 0):

                    $array_vinculacao = array(
                        'instituicao_id' => $request->instituicao,
                        'usuario_id' => $usuario->id,
                        'id_externo' => $cod_prontuario_api
                    );

                   return response()->json(DB::table('instituicao_has_pacientes')->insert($array_vinculacao));

                endif;
                


            }else{
                return response()->json('api_dados_divergentes');
            }

            /* FIM VERIFICAÇAO DE DADOS */


        }else{
            return response()->json('api_usuario_inexistente');
        }




    }


}
