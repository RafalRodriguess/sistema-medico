<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Usuarios\CriarUsuarioRequest;
use App\Http\Requests\Usuarios\EditarUsuarioRequest;
use App\Notifications\NotificationUser;
use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Usuarios extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_admin', 'visualizar_usuario');
        
        return view('admin.usuarios/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_usuario');
        $usuarios = Usuario::all();
        return view('admin.usuarios/criar', \compact('usuarios'));
    }

    public function store(CriarUsuarioRequest $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_usuario');
        DB::transaction(function () use ($request){
            $usuario = Usuario::create($request->validated());  
            
            $usuario_logado = $request->user('admin');
            $usuario->criarLogCadastro($usuario_logado);

            return $usuario;
        });
        // Log::create([
        //     'model' => Comercial::class,
        //     'model_id' => $comercial->id,
        // ])

        return redirect()->route('usuarios.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuario criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Usuario  $Usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
        //
    }

    public function edit(Usuario $usuario)
    {
        $this->authorize('habilidade_admin', 'editar_usuario');
        return view('admin.usuarios/editar', \compact('usuario'));
    }

    public function update(EditarUsuarioRequest $request, Usuario $usuario)
    {
        $this->authorize('habilidade_admin', 'editar_usuario');
        $dados = $request->validated();

        DB::transaction(function () use ($usuario, $request, $dados){
            $usuario->update($dados);  
            
            $usuario_logado = $request->user('admin');
            $usuario->criarLogEdicao($usuario_logado);

            return $usuario;
        });

        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('usuarios.edit', [$usuario])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuario atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Usuario  $Usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Usuario $usuario)
    {
        $this->authorize('habilidade_admin', 'excluir_usuario');
        DB::transaction(function () use ($usuario, $request){
            $usuario->delete();
            
            $usuario_logado = $request->user('admin');
            $usuario->criarLogExclusao($usuario_logado);

            return $usuario;
        });
        
    
        return redirect()->route('usuarios.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuario excluído com sucesso!'
        ]);
    }

    public function usuarioDevice(Usuario $usuario)
    {
        $this->authorize('habilidade_admin', 'dispositivo_usuario');

        $dispositivos = $usuario->tokens()->get();

        return view('admin.usuarios.devices', [
            'dispositivos' => $dispositivos,
            'usuario' => $usuario,
        ]);
    }

    public function usuarioRemoveDevice(Request $request, Usuario $usuario)
    {   
        $dispositivos = $usuario->tokens()->where('id', $request->input('id'))->first();
        abort_unless($dispositivos, 404);

        DB::transaction(function () use ($dispositivos, $request, $usuario){
            $dispositivos->delete();
            
            $administrador_logado = $request->user('admin');
            $usuario->criarLog(
                $administrador_logado,
                "Removendo dispositivo do usuário: {$usuario->nome} ",
                $dispositivos
            );

            return $dispositivos;
        });

        return redirect()->route('usuario.usuarioDevice', [$usuario])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Dispositivo removido com sucesso!'
        ]);
    }
}
