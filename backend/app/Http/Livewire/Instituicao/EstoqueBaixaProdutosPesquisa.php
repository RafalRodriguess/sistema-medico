<?php

namespace App\Http\Livewire\Instituicao;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Instituicao;

class EstoqueBaixaProdutosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';
    public $instituicao;
    private  $estoqueBaixas;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoque_entrada');
        $this->performQuery();

        return view('livewire.instituicao.estoque-baixa-pesquisa', [
            'estoqueBaixas' => $this->estoqueBaixas
        ]);
    }

    private function performQuery(): void
    {

        $query = $this->instituicao->estoqueBaixa()->search($this->pesquisa);

        $this->estoqueBaixas = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
