<?php

namespace App\Http\Livewire\Admin;

use App\Ramo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class RamosPesquisa extends Component
{
    
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private  $ramos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        // $this->authorize('habilidade_admin', 'visualizar_ramo');
        $this->performQuery();

        return view('livewire.admin.ramos-pesquisa', [
            'ramos' => $this->ramos
        ]);
    }

    private function performQuery(): void
    {
        $query = Ramo::query()->search($this->pesquisa);

        $this->ramos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
