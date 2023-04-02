<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instituicao\EditarInstituicaoRequest;
use App\Http\Requests\Instituicao\CriarInstituicaoRequest;
use App\Http\Requests\ContaBancaria\CriarContaBancariaRequest;
use App\Http\Requests\Instituicao\HabilitarInstituicaoRequest;
use App\Instituicao;
use App\ContaBancaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Libraries\PagarMe;
use App\Ramo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Image;

class Instituicoes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_admin', 'visualizar_instituicao');

        return view('admin.instituicao/lista');
    }

    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_instituicao');

        return view('admin.instituicao/criar', [ 
            'tipos' => Instituicao::getTipos(),
            'ramos' => Ramo::get(),
        ]);
    }

    public function store(CriarInstituicaoRequest $request){

        $this->authorize('habilidade_admin', 'cadastrar_instituicao');

        $dados = $request->validated();

        $dados['finalizar_consultorio'] = $request->boolean('finalizar_consultorio');
        $dados['enviar_pesquisa_satisfacao_atendimentos'] = $request->boolean('enviar_pesquisa_satisfacao_atendimentos');
        $dados['ausente_agenda'] = $request->boolean('ausente_agenda');
        $dados['desconto_por_procedimento_agenda'] = $request->boolean('desconto_por_procedimento_agenda');
        $dados['possui_convenio_terceiros'] = $request->boolean('possui_convenio_terceiros');
        $dados['codigo_acesso_terceiros'] = Hash::make($dados['codigo_acesso_terceiros']);
        // $dados['dinheiro'] = $request->boolean('dinheiro');
        // $dados['sincronizacao_agenda'] = $request->boolean('sincronizacao_agenda');

        if (instituicao::where('nome', $dados['nome'])->exists()) {
            throw ValidationException::withMessages([
                'nome'=> ['Nome já cadastrado'],
            ]);
        }

        if (instituicao::where('chave_unica', $dados['chave_unica'])->exists()) {
            throw ValidationException::withMessages([
                'nome'=> ['Chave já cadastrada'],
            ]);
        }

        if ($request->hasFile('imagem')) {

            $random = Str::random(20);
            $imageName = $random. '.' . $request->imagem->extension();
            $imagem_original = $request->imagem->storeAs("/instituicoes/{$random}", $imageName, config('filesystems.cloud'));
            $dados['imagem'] = $imagem_original;


            $ImageResize = Image::make($request->imagem);

            $image300pxName = "/instituicoes/{$random}/300px-{$imageName}";
            $image200pxName = "/instituicoes/{$random}/200px-{$imageName}";
            $image100pxName = "/instituicoes/{$random}/100px-{$imageName}";

            $ImageResize->resize(300, 300, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image300pxName, (string) $ImageResize->encode());

            $ImageResize->resize(200, 200, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image200pxName, (string) $ImageResize->encode());

            $ImageResize->resize(100, 100, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image100pxName, (string) $ImageResize->encode());


            // $caminho = "/instituicoes";
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "public");
            // $dados['imagem'] = $caminhoCloud;
        }

        $instituicao = DB::transaction(function () use ($request, $dados){

            $instituicao = instituicao::create($dados);

            $usuario_logado = $request->user('admin');

            $instituicao->criarLogCadastro(
              $usuario_logado
            );

            return $instituicao;
        });

        $this->HorariosFuncionamento($instituicao);

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Instituição criada com sucesso!'
        ]);

        // return redirect()->route('instituicoes.index')->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Instituição criada com sucesso!'
        // ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Instituicao $instituicao)
    {
        $this->authorize('habilidade_admin', 'editar_instituicao');
        $ramos = Ramo::get();
        return view('admin.instituicao.editar', \compact('instituicao', 'ramos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarInstituicaoRequest $request, Instituicao $instituicao)
    {
        $this->authorize('habilidade_admin', 'editar_instituicao');
        $dados = $request->validated();
        $dados['finalizar_consultorio'] = $request->boolean('finalizar_consultorio');
        $dados['possui_faturamento_sancoop'] = $request->boolean('possui_faturamento_sancoop');
        $dados['enviar_pesquisa_satisfacao_atendimentos'] = $request->boolean('enviar_pesquisa_satisfacao_atendimentos');
        $dados['apibb_possui'] = $request->boolean('apibb_possui');
        $dados['possui_convenio_terceiros'] = $request->boolean('possui_convenio_terceiros');
        $dados['automacao_whatsapp_botoes'] = $request->boolean('automacao_whatsapp_botoes');
        $dados['automacao_whatsapp_aniversario'] = $request->boolean('automacao_whatsapp_aniversario');
        $dados['integracao_asaplan'] = $request->boolean('integracao_asaplan');
        if($dados['possui_convenio_terceiros'] && $instituicao->codigo_acesso_terceiros == null && $dados['codigo_acesso_terceiros'] == null){
           
            return response()->json([
                'icon' => 'error',
                'title' => 'Error.',
                'text' => 'Preencha o campo codigo de acesso de terceiros!'
            ]);
                
        }
    
        if(array_key_exists('codigo_acesso_terceiros', $dados)){
            if($dados['codigo_acesso_terceiros'] != null){
                $dados['codigo_acesso_terceiros'] = Hash::make($dados['codigo_acesso_terceiros']);
            }else{
                unset($dados['codigo_acesso_terceiros']);
            }
        }
        
        if(!$dados['apibb_possui']){
            $dados['apibb_codigo_cedente'] = null;
            $dados['apibb_indicador_pix'] = null;
            $dados['apibb_client_id'] = null;
            $dados['apibb_client_secret'] = null;
            $dados['apibb_gw_dev_app_key'] = null;
            $dados['p_juros'] = null;
            $dados['p_multa'] = null;
            $dados['dias_pagamento'] = null;
        }
        $dados['ausente_agenda'] = $request->boolean('ausente_agenda');
        $dados['desconto_por_procedimento_agenda'] = $request->boolean('desconto_por_procedimento_agenda');
        // $dados['cartao_entrega'] = $request->boolean('cartao_entrega');
        // $dados['dinheiro'] = $request->boolean('dinheiro');
        // $dados['sincronizacao_agenda'] = $request->boolean('sincronizacao_agenda');

        //CASO POSSUA FATURAMENTO SANCOOP IREMOS ATUALIZAR O CÓDIGO DA INSTITUIÇÃO NA SANCOOP
        if($dados['possui_faturamento_sancoop'] == 1):
            

            //COMENTADO ATÉ TER A LISTA DO CNPJ POIS ESTÁ DIVERGENTE NA BASE DA SANCOOP
        //    $cod_instituicao_sancoop =  $this->consultarCodInstituicaoSancoop($instituicao->cnpj);

        //    if(!empty($cod_instituicao_sancoop)):
        //     $dados['sancoop_cod_instituicao'] =  $cod_instituicao_sancoop;
        //    endif;

        endif;

        if ($request->hasFile('imagem')) {

            if($instituicao->imagem){
                $pasta = Str::of($instituicao->imagem)->explode('/');
                Storage::cloud()->deleteDirectory($pasta[0].'/'.$pasta[1]);
            }

            $random = Str::random(20);
            $imageName = $random. '.' . $request->imagem->extension();
            $imagem_original = $request->imagem->storeAs("/instituicoes/{$random}", $imageName, config('filesystems.cloud'));
            $dados['imagem'] = $imagem_original;


            $ImageResize = Image::make($request->imagem);

            $image300pxName = "/instituicoes/{$random}/300px-{$imageName}";
            $image200pxName = "/instituicoes/{$random}/200px-{$imageName}";
            $image100pxName = "/instituicoes/{$random}/100px-{$imageName}";

            $ImageResize->resize(300, 300, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image300pxName, (string) $ImageResize->encode());

            $ImageResize->resize(200, 200, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image200pxName, (string) $ImageResize->encode());

            $ImageResize->resize(100, 100, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image100pxName, (string) $ImageResize->encode());

            // Storage::disk('public')->delete($instituicao->imagem);

            // $caminho = "/instituicoes";
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "public");
            // $dados['imagem'] = $caminhoCloud;
        }

        DB::transaction(function () use ($instituicao,$request,$dados){
            // dd($dados);
            $instituicao->update($dados);

            $usuario_logado = $request->user('admin');
            $instituicao->criarLogEdicao(
              $usuario_logado
            );

            return $instituicao;
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Instituição atualizada com sucesso!'
        ]);
        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        // return redirect()->route('instituicoes.edit', [$instituicao])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Instituição atualizado com sucesso!'
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function habilitarDesabilitar(Request $request ,Instituicao $instituicao)
    {
        $this->authorize('habilidade_admin', 'habilitar_instituicao');
        // $dados = $request->validated();
        if ($instituicao->habilitado == false) {
            $dados['habilitado'] = true;
        } else {
            $dados['habilitado'] = false;
        }

        DB::transaction(function () use ($instituicao,$request,$dados){
            $instituicao->update($dados);

            $usuario_logado = $request->user('admin');
            $instituicao->criarLogEdicao(
              $usuario_logado
            );

            return $instituicao;
        });

        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('instituicoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Instituição atualizado com sucesso!'
        ]);
    }

    public function editBanco(Instituicao $instituicao){
        $this->authorize('habilidade_admin', 'editar_conta_bancaria_instituicao');
        return view('admin.instituicao/banco', \compact('instituicao'));
    }

    public function updateBanco(CriarContaBancariaRequest $request, Instituicao $instituicao){

        $this->authorize('habilidade_admin', 'editar_conta_bancaria_instituicao');

        $pagarMe = new PagarMe();

        $banco_pagarme = $pagarMe->cadastrarContaBancaria([
            'bank_code' => $request->banco_id,
            'agencia' => $request->agencia,
            'agencia_dv' => $request->agencia_dv,
            'conta' => $request->conta,
            'conta_dv' => $request->conta_dv,
            'type' => $request->type,
            'document_number' => $request->documento_titular,
            'legal_name' => $request->nome_titular
        ]);

        $banco = ContaBancaria::create([
            'bank_name' => $request->banco_nome,
            'bank_code' => $banco_pagarme->bank_code,
            'agencia' => $banco_pagarme->agencia,
            'agencia_dv' => $banco_pagarme->agencia_dv,
            'conta' => $banco_pagarme->conta,
            'conta_dv' => $banco_pagarme->conta_dv,
            'type' => $banco_pagarme->type,
            'documento_titular' => $banco_pagarme->document_number,
            'nome_titular' => $banco_pagarme->legal_name,
            'id_pagarme' => $banco_pagarme->id,
        ]);

        $instituicao->banco()->associate($banco);
        if($instituicao->id_recebedor==null){
            $instituicao->id_recebedor = $pagarMe->cadastrarRecebedor($banco->id_pagarme)->id;
        }else{
            $pagarMe->atualizarBancoRecebedor($instituicao->id_recebedor, $banco->id_pagarme);
        }
        $instituicao->update();


        return redirect()->route('instituicoes.banco', [$instituicao])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Banco atualizado com sucesso!'
        ]);

    }

    private function HorariosFuncionamento($instituicao)
    {
        $semana[0] = 'domingo';
        $semana[1] = 'segunda-feira';
        $semana[2] = 'terça-feira';
        $semana[3] = 'quarta-feira';
        $semana[4] = 'quinta-feira';
        $semana[5] = 'sexta-feira';
        $semana[6] = 'sabado';
        for ($i=0; $i < 7; $i++) { 
            $dados = [
                'dia_semana' => $semana[$i],
                'horario_inicio' => '08:00',
                'horario_fim' => '18:00',
                'full_time' => false,
                'fechado' => false,
            ];

            DB::transaction( function() use($dados, $instituicao){
                $instituicao->horarioFuncionamento()->create($dados);
            });
        }
    }


    public function consultarCodInstituicaoSancoop($cnpj)
    {


        //OBTENDO TOKEN DE AUTORIZAÇÃO
        // CODIGOS INSTITUICOES NA SANCOOP - 79 angios - 3623 santa casa
        $parameters['ID'] = 181;
        $parameters['Hash'] = 'TWVkLlNpb3MyMkAhQCM';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://zltecnologia.ddns.net:8902/Token');
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

            //TRATANDO CNPJ PARA CONSULTA
            $cnpj_consulta = str_replace('.', '', $cnpj);
            $cnpj_consulta = str_replace('/', '', $cnpj_consulta);
            $cnpj_consulta = str_replace('-', '', $cnpj_consulta);

            $parameters['cnpj'] = $cnpj_consulta;
            
            //CONSULTANDO API SANCOOP PARA VERIFICAR SE EXISTE O COOPERADO
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://zltecnologia.ddns.net:8902/Hospital?CNPJ='.$parameters['cnpj'].'');
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

            // curl_close($ch);
            // print_r($return);
            // exit;

            if(!empty($return['result']->Hospital)):
                return $return['result']->Hospital;
            else:
                return false;
            endif;

        endif;

    }
    

    public function consultarCooperadoSancoop(Request $request, Instituicao $instituicao)
    {

        //OBTENDO TOKEN DE AUTORIZAÇÃO
        // CODIGOS INSTITUICOES NA SANCOOP - 79 angios - 3623 santa casa
        $parameters['ID'] = 181;
        $parameters['Hash'] = 'TWVkLlNpb3MyMkAhQCM';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://zltecnologia.ddns.net:8902/Token');
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

            //TRATANDO CNPJ PARA CONSULTA
            $cnpj_consulta = str_replace('.', '', $instituicao->cnpj);
            $cnpj_consulta = str_replace('/', '', $cnpj_consulta);
            $cnpj_consulta = str_replace('-', '', $cnpj_consulta);

            // $parameters['cnpj'] = $cnpj_consulta;
            //CPF REINALDO
            $parameters['CPF'] = '734.134.376-15';
            
            //CONSULTANDO API SANCOOP PARA VERIFICAR SE EXISTE O COOPERADO
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://zltecnologia.ddns.net:8902/Cooperado?CPF='.$parameters["CPF"].'');
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

            print_r($return['result']);
            exit;

        endif;
    }

    public function exportDados(Request $request, Instituicao $instituicao){
        set_time_limit(0);
        ini_set('memory_limit', '5120M'); // or you could use 1G

        $dados = array(
            'instituicao' => $instituicao->toArray(),
            'pacientes' => $instituicao->pacientes()->with([
                'documentos',
                'carteirinha',
                'prontuario',
                'receituario',
                'atestado',
                'laudo',
                'encaminhamento',
                'relatorio',
                'exame',
                'refracao',
                'odontologicos',
                'pastas'
            ])->get()->toArray(),
            'prestadores' => $instituicao->prestadores()->with([
                'prestador',
                'agenda.agendamentos.pessoa',
                'agenda.agendamentos.contaReceber',
                'agenda.agendamentos.agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimento',
                'agenda.agendamentos.agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios',
            ])->get()->toArray(),
            'fornecedores' => $instituicao->fornecedores()->get()->toArray(),
            'centrosCustos' => $instituicao->centrosCustos()->get()->toArray(),
            'contas' => $instituicao->contas()->get()->toArray(),
            'planosContas' => $instituicao->planosContas()->get()->toArray(),
            'contasPagar' => $instituicao->contasPagar()->with([
                'contaCaixa',
                'planoConta',
                'prestador',
                'fornecedor',
                'paciente',
                'centroCusto',
            ])->get()->toArray(),
            'contasReceber' => $instituicao->contasReceber()->with([
                'contaCaixa',
                'planoConta',
                'prestador',
                'paciente',
                'notaFiscal',
            ])->get()->toArray(),
            'convenios' => $instituicao->convenios()->get()->toArray(),
            'procedimentos' => $instituicao->procedimentos()->get()->toArray(),
            'notasFiscais' => $instituicao->notasFiscais()->get()->toArray()
        );
        
        $file_name = "backup_{$dados['instituicao']['id']}_".str_replace(" ", "_", $dados['instituicao']['nome'])."_".date("Ydm_His");
        
        $headers = array(
            "Content-type" => "application/json",
            "Content-Disposition" => "attachment; filename={$file_name}.json",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );


        return response()->stream(function () use($dados){
            echo json_encode($dados);
        }, 200,  $headers)->send();       

    }
}
