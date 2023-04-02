<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Instituicao;
use App\Pessoa;
use App\PessoaDocumento;
use Illuminate\Http\Request;
use App\Http\Requests\Pessoa\{
    CreatePessoaRequest,
    EditPessoaRequest,
    IniciarCriacaoPessoaRequest
};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Support\ConverteValor;
use Illuminate\Support\Facades\Gate;

class Pessoas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_pessoas');

        return view('instituicao.pessoas.lista', [
            'instituicao' => Instituicao::find($request->session()->get('instituicao'))
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(IniciarCriacaoPessoaRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $campos_obg = json_decode($instituicao->config);
        $campos_obg = (!empty($campos_obg->pessoas)) ? $campos_obg->pessoas : null;
        $convenios = $instituicao->convenios()->get();
        $dados_inicio = $request->validated();

        return view('instituicao.pessoas.criar', [
            'personalidades' => Pessoa::getPersonalidades(),
            'tipos' => Pessoa::getTipos(),
            'tipos_documentos' => PessoaDocumento::getTipos(),
            'referencia_relacoes' => Pessoa::getRelacoesParentescos(),
            'sexo' => Pessoa::getSexos(),
            'estado_civil' => Pessoa::getEstadosCivil(),
            'campos_obg' => $campos_obg,
            'convenios' => $convenios,
            'dados_inicio' => $dados_inicio
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePessoaRequest $request)
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        $dados['gerar_via_acompanhante'] = $request->boolean('gerar_via_acompanhante');
       
        $pessoa = null;

        if($dados['tipo'] == 2){
            $confg_paciente = (!empty(json_decode($instituicao->config)->pessoas)) ? json_decode($instituicao->config)->pessoas : null;
            if(!empty($confg_paciente)){
                foreach($confg_paciente as $campo => $ativo){
                    if($campo == "endereco"){
                        if(empty($dados['cep']) || empty($dados['estado']) || empty($dados['cidade']) || empty($dados['cidade']) || empty($dados['bairro']) || empty($dados['rua']) || empty($dados['numero']))
                        return response()->json([
                            'icon' => 'error',
                            'title' => 'Falha.',
                            'text' => 'Campos referente ao endereço devem ser preenchidos'
                        ]);
                    }else if(empty($dados[$campo])){
                        return response()->json([
                            'icon' => 'error',
                            'title' => 'Falha.',
                            'text' => "Campo {$campo} deve ser preenchido!"
                        ]);
                    }
                }
            }
        }
        
        if($dados['cpf']) $pessoa = Pessoa::where('instituicao_id', $instituicao->id)
            ->where('cpf', $dados['cpf'])->first();

        if($dados['cnpj']) $pessoa = Pessoa::where('instituicao_id', $instituicao->id)
            ->where('cnpj', $dados['cnpj'])->first();

        if($pessoa) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => 'Pessoa já cadastrada!'
            ]);
        }

        DB::transaction(function() use ($instituicao, $request, $dados) {
            $pessoa = $instituicao->instituicaoPessoas()->create($dados);
            $pessoa->criarLogCadastro($request->user('instituicao'), $instituicao->id);


            /*CASO POSSUA TELEATENDIMENTO VAMOS FAZER VALIDAÇÕS DO EVIDA/BUSCARE */
            if(!empty($pessoa) && $instituicao->telemedicina_integrado == 1 && !empty($pessoa->cpf)):

                //CONFERINDO SE JÁ EXISTE VINCULO NO EVIDA/BUSCARE
                $pessoa_evida = $this->consultaPessoaEvida($pessoa->cpf);
    
                //SE EXISTIR VAMOS APENAS ATUALIZAR
                if(!empty($pessoa_evida)):
                    $dataPessoa['teleatendimento_id_pessoa'] = $pessoa_evida;
                //SE NÃO TEMOS QUE CRIAR
                else:
                    if($novo_pessoa_id = $this->criarPessoaEvida($pessoa)):
                        $dataPessoa['teleatendimento_id_pessoa'] = $novo_pessoa_id;
                    endif;
                endif;

    
            endif;
            /*FIM EVIDA/BUSCARE*/



            if(isset($dados['carteirinha'])){
                $carteirinhas = collect($request->validated()['carteirinha'])
                    ->filter(function($carteirinha){
                        return !is_null($carteirinha['convenio_id']);
                    })
                    ->map(function($carteirinha){
                        return [
                            'convenio_id' => $carteirinha['convenio_id'],
                            'plano_id' => $carteirinha['plano_id'],
                            'carteirinha' => $carteirinha['carteirinha'],
                            'validade' => $carteirinha['validade'],
                        ];
                    });

                foreach ($carteirinhas as $key => $value) {
                    $carteirinha = $pessoa->carteirinha()->create($value);
                    $carteirinha = $carteirinha->criarLogCadastro($request->user('instituicao'), $instituicao->id);
                }
            }

            if(isset($dados['documentos'])) {
                $documentos = collect($dados['documentos'])
                    ->filter(function ($documento) {
                        return !is_null($documento);
                    })
                    ->map(function($documento) use ($pessoa) {
                        $arquivo = $documento['arquivo'];
                        $arquivo_path = "documentos/pessoas/pessoa"."_".$pessoa->id."/";
                        $arquivo_new_nome = Carbon::now()->timestamp."_".$arquivo->getClientOriginalName();
                        $arquivo->storeAs($arquivo_path, $arquivo_new_nome, 'public');
                        return [
                            'tipo' => $documento['tipo'],
                            'file_path_name' => $arquivo_path.$arquivo_new_nome,
                            'descricao' => $documento['descricao'],
                        ];
                    });
                foreach($documentos as $documento) {
                    $novo_documento = $pessoa->documentos()->create($documento);
                    $novo_documento->criarLogCadastro($request->user('instituicao'), $instituicao->id);
                }
            }

            //VAMOS ATUALIZAR O CÓDIGO ID EVIDA
            if(!empty($dataPessoa)):
                DB::table('pessoas')
                ->where('id', $pessoa->id)
                ->update(array(
                    'teleatendimento_id_pessoa' => $dataPessoa['teleatendimento_id_pessoa']
                ));
            endif;
        });

        
        
        


        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pessoa criada com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPessoa(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $pessoa = null;
        $pessoa = Pessoa::where('instituicao_id', $instituicao->id)
            ->where($request->documento, $request->valor)->first();
   
        if($pessoa) return response()->json([
            'status' => 0,
            'mensagem' => strtoupper($request->documento)." já registrado como ".Pessoa::getTipoTexto($pessoa->tipo).".",
            'documento' => $request->documento,
        ]);

        return response()->json([
            'status' => 1,
            'mensagem' => strtoupper($request->documento)." ainda não registrado.",
            'documento' => $request->documento,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Pessoa $pessoa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);

        $campos_obg = json_decode($instituicao->config);
        $campos_obg = (!empty($campos_obg->pessoas)) ? $campos_obg->pessoas : null;
        $convenios = $instituicao->convenios()->get();

        /* INTEGRAÇÃO ASAPLAN */
        if(!empty($pessoa->asaplan_filial)):

            $filial_asaplan = DB::table('instituicoes_asaplan_filiais')
                ->select('*')
                ->where('instituicoes_id', $request->session()->get('instituicao'))
                ->where('cod_filial', $pessoa->asaplan_filial)
                ->first();

           $filiais_instituicoes = DB::table('instituicoes_asaplan_filiais')
                ->select('*')
                ->where('instituicoes_id', $request->session()->get('instituicao'))
                ->get();

        else:

            $filial_asaplan = null;
            $filiais_instituicoes = null;

        endif;

        return view('instituicao.pessoas.editar', [
            'personalidades' => Pessoa::getPersonalidades(),
            'tipos' => Pessoa::getTipos(),
            'referencia_relacoes' => Pessoa::getRelacoesParentescos(),
            'pessoa' => $pessoa,
            'sexo' => Pessoa::getSexos(),
            'estado_civil' => Pessoa::getEstadosCivil(),
            'campos_obg' => $campos_obg,
            'convenios' => $convenios,
            'instituicao' => $instituicao,
            'filial_asaplan' => $filial_asaplan,
            'filiais_instituicoes' => $filiais_instituicoes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditPessoaRequest $request, Pessoa $pessoa)
    {   
        $this->authorize('habilidade_instituicao_sessao', 'editar_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);

        $dados = $request->validated();

        $dados['gerar_via_acompanhante'] = $request->boolean('gerar_via_acompanhante');

        if($dados['tipo'] == 2){
            $confg_paciente = (!empty(json_decode($instituicao->config)->pessoas)) ? json_decode($instituicao->config)->pessoas : null;
            if(!empty($confg_paciente)){
                foreach($confg_paciente as $campo => $ativo){
                    if($campo == "endereco"){
                        if(empty($dados['cep']) || empty($dados['estado']) || empty($dados['cidade']) || empty($dados['cidade']) || empty($dados['bairro']) || empty($dados['rua']) || empty($dados['numero']))
                        return response()->json([
                            'icon' => 'error',
                            'title' => 'Falha.',
                            'text' => 'Campos referente ao endereço devem ser preenchidos'
                        ]);
                    }else if(empty($dados[$campo])){
                        return response()->json([
                            'icon' => 'error',
                            'title' => 'Falha.',
                            'text' => "Campo {$campo} deve ser preenchido!"
                        ]);
                    }
                }
            }
        }

        /*CASO POSSUA TELEATENDIMENTO VAMOS FAZER VALIDAÇÕS DO EVIDA/BUSCARE */
        if(!empty($pessoa) && $instituicao->telemedicina_integrado == 1 && !empty($pessoa->cpf) && empty($pessoa->teleatendimento_id_pessoa)):

            //CONFERINDO SE JÁ EXISTE VINCULO NO EVIDA/BUSCARE
            $pessoa_evida = $this->consultaPessoaEvida($pessoa->cpf);

            //SE EXISTIR VAMOS APENAS ATUALIZAR
            if(!empty($pessoa_evida)):
                $dados['teleatendimento_id_pessoa'] = $pessoa_evida;
            //SE NÃO TEMOS QUE CRIAR
            else:
                if($novo_pessoa_id = $this->criarPessoaEvida($pessoa)):
                    $dados['teleatendimento_id_pessoa'] = $novo_pessoa_id;
                endif;
            endif;


        endif;
        /*FIM EVIDA/BUSCARE*/

        /*CASO POSSUA INTEGRAÇÃO ASAPLAN E HABILIDADE PARA EDITAR */
        if(!empty($pessoa) 
        && $instituicao->integracao_asaplan == 1 
        && Gate::allows('habilidade_instituicao_sessao', 'editar_dados_integracao_pacientes_asaplan')):
            $dados['asaplan_tipo'] = $request->asaplan_tipo;
            $dados['asaplan_chave_plano'] = $request->asaplan_chave_plano;
            $dados['asaplan_situacao_plano'] = $request->asaplan_situacao_plano;
            $dados['asaplan_nome_titular'] = $request->asaplan_nome_titular;
            $dados['asaplan_nome_titular'] = $request->asaplan_nome_titular;
            $dados['asaplan_filial'] = $request->asaplan_filial;
        endif;
        /*FIM INTEGRAÇÃO ASAPLAN E HABILIDADE PARA EDITAR*/


        $pessoa = DB::transaction(function() use ($instituicao, $request, $dados, $pessoa) {
            $pessoa->update($dados);
            $pessoa->criarLogEdicao($request->user('instituicao'), $instituicao->id);

            if(isset($dados['carteirinha'])){
                $carteirinhas = collect($request->validated()['carteirinha'])
                    ->filter(function($carteirinha){
                        return !is_null($carteirinha['convenio_id']);
                    })
                    ->map(function($carteirinha){
                        return [
                            'id' => $carteirinha['id'],
                            'tipo' => $carteirinha['tipo'],
                            'convenio_id' => $carteirinha['convenio_id'],
                            'plano_id' => $carteirinha['plano_id'],
                            'carteirinha' => $carteirinha['carteirinha'],
                            'validade' => $carteirinha['validade'],
                        ];
                    });

                foreach ($carteirinhas as $key => $value) {
                    if($value['tipo'] == 'existe'){
                        $carteirinha = $pessoa->carteirinha()->where('id', $value['id'])->first();
                        $carteirinha->update($value);
                        $carteirinha->criarLogEdicao($request->user('instituicao'), $instituicao->id);

                    }else if($value['tipo'] == "novo"){
                        $carteirinha = $pessoa->carteirinha()->create($value);
                        $carteirinha = $carteirinha->criarLogCadastro($request->user('instituicao'), $instituicao->id);
                        
                    }else{
                        $carteirinha = $pessoa->carteirinha()->where('id', $value['id'])->first();
                        $carteirinha->delete();
                        $carteirinha->criarLogExclusao($request->user('instituicao'), $instituicao->id);
                    }
                }
            }

            return $pessoa;
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pessoa editada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pessoa $pessoa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);

        $usuario_logado = $request->user('instituicao');

        DB::transaction(function() use ($instituicao, $request, $pessoa, $usuario_logado) {
            $pessoa->delete();
            $documentos = $pessoa->documentos()->get();
            foreach($documentos as $documento){
                Storage::disk('public')->delete($documento->file_path_name);
                $documento->delete();
                $documento->criarLogExclusao($usuario_logado, $instituicao->id);
            }
            $pessoa->criarLogExclusao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.pessoas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pessoa excluída com sucesso!'
        ]);
    }

    public function abrirProntuario(Request $request, Pessoa $pessoa)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $paciente = $pessoa;

        $dadosAgendamento = [
            'pessoa_id' => $paciente->id,
            'data' => date('Y-m-d H:i'),
        ];

        $usuario_logado = $request->user('instituicao');

        $agendamento = Agendamentos::create($dadosAgendamento);

        $agendamento->criarLog($usuario_logado, 'Atendimento avulso', $dadosAgendamento, $instituicao->id);
        
        $atendimento = DB::transaction( function() use($usuario_logado, $paciente, $instituicao, $agendamento){
    
            $dadosAtendimento = [
                'pessoa_id' => $paciente->id,
                'data_hora' => date('Y-m-d H:i'),
                'tipo' => 4,
                'status' => 0
            ];

            $atendimento = $agendamento->atendimento()->create($dadosAtendimento);
            $atendimento->criarLogCadastro($usuario_logado, $instituicao->id);
            return $atendimento;
        });

        $agendamentoAtendidos = $paciente->agendamentos()->where('status', '!=', 'ausente')->count();
        $agendamentoAusentes = $paciente->agendamentos()->where('status', 'ausente')->count();
        
        $idade = null;
        if($paciente->nascimento){
            $idade = ConverteValor::calcularIdade($paciente->nascimento);
        }

        return view('instituicao.prontuarios.prontuario', \compact('agendamento', 'paciente', 'atendimento', 'agendamentoAtendidos', 'agendamentoAusentes', 'idade'));
    }
    
    public function abrirProntuarioResumo(Request $request, Pessoa $pessoa)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $paciente = $pessoa;

        $agendamento = null;
        $atendimento = (object) array('created_at' => date('Y-m-d H:i'));

        $agendamentoAtendidos = $paciente->agendamentos()->where('status', '!=', 'ausente')->count();
        $agendamentoAusentes = $paciente->agendamentos()->where('status', 'ausente')->count();
        
        $idade = null;
        if($paciente->nascimento){
            $idade = ConverteValor::calcularIdade($paciente->nascimento);
        }

        return view('instituicao.prontuarios.prontuario', \compact('agendamento', 'paciente', 'atendimento', 'agendamentoAtendidos', 'agendamentoAusentes', 'idade'));
    }

    public function getFornecedores(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $pessoas = Pessoa::where('instituicao_id', $instituicao->id)->where('tipo', 3)->get();
    
        return json_encode($pessoas);
    }

    /* APIS EVIDA / BUSCARE */
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

    /* FIM APIS EVIDA / BUSCARE */

    /*API ASAPLAN SINCRONIZAR */

    function sincronizarPessoasAsaplan(Request $request)
    {
        \Illuminate\Support\Facades\Artisan::call('automacao:asaplan', [
            '--queue' => true,
            '--instituicao' => $request->session()->get('instituicao')
        ]);

        // consege utestar? yess pode testar?pode rodou? yes


        return  'ok' ;

       /*ELIMINAR ISTO PRA TRÁS */

        $filiais = DB::table('instituicoes_asaplan_filiais')
                ->select('*')
                ->where('instituicoes_id', $request->session()->get('instituicao'))
                ->get();

                

       if(!empty($filiais)):

            foreach ($filiais as $filial):


                /*  ATUALIZANDO OS TITULARES  */

                $titulares = DB::connection($filial->database)
                  ->table('clientes_titular')
                  ->select('clientes_titular.idcliente_titular', 
                           'clientes_titular.nome', 
                           'clientes_titular.data_nascimento', 
                           'clientes_titular.telefone', 
                           'clientes_titular.situacao', 
                           'clientes_titular.rg', 
                           'clientes_titular.cpf', 
                           'clientes_titular.numero as numero_casa', 
                           'clientes_titular.complemento as complemento_casa', 
                           'clientes_titular.profissao', 
                           'clientes_titular.email', 
                           'ruas.nome as nome_rua', 
                           'ruas.cep as cep_rua', 
                           'bairros.nome as nome_bairro', 
                           'cidades.nome as nome_cidade',
                           'estados.sigla as sigla_estado')
                  ->where('atualizou_dados', 1)
                  ->join('ruas', 'ruas.idrua', '=', 'clientes_titular.idrua','left')
                  ->join('bairros', 'bairros.idbairro', '=', 'clientes_titular.idbairro','left')
                  ->join('cidades', 'cidades.id', '=', 'bairros.cidades_id','left')
                  ->join('estados', 'estados.id', '=', 'cidades.estados_id','left')
                  ->get();

                  

                  if(!empty($titulares)):

                    foreach ($titulares as $titular):

                        if(!empty($titular->data_nascimento) && $titular->data_nascimento != '0000-00-00'):
                            $nascimento_titular = $titular->data_nascimento;
                        else:
                            $nascimento_titular = null;
                        endif;

                        //PRIMEIRO VERIRICAMOS SE EXISTE O TITULAR, CASO EXISTA ATUALIZAR DADOS

                        $paciente = DB::table('pessoas')
                                     ->select('id')
                                     ->where('instituicao_id', $request->session()->get('instituicao'))
                                     ->where('asaplan_tipo', 1)
                                     ->where('asaplan_chave_plano', $titular->idcliente_titular)
                                     ->where('asaplan_filial', $filial->cod_filial)
                                     ->first();

                                     

                        //se existir vamos atualizar vou apagar isso depois
                        if(!empty($paciente->id)):

                            DB::table('pessoas')
                              ->where('id',  $paciente->id)
                              ->update(array(
                                    'nome' => $titular->nome,
                                    'asaplan_nome_titular' => $titular->nome,
                                    'asaplan_situacao_plano' => $titular->situacao,
                                    'nascimento' => $nascimento_titular,
                                    'telefone1' => $titular->telefone,
                                    'asaplan_ultima_atualizacao' => date('Y-m-d'),
                                    'cpf' => $titular->cpf,
                                    'identidade' => $titular->rg,
                                    'cep' => $titular->cep_rua,
                                    'estado' => $titular->sigla_estado,
                                    'cidade' => $titular->nome_cidade,
                                    'bairro' => $titular->nome_bairro,
                                    'rua' => $titular->nome_rua,
                                    'numero' => $titular->numero_casa,
                                    'complemento' => $titular->complemento_casa,
                                    'profissao' => $titular->profissao,
                                    'email' => $titular->email,
                              ));

                        //se não vamos criar
                        else:

                            $novo_paciente = array(           
                                'personalidade'  => 1,          
                                'tipo'  => 2,
                                'asaplan_tipo' => 1,
                                'asaplan_chave_plano' => $titular->idcliente_titular,
                                'nome' => $titular->nome,
                                'asaplan_nome_titular' => $titular->nome,
                                'asaplan_situacao_plano' => $titular->situacao,
                                'nascimento' => $nascimento_titular,
                                'telefone1' => $titular->telefone,
                                'asaplan_id_titular' => $titular->idcliente_titular,
                                'asaplan_filial' => $filial->cod_filial,
                                'asaplan_ultima_atualizacao' => date('Y-m-d'),
                                'instituicao_id' => $request->session()->get('instituicao'),
                                'cpf' => $titular->cpf,
                                'identidade' => $titular->rg,
                                'cep' => $titular->cep_rua,
                                'estado' => $titular->sigla_estado,
                                'cidade' => $titular->nome_cidade,
                                'bairro' => $titular->nome_bairro,
                                'rua' => $titular->nome_rua,
                                'numero' => $titular->numero_casa,
                                'complemento' => $titular->complemento_casa,
                                'profissao' => $titular->profissao,
                                'email' => $titular->email,
                            );

                            DB::table('pessoas')->insert($novo_paciente);


                        endif;


                           /* ATUALIZANDO OS BENEFICIÁRIOS */

                            $beneficiarios = DB::connection($filial->database)
                            ->table('beneficiarios')
                            ->select('clientes_titular.idcliente_titular', 
                                    'clientes_titular.nome as nome_titular', 
                                    'clientes_titular.telefone', 
                                    'clientes_titular.situacao', 
                                    'beneficiarios.idbeneficiario', 
                                    'beneficiarios.nome', 
                                    'beneficiarios.cpf', 
                                    'beneficiarios.rg', 
                                    'beneficiarios.profissao', 
                                    'beneficiarios.data_nascimento')
                            ->where('beneficiarios.idcliente_titular', $titular->idcliente_titular)
                            ->where('beneficiarios.orbito','!=', 'True')
                            ->join('clientes_titular', 'clientes_titular.idcliente_titular', '=', 'beneficiarios.idcliente_titular')
                            ->get();

                            if(!empty($beneficiarios)):

                                foreach ($beneficiarios as $beneficiario):

                                    if(!empty($beneficiario->data_nascimento) && $beneficiario->data_nascimento != '0000-00-00'):
                                        $nascimento_beneficiario = $beneficiario->data_nascimento;
                                    else:
                                        $nascimento_beneficiario = null;
                                    endif;

                                    //PRIMEIRO VERIRICAMOS SE EXISTE O TITULAR, CASO EXISTA ATUALIZAR DADOS

                                    $paciente_beneficiario = DB::table('pessoas')
                                                               ->select('id')
                                                               ->where('instituicao_id', $request->session()->get('instituicao'))
                                                               ->where('asaplan_tipo', 2)
                                                               ->where('asaplan_chave_plano', $beneficiario->idbeneficiario)
                                                               ->where('asaplan_filial', $filial->cod_filial)
                                                               ->first();

                                    //se existir vamos atualizar
                                      if(!empty($paciente_beneficiario->id)):

                                                    DB::table('pessoas')
                                                            ->where('id',  $paciente_beneficiario->id)
                                                            ->update(array(
                                                                    'nome' => $beneficiario->nome,
                                                                    'asaplan_nome_titular' => $beneficiario->nome_titular,
                                                                    'asaplan_situacao_plano' => $beneficiario->situacao,
                                                                    'nascimento' => $nascimento_beneficiario,
                                                                    'telefone1' => $beneficiario->telefone,
                                                                    'asaplan_ultima_atualizacao' => date('Y-m-d'),
                                                                    'cpf' => $beneficiario->cpf,
                                                                    'identidade' => $beneficiario->rg,
                                                                    'profissao' => $beneficiario->profissao,
                                                            ));

                                      else:

                                        $novo_paciente = array(
                                            'personalidade'  => 1,          
                                            'tipo'  => 2,
                                            'asaplan_tipo' => 2,
                                            'asaplan_chave_plano' => $beneficiario->idbeneficiario,
                                            'nome' => $beneficiario->nome,
                                            'asaplan_nome_titular' => $beneficiario->nome_titular,
                                            'asaplan_situacao_plano' => $beneficiario->situacao,
                                            'nascimento' => $nascimento_beneficiario,
                                            'telefone1' => $beneficiario->telefone,
                                            'asaplan_id_titular' => $titular->idcliente_titular,
                                            'asaplan_filial' => $filial->cod_filial,
                                            'asaplan_ultima_atualizacao' => date('Y-m-d'),
                                            'instituicao_id' => $request->session()->get('instituicao'),
                                            'cpf' => $beneficiario->cpf,
                                            'identidade' => $beneficiario->rg,
                                            'profissao' => $beneficiario->profissao,
                                        );
            
                                        DB::table('pessoas')->insert($novo_paciente);

                                      endif;


                                endforeach;

                            endif;


                            /*AGORA ATUALIZAMOS O BANCO DO PLANO COMO TITULAR IMPORTADO E ATUALIZADO*/

                            DB::connection($filial->database)
                              ->table('clientes_titular')
                              ->where('idcliente_titular',  $titular->idcliente_titular)
                              ->update(array(
                                      'atualizou_dados' => 0,
                            ));




                    endforeach;

                  endif;




                
                
            endforeach;

            echo true;

       endif;

    }
   /* FIM */
}
