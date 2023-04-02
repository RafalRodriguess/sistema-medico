<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Marcas\CriarRequestMarca;
use App\Http\Requests\Marcas\EditarRequestMarca;
use App\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Marcas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_admin', 'visualizar_marcas');

        return view('admin.marcas/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_marcas');
        $marcas = Marca::all();
        return view('admin.marcas/criar', \compact('marcas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarRequestMarca $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_marcas');

        $dados = $request->validated();

        $dados['slug'] = Str::slug($dados['nome'], '-', 'pt_BR');
        if (Marca::where('slug', $dados['slug'])->exists()) {
            throw ValidationException::withMessages([
                'nome'=> ['Marca já cadastrada'],
            ]);
        }

        if ($request->hasFile('imagem')) {
            $caminho = "/marcas";
            $caminhoCloud = $request->imagem->storePublicly($caminho, "cloud");
            $dados['imagem'] = $caminhoCloud;
        }

        DB::transaction(function () use ($request, $dados){

            $marcas = Marca::create($dados);

            $usuario_logado = $request->user('admin');

            $marcas->criarLogCadastro(
              $usuario_logado
            );

            return $marcas;
        });

        // return response()->json([
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'marca criado com sucesso!'
        // ]);

        return redirect()->route('marcas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'marca criado com sucesso!'
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
    public function edit(Marca $marca)
    {
        $this->authorize('habilidade_admin', 'editar_marcas');
        return view('admin.marcas/editar', \compact('marca'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarRequestMarca $request, Marca $marca)
    {
        $this->authorize('habilidade_admin', 'editar_marcas');
        $dados = $request->validated();

        $dados['slug'] = Str::slug($dados['nome'], '-', 'pt_BR');
        if (Marca::where('id', '<>', $marca->id)->where('slug', $dados['slug'])->exists()) {
            throw ValidationException::withMessages([
                'nome'=> ['Marca já cadastrada'],
            ]);
        }

        if ($request->hasFile('imagem')) {
            Storage::cloud()->delete($marca->imagem);

            $caminho = "/marcas";
            $caminhoCloud = $request->imagem->storePublicly($caminho, "cloud");
            $dados['imagem'] = $caminhoCloud;
        }

        DB::transaction(function () use ($request, $marca, $dados){
            $marca->update($dados);

            $usuario_logado = $request->user('admin');
            $marca->criarLogEdicao(
              $usuario_logado
            );

            return $marca;
        });

        // return response()->json([
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'marca atualizado com sucesso!'
        // ]);

        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('marcas.edit', [$marca])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'marca atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Marca $marca)
    {
        $this->authorize('habilidade_admin', 'excluir_marcas');
        DB::transaction(function () use ($request, $marca){
            $marca->delete();

            $usuario_logado = $request->user('admin');
            $marca->criarLogExclusao(
              $usuario_logado
            );

            return $marca;
        });

        return redirect()->route('marcas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'marca excluído com sucesso!'
        ]);
    }
}
