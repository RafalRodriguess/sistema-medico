<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class TiposChamadaTotemBusca extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $tipos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->performQuery();

        return view('livewire.instituicao.tipos-chamada-totem-busca', [
            'tipos' => $this->tipos
        ]);
    }

    private function performQuery() {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $this->tipos = $instituicao->tiposChamadaTotem()
                                    ->search($this->pesquisa)
                                    ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
