<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
use App\Http\Requests\MotivoCancelamentos\CriarMotivoCancelamentosRequest;
use App\Instituicao;
use App\MotivoCancelamento; 
use Illuminate\Support\Facades\DB;

class MotivoCancelamentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivo_cancelamento');
    
        return view('instituicao.motivo_cancelamentos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivo_cancelamento');

        return view('instituicao.motivo_cancelamentos.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarMotivoCancelamentosRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_tipo_partos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $motivoCancelamento = $instituicao->motivo_cancelamentos()->create($dados);
            $motivoCancelamento->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.motivoCancelamentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo criado com sucesso!'
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
    public function edit(MotivoCancelamento $motivo_cancelamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivo_cancelamento');

        return view('instituicao.motivo_cancelamentos.editar',\compact("motivo_cancelamento"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarMotivoCancelamentosRequest $request, MotivoCancelamento $motivo_cancelamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivo_cancelamento');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$motivo_cancelamento->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $motivo_cancelamento){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $motivo_cancelamento->update($dados);
            $motivo_cancelamento->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.motivoCancelamentos.edit', [$motivo_cancelamento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MotivoCancelamento $motivo_cancelamento)
    {  

        $this->authorize('habilidade_instituicao_sessao', 'excluir_motivo_cancelamento');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$motivo_cancelamento->instituicao_id, 403);
        DB::transaction(function () use ($motivo_cancelamento, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $motivo_cancelamento->delete();
            $motivo_cancelamento->criarLogExclusao($usuario_logado, $instituicao);
            

            return $motivo_cancelamento;
        });
        
        return redirect()->route('instituicao.motivoCancelamentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo excluído com sucesso!'
        ]);
    }
}
