<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;

class SolicitacaoCompras extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $instituicao;

    private $solicitacao_compras;
   
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
     //   $this->authorize('habilidade_instituicao_sessao', 'visualizar_cadastro_procedimentos');
        $this->performQuery();
        return view('livewire.instituicao.solicitacao-compras', [
            'solicitacao_compras' => $this->solicitacao_compras
        ]);
    }

    private function performQuery(): void
    {   
        $query = $this->instituicao->solicitacaoCompras()->search($this->pesquisa);
        $this->solicitacao_compras = $query->paginate(15);
   }
} 
