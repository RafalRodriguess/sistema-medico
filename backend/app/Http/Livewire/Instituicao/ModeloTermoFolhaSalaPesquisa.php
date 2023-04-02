<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ModeloTermoFolhaSalaPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';
    public $instituicao;

    private $modeloTermoFolha;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_termo_folha_sala');
        $this->performQuery();

        return view('livewire.instituicao.modelo-termo-folha-sala-pesquisa', [
            'modeloTermoFolha' => $this->modeloTermoFolha,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->modelosTermoFolhaSala()->search($this->pesquisa);
        $this->modeloTermoFolha = $query->paginate(15);

    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
