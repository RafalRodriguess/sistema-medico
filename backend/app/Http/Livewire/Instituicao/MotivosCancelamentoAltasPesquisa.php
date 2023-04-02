<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class MotivosCancelamentoAltasPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $instituicao;

    private $motivos_cancelamento_altas;

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

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_altas');

        $this->performQuery();
        
        return view('livewire.instituicao.motivos-cancelamento-altas-pesquisa', [
            'motivos_cancelamento_altas' => $this->motivos_cancelamento_altas,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->motivosCancelamentoAltas()->search($this->pesquisa);

        $this->motivos_cancelamento_altas = $query->paginate(15);
    }
}
