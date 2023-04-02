<?php

namespace App\Http\Livewire\Admin;

use App\EstoqueEntradas;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class EstoqueEntrada extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private  $estoqueEntradas;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_comercial');
        $this->performQuery();

        return view('livewire.admin.estoque-entrada', [
            'estoqueEntradas' => $this->estoqueEntradas
        ]);
    }

    private function performQuery(): void
    {

        $query = EstoqueEntradas::query()
        ->leftJoin('tipos_documentos as td', 'td.id', '=', 'id_tipo_documento')
        ->leftJoin('estoques as et', 'et.id', '=', 'id_estoque')
        ->leftJoin('pessoas as ps', 'ps.id', '=', 'id_fornecedor')
        ->select('ps.nome as descricao_fornecedor','ps.id as id_fonecedor','et.descricao as descricao_estoques','et.id as id_estoque','td.id as id_tipo_documento','estoque_entradas.id','td.descricao as descricao_tipo_documento','consignado','contabiliza','numero_documento','serie','id_fornecedor','data_emissao','data_hora_entrada')
        ->search($this->pesquisa);

        $this->estoqueEntradas = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
