<?php

namespace App\Http\Livewire\Instituicao;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;

class ContasPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $contas;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_contas');
        $this->performQuery();
        return view('livewire.instituicao.contas-pesquisa', [
            'contas' => $this->contas,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->contas()->search($this->pesquisa);
        $this->contas = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
