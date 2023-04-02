<?php

namespace App\Http\Livewire\Admin;

use App\SetorExame;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class SetorExamePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private  $setores;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        // $this->authorize('habilidade_admin', 'visualizar_ramo');
        $this->performQuery();

        return view('livewire.admin.setor-exame-pesquisa', [
            'setores' => $this->setores
        ]);
    }

    private function performQuery(): void
    {
        $query = SetorExame::query()->search($this->pesquisa);

        $this->setores = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
