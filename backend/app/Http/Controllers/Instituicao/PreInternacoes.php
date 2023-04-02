<?php

namespace App\Http\Controllers\Instituicao;

use App\Cid;
use App\Convenio;
use App\Especialidade;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pessoa\CreatePessoaRequest;
use App\Http\Requests\PreInternacoes\CriarPreInternacaoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PreInternacao;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\Pessoa;
use App\Prestador;
use App\UnidadeInternacao;

class PreInternacoes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_pre_internacao');

        return view('instituicao.pre_internacoes.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_pre_internacao');
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);
        
        $pacientes = $instituicao->instituicaoPessoas()->where('tipo', '2')->get();
        // $medicos = $instituicao->prestadores()->with('prestador')->where('tipo', '2')->get();
        $medicos = Prestador::whereHas('prestadoresInstituicoes', function($q) use ($instituicao){
            $q->where('ativo', 1);
            $q->where('instituicoes_id',$instituicao->id);
        })->get();

        $convenios = $instituicao->convenios()->get();
        
        $origens = $instituicao->origens()->get();
       
        $cids = Cid::get();
        $acomodacoes = $instituicao->acomodacoes()->get();
        $unidades = $instituicao->unidadesInternacoes()->get();

        $especialidades = $instituicao->especialidadesInstituicao()->get();
        
        return view('instituicao.pre_internacoes.criar', \compact('pacientes', 'medicos', 'origens', 'acomodacoes', 'unidades', 'cids', 'convenios', 'especialidades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarPreInternacaoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_pre_internacao');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        $procedimentos = collect($request->validated()['itens'])
        ->filter(function ($itens) {
            return !is_null($itens['convenio']);
        })
        ->map(function ($itens){
            return [
                'convenio_id' => $itens['convenio'],
                'proc_conv_id' => $itens['procedimento'],
                'quantidade_procedimento' => !empty($itens['quantidade_procedimento']) ? $itens['quantidade_procedimento'] : 1,
                'valor' => !empty($itens['valor']) ? $valor = str_replace(['.', ','],['','.'], $itens['valor']) : 0,
            ];
        });

        DB::transaction(function() use ($request, $instituicao, $procedimentos){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $dados['pre_internacao'] = 1;
            $dados['previsao'] = $dados['previsao'] ? str_replace('T', ' ', $dados['previsao']) : null;
            $dados['possui_responsavel'] = (!empty($dados['possui_responsavel'])) ? $dados['possui_responsavel'] : 0;

            $preInternacoes = $instituicao->preinternacoes()->create($dados);
            if(!empty($procedimentos)){
                $preInternacoes->procedimentos()->attach($procedimentos);
            }
            $preInternacoes->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.preInternacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pre Internação criada com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, PreInternacao $pre_internacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_pre_internacao');

        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);
        
        $pacientes = $instituicao->instituicaoPessoas()->where('tipo', '2')->get();
        $medicos = Prestador::whereHas('prestadoresInstituicoes', function($q) use ($instituicao){
            $q->where('ativo', 1);
            $q->where('instituicoes_id',$instituicao->id);
        })->get();

        $origens = $instituicao->origens()->get();
        $convenios = $instituicao->convenios()->get();
        $procedimentos = $pre_internacao->procedimentos()->with(['procedimentoInstituicao.procedimento', 'convenios'])->get();

        $cids = Cid::get();
        $acomodacoes = $instituicao->acomodacoes()->get();
        $unidades = $instituicao->unidadesInternacoes()->get();

        $especialidades = $instituicao->especialidadesInstituicao()->get();

        return view('instituicao.pre_internacoes.editar', \compact('pre_internacao', 'pacientes', 'medicos', 'origens', 'acomodacoes', 'unidades', 'cids', 'procedimentos', 'convenios', 'especialidades'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarPreInternacaoRequest $request, PreInternacao $pre_internacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_pre_internacao');

        $procedimentos = null;
        if(!empty($request->validated()['itens'])){
            $procedimentos = collect($request->validated()['itens'])
                ->filter(function ($itens) {
                    return !is_null($itens['convenio']);
                })
                ->map(function ($itens){
                    return [
                        'convenio_id' => $itens['convenio'],
                        'proc_conv_id' => $itens['procedimento'],
                        'quantidade_procedimento' => !empty($itens['quantidade_procedimento']) ? $itens['quantidade_procedimento'] : 1,
                        'valor' => !empty($itens['valor']) ? $valor = str_replace(['.', ','],['','.'], $itens['valor']) : 0,
                    ];
                });
        }

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$pre_internacao->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $pre_internacao, $procedimentos){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $pre_internacao->update($dados);

            $pre_internacao->procedimentos()->detach();
            $pre_internacao->procedimentos()->attach($procedimentos);
            $pre_internacao->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.preInternacoes.edit', [$pre_internacao])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pre Internação alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PreInternacao $preInternacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_tipos_documentos');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$preInternacao->instituicao_id, 403);

        DB::transaction(function () use ($preInternacao, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $preInternacao->delete();
            $preInternacao->criarLogExclusao($usuario_logado, $instituicao);
            

            return $preInternacao;
        });
            
        return redirect()->route('instituicao.preInternacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pre Internação excluída com sucesso!'
        ]);
    
    }

    public function getPaciente(Request $request)
    {
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);

        $paciente = $instituicao->instituicaoPessoas()->where('id', $request->input('paciente_id'))->first();
       
        return response()->json($paciente);
    }

    public function getEspecialidades(Request $request)
    {        
        $instituicao = $request->session()->get("instituicao");
        $especialidades = Especialidade::
        whereHas('prestadoresInstituicao', function($q) use ($instituicao, $request){
            $q->where('ativo', 1);
            $q->where('prestadores_id', $request->input('medico_id'));
            $q->where('instituicoes_id',$instituicao);
        })->get();

        return response()->json($especialidades);
    }

    public function getleitos(Request $request)
    {        
        $unidade = UnidadeInternacao::find($request->input('unidade_id'));

        return response()->json($unidade->leitos()->get());
    }

    public function addPacienteModal(Request $request){

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $campos_obg = json_decode($instituicao->config);
        $campos_obg = (!empty($campos_obg->pessoas)) ? $campos_obg->pessoas : null;

        $paciente = Pessoa::find($request->input('paciente_id'));

        return view('instituicao.pre_internacoes.add_paciente_form',[
            'referencia_relacoes' => Pessoa::getRelacoesParentescos(),
            'sexo' => Pessoa::getSexos(),
            'estado_civil' => Pessoa::getEstadosCivil(),
            'campos_obg' => $campos_obg,
            'paciente' => $paciente,
        ]);
    }

    public function getProcedimentos(Request $request, Convenio $convenio)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $procedimentos = $instituicao->procedimentosInstituicoes()->whereHas('instituicaoProcedimentosConvenios', function ($query) use ($convenio) {
            $query->where('convenios_id', $convenio->id);
        })->with(['procedimento', 'instituicaoProcedimentosConvenios' => function ($query) use ($convenio) {
            $query->where('convenios_id', $convenio->id);
        }])
        ->get();

        foreach ($procedimentos as $k => $v) {
            $dataWhere = ['procedimento_instituicao_convenio_id' => $v->instituicaoProcedimentosConvenios[0]->pivot->id];
            if(!empty($request->input('prestador_id'))){
                $dataWhere['prestador_id'] = $request->input('prestador_id');
            }
            

            $procConv = DB::table('procedimentos_convenios_has_repasse_medico')
                ->where($dataWhere)
                ->first();

            if (!empty((float) $procConv->valor_cobrado)) {
                $procedimentos[$k]->instituicaoProcedimentosConvenios[0]->pivot->valor =  $procConv->valor_cobrado;
            }
        }

        return response()->json($procedimentos);
    }

    public function salvarPaciente(CreatePessoaRequest $request){

        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

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

        // dd($request->input('paciente_id'));

        if(empty($request->input('paciente_id'))){
            if($dados['cpf']) $pessoa = Pessoa::where('instituicao_id', $instituicao->id)->where('cpf', $dados['cpf'])->first();


            if($pessoa) {
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Falha.',
                    'text' => 'Pessoa já cadastrada!'
                ]);
            }
        

            $newPaciente = DB::transaction(function() use ($instituicao, $request, $dados) {
                $pessoa = $instituicao->instituicaoPessoas()->create($dados);
                $pessoa->criarLogCadastro($request->user('instituicao'), $instituicao->id);

                return $pessoa;
                
            });

            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Pessoa criada com sucesso!',
                'dados' => $newPaciente
            ]);
        }else{
            $pessoa = Pessoa::find($request->input('paciente_id'));


            $editPaciente = DB::transaction(function() use ($instituicao, $request, $dados, $pessoa) {
                $pessoa->update($dados);
                $pessoa->criarLogEdicao($request->user('instituicao'), $instituicao->id);

                return $pessoa;
            });

            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Pessoa editada com sucesso!',
                'dados' => $editPaciente
            ]);
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

    public function getCids(Request $request)
    {
        if ($request->ajax()) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            // $pacientes = $instituicao->instituicaoPessoas()->getPacientes($nome)->simplePaginate(100);

            $cids = Cid::where('descricao', 'like', "%{$nome}%")->simplePaginate(150);

            $morePages = true;
            if (empty($cids->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $cids->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );

            return response()->json($results);
        }
    }

    
}
