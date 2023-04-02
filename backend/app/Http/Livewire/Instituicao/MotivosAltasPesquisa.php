<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class MotivosAltasPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $instituicao;

    private $motivos_altas;

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

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_altas');

        $this->performQuery();
        
        return view('livewire.instituicao.motivos-altas-pesquisa', [
            'motivos_altas' => $this->motivos_altas,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->motivosAltas()->search($this->pesquisa);

        $this->motivos_altas = $query->paginate(15);
    }
}
