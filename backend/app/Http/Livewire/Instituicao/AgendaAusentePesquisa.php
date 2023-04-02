<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\Prestador;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class AgendaAusentePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';
    public $prestador;

    public $internacao;

    private $horarios;

    public $instituicao;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request, Prestador $prestador)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        $this->prestador = $prestador;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_agenda_ausente');
        $this->performQuery();

        return view('livewire.instituicao.agenda-ausente-pesquisa', [
            'horarios' => $this->horarios,
            'prestador' => $this->prestador
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->agendasAusente()
            ->search($this->pesquisa)
            ->where('prestador_id', $this->prestador->id)
            ->with('prestadores')
            ->orderBy('data', 'DESC');
        $this->horarios = $query->paginate(15);

    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
