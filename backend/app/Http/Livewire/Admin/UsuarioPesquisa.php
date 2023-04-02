<?php

namespace App\Http\Livewire\Admin;

use App\Usuario;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class UsuarioPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private  $usuarios;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_usuario');
        $this->performQuery();

        return view('livewire.admin.usuario-pesquisa', [
            'usuarios' => $this->usuarios
        ]);
    }

    private function performQuery(): void
    {
        $query = Usuario::query()
                    ->search($this->pesquisa);
        $this->usuarios = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
