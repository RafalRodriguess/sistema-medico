<?php

namespace App\Http\Livewire\Instituicao;

use App\Conta;
use App\ContaReceber;
use App\Instituicao;
use App\PlanoConta;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ContasReceberPesquisa extends Component
{

    use AuthorizesRequests;
    use WithPagination;
    
    public $formaPagamento = '';
    public $status = 3;
    public $processada = 3;

    public $tipo = '';
    public $search = '';
    public $tipo_id = 0;
    public $data_inicio = '';
    public $data_fim = '';
    public $conta_id = 0;
    public $plano_conta_id = 0;

    public  $instituicao;

    private  $contas_receber;

    protected $updatesQueryString = [
        'formaPagamento' => ['except' => ''],
        'status' => ['except' => 3],
        'processada' => ['except' => 3],
        'tipo' => ['except' => ''],
        'search' => ['except' => ''],
        'tipo_id' => ['except' => 0],
        'data_inicio' => ['except' => ''],
        'data_fim' => ['except' => ''],
        'conta_id' => ['except' => 0],
        'plano_conta_id' => ['except' => 0],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_contas_receber');
        
        $this->performQuery();

        $formaPagamentos = ContaReceber::formas_pagamento();
        $tipos = ContaReceber::tipos();
        // $contas = Conta::get();
        $contas = $this->instituicao->Contas()->get();
        $planosConta = $this->instituicao->planosContas()->getReceitas()->get();

        return view('livewire.instituicao.contas-receber-pesquisa', [
            'contas_receber' => $this->contas_receber,
            'formaPagamentos' => $formaPagamentos,
            'tipos' => $tipos,
            'contas' => $contas,
            'planosConta' => $planosConta,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->contasReceber()
            ->searchFinanceiro($this->formaPagamento, $this->status, $this->tipo, $this->search, $this->tipo_id, $this->data_inicio, $this->data_fim, $this->conta_id, $this->plano_conta_id);
        $this->contas_receber = $query->paginate(15);
    }

    public function updatingFormaPagamento(): void
    {
        $this->resetPage();
    }
    
    public function updatingStatus(): void
    {
        $this->resetPage();
    }
    
    public function updatingProcessada(): void
    {
        $this->resetPage();
    }
    public function updatingTipo(): void
    {
        $this->tipo_id = 0;
        $this->resetPage();
    }
    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingTipoId(): void
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
    public function updatingCaixaId(): void
    {
        $this->resetPage();
    }
    public function updatingPlanoContaCaixaId(): void
    {
        $this->resetPage();
    }
}
