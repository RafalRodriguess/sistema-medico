<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\InstituicaoProcedimentos;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;

class ProcedimentosPesquisa extends Component
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
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_cadastro_procedimentos');
        
        $this->performQuery();
        
        return view('livewire.instituicao.procedimentos-pesquisa', ['procedimentos' => $this->procedimentos]);
    }

    private function performQuery(): void
    {

       $query = InstituicaoProcedimentos::search($this->pesquisa,$this->instituicao )
       ->with('procedimento');
       $this->procedimentos = $query->paginate(15);
   }
} 
