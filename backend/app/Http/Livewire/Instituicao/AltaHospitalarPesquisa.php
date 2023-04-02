<?php

namespace App\Http\Livewire\Instituicao;

use App\AltaHospitalar;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class AltaHospitalarPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $internacao;

    public $instituicao;

    private $altasHospitalar;

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
        //$this->authorize('habilidade_instituicao_sessao', 'visualizar_cirurgias');
        $this->performQuery();

        return view('livewire.instituicao.alta-hospitalar-pesquisa', [
            'altasHospitalar' => $this->altasHospitalar,
        ]);
    }

    private function performQuery(): void
    {
        $query = AltaHospitalar::search($this->pesquisa)->with('internacao');
        $this->altasHospitalar = $query->paginate(15);

    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
