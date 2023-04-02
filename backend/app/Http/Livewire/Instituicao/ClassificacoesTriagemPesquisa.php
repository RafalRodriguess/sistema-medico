<?php

namespace App\Http\Livewire\Instituicao;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;
use App\Instituicao;

class ClassificacoesTriagemPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private  $classificacoes;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];


    public function render()
    {
        $this->performQuery();

        return view('livewire.instituicao.classificacoes-triagem-pesquisa', [
            'classificacoes' => $this->classificacoes
        ]);
    }

    private function performQuery() {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $this->classificacoes = $instituicao->classificacoesTriagem()
                                            ->search($this->pesquisa)
                                            ->whereNull('deleted_at')
                                            ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
