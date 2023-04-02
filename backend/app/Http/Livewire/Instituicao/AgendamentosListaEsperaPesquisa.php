<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class AgendamentosListaEsperaPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = 0;
    public $exibirTodos = 0;

    public $instituicao;

    private $listaEspera;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => 0],
        'exibirTodos' => ['except' => 0],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_agendamentos_lista_espera');
        $this->performQuery();

        return view('livewire.instituicao.agendamentos-lista-espera-pesquisa', [
            'listaEspera' => $this->listaEspera,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->agendamentosListaEspera()->search($this->pesquisa, $this->exibirTodos);
        $this->listaEspera = $query->paginate(15);

    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
    
    public function updatingExibirTodos(): void
    {
        $this->resetPage();
    }
}
