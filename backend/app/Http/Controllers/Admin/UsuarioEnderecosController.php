<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsuarioEnderecos\CriarUsuarioEnderecoRequest;
use App\Usuario;
use App\UsuarioEndereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioEnderecosController extends Controller 
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function index(Usuario $usuario)
    {
        $this->authorize('habilidade_admin', 'visualizar_endereco_usuario');
    
        return view('admin.usuario_enderecos.lista', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function create(Usuario $usuario)
    {
        $this->authorize('habilidade_admin', 'cadastrar_endereco_usuario');
        // $enderecos = $usuario->usuarioEnderecos()->get();
        return view('admin.usuario_enderecos.criar', \compact('usuario'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function store(CriarUsuarioEnderecoRequest $request, Usuario $usuario)
    {
        $this->authorize('habilidade_admin', 'cadastrar_endereco_usuario');
        DB::transaction(function () use ($usuario, $request){
            $endereco = $usuario->usuarioEnderecos()->create($request->validated());
            
            $usuario_logado = $request->user('admin');
            $endereco->criarLogCadastro($usuario_logado);

            return $endereco;
        });

        return redirect()->route('usuario_enderecos.index', [$usuario])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Endereço criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @param  \App\UsuarioEndereco  $usuarioEndereco
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario, UsuarioEndereco $usuarioEndereco)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @param  \App\UsuarioEndereco  $usuarioEndereco
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuario $usuario, UsuarioEndereco $endereco)
    {
        $this->authorize('habilidade_admin', 'editar_endereco_usuario');
        abort_unless($usuario->id === $endereco->usuario_id, 403);

        return view('admin.usuario_enderecos.editar', \compact('usuario','endereco'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuario  $usuario
     * @param  \App\UsuarioEndereco  $usuarioEndereco
     * @return \Illuminate\Http\Response
     */
    public function update(CriarUsuarioEnderecoRequest $request, Usuario $usuario, UsuarioEndereco $endereco)
    {
        $this->authorize('habilidade_admin', 'editar_endereco_usuario');
        abort_unless($usuario->id === $endereco->usuario_id, 403);
        $dados = $request->validated();

        DB::transaction(function () use ($endereco, $request, $dados){
            $endereco->update($dados);  
            
            $usuario_logado = $request->user('admin');
            $endereco->criarLogEdicao($usuario_logado);

            return $endereco;
        });
        
        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('usuario_enderecos.edit', [$usuario, $endereco])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuario endereço atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Usuario  $usuario
     * @param  \App\UsuarioEndereco  $usuarioEndereco
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Usuario $usuario, UsuarioEndereco $endereco)
    {
        $this->authorize('habilidade_admin', 'excluir_endereco_usuario');
        abort_unless($usuario->id === $endereco->usuario_id, 403);

        DB::transaction(function () use ($endereco, $request){
            $endereco->delete();
            
            $usuario_logado = $request->user('admin');
            $endereco->criarLogExclusao($usuario_logado);

            return $endereco;
        });
        
    
        return redirect()->route('usuario_enderecos.index',[$usuario])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuario endereço excluído com sucesso!'
        ]);
    }
}
