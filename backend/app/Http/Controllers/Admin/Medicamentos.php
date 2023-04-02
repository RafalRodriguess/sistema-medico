<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medicamentos\CriarMedicamentoRequest;
use App\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Medicamentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_admin', 'visualizar_medicamentos');

        return view('admin.medicamentos/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_medicamentos');
        $medicamentos = Medicamento::all();
        return view('admin.medicamentos/criar', \compact('medicamentos'));
    }

    public function store(CriarMedicamentoRequest $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_medicamentos');
        $dados = $request->validated();

        DB::transaction(function () use ($dados, $request){
            $medicamento = Medicamento::create($dados);

            $usuario_logado = $request->user('admin');
            $medicamento->criarLogCadastro($usuario_logado);

            return $medicamento;
        });
        
        
        return redirect()->route('medicamentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Medicamento criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Medicamento  $medicamento
     * @return \Illuminate\Http\Response
     */
    public function show(Medicamento $medicamento)
    {
        //
    }

    public function edit(Medicamento $medicamento)
    {
        $this->authorize('habilidade_admin', 'editar_medicamentos');
        return view('admin.medicamentos/editar', \compact('medicamento'));
    }

    public function update(CriarMedicamentoRequest $request, Medicamento $medicamento)
    {
        $this->authorize('habilidade_admin', 'editar_medicamentos');
        $dados = $request->validated();

        DB::transaction(function () use ($request, $medicamento, $dados){
            $medicamento->update($dados);

            $usuario_logado = $request->user('admin');
            $medicamento->criarLogEdicao($usuario_logado);

            return $medicamento;
        });
  
        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('medicamentos.edit', [$medicamento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Medicamento atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Medicamento  $medicamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Medicamento $medicamento)
    {
        $this->authorize('habilidade_admin', 'excluir_medicamentos');
        DB::transaction(function () use ($request, $medicamento){
            $medicamento->delete();

            $usuario_logado = $request->user('admin');
            $medicamento->criarLogExclusao($usuario_logado);

            return $medicamento;
        });
    
        return redirect()->route('medicamentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Medicamento excluído com sucesso!'
        ]);
    }
}
