<?php

namespace App\Repositories;

use App\VinculoBrasindice;
use Illuminate\Http\Request;

class VinculoBrasindiceRepository
{
    protected $vinculo;

    public function __construct(VinculoBrasindice $vinculo)
    {
        $this->vinculo = $vinculo;
    }

    public function get(Request $request)
    {
        $filters = $request->all();
        
        $query = $this->vinculo->where('instituicao_id', $request->session()->get("instituicao"));

        if (isset($filters['edicao'])) {
            $query->where('edicao', $filters['edicao']);
        }
        if (isset($filters['tiss'])) {
            $query->where('tiss', $filters['tiss']);
        }
        if (isset($filters['ean'])) {
            $query->where('ean', $filters['ean']);
        }
        if (isset($filters['tuss'])) {
            $query->where('tuss', $filters['tuss']);
        }
        if (isset($filters['tipo_id'])) {
            $query->where('tipo_id', $filters['tipo_id']);
        }
        if (isset($filters['laboratorio'])) {
            $query->where('laboratorio', 'like', '%' . $filters['laboratorio'] . '%');
        }
        if (isset($filters['medicamento'])) {
            $query->where('medicamento', 'like', '%' . $filters['medicamento'] . '%');
        }

        $query->orderBy('edicao', 'DESC');
        $query->orderBy('tiss', 'ASC');

        return $query->paginate(15)->appends($filters);
    }
}
