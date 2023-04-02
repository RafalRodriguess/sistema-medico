<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\Especialidade;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class EspecialidadePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $instituicao;

    private  $especialidades;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_especialidade');

        $this->performQuery();

        return view('livewire.instituicao.especialidade-pesquisa', [
            'especialidades' => $this->especialidades,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->especialidadesInstituicao()->search($this->pesquisa);
        // $query = Especialidade::search($this->pesquisa);
        $this->especialidades = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
