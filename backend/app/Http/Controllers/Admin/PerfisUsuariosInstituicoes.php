<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PerfisUsuariosInstituicoes\CreatePerfil;
use App\Http\Requests\PerfisUsuariosInstituicoes\EditPerfil;
use App\InstituicaoHabilidadeGrupo;
use App\PerfilInstituicaoHabilidade;
use App\PerfilUsuarioInstituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerfisUsuariosInstituicoes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_admin', 'visualizar_perfil_instituicao');
        return view('admin.perfis_usuarios_instituicoes/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_perfil_instituicao');
        return view('admin.perfis_usuarios_instituicoes/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePerfil $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_perfil_instituicao');
        DB::transaction(function () use ($request){
            $perfilUsuario = PerfilUsuarioInstituicao::create($request->validated());
            
            $usuario_logado = $request->user('admin');
            $perfilUsuario->criarLogCadastro($usuario_logado);

            return $perfilUsuario;
        });

        return redirect()->route('perfis-usuarios-instituicoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Perfil de Usuário criado com sucesso!'
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
    public function edit(PerfilUsuarioInstituicao $perfil_usuario)
    {
        $this->authorize('habilidade_admin', 'editar_perfil_instituicao');
        return view('admin.perfis_usuarios_instituicoes/editar', compact('perfil_usuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(EditPerfil $request, PerfilUsuarioInstituicao $perfil_usuario)
    {
        $this->authorize('habilidade_admin', 'editar_perfil_instituicao');
        $dados = $request->validated();

        DB::transaction(function () use ($perfil_usuario, $request, $dados){
            $perfil_usuario->update($dados); 
            
            $usuario_logado = $request->user('admin');
            $perfil_usuario->criarLogEdicao($usuario_logado);

            return $perfil_usuario;
        });
         
        //return redirect()->route('usuarios.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('perfis-usuarios-instituicoes.edit', [$perfil_usuario])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Perfil de Usuário atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PerfilUsuarioInstituicao $perfil_usuario)
    {
        $this->authorize('habilidade_admin', 'excluir_perfil_instituicao');
        DB::transaction(function () use ($perfil_usuario, $request){
            $perfil_usuario->delete();
            
            $usuario_logado = $request->user('admin');
            $perfil_usuario->criarLogExclusao($usuario_logado);

            return $perfil_usuario;
        });
        
    
        return redirect()->route('perfis-usuarios-instituicoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Perfil de Usuário excluído com sucesso!'
        ]);
    }

    public function habilidades(Request $request, PerfilUsuarioInstituicao $perfil_usuario){
        $this->authorize('habilidade_admin', 'habilidades_perfil_instituicao');
        $habilidades = InstituicaoHabilidadeGrupo::with('instituicaoHabilidades')->get();
        $selecionadas = PerfilInstituicaoHabilidade::where('perfil_id', $perfil_usuario->id)->get()->toArray();

        $perfil_usuario->load("habilidades");
        

        return view('admin.perfis_usuarios_instituicoes.habilidades', \compact('perfil_usuario', 'habilidades', 'selecionadas'));

    }

    public function habilidade(Request $request, PerfilUsuarioInstituicao $perfil_usuario){
        $this->authorize('habilidade_admin', 'habilidades_perfil_instituicao');
        
        $dados = $request->input('habilidades');
        $habilidades = array();
        
        foreach($dados as $k => $v){
            if($v){
                $habilidades[] = [
                    'perfil_id' => $perfil_usuario->id,
                    'habilidade_id' => $k
                ];
            }
        }

        $perfil_usuario->habilidades()->detach();
        $perfil_usuario->habilidades()->attach($habilidades);

        return redirect()->route('perfis-usuarios-instituicoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Habilidades atribuidas com sucesso!'
        ]);
    }
}
