<?php

namespace App\Http\Controllers\API;

use App\Comercial;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComerciaisCollection;
use Illuminate\Http\Request;

/**
 * ComercialController
 */
class ComercialController extends Controller
{
    /**
     * ComercialController->index()
     *
     * @param Request $request
     * @return ComerciaisCollection
     */
    public function index(Request $request)
    {
        $comerciais = Comercial::query()
            ->where('exibir', 1)
            // ->whereHas('produtos')
            ->when($request->query('search'),function($q) use ($request){
                $q->search($request->query('search'), false);
            })
            ->when($request->query('type'),function($q) use ($request){
                $q->where('categoria',$request->query('type'));
            })
            ->paginate(30);

        return new ComerciaisCollection($comerciais);
    }
}
