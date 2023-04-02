<?php

namespace App\Http\Livewire\Admin;

use App\Medicamento;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class MedicamentoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private  $medicamentos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_medicamentos');
        $this->performQuery();

        return view('livewire.admin.medicamento-pesquisa', [
            'medicamentos' => $this->medicamentos
        ]);
    }

    private function performQuery(): void
    {
        $query = Medicamento::query()
                    ->search($this->pesquisa);
        $this->medicamentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
