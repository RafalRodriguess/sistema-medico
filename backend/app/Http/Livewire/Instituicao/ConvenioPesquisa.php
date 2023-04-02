<?php

namespace App\Http\Livewire\Instituicao;

use App\Convenio;
use App\Especialidade;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ConvenioPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $convenios;

    public $instituicao;
    
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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_convenio');
        $this->performQuery();

        $prestadores = Especialidade::
        whereHas('prestadoresInstituicao', function($q) {
            $q->where('ativo', 1);
            $q->where('instituicoes_id',$this->instituicao->id);
        })->with([
            'prestadoresInstituicao' => function($q) {
                $q->where('ativo', 1);
                $q->where('instituicoes_id',$this->instituicao->id);
            },
            'prestadoresInstituicao.prestador' => function($q){
                $q->select('id','nome');
            }
        ])->get();

        return view('livewire.instituicao.convenio-pesquisa', [
            'convenios' => $this->convenios,
            'prestadores' => $prestadores
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->convenios()->search($this->pesquisa);
        $this->convenios = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
