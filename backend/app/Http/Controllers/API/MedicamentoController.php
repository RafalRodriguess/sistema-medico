<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicamentosCollection;
use App\Medicamento;
use Illuminate\Http\Request;

class MedicamentoController extends Controller
{
    /**
     * MedicamentoController->index()
     *
     * @param Request $request
     * @return MedicamentosCollection
     */
    public function index(Request $request)
    {
        $medicamentos = Medicamento::query()->orderBy('componente')
            // ->whereHas('produtos')
            ->search($request->query('pesquisa', ''), false)
            ->paginate(30);

        return new MedicamentosCollection($medicamentos);
    }
}
