<?php

namespace App\Http\Livewire\Admin;

use App\Administrador;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class AdministradoresPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private $administradores;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_administrador');
        $this->performQuery();

        return view('livewire.admin.administradores-pesquisa', [
            'administradores' => $this->administradores
        ]);
    }

    private function performQuery(): void
    {
        $query = Administrador::query()
                    ->search($this->pesquisa);
        $this->administradores = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
    
}
