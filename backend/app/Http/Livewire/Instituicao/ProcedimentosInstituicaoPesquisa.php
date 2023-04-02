<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\Procedimento;
use App\InstituicaoProcedimentos;
use App\GruposProcedimentos;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ProcedimentosInstituicaoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $procedimentos;

    public $grupos;
    public $instituicao;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'procedimento' => ['except' => 0],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        $this->grupos = GruposProcedimentos::
            whereHas('procedimentos_instituicoes',function($q){
                $q->where('instituicoes_id', $this->instituicao->id)
                ->where( function($q){
                    $q->where('tipo','ambos')
                    ->orWhere('tipo','avulso');
                })
                ->whereHas('procedimento',function($q){
                    $q->where('tipo','exame');
                });
        })->get();
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_procedimentos');
        $this->performQuery();

        return view('livewire.instituicao.procedimentos-instituicao-pesquisa', [
            'procedimentos' => $this->procedimentos,
        ]);
    }

    private function performQuery(): void
    {

       $query = InstituicaoProcedimentos::search($this->pesquisa,$this->instituicao )
       ->with(['grupoProcedimento', 'procedimento']);
//    ->get();
//        dd($query);
       $this->procedimentos = $query->paginate(15);
   }

   public function updatingPesquisa(): void
   {
    $this->resetPage();
}

public function updatingEspecialidade(): void
{
    $this->resetPage();
}

}
