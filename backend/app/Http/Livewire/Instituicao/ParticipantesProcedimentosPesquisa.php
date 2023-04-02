<?php

namespace App\Http\Livewire\Instituicao;

use App\ProcedimentosConveniosInstituicoesPrestadores;
use App\Procedimento;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ParticipantesProcedimentosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $procedimentos;

    private $prestador;

    public $instituicao;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'prestador' => ['except' => ''],
    ];

    public function mount(Int $instituicao_prestador)
    {

        $this->prestador = $instituicao_prestador;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        // $this->authorize('habilidade_instituicao_sessao', 'visualizar_procedimentos');
        $this->performQuery();

        return view('livewire.instituicao.participantes-procedimentos-pesquisa', [
            'procedimentos' => $this->procedimentos,
            'id_instituicao_prestador' => $this->prestador,
        ]);
    }


    private function performQuery(): void
    {
        $query = Procedimento::searchConveniosInstituicaoPrestadores($this->pesquisa, $this->prestador);
        $this->procedimentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
