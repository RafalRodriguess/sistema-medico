<?php

namespace App\Http\Controllers\API;

use App\Comercial;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComercialProdutoResource;
use App\Http\Resources\ComercialProdutosCollection;
use App\Http\Resources\ProdutoDescricaoCollection;
use App\Produto;
use Illuminate\Http\Request;

class ComercialProdutoController extends Controller
{
    /**
     * ComercialProdutoController->index()
     *
     * @param Request $request
     * @return ComercialProdutosCollection
     */
    public function index(Request $request, Comercial $comercial)
    {
        $produtos = $comercial->produtos()->orderBy('nome')
            ->where('exibir', 1)
            ->when($request->query('nome'),function($q) use ($request){
                $q->search($request->query('nome'), 0,0);
            })
            ->when($request->query('promocao')=='true',function($q) use ($request){
                $q->where('promocao',1);
            })
            ->when($request->query('medicamento')=='true',function($q) use ($request){
                $q->where('tipo_produto','medicamento');
            })
            ->when($request->query('composicao'),function($q) use ($request){
                $q->whereHas('medicamentos',function($q) use ($request){
                    $q->search($request->query('composicao'));
                });
            })
            ->when($request->query('marcas'),function($q) use ($request){
                $q->whereIn('marca_id', $request->get('marcas'));
            })
            ->when($request->query('categorias'),function($q) use ($request){
                $q->whereIn('categoria_id', $request->get('categorias'));
            })
            ->when($request->query('subcategorias'),function($q) use ($request){
                $q->whereIn('sub_categoria_id', $request->get('subcategorias'));
            })
            ->paginate(30);

        return new ComercialProdutosCollection($produtos);
    }

    public function descricao(Request $request, Produto $produto)
    {
        return new ComercialProdutoResource($produto);
    }
}
