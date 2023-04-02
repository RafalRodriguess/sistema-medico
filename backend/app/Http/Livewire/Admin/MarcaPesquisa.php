<?php

namespace App\Http\Livewire\Admin;

use App\Marca;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class MarcaPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private  $marcas;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_marcas');
        $this->performQuery();

        return view('livewire.admin.marca-pesquisa', [
            'marcas' => $this->marcas
        ]);
    }

    private function performQuery(): void
    {
        $query = Marca::query()
                    ->search($this->pesquisa);
        $this->marcas = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
