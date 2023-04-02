<?php

namespace App\Http\Livewire\Instituicao;

use App\Atendimento;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class AtentimentosInstituicaoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $atendimentos;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_atendimentos');
        $this->performQuery();

        return view('livewire.instituicao.atentimentos-instituicao-pesquisa', [
            'atendimentos' => $this->atendimentos
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->atendimentos()->search($this->pesquisa);
        $this->atendimentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
