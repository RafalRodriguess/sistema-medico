<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModeloReceituario\CriarModeloReceituarioRequest;
use App\Instituicao;
use App\InstituicaoMedicamento;
use App\ModeloReceituario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ModeloReceituarios extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_receituario');
    
        return view('instituicao.configuracoes.modelo_receituario.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_receituario');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $usuario_logado = $request->user('instituicao');
        $prestadores = $instituicao->prestadores()->where('tipo', 2)->whereNotNull('especialidade_id')->with(['prestador','especialidade']);

        if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_modelo_receituario')){
            $prestadores->where('instituicao_usuario_id', $usuario_logado->id);
        }

        $prestadores = $prestadores->get();

        $medicamentos = $instituicao->medicamentos()->get();

        return view('instituicao.configuracoes.modelo_receituario.criar', \compact('prestadores', 'medicamentos'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarModeloReceituarioRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_receituario');

        $dados = $request->validated();

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $instituicao_prestador = $instituicao->prestadores()->where('id', $dados['instituicao_prestador_id'])->first();

        abort_unless($instituicao->id === $instituicao_prestador->instituicoes_id, 403);

        DB::transaction(function() use ($request, $instituicao, $dados){
            $tipo = ($request->boolean('tipo')) ? 'especial' : 'simples';
            $estrutura = ($request->boolean('estrutura')) ? 'livre' : 'formulario';

            $dados['tipo'] = $tipo;
            $dados['estrutura'] = $estrutura;
            
            if ($request->boolean('estrutura')) {
                $dados['receituario'] = [
                    'receituario' => $dados['texto'],
                ];
            }else{
                
                foreach ($request->validated()['medicamentos'] as $key => $value) {
                
                    $medicamento = InstituicaoMedicamento::find($value['medicamento']);  
                    $composicao = null;
                    if(array_key_exists('composicoes', $request->validated())) { 
                        if(array_key_exists($key, $request->validated()['composicoes'])) { 
                            foreach ($request->validated()['composicoes'][$key] as $key => $composicoes) {
                                $composicao[] = [
                                    "substancia" => $composicoes['substancia'],
                                    "concentracao" => $composicoes['concentracao'],
                                ];
                            }
                        }
                    }
    
                    $medicamentos[] = [
                        "medicamento" => [
                            'nome' => $medicamento->nome.' ('.$medicamento->forma_farmaceutica.' '.$medicamento->concentracao.')',
                            'medicamento_id' => $medicamento->id,
                            'composicao' => $composicao,
                        ],
                        "quantidade" => $value['quantidade'],
                        "posologia" => $value['posologia'],
                    ];
                }

                $dados['receituario'] = $medicamentos;
            }

            unset($dados['medicamentos']);
            unset($dados['texto']);

            $usuario_logado = $request->user('instituicao');
            
            $modelo = ModeloReceituario::create($dados);
            $modelo->criarLogCadastro($usuario_logado, $instituicao->id);
            
        });

        return redirect()->route('instituicao.modeloReceituario.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de receituário criado com sucesso!'
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
    public function edit(Request $request, ModeloReceituario $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_receituario');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id === $modelo->instituicaoPrestador->instituicoes_id, 403);
        $usuario_logado = $request->user('instituicao');
        $prestadores = $instituicao->prestadores()->where('tipo', 2)->whereNotNull('especialidade_id')->with(['prestador','especialidade']);

        if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_modelo_receituario')){
            $prestadores->where('instituicao_usuario_id', $usuario_logado->id);
        }

        $prestadores = $prestadores->get();

        $medicamentos = $instituicao->medicamentos()->get();

        // dd($modelo->receituario[0]['medicamento']);
        return view('instituicao.configuracoes.modelo_receituario.editar',\compact("modelo", "prestadores", 'medicamentos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarModeloReceituarioRequest $request, ModeloReceituario $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_receituario');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id === $modelo->instituicaoPrestador->instituicoes_id, 403);

        $dados = $request->validated();

        DB::transaction(function() use ($request, $instituicao, $dados, $modelo){
            $tipo = ($request->boolean('tipo')) ? 'especial' : 'simples';
            $estrutura = ($request->boolean('estrutura')) ? 'livre' : 'formulario';

            $dados['tipo'] = $tipo;
            $dados['estrutura'] = $estrutura;
            
            if ($request->boolean('estrutura')) {
                $dados['receituario'] = [
                    'receituario' => $dados['texto'],
                ];
            }else{
                
                foreach ($request->validated()['medicamentos'] as $key => $value) {
                
                    $medicamento = InstituicaoMedicamento::find($value['medicamento']);  
                    $composicao = null;
                    if(array_key_exists('composicoes', $request->validated())) { 
                        if(array_key_exists($key, $request->validated()['composicoes'])) { 
                            foreach ($request->validated()['composicoes'][$key] as $key => $composicoes) {
                                $composicao[] = [
                                    "substancia" => $composicoes['substancia'],
                                    "concentracao" => $composicoes['concentracao'],
                                ];
                            }
                        }
                    }
    
                    $medicamentos[] = [
                        "medicamento" => [
                            'nome' => $medicamento->nome.' ('.$medicamento->forma_farmaceutica.' '.$medicamento->concentracao.')',
                            'medicamento_id' => $medicamento->id,
                            'composicao' => $composicao,
                        ],
                        "quantidade" => $value['quantidade'],
                        "posologia" => $value['posologia'],
                    ];
                }

                $dados['receituario'] = $medicamentos;
            }

            unset($dados['medicamentos']);
            unset($dados['texto']);

            $usuario_logado = $request->user('instituicao');
            
            $modelo->update($dados);
            $modelo->criarLogEdicao($usuario_logado, $instituicao->id);
            
        });

        return redirect()->route('instituicao.modeloReceituario.edit', [$modelo])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de receituário alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ModeloReceituario $modelo)
    {  
        $this->authorize('habilidade_instituicao_sessao', 'excluir_modelo_receituario');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $modelo->instituicaoPrestador->instituicoes_id, 403);

        DB::transaction(function() use ($request, $instituicao, $modelo){
            $usuario_logado = $request->user('instituicao');
            
            $modelo->delete();
            $modelo->criarLogExclusao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.modeloReceituario.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de receituário excluido com sucesso!'
        ]);
    }

    public function formMedicamentos(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $medicamentos = $instituicao->medicamentos()->get();
        return view('instituicao.prontuarios.receituarios.form', compact('medicamentos'));
    }
    
    public function formAddMedicamentos(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $medicamentos = $instituicao->medicamentos()->get();
        return view('instituicao.medicamentos.formulario', compact('medicamentos'));
    }
}
