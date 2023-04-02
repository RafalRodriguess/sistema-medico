<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class FaturamentoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $faturamentos;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_faturamentos');
        $this->performQuery();

        //dd($this->faturamentos);
        return view('livewire.instituicao.faturamento-pesquisa', [
            'faturamentos' => $this->faturamentos,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->faturamentos()->search($this->pesquisa);
        $this->faturamentos = $query->paginate(15);
    }
}
