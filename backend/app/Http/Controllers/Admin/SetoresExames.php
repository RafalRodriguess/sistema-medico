<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetorExame\CriarSetorExameRequest;
use App\Http\Requests\SetorExame\EditarSetorExameRequest;
use App\Instituicao;
use App\SetorExame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetoresExames extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_admin', 'visualizar_setor_exame');
        return view('admin.setor_exame/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_setor_exame');
        $instituicoes = Instituicao::get();
        return view('admin.setor_exame/criar', \compact('instituicoes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarSetorExameRequest $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_setor_exame');
        DB::transaction(function () use ($request){
            $dados = $request->validated();
            $dados['ativo'] = 1;
            $setor = SetorExame::create($dados);  
            
            $usuario_logado = $request->user('admin');
            $setor->criarLogCadastro($usuario_logado);

            return $setor;
        });

        return redirect()->route('setorExame.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Setor criado com sucesso!'
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
    public function edit(Request $request, SetorExame $setor)
    {
        $this->authorize('habilidade_admin', 'editar_setor_exame');
        return view('admin.setor_exame/editar', compact('setor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarSetorExameRequest $request, SetorExame $setor)
    {
        $this->authorize('habilidade_admin', 'editar_setor_exame');
        $dados = $request->validated();

        DB::transaction(function () use ($request, $setor, $dados){
            $setor->update($dados);

            $usuario_logado = $request->user('admin');
            $setor->criarLogEdicao($usuario_logado);

            return $setor;
        });

        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('setorExame.edit', [$setor])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Setor atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, SetorExame $setor)
    {
        $this->authorize('habilidade_admin', 'excluir_setor_exame');
        $texto = DB::transaction(function () use ($request, $setor){
            if($setor->ativo == 1){
                $dados['ativo'] = 0;
                $texto = 'desativado';
            }else{
                $dados['ativo'] = 1;
                $texto = 'ativado';
            }
            $setor->update($dados);

            $usuario_logado = $request->user('admin');
            $setor->criarLogEdicao($usuario_logado);

            return $texto;
        });

        return redirect()->route('setorExame.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => "setor {$texto} com sucesso!"
        ]);
    }
    
    public function desativarAtivar(Request $request, SetorExame $setor)
    {
        $this->authorize('habilidade_admin', 'excluir_setor_exame');
        $texto = DB::transaction(function () use ($request, $setor){
            if($setor->ativo == 1){
                $dados['ativo'] = 0;
                $texto = 'desativado';
            }else{
                $dados['ativo'] = 1;
                $texto = 'ativado';
            }
            $setor->update($dados);

            $usuario_logado = $request->user('admin');
            $setor->criarLogEdicao($usuario_logado);

            return $texto;
        });

        return redirect()->route('setorExame.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => "setor {$texto} com sucesso!"
        ]);
    }
}
