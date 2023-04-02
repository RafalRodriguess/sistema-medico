<?php

namespace App\Http\Livewire\Instituicao;

use App\Conta;
use App\ContaPagar;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ContasPagarPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $formaPagamento = '';
    public $status = 3;

    public $tipo = '';
    public $search = '';
    public $tipo_id = 0;
    public $menor = '';
    public $maior = '';
    public $data_inicio = '';
    public $data_fim = '';
    public $conta_id = 0;
    public $plano_conta_id = 0;
    public $valor_total_nf = '';
    public $nota_fiscal = '';
    public $contas;
    public $contasIds = [];

    public  $usuario;
    public  $instituicao;

    private  $contas_pagar;

    protected $updatesQueryString = [
        'formaPagamento' => ['except' => ''],
        'status' => ['except' => 3],
        'processada' => ['except' => 3],
    ];

    public function mount(Request $request)
    {
        // $this->venda = $venda;
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        $this->usuario = $request->user('instituicao');
        $this->contas = $this->usuario->contasInstituicao()->get();
        foreach ($this->contas as $key => $value) {
            $this->contasIds[] =  $value->id;
        }
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_contas_pagar');
        
        $this->performQuery();

        $formaPagamentos = ContaPagar::formas_pagamento();
        $tipos = ContaPagar::tipos_all();
        
        // $contas = $this->instituicao->contas()->get();
        $planosConta = $this->instituicao->planosContas()->where('padrao', 1)->get();

        // $total_pagos = $this->instituicao->contasPagar()->where('status', 1)->sum('valor_pago');
        // $total_parcelas = $this->instituicao->contasPagar()->sum('valor_parcela');

        return view('livewire.instituicao.contas-pagar-pesquisa', [
            'contas_pagar' => $this->contas_pagar,
            'formaPagamentos' => $formaPagamentos,
            'tipos' => $tipos,
            'contas' => $this->contas,
            'planosConta' => $planosConta,
        ]);
    }

    private function performQuery(): void
    {

        $query = $this->instituicao->contasPagar()
                ->searchFinanceiro($this->formaPagamento, $this->status, $this->tipo, $this->search, $this->tipo_id, $this->data_inicio, $this->data_fim, $this->conta_id, $this->plano_conta_id, $this->menor, $this->maior, $this->valor_total_nf, $this->nota_fiscal, $this->contasIds);

        $this->contas_pagar = $query->paginate(15);
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
}
