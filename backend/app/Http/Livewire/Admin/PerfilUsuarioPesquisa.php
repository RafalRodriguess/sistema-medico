<?php

namespace App\Http\Livewire\Admin;

use App\PerfilUsuario;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class PerfilUsuarioPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private  $perfis_usuarios;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_perfis_usuarios');
        $this->performQuery();

        return view('livewire.admin.perfil-usuario-pesquisa', [
            'perfis_usuarios' => $this->perfis_usuarios
        ]);
    }

    private function performQuery(): void
    {
        $query = PerfilUsuario::query()
                    ->search($this->pesquisa);
        $this->perfis_usuarios = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
