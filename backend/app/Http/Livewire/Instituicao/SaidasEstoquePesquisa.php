<?php

namespace App\Http\Livewire\Instituicao;

use App\Http\Controllers\Instituicao\SaidasEstoque;
use App\Instituicao;
use App\SaidaEstoque;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class SaidasEstoquePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $instituicao;

    private  $saidas;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        $this->performQuery();
        return view('livewire.instituicao.saidas-estoque-pesquisa', [
            'saidas' => $this->saidas
        ]);
    }

    private function performQuery() {
        $this->saidas = $this->instituicao->saidasEstoque()
            ->with([
                'estoque',
                'paciente',
                'agendamento',
                'agendamento.pessoa',
                'centroDeCusto',
                'produtosBaixa.produtos'
            ])
            ->orderBy('id', 'desc')
            ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
