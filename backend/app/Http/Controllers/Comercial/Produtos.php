<?php

namespace App\Http\Controllers\Comercial;

use App\Categoria;
use App\Comercial;
use App\Http\Controllers\Controller;
use App\Http\Requests\Produtos\CriarProdutoRequest;
use App\Http\Requests\Produtos\EditarEstoqueProdutosRequest;
use App\Http\Requests\Produtos\EditarProdutoRequest;
use App\Http\Requests\Produtos\EditarPromocaoProdutoRequest;
use App\Instituicao;
use App\Marca;
use App\Medicamento;
use App\Produto;
use App\SubCategoria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class Produtos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'visualizar_produto');

        return view('comercial.produtos/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_produto');

        $comercial = Comercial::find($request->session()->get('comercial'));
        $categorias = $comercial->categorias()->get();

        foreach ($categorias as $categoria) {
            $categoria->setRelation('comercial', $comercial);
        }

        $medicamentos = Medicamento::all();
        $marcas = Marca::all();

        return view('comercial.produtos/criar',\compact('categorias','medicamentos','marcas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarProdutoRequest $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_produto');

        $usuario_logado = $request->user('comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));

        $dados = $request->validated();

        if($dados['marca'])
        {
            $marca = $this->findBySlug($dados['marca']);
            $dados['marca_id'] = $marca->id;

            unset($dados['marca']);
        }

        if ($dados['tipo_produto'] !== 'medicamento') {
            $dados['tarja'] = null;
            $dados['generico'] = null;
        }

        if ($dados['tipo_produto'] === 'medicamento') {
            $dados['generico'] = $request->boolean('generico');
        }

        $random = Str::random(20);
        $cnpj = preg_replace('/[^0-9]/', '', $comercial->cnpj);
        $imageName = $random. '.' . $request->imagem->extension();
        $imagem_original = $request->imagem->storeAs("/comerciais/{$cnpj}/produto/{$random}", $imageName, config('filesystems.cloud'));
        $dados['imagem'] = $imagem_original;


        $ImageResize = Image::make($request->imagem);

        $image300pxName = "/comerciais/{$cnpj}/produto/{$random}/300px-{$imageName}";
        $image200pxName = "/comerciais/{$cnpj}/produto/{$random}/200px-{$imageName}";
        $image100pxName = "/comerciais/{$cnpj}/produto/{$random}/100px-{$imageName}";

        $ImageResize->resize(300, 300, function($constraint) {
            $constraint->aspectRatio();
        });
        Storage::cloud()->put($image300pxName, (string) $ImageResize->encode());

        $ImageResize->resize(200, 200, function($constraint) {
            $constraint->aspectRatio();
        });
        Storage::cloud()->put($image200pxName, (string) $ImageResize->encode());

        $ImageResize->resize(100, 100, function($constraint) {
            $constraint->aspectRatio();
        });
        Storage::cloud()->put($image100pxName, (string) $ImageResize->encode());


        // $caminho = "/comerciais/{$cnpj}/produto";
        // $caminhoCloud = $request->imagem->storePublicly($caminho, "public");
        // $dados['imagem'] = $caminhoCloud;
        // if($dados['tipo_produto'] === 'medicamento'){
        //     foreach ($request->input('medicamentos') as $key => $value) {
        //         dd($value[0]);
        //         // $produto->medicamentos()->sync($value);
        //     }
        // }
        $medicamentos = [];
        // dd($dados);
        if ($dados['tipo_produto'] === 'medicamento') {

            foreach ($dados['composicao'] as $key => $value) {
                $medicamentos[$key]['medicamento_id'] = $value;
            }
            foreach ($dados['quantidade'] as $key => $value) {
                $medicamentos[$key]['quantidade'] = $value;
            }
            foreach ($dados['unidade'] as $key => $value) {
                $medicamentos[$key]['unidade'] = $value;
            }
        }

        unset($dados['composicao']);
        unset($dados['quantidade']);
        unset($dados['unidade']);
        // dd($medicamentos);
        $produto = DB::transaction(function () use ($comercial, $dados, $request, $usuario_logado, $medicamentos) {
            $produto = $comercial->produtos()->create($dados);

            $produto->medicamentos()->attach($medicamentos);

            $produto->criarLogCadastro(
                $usuario_logado,
                $comercial->id
            );

            return $produto;
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Produto criado com sucesso!'
        ]);

        // return redirect()->route('comercial.produtos.index')->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Produto criado com sucesso!'
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function show(Produto $perfilUsuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Produto $produto)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_produto');

        $comercial = Comercial::find($request->session()->get('comercial'));
        abort_unless($produto->comercial_id === $comercial->id, 403);

        $componentes = null;
        if($produto->tipo_produto == 'medicamento'){
            $componentes = $produto->medicamentos()->where('produto_id', $produto->id)->withPivot('quantidade', 'unidade')->get();
        }

        $categorias = $comercial->categorias()->get();

        $medicamentos = Medicamento::all();
        $marcas = Marca::all();

        $sub_categorias = null;
        if ($produto->sub_categoria_id) {
            $sub_categorias = $comercial->subCategorias()->where('categoria_id', $produto->categoria_id)->get();
        }

        return view('comercial.produtos/editar', \compact('produto','categorias','sub_categorias','medicamentos','componentes','marcas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function update(EditarProdutoRequest $request, Produto $produto)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_produto');
        $usuario_logado = $request->user('comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));
        abort_unless($produto->comercial_id === $comercial->id, 403);

        $dados = $request->validated();

        if($dados['marca'])
        {
            $marca = $this->findBySlug($dados['marca']);
            $dados['marca_id'] = $marca->id;

            unset($dados['marca']);
        }

        if ($dados['tipo_produto'] !== 'medicamento') {
            $dados['tarja'] = null;
            $dados['generico'] = null;
        }

        if ($dados['tipo_produto'] === 'medicamento') {
            $dados['generico'] = $request->boolean('generico');
        }

        if ($request->hasFile('imagem')) {
            // Storage::disk('public')->delete($produto->imagem);
            if($produto->imagem){
                $pasta = Str::of($produto->imagem)->explode('/');
                Storage::cloud()->deleteDirectory("/comerciais/{$pasta[1]}/produto/{$pasta[3]}");
            }
            $random = Str::random(20);
            $cnpj = preg_replace('/[^0-9]/', '', $comercial->cnpj);
            $imageName = $random. '.' . $request->imagem->extension();
            $imagem_original = $request->imagem->storeAs("/comerciais/{$cnpj}/produto/{$random}", $imageName, config('filesystems.cloud'));
            $dados['imagem'] = $imagem_original;


            $ImageResize = Image::make($request->imagem);

            $image300pxName = "/comerciais/{$cnpj}/produto/{$random}/300px-{$imageName}";
            $image200pxName = "/comerciais/{$cnpj}/produto/{$random}/200px-{$imageName}";
            $image100pxName = "/comerciais/{$cnpj}/produto/{$random}/100px-{$imageName}";

            $ImageResize->resize(300, 300, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image300pxName, (string) $ImageResize->encode());

            $ImageResize->resize(200, 200, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image200pxName, (string) $ImageResize->encode());

            $ImageResize->resize(100, 100, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image100pxName, (string) $ImageResize->encode());

            // $cnpj = preg_replace('/[^0-9]/', '', $comercial->cnpj);
            // $caminho = "/comerciais/{$cnpj}/produto";
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "public");
            // $dados['imagem'] = $caminhoCloud;
        }else{
            unset($dados['imagem']);
        }

        $medicamentos = [];
        // dd($dados);
        if ($dados['tipo_produto'] === 'medicamento') {

            foreach ($dados['composicao'] as $key => $value) {
                $medicamentos[$key]['medicamento_id'] = $value;
            }
            foreach ($dados['quantidade'] as $key => $value) {
                $medicamentos[$key]['quantidade'] = $value;
            }
            foreach ($dados['unidade'] as $key => $value) {
                $medicamentos[$key]['unidade'] = $value;
            }
        }

        unset($dados['composicao']);
        unset($dados['quantidade']);
        unset($dados['unidade']);

        $produto = DB::transaction(function () use ($produto, $dados, $request, $comercial, $usuario_logado, $medicamentos) {
            $produto->update($dados);
            DB::table('produto_medicamentos')->where([
                ['produto_id', $produto->id],
            ])->delete();

            $produto->medicamentos()->attach($medicamentos);

            $produto->criarLogEdicao(
                $usuario_logado,
                $comercial->id
            );

            return $produto;
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Produto atualizado com sucesso!'
        ]);
        //return redirect()->route('usuarios.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        // return redirect()->route('comercial.produtos.edit', [$produto])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Produto atualizado com sucesso!'
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PerfilUsuario  $perfilUsuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Produto $produto)
    {
        $this->authorize('habilidade_comercial_sessao', 'excluir_produto');
        $usuario_logado = $request->user('comercial');
        $comercialId = $request->session()->get('comercial');
        abort_unless($produto->comercial_id === $comercialId, 403);

        DB::transaction(function () use ($request, $produto, $comercialId, $usuario_logado){
            $produto->delete();

            $produto->criarLogExclusao(
                $usuario_logado,
                $comercialId
            );

            return $produto;
        });


        return redirect()->route('comercial.produtos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Produto excluído com sucesso!'
        ]);
    }

    public function getsubcategorias(Request $request)
    {
        $categoria = $request->input('categoria');
        $comercial = Comercial::find($request->session()->get('comercial'));
        $sub_categoria = $comercial->subCategorias()->where('categoria_id', $categoria)->get();
        return $sub_categoria->toJson();
    }

    public function editPromocao(Request $request, Produto $produto)
    {
        $this->authorize('habilidade_comercial_sessao', 'promocao_produto');
        $comercialId = $request->session()->get('comercial');
        abort_unless($produto->comercial_id === $comercialId, 403);

        return view('comercial.produtos/promocao', \compact('produto'));
    }

    public function updatePromocao(EditarPromocaoProdutoRequest $request, Produto $produto)
    {
        $this->authorize('habilidade_comercial_sessao', 'promocao_produto');
        $usuario_logado = $request->user('comercial');
        $comercialId = $request->session()->get('comercial');
        abort_unless($produto->comercial_id === $comercialId, 403);

        $dados = $request->validated();
        if (!$request->boolean('promocao')) {
            $dados = [
                'promocao' => false,
                'preco_promocao' => null,
                'promocao_inicial' => null,
                'promocao_final' => null,
            ];
        } else {
            $dados['promocao'] = true;
        }

        DB::transaction(function () use ($request, $usuario_logado, $comercialId, $produto, $dados){
            $produto->update($dados);
            $produto->criarLog(
                $usuario_logado,
                'Promocao de produto',
                $produto->getChanges(),
                $comercialId
            );
            return $produto;
        });

        return redirect()->route('comercial.produtos.promocao', [$produto])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Produto atualizado com sucesso!'
        ]);
    }

    public function editEstoque(Request $request, Produto $produto)
    {
        $this->authorize('habilidade_comercial_sessao', 'estoque_produto');
        $comercialId = $request->session()->get('comercial');
        abort_unless($produto->comercial_id === $comercialId, 403);

        return view('comercial.produtos/estoque', \compact('produto'));
    }

    public function updateEstoque(EditarEstoqueProdutosRequest $request, Produto $produto)
    {
        $this->authorize('habilidade_comercial_sessao', 'estoque_produto');
        $usuario_logado = $request->user('comercial');
        $comercialId = $request->session()->get('comercial');
        abort_unless($produto->comercial_id === $comercialId, 403);

        $dados =$request->validated();

        $dados['estoque_ilimitado'] = $request->boolean('estoque_ilimitado');
        $dados['permitir_comprar_muitos'] = $request->boolean('permitir_comprar_muitos');

        DB::transaction(function () use ($request, $usuario_logado, $comercialId, $produto, $dados){
            $produto->update($dados);
            $produto->criarLog(
                $usuario_logado,
                'Quantidade estoque de produto',
                $produto->getChanges(),
                $comercialId
            );
            return $produto;
        });

        return redirect()->route('comercial.produtos.estoque', [$produto])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Produto atualizado com sucesso!'
        ]);
    }

    function findBySlug($nome) {
        $slug = Str::slug($nome, '-', 'pt_BR');
        return Marca::firstOrCreate([
            'slug' => $slug,
        ], [
            'nome' => $nome,
        ]);
    }

    public function desativar(Request $request,Produto $produto)
    {
        $this->authorize('habilidade_comercial_sessao', 'desativar_produto');
        $usuario_logado = $request->user('comercial');
        $comercialId = $request->session()->get('comercial');
        abort_unless($produto->comercial_id === $comercialId, 403);

        $dados['exibir'] = $request->input('exibir')? 0 : 1;

        DB::transaction(function () use ($usuario_logado, $comercialId, $produto, $dados){
            $produto->update($dados);
            $produto->criarLog(
                $usuario_logado,
                'Produtos ativar/desativar',
                $produto->getChanges(),
                $comercialId
            );
            return $produto;
        });

        return redirect()->route('comercial.produtos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Produto atualizado com sucesso!'
        ]);
    }
}
