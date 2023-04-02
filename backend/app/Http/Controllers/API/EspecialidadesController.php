<?php

namespace App\Http\Controllers\API;

use App\Especialidade;
use App\Http\Controllers\Controller;
use App\Http\Resources\EspecialidadeResource;
use App\Http\Resources\EspecialidadesCollection;
use App\Instituicao;
use Illuminate\Http\Request;

class EspecialidadesController extends Controller
{
    public function getEspecialidades(Request $request, Instituicao $instituicao)
    {   

        $especialidades = Especialidade::whereHas('prestadoresInstituicao', function($q) use($instituicao) {
            $q->where('instituicoes_id', $instituicao->id);
        })->orderBy('nome', 'ASC')->get();

        return new EspecialidadesCollection($especialidades);
    }
    
    public function especialidade(Request $request, Especialidade $especialidade)
    {   
        return new EspecialidadeResource($especialidade);
    }
}
