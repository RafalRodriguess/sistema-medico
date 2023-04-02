<?php

namespace App\Http\Livewire\Instituicao;

use App\Especialidade;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class EscalasMedicasPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $especialidade_id = 0;

    public $origem_id = 0;

    public $instituicao;

    private $escalas_medicas;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'especialidade_id' => ['except' => 0],
        'origem_id' => ['except' => 0],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }
    
    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_escalas_medicas');

        $this->performQuery();
                                                           
        return view('livewire.instituicao.escalas-medicas-pesquisa', [
            'escalas_medicas' => $this->escalas_medicas,
            'especialidades' => Especialidade::all(),
            'origens' => $this->instituicao->origens()->get()
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->escalasMedicas()
            ->with('especialidade')->with('escalaPrestadores')->with('origem')->with('instituicao')
            ->searchByEspecialidade($this->especialidade_id)
            ->searchByOrigem($this->origem_id)
            ->searchByRegra($this->pesquisa);

        $this->escalas_medicas = $query->paginate(15);
    }

    public function queryByEspecialidade(int $especialidade_id)
    {
        $this->especialidade_id = $especialidade_id;
    }

    public function queryByorigem(int $origem_id)
    {
        $this->origem_id = $origem_id;
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
