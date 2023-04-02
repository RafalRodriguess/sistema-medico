<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\PrestadorSolicitante;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class PrestadoresSolicitantesPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $especialidade = 0;

    public $instituicao;

    private  $solicitantes;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'especialidade' => ['except' => 0],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_solicitantes');

        $this->performQuery();
        
        return view('livewire.instituicao.prestadores-solicitantes-pesquisa', [
            'solicitantes' => $this->solicitantes,
            'instituicao_id' => $this->instituicao->id,
        ]);
    }

    private function performQuery(): void
    {
        $query = PrestadorSolicitante::search($this->pesquisa);

        $this->solicitantes = $query->paginate(15);
    } 

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
