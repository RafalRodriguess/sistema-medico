<?php

namespace App\Http\Livewire\Comercial;

use App\Comercial;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class SubCategoriaPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $categoria = 0;

    public $comercial;

    private  $sub_categorias;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'categoria' => ['except' => 0],
    ];

    public function mount(Request $request)
    {
        $this->comercial = Comercial::find($request->session()->get('comercial'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "comercial"]);
        $this->authorize('habilidade_comercial_sessao', 'visualizar_sub_categoria');
        
        $this->performQuery();

        $categorias = $this->comercial->categorias()->get();

        return view('livewire.comercial.sub-categoria-pesquisa', [
            'sub_categorias' => $this->sub_categorias,
            'categorias' => $categorias,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->comercial->subCategorias()->with('categoria')
                    ->search($this->pesquisa, $this->categoria);
        $this->sub_categorias = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
    
    public function updatingCategoria(): void
    {
        $this->resetPage();
    }

}
