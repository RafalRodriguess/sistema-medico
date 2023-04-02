<?php

namespace App\Http\Livewire\Instituicao;

use App\CentroCirurgico;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class CentrosCirurgicosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $cc_id = 0;

    public $instituicao;

    private $centros_cirurgicos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'cc_id' => ['except' => 0],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }
    
    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_centros_cirurgicos');

        $this->performQuery();
                                                            
        return view('livewire.instituicao.centros-cirurgicos-pesquisa', [
            'centros_cirurgicos' => $this->centros_cirurgicos,
            'centros_custos' => $this->instituicao->centrosCustos()->orderBy('codigo', 'asc')->get(),
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->centrosCirurgicos()->with('centroCusto')
            ->where(function($query){
                if($this->cc_id != 0) $query->where('cc_id', $this->cc_id);
            })->search($this->pesquisa);

        $this->centros_cirurgicos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

    public function queryByCentroCusto(int $cc_id)
    {
        $this->cc_id = $cc_id;
    }
}
