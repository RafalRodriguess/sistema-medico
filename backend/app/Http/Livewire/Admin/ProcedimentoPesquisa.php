<?php

namespace App\Http\Livewire\Admin;

use App\Procedimento;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ProcedimentoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private  $procedimentos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_procedimentos');
        $this->performQuery();

        return view('livewire.admin.procedimento-pesquisa', [
            'procedimentos' => $this->procedimentos
        ]);
    }

    private function performQuery(): void
    {
        $query = Procedimento::query()
                    ->search($this->pesquisa);

        $this->procedimentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
