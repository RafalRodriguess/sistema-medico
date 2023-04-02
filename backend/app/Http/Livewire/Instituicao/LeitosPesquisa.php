<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\UnidadeInternacao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class LeitosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $instituicao;

    private $leitos;

    private $unidade_internacao;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request, UnidadeInternacao $unidade_internacao)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));

        $this->unidade_internacao = $unidade_internacao;
    }
    
    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_leitos');

        $this->performQuery();

        return view('livewire.instituicao.leitos-pesquisa', [
            'leitos' => $this->leitos,
            'unidade_internacao' => $this->unidade_internacao
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->unidade_internacao->leitos();

        $this->leitos = $query->paginate(15);
    }
}
