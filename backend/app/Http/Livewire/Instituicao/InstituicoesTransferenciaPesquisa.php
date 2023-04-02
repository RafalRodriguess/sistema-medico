<?php

namespace App\Http\Livewire\Instituicao;

use App\Especialidade;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class InstituicoesTransferenciaPesquisa extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $instituicao;

    private $instituicoes_transferencia;

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

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_instituicoes_transferencia');

        $this->performQuery();
                                                           
        return view('livewire.instituicao.instituicoes-transferencia-pesquisa', [
            'instituicoes_transferencia' => $this->instituicoes_transferencia,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->instituicoesTransferencia()->searchByDescricao($this->pesquisa);

        $this->instituicoes_transferencia = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
