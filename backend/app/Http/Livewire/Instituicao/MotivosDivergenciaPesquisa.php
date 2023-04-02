<?php

namespace App\Http\Livewire\Instituicao;

use App\MotivoDivergencia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class MotivosDivergenciaPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $motivos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];


    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->performQuery();

        return view('livewire.instituicao.motivos-divergencia-pesquisa', [
            'motivos' => $this->motivos
        ]);
    }

    private function performQuery() {
        $this->motivos = MotivoDivergencia::query()->search($this->pesquisa)->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
