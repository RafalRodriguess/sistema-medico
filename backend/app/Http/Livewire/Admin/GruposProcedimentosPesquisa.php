<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\GruposProcedimentos;
use Livewire\WithPagination;

class GruposProcedimentosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private  $grupos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_grupos');
        $this->performQuery();

        return view('livewire.admin.grupos-procedimentos-pesquisa', [
            'grupos' => $this->grupos
        ]);
    }

    private function performQuery(): void
    {
       
        $query = GruposProcedimentos::query()
                    ->search($this->pesquisa);
        $this->grupos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
