<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
 
use App\Http\Requests\MotivoPedidos\CriarMotivopPedidoRequest;
use App\Instituicao;
use App\MotivoPedido; 
use Illuminate\Support\Facades\DB;

class MotivoPedidos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivo_pedido');
    
        return view('instituicao.motivo_pedidos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivo_pedido');

        return view('instituicao.motivo_pedidos.criar');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarMotivopPedidoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivo_pedido');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $motivoPedido = $instituicao->motivo_pedidos()->create($dados);
            $motivoPedido->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.motivoPedidos.index')->with('mensagem', [
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
    public function edit(MotivoPedido $motivo_pedido)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivo_pedido');

        return view('instituicao.motivo_pedidos.editar',\compact("motivo_pedido"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarMotivopPedidoRequest $request, MotivoPedido $motivo_pedido)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivo_pedido');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$motivo_pedido->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $motivo_pedido){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $motivo_pedido->update($dados);
            $motivo_pedido->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.motivoPedidos.edit', [$motivo_pedido])->with('mensagem', [
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
    public function destroy(Request $request, MotivoPedido $motivo_pedido)
    {  

        $this->authorize('habilidade_instituicao_sessao', 'excluir_motivo_pedido');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$motivo_pedido->instituicao_id, 403);
        DB::transaction(function () use ($motivo_pedido, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $motivo_pedido->delete();
            $motivo_pedido->criarLogExclusao($usuario_logado, $instituicao);
            

            return $motivo_pedido;
        });
        
        return redirect()->route('instituicao.motivoPedidos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo excluído com sucesso!'
        ]);
    }
}
