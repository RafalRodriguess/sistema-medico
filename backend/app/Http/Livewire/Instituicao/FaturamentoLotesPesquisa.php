<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\FaturamentoLote;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;

class FaturamentoLotesPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $faturamentoLotes;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_lotes');
        $this->performQuery();
        return view('livewire.instituicao.faturamento-lotes-pesquisa', [
            'faturamentoLotes' => $this->faturamentoLotes,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->faturamento_lotes()->with('guias')->search($this->pesquisa)->orderBy('id', 'desc');
        $this->faturamentoLotes = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
