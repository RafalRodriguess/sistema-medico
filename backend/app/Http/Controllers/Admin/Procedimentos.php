<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Procedimentos\CriarProcedimentoRequest;
use App\Procedimento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class Procedimentos extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index(Request $request)
        {
           $this->authorize('habilidade_admin', 'visualizar_procedimentos');

           return view('admin.procedimentos/lista');
       }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$this->authorize('habilidade_admin', 'cadastrar_procedimentos');
    	$procedimentos = Procedimento::all(); // busca todos os procediments registrados
        return view('admin.procedimentos/criar', \compact('procedimentos'));
    }


    public function edit(Procedimento $procedimento)
    {
        $this->authorize('habilidade_admin', 'editar_procedimentos');
        return view('admin.procedimentos/editar', \compact('procedimento'));
    }


    public function update(CriarProcedimentoRequest $request, Procedimento $procedimento)
    {
        $this->authorize('habilidade_admin', 'editar_procedimentos');
        $dados = $request->validated();

        DB::transaction(function () use ($request, $procedimento, $dados){
            $procedimento->update($dados);

            $usuario_logado = $request->user('admin');
            $procedimento->criarLogEdicao($usuario_logado);

            return $procedimento;
        });

        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('procedimentos.edit', [$procedimento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimento atualizado com sucesso!'
        ]);
    }



    public function store(CriarProcedimentoRequest $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_procedimentos');
        $dados = $request->validated();

        DB::transaction(function () use ($dados, $request){
            $procedimento = Procedimento::create($dados);
            $usuario_logado = $request->user('admin');
            $procedimento->criarLogCadastro($usuario_logado);
            return $procedimento;
        });


        return redirect()->route('procedimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimento criado com sucesso!'
        ]);
    }


   /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Procedimento  $procedimento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Procedimento $procedimento)
    {
        $this->authorize('habilidade_admin', 'excluir_procedimentos');
        DB::transaction(function () use ($request, $procedimento){
            $procedimento->delete();

            $usuario_logado = $request->user('admin');
            $procedimento->criarLogExclusao($usuario_logado);

            return $procedimento;
        });

        return redirect()->route('procedimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimento excluído com sucesso!'
        ]);
    }


}
