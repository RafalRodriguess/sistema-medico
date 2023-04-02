<?php

namespace App\Http\Livewire\Admin;

use App\Especializacao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class EspecializacoesPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    private $especializacoes;
    public $pesquisa = '';

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_especializacao');

        $this->performQuery();

        return view('livewire.admin.especializacao-pesquisa', [
            'especializacoes' => $this->especializacoes,
        ]);
    }

    private function performQuery(): void
    {
        $query = Especializacao::search($this->pesquisa);
        $this->especializacoes = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
