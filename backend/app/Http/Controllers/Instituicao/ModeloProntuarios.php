<?php

namespace App\Http\Controllers\Instituicao;

use App\Cid;
use App\Http\Controllers\Controller;
use App\Http\Requests\ModeloProntuario\CriarModeloProntuarioRequest;
use App\Instituicao;
use App\ModeloProntuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ModeloProntuarios extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_prontuario');
    
        return view('instituicao.configuracoes.modelo_prontuario.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_prontuario');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $usuario_logado = $request->user('instituicao');
        $prestadores = $instituicao->prestadores()->where('tipo', 2)->with(['prestador','especialidade']);

        if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_modelo_prontuario')){
            $prestadores->where('instituicao_usuario_id', $usuario_logado->id);
        }

        $prestadores = $prestadores->get();
        $cids = Cid::get();
        $modelo['prontuario'] = [];

        return view('instituicao.configuracoes.modelo_prontuario.criar', \compact('prestadores', 'cids', 'modelo'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarModeloProntuarioRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_prontuario');

        $dados = $request->validated();

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $instituicao_prestador = $instituicao->prestadores()->where('id', $dados['instituicao_prestador_id'])->first();

        abort_unless($instituicao->id === $instituicao_prestador->instituicoes_id, 403);

        DB::transaction(function() use ($request, $instituicao, $dados){
            $usuario_logado = $request->user('instituicao');
            if($dados['modelo'] == 'padrao'){
                $data['prontuario'] = [
                    'tipo' => $dados['modelo'],
                    'queixa_principal' => $dados['queixa_principal'],
                    'h_m_a' => $dados['h_m_a'],
                    'h_p' => $dados['h_p'],
                    'h_f' => $dados['h_f'],
                    'hipotese_diagnostica' => $dados['hipotese_diagnostica'],
                    'conduta' => $dados['conduta'],
                    'exame_fisico' => $dados['exame_fisico'],
                    'obs' => $dados['obs'],
                ];
                if($dados['cid'] != ""){
                    $data['prontuario']['cid'] = [
                        'id' => $dados['cid'],
                        'texto' => Cid::find($dados['cid'])->descricao
                    ] ;
                }
            }else{
                $data['prontuario'] = [
                    'tipo' => 'old',
                    'obs' => $dados['texto'],
                ];
            }

            $data['instituicao_prestador_id'] = $dados['instituicao_prestador_id'];
            $data['descricao'] = $dados['descricao'];

            $modelo = ModeloProntuario::create($data);
            $modelo->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modeloProntuario.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de prontuário criado com sucesso!'
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
    public function edit(Request $request, ModeloProntuario $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_prontuario');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id === $modelo->instituicaoPrestador->instituicoes_id, 403);
        $usuario_logado = $request->user('instituicao');
        $prestadores = $instituicao->prestadores()->where('tipo', 2)->with(['prestador','especialidade']);

        if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_modelo_prontuario')){
            $prestadores->where('instituicao_usuario_id', $usuario_logado->id);
        }

        $prestadores = $prestadores->get();
        $cids = Cid::get();

        return view('instituicao.configuracoes.modelo_prontuario.editar',\compact("modelo", "prestadores", 'cids'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarModeloProntuarioRequest $request, ModeloProntuario $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_prontuario');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id === $modelo->instituicaoPrestador->instituicoes_id, 403);

        DB::transaction(function() use ($request, $instituicao, $modelo){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            if($dados['modelo'] == 'padrao'){
                $data['prontuario'] = [
                    'tipo' => $dados['modelo'],
                    'queixa_principal' => $dados['queixa_principal'],
                    'h_m_a' => $dados['h_m_a'],
                    'h_p' => $dados['h_p'],
                    'h_f' => $dados['h_f'],
                    'hipotese_diagnostica' => $dados['hipotese_diagnostica'],
                    'conduta' => $dados['conduta'],
                    'exame_fisico' => $dados['exame_fisico'],
                    'obs' => $dados['obs'],
                ];
                if($dados['cid'] != ""){
                    $data['prontuario']['cid'] = [
                        'id' => $dados['cid'],
                        'texto' => Cid::find($dados['cid'])->descricao
                    ] ;
                }
            }else{
                $data['prontuario'] = [
                    'tipo' => 'old',
                    'obs' => $dados['texto'],
                ];
            }

            $data['instituicao_prestador_id'] = $dados['instituicao_prestador_id'];
            $data['descricao'] = $dados['descricao'];
                        
            $modelo->update($data);
            $modelo->criarLogEdicao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modeloProntuario.edit', [$modelo])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de prontuário alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ModeloProntuario $modelo)
    {  
        $this->authorize('habilidade_instituicao_sessao', 'excluir_modelo_prontuario');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $modelo->instituicaoPrestador->instituicoes_id, 403);

        DB::transaction(function() use ($request, $instituicao, $modelo){
            $usuario_logado = $request->user('instituicao');
            
            $modelo->delete();
            $modelo->criarLogExclusao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.modeloProntuario.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de prontuário excluido com sucesso!'
        ]);
    }
}
