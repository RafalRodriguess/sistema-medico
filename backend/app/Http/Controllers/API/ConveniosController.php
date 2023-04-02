<?php

namespace App\Http\Controllers\API;

use App\Convenio;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConvenioUserCollection;
use Illuminate\Http\Request;

class ConveniosController extends Controller
{
    public function getConvenios(Request $request)
    {
        $convenios = Convenio::get();

        return new ConvenioUserCollection($convenios);
    }
}
