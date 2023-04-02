<?php

namespace App\Http\Controllers\Comercial;

use App\Categoria;
use App\Comercial;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoria\CriarSubCategoriaRequest;
use App\SubCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Sub_categorias extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'visualizar_sub_categoria');
        
        return view('comercial.sub_categorias/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_sub_categoria');

        $comercial = Comercial::find($request->session()->get('comercial'));
        $categorias = $comercial->categorias()->get();

        return view('comercial.sub_categorias/criar',\compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarSubCategoriaRequest $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_sub_categoria');
        
        $usuario_logado = $request->user('comercial');

        $comercial = Comercial::find($request->session()->get('comercial'));

        DB::transaction(function () use ($comercial, $request, $usuario_logado){
            $sub_categoria = $comercial->subCategorias()->create($request->validated());

            $sub_categoria->criarLogCadastro(
                $usuario_logado,
                $comercial->id
            );

            return $sub_categoria;
        });

        return redirect()->route('comercial.sub_categorias.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Sub Categoria criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategoria $perfilUsuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, SubCategoria $sub_categoria)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_sub_categoria');
        
        $comercial = Comercial::find($request->session()->get('comercial'));
        abort_unless($sub_categoria->comercial_id === $comercial->id, 403);

        $categorias = $comercial->categorias()->get();

        return view('comercial.sub_categorias/editar', \compact('sub_categoria','categorias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function update(CriarSubCategoriaRequest $request, SubCategoria $sub_categoria)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_sub_categoria');

        $usuario_logado = $request->user('comercial');

        $comercialId = $request->session()->get('comercial');
        abort_unless($sub_categoria->comercial_id === $comercialId, 403);

        $dados = $request->validated();

        DB::transaction(function () use ($comercialId, $usuario_logado, $dados, $sub_categoria){
            $sub_categoria->update($dados);

            $sub_categoria->criarLogEdicao(
                $usuario_logado,
                $comercialId
            );

            return $sub_categoria;
        });

        
        //return redirect()->route('usuarios.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('comercial.sub_categorias.edit', [$sub_categoria])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Sub Categoria atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, SubCategoria $sub_categoria)
    {
        $this->authorize('habilidade_comercial_sessao', 'excluir_sub_categoria');

        $usuario_logado = $request->user('comercial');
        $comercialId = $request->session()->get('comercial');
        abort_unless($sub_categoria->comercial_id === $comercialId, 403);

        DB::transaction(function () use ($comercialId, $usuario_logado, $sub_categoria){
            $sub_categoria->delete();

            $sub_categoria->criarLogExclusao(
                $usuario_logado,
                $comercialId
            );

            return $sub_categoria;
        });        
    
        return redirect()->route('comercial.sub_categorias.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Sub Categoria excluído com sucesso!'
        ]);
    }
}
