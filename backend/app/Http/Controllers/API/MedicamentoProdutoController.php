<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicamentoProdutosCollection;
use App\Medicamento;
use Illuminate\Http\Request;

class MedicamentoProdutoController extends Controller
{
    /**
     * MedicamentoController->index()
     *
     * @param Request $request
     * @return MedicamentoProdutosCollection
     */
    public function index(Request $request, Medicamento $medicamento)
    {
        $produtos = $medicamento->produtos()->orderBy('nome')
            // ->where('produtos')
            // ->search($request->query('pesquisa', ''), false)
            ->paginate(30);

        return new MedicamentoProdutosCollection($produtos);
    }
}
