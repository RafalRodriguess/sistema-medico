<?php

namespace App\Http\Livewire\Instituicao;

use App\Convenio;
use App\ConvenioPlano;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ConveniosPlanoPesquisa extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $convenioPlanos;

    public $convenio;

    public function mount(Convenio $convenio)
    {
        $this->convenio = $convenio;
    }

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_convenio_planos');
        $this->performQuery();

        return view('livewire.instituicao.convenios-plano-pesquisa', [
            'convenioPlanos' => $this->convenioPlanos
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->convenio->planos()->search($this->pesquisa);
        $this->convenioPlanos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
