<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\RegraCobranca;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class RegraCobrancaItemPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $grupo = 0;
    public $faturamento = 0;

    public $regra;
    public $instituicao;

    private $itens;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => 0],
        'faturamento' => ['except' => 0],
    ];

    public function mount(Request $request, RegraCobranca $regra)
    {
        $this->regra = $regra;
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_regras_cobranca_itens');
        $this->performQuery();

        $grupos = $this->instituicao->grupoProcedimentos()->get();
        $faturamentos = $this->instituicao->faturamentos()->get();

        return view('livewire.instituicao.regra-cobranca-item-pesquisa', [
            'itens' => $this->itens,
            'grupos' => $grupos,
            'faturamentos' => $faturamentos,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->regra->itens()->search($this->grupo, $this->faturamento);
        $this->itens = $query->paginate(15);
    }

    public function updatingGrupo(): void
    {
        $this->resetPage();
    }
    public function updatingFaturamento(): void
    {
        $this->resetPage();
    }
}
