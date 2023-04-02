<?php

namespace App\Http\Livewire\Admin;

use App\EstoqueEntradaProdutos;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class EstoqueEntradaProduto extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';


    protected  $estoqueEntradaProduto;
    public  $id_entrada;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_comercial');
        $this->performQuery();

        return view('livewire.admin.estoque-entrada-produto', [
            'estoqueEntradaProduto' => $this->estoqueEntradaProduto
        ]);
    }

    private function performQuery()
    {
        $entrada = (explode('/',$_SERVER['PHP_SELF']));

            $query = EstoqueEntradaProdutos::where('id_entrada',$entrada[4]);

            $queryGet = $query->get();

            if($queryGet->isEmpty()){
                $this->estoqueEntradaProduto = $query->paginate(0);
                $this->id_entrada = $entrada[4];
            }else{
                $this->estoqueEntradaProduto = $query->paginate(500);
            }
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
