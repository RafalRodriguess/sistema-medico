<?php

namespace App\Http\Livewire\Admin;

use App\Comercial;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class UsuarioComercialPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $comercial;

    private  $comercialusuarios;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Comercial $comercial)
    {
        $this->comercial = $comercial;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_usuario_comercial');
        $this->performQuery();

        return view('livewire.admin.usuario-comercial-pesquisa', [
            'comercialusuarios' => $this->comercialusuarios,
            'comercial' => $this->comercial
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->comercial->comercialUsuarios()
                    ->search($this->pesquisa);
        $this->comercialusuarios = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
