<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Atendimentos\{
    AdminCreateAtendimento,
    AdminEditAtendimento
};
use Illuminate\Support\Facades\DB;
use App\Atendimento;

class Atendimentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_admin', 'visualizar_atendimentos');

        return view('admin.atendimentos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_atendimentos');

        return view('admin.atendimentos.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCreateAtendimento $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_atendimentos');

        
        DB::transaction(function() use ($request){
            $usuario_logado = $request->user('admin');
            $dados = $request->validated();
            $atendimento = new Atendimento();
            $atendimento->fill($dados);
            $atendimento->save();
            $atendimento->criarLogCadastro($usuario_logado);
        });

        return redirect()->route('admin.atendimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atendimento criado com sucesso!'
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
    public function edit(Request $request, Atendimento $atendimento)
    {
        $this->authorize('habilidade_admin', 'editar_atendimentos');

        return view('admin.atendimentos.editar', \compact('atendimento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminEditAtendimento $request, Atendimento $atendimento)
    {
        $this->authorize('habilidade_admin', 'editar_atendimentos');

        
        $atendimento = DB::transaction(function() use ($request, $atendimento){
            $usuario_logado = $request->user('admin');
            $dados = $request->validated();
            $atendimento->fill($dados);
            $atendimento->update();
            $atendimento->criarLogEdicao($usuario_logado);
            return $atendimento;
        });

        return redirect()->route('admin.atendimentos.edit', [$atendimento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atendimento atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Atendimento $atendimento)
    {
        $this->authorize('habilidade_admin', 'excluir_atendimentos');

        DB::transaction(function () use ($atendimento, $request) {


            $atendimento->delete();

            $usuario_logado = $request->user('admin');
            $atendimento->criarLogExclusao(
              $usuario_logado
            );

            return $atendimento;
        });
        
        return redirect()->route('admin.atendimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atendimento excluído com sucesso!'
        ]);
    }
}
