<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class PaineisTotemBusca extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $paineis;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->performQuery();

        return view('livewire.instituicao.paineis-totem-busca', [
            'paineis' => $this->paineis
        ]);
    }

    private function performQuery() {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $this->paineis = $instituicao->paineisTotem()
                                    ->search($this->pesquisa)
                                    ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
