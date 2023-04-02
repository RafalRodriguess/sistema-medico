<?php

namespace App\Http\Controllers\Admin;

use App\HabilidadeGrupo;
use App\Http\Requests\PerfisUsuarios\CriarPerfisRequest;
use App\PerfilUsuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PerfisUsuarios\UpdatePerfisHabilidadeRequest;
use Illuminate\Support\Facades\DB;

class Perfis_usuarios extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->authorize('habilidade_admin', 'visualizar_perfis_usuarios');
        return view('admin.perfis_usuarios/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_perfis_usuarios');
        return view('admin.perfis_usuarios/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarPerfisRequest $request)
    {   
        $this->authorize('habilidade_admin', 'cadastrar_perfis_usuarios');
        DB::transaction(function () use ($request){
            $perfilUsuario = PerfilUsuario::create($request->validated());
            
            $usuario_logado = $request->user('admin');
            $perfilUsuario->criarLogCadastro($usuario_logado);

            return $perfilUsuario;
        });
        

        return redirect()->route('perfis_usuarios.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Perfil de Usuário criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function show(PerfilUsuario $perfilUsuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function edit(PerfilUsuario $perfisUsuario)
    {
        $this->authorize('habilidade_admin', 'editar_perfis_usuarios');
        return view('admin.perfis_usuarios/editar', \compact('perfisUsuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function update(CriarPerfisRequest $request, PerfilUsuario $perfisUsuario)
    {
        $this->authorize('habilidade_admin', 'editar_perfis_usuarios');
        $dados = $request->validated();

        DB::transaction(function () use ($perfisUsuario, $request, $dados){
            $perfisUsuario->update($dados); 
            
            $usuario_logado = $request->user('admin');
            $perfisUsuario->criarLogEdicao($usuario_logado);

            return $perfisUsuario;
        });
         
        //return redirect()->route('usuarios.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('perfis_usuarios.edit', [$perfisUsuario])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Perfil de Usuário atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PerfilUsuario $perfisUsuario)
    {
        $this->authorize('habilidade_admin', 'excluir_perfis_usuarios');
        DB::transaction(function () use ($perfisUsuario, $request){
            $perfisUsuario->delete();
            
            $usuario_logado = $request->user('admin');
            $perfisUsuario->criarLogExclusao($usuario_logado);

            return $perfisUsuario;
        });
        
    
        return redirect()->route('perfis_usuarios.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Perfil de Usuário excluído com sucesso!'
        ]);
    }

    public function editHabilidades(Request $request, PerfilUsuario $perfisUsuario)
    {
        $this->authorize('habilidade_admin', 'habilidades_perfis_usuarios');
        $habilidades = HabilidadeGrupo::with("habilidades")->get();
        $perfisUsuario->load("habilidades");

        return view('admin.perfis_usuarios.habilidades', \compact('perfisUsuario', 'habilidades'));
    }

    public function updateHabilidades(UpdatePerfisHabilidadeRequest $request, PerfilUsuario $perfisUsuario)
    {
        $this->authorize('habilidade_admin', 'habilidades_perfis_usuarios');
        $habilidades = collect($request->validated()['habilidades'])
            ->filter(function ($habilidade) {
                return $habilidade == "1";
            })
            ->map(function ($habilitado) {
                return [
                    'habilitado' => $habilitado,
                ];
            });

        DB::transaction(function () use ($perfisUsuario, $habilidades, $request){
            $perfisUsuario->habilidades()->sync($habilidades);
            
            $usuario_logado = $request->user('admin');
            $perfisUsuario->criarLog(
                $usuario_logado,
                'Alteração habilidades do perfil',
                $habilidades
            );

            return $perfisUsuario;
        });
        
    
        return redirect()->route('perfis_usuarios.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Habilidades do perfil de usuário editado com sucesso!'
        ]);
    }
}
