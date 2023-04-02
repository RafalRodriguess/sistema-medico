<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class MovimentacaoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $conta_origem = 0;
    public $conta_destino = 0;
    public $data_inicio = '';
    public $data_fim = '';

    public $instituicao;

    private  $movimentacoes;

    protected $updatesQueryString = [
        'conta_origem' => ['except' => 0],
        'conta_destino' => ['except' => 0],
        'data_inicio' => ['except' => ''],
        'data_fim' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_movimentacoes');
        
        $this->performQuery();

        $contas = $this->instituicao->contas()->get();
        
        return view('livewire.instituicao.movimentacao-pesquisa', [
            'movimentacoes' => $this->movimentacoes,
            'contas' => $contas,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->movimentacoes()
                    ->search($this->conta_origem, $this->conta_destino, $this->data_inicio, $this->data_fim);
        $this->movimentacoes = $query->paginate(15);
    }

    public function updatingContaOrigem(): void
    {
        $this->resetPage();
    }

    public function updatingContaDestino(): void
    {
        $this->resetPage();
    }

    public function updatingDataInicio(): void
    {
        $this->resetPage();
    }

    public function updatingDataFim(): void
    {
        $this->resetPage();
    }
}
