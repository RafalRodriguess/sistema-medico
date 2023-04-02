<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medicamentos\CriarMedicamentoRequest;
use App\Http\Requests\Receituario\CriarMedicamentoReceituarioRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\InstituicaoMedicamento as Medicamento;

class Medicamentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_medicamentos');

        return view('instituicao.medicamentos/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_medicamentos');
        
        return view('instituicao.medicamentos/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarMedicamentoReceituarioRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_medicamentos');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        
        $dados = $request->validated();

        DB::transaction(function () use ($dados, $request, $instituicao){
            $usuario_logado = $request->user('instituicao');

            $dados = $request->validated();
            $dados['status'] = 1;
            if(array_key_exists('composicoes', $dados)){
                foreach ($dados['composicoes'] as $key => $value) {
                    $dados['composicao'][] = $value;
                }
            }

            $medicamento = $instituicao->medicamentos()->create($dados);

            $medicamento->criarLogCadastro($usuario_logado, $instituicao->id);

            if(array_key_exists('posologia', $dados) || array_key_exists('quantidade', $dados)){
                $data[] = [
                    'posologia' => (array_key_exists('posologia', $dados)) ? $dados['posologia'] : null, 
                    'quantidade' => (array_key_exists('quantidade', $dados)) ? $dados['quantidade'] : null, 
                    'instituicao_usuario_id' => $usuario_logado->id,
                ];
                
                $medicamento->usuario()->attach($data);
            }

            return $medicamento;
        });
        
        
        return redirect()->route('instituicao.medicamentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Medicamento criado com sucesso!'
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
    public function edit(Request $request, Medicamento $medicamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_medicamentos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$medicamento->instituicao_id, 403);
        $usuario_logado = $request->user('instituicao');
        
        $vinculo = $medicamento->usuario()->where('instituicao_usuario_id', $usuario_logado->id)->first();
        
        return view('instituicao.medicamentos/editar', \compact('medicamento', 'vinculo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarMedicamentoReceituarioRequest $request, Medicamento $medicamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_medicamentos');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$medicamento->instituicao_id, 403);

        DB::transaction(function () use ($request, $medicamento){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            if(array_key_exists('composicoes', $dados)){
                foreach ($dados['composicoes'] as $key => $value) {
                    $dados['composicao'][] = $value;
                }
            }

            $medicamento->update($dados);

            DB::table("medicamentos_add_prestador")->where([
                "instituicao_usuario_id" => $usuario_logado->id,
                "instituicao_medicamento_id" => $medicamento->id
            ])->delete();
            
            if(array_key_exists('posologia', $dados) || array_key_exists('quantidade', $dados)){
                $data[] = [
                    'posologia' => (array_key_exists('posologia', $dados)) ? $dados['posologia'] : null, 
                    'quantidade' => (array_key_exists('quantidade', $dados)) ? $dados['quantidade'] : null, 
                    'instituicao_usuario_id' => $usuario_logado->id,
                ];
                
                $medicamento->usuario()->attach($data);
            }

            
            $medicamento->criarLogEdicao($usuario_logado);

            return $medicamento;
        });
  
        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('instituicao.medicamentos.edit', [$medicamento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Medicamento atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Medicamento $medicamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_medicamentos');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$medicamento->instituicao_id, 403);

        DB::transaction(function () use ($request, $medicamento, $instituicao){

            $medicamento->delete();

            $usuario_logado = $request->user('instituicao');
            $medicamento->criarLogExclusao($usuario_logado, $instituicao);

            return $medicamento;
        });
        
    
        return redirect()->route('instituicao.medicamentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => "Medicamento excluido com sucesso!"
        ]);
    }

    public function getFormulario()
    {
        return view('instituicao.medicamentos/formulario');
    }
}
