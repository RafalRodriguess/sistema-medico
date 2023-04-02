<?php

namespace App\Http\Livewire\Comercial;

use App\Comercial;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ComercialUsuarioPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $comercial;

    private  $comercialusuario;

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
        $this->authorize('habilidade_comercial_sessao', 'visualizar_usuario');
        
        $this->performQuery();

        return view('livewire.comercial.comercial-usuario-pesquisa', [
            'comercialusuario' => $this->comercialusuario,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->comercial->comercialUsuarios()
                    ->search($this->pesquisa);
        $this->comercialusuario = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
