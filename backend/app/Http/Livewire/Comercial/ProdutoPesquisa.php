<?php

namespace App\Http\Livewire\Comercial;

use App\Comercial;
use App\Marca;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ProdutoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $categoria = 0;

    public $marca = 0;

    public $comercial;

    private  $produtos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'categoria' => ['except' => 0],
        'marca' => ['except' => 0],
    ];

    public function mount(Request $request)
    {
        $this->comercial = Comercial::find($request->session()->get('comercial'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "comercial"]);
        $this->authorize('habilidade_comercial_sessao', 'visualizar_produto');
        
        $this->performQuery();

        $categorias = $this->comercial->categorias()->get();
        $marcas = Marca::all();

        return view('livewire.comercial.produto-pesquisa', [
            'produtos' => $this->produtos,
            'categorias' => $categorias,
            'marcas' => $marcas,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->comercial->produtos()->with('categorias')->with('marcas')
                    ->search($this->pesquisa, $this->categoria, $this->marca);
        $this->produtos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
    
    public function updatingCategoria(): void
    {
        $this->resetPage();
    }
    
    public function updatingMarca(): void
    {
        $this->resetPage();
    }

}
