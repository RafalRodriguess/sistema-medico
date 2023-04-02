<?php

namespace App\Http\Livewire\Comercial;

use App\Comercial;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriaPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $comercial;

    private  $categorias;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->comercial = Comercial::find($request->session()->get('comercial'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "comercial"]);
        $this->authorize('habilidade_comercial_sessao', 'visualizar_categoria');
        
        $this->performQuery();

        return view('livewire.comercial.categoria-pesquisa', [
            'categorias' => $this->categorias,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->comercial->categorias()
                    ->search($this->pesquisa);
        $this->categorias = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
