<?php

namespace App\Http\Controllers\Comercial;

use App\Categoria;
use App\Comercial;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\CriarCategoriaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Categorias extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'visualizar_categoria');
 
        return view('comercial.categorias/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_categoria');

        return view('comercial.categorias/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarCategoriaRequest $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_categoria');

        // Este comercial não é mais do usuario, e da sessao
        $usuario_logado = $request->user('comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));

        DB::transaction(function () use ($request, $usuario_logado, $comercial){
            $categoria = $comercial->categorias()->create($request->validated());

            $categoria->criarLogCadastro(
                $usuario_logado,
                $comercial->id
            );
            return $categoria;
        });        

        return redirect()->route('comercial.categorias.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Categoria criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $perfilUsuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Categoria $categoria)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_categoria');

        $comercial_id = $request->session()->get('comercial');
        abort_unless($categoria->comercial_id === $comercial_id, 403);

        return view('comercial.categorias/editar', \compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function update(CriarCategoriaRequest $request, Categoria $categoria)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_categoria');
        $usuario_logado = $request->user('comercial');
        $comercial_id = $request->session()->get('comercial');
        abort_unless($categoria->comercial_id === $comercial_id, 403);

        $dados = $request->validated();

        DB::transaction(function () use ($usuario_logado, $comercial_id, $dados, $categoria){
            $categoria->update($dados);  

            $categoria->criarLogEdicao(
                $usuario_logado,
                $comercial_id
            );
            return $categoria;
        });

        //return redirect()->route('usuarios.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('comercial.categorias.edit', [$categoria])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Categoria atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Categoria $categoria)
    {   
        $this->authorize('habilidade_comercial_sessao', 'excluir_categoria');
        $usuario_logado = $request->user('comercial');
        $comercial_id = $request->session()->get('comercial');
        abort_unless($categoria->comercial_id === $comercial_id, 403);

        DB::transaction(function () use ($usuario_logado, $comercial_id, $categoria){
            $categoria->delete(); 

            $categoria->criarLogExclusao(
                $usuario_logado,
                $comercial_id
            );
            return $categoria;

        });        
    
        return redirect()->route('comercial.categorias.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Categoria excluído com sucesso!'
        ]);
    }
}
