<?php

namespace App\Http\Livewire\Instituicao;

use App\ProcessoTriagem;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;
use App\Totem;

class ProcessosTriagemPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $processos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];


    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->performQuery();

        return view('livewire.instituicao.processos-triagem-pesquisa', [
            'processos' => $this->processos
        ]);
    }

    private function performQuery() {
        $this->processos = ProcessoTriagem::query()->search($this->pesquisa)
                                            ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
