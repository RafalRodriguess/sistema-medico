<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ramoss\CreateRamos;
use App\InstituicaoHabilidade;
use App\InstituicaoHabilidadeGrupo;
use App\Ramo;
use App\RamoHabilidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Ramos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_admin', 'visualizar_ramo');
        return view('admin.ramos/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_ramo');
        return view('admin.ramos/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRamos $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_ramo');
        DB::transaction(function () use ($request){
            $ramo = Ramo::create($request->validated());  
            
            $usuario_logado = $request->user('admin');
            $ramo->criarLogCadastro($usuario_logado);

            return $ramo;
        });

        return redirect()->route('ramos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Ramo criado com sucesso!'
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
    public function edit(Request $request, Ramo $ramo)
    {
        $this->authorize('habilidade_admin', 'editar_ramo');
        return view('admin.ramos/editar', compact('ramo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateRamos $request, Ramo $ramo)
    {
        $this->authorize('habilidade_admin', 'editar_ramo');
        $dados = $request->validated();

        DB::transaction(function () use ($request, $ramo, $dados){
            $ramo->update($dados);

            $usuario_logado = $request->user('admin');
            $ramo->criarLogEdicao($usuario_logado);

            return $ramo;
        });

        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('ramos.edit', [$ramo])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Ramo atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Ramo $ramo)
    {
        $this->authorize('habilidade_admin', 'excluir_ramo');
        DB::transaction(function () use ($request, $ramo){
            $ramo->delete();

            $usuario_logado = $request->user('admin');
            $ramo->criarLogExclusao($usuario_logado);

            return $ramo;
        });

        return redirect()->route('ramos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Ramo excluído com sucesso!'
        ]);
    }

    public function habilidades(Request $request, Ramo $ramo)
    {
        $this->authorize('habilidade_admin', 'habilidades_ramo');
        $selecionadas = RamoHabilidade::where('ramo_id', $ramo->id)->get()->toArray();

        $habilidades = InstituicaoHabilidadeGrupo::with('instituicaoHabilidades')->get();

        return view('admin.ramos/habilidades', compact('ramo', 'habilidades', 'selecionadas'));
    }

    public function habilidade(Request $request, Ramo $ramo)
    {
        $this->authorize('habilidade_admin', 'habilidades_ramo');
        $dados = $request->input('habilidades');
        $habilidades = array();
        
        foreach($dados as $k => $v){
            if($v){
                $habilidades[] = [
                    'ramo_id' => $ramo->id,
                    'habilidade_id' => $k
                ];
            }
        }

        $ramo->habilidades()->detach();
        $ramo->habilidades()->attach($habilidades);

        return redirect()->route('ramos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Habilidades atribuidas com sucesso!'
        ]);
        
    }
}
