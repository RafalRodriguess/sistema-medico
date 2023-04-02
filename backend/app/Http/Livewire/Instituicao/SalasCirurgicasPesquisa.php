<?php

namespace App\Http\Livewire\Instituicao;

use App\CentroCirurgico;
use App\Http\Livewire\Comercial\PedidosPesquisa;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class SalasCirurgicasPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $tipo_id = 0;

    public $instituicao;

    public $centro_cirurgico;

    private $salas_cirurgicas;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'tipo_id' => ['except' => 0],
    ];

    public function mount(Request $request, CentroCirurgico $centro_cirurgico)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));

        $this->centro_cirurgico = $centro_cirurgico;

    }
    
    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_salas_cirurgicas');

        $this->performQuery();
        return view('livewire.instituicao.salas-cirurgicas-pesquisa', [
            'salas_cirurgicas' => $this->salas_cirurgicas
        ]);
    }

    private function performQuery(): void
    {

        $query = $this->centro_cirurgico->salasCirurgicas()
            ->where(function($query){
                if($this->tipo_id != 0) $query->where('tipo', $this->tipo_id);
            })->search($this->pesquisa);

        $this->salas_cirurgicas = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

    public function queryByTipo(int $tipo_id): void
    {
        $this->tipo_id = $tipo_id;
    }
}
