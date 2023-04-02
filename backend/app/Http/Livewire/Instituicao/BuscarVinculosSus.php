<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class BuscarVinculosSus extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $procedimentos;
    private $instituicao;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => '']
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->performQuery();

        return view('livewire.instituicao.buscar-vinculos-sus', [
            'procedimentos' => $this->procedimentos
        ]);
    }

    private function performQuery()
    {
        $this->instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $pesquisa = $this->pesquisa;
        $this->procedimentos = $this->instituicao->procedimentos()
            ->where(function($query) use ($pesquisa) {
                $query->where('procedimentos.descricao', 'like', "%{$pesquisa}%")
                    ->orWhere('procedimentos.id', 'like', "%{$pesquisa}%");
            })
            ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
