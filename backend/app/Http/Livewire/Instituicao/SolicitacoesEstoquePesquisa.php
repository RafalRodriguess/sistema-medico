<?php

namespace App\Http\Livewire\Instituicao;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;
use App\Instituicao;

class SolicitacoesEstoquePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $solicitacoes;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->performQuery();

        return view('livewire.instituicao.solicitacoes-estoque-pesquisa', [
            'solicitacoes' => $this->solicitacoes
        ]);
    }

    private function performQuery() {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        // Busca as filas totem que são permitidas a partir da instituição
        $this->solicitacoes = $instituicao->solicitacoesEstoque()->search($this->pesquisa)->orderBy('solicitacoes_estoque.id', 'desc')->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
