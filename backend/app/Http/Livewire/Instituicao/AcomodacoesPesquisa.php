<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class AcomodacoesPesquisa extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $especialidade = 0;

    public $instituicao;

    private  $acomodacoes;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'especialidade' => ['except' => 0],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    private function performQuery(): void
    {
        $query = Instituicao::find($this->instituicao->id)->acomodacoes();

        $this->acomodacoes = $query->paginate(15);
    }
    
    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_acomodacoes');

        $this->performQuery();
        return view('livewire.instituicao.acomodacoes-pesquisa', [
            'acomodacoes' => $this->acomodacoes,
        ]);
    }
}
