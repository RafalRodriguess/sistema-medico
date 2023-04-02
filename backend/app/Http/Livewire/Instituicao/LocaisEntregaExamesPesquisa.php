<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithPagination;

class LocaisEntregaExamesPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $locais;

    public $instituicao;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'fila_triagem_id',
        'origens_id'
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->performQuery();

        return view('livewire.instituicao.locais-entrega-exames-pesquisa', [
            'locais' => $this->locais
        ]);
    }

    private function performQuery()
    {
        $instituicao = Instituicao::find(request()->session()->get('instituicao'));
        $pesquisa = $this->pesquisa;
        $this->locais = $instituicao->locaisEntregaExame()
            ->where('descricao', 'like', "%$pesquisa%")
            ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
