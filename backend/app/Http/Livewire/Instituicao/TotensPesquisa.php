<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;
use App\Totem;

class TotensPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private  $totens;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];


    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->performQuery();

        return view('livewire.instituicao.totens-pesquisa', [
            'totens' => $this->totens
        ]);
    }

    private function performQuery() {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $this->totens = Totem::query()->search($this->pesquisa)
            ->where('instituicoes_id', $instituicao->id)
            ->whereNull('deleted_at')
            ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
