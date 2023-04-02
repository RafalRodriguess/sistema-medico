<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class FormasPagamentosPesquisa extends Component
{
    
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $formasPagamento;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_forma_pagamento');
        $this->performQuery();

        //dd($this->formasPagamentos);
        return view('livewire.instituicao.formas-pagamentos-pesquisa', [
            'formasPagamentos' => $this->formasPagamentos,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->formasPagamentos()->search($this->pesquisa);
        $this->formasPagamentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
