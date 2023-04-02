<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\Prestador;
use App\Especialidade;
use App\InstituicoesPrestadores;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class PrestadoresPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $especialidade = 0;

    public $instituicao;

    private  $prestadores;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_prestador');

        $this->performQuery();
        
        return view('livewire.instituicao.prestadores-pesquisa', [
            'prestadores' => $this->prestadores,
            'instituicao_id' => $this->instituicao->id,
            'especialidades' => Especialidade::all()
        ]);
    }

    private function performQuery(): void
    {
        $query = Prestador::searchByInstituicao($this->pesquisa, 
            $this->especialidade, $this->instituicao->id)->with(['especialidade' => function($q) {
                $q->wherePivot('instituicoes_id',$this->instituicao->id);
            }]);

        $this->prestadores = $query->paginate(15);
    } 

    public function updatingEspecialidade(): void
    {
        $this->resetPage();
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
