<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\FaturamentoLote;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;

class FaturamentoLotesGuiasPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $dados;

    private  $prestadores;
    private  $convenios;
    public  $faturamento;
    private  $guias;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request, FaturamentoLote $faturamento)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        // dd($faturamento->toArray());
        $this->faturamento = $faturamento;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_lotes');
        $this->performQuery();
        return view('livewire.instituicao.faturamento-lotes-guias-pesquisa', [
            'prestadores' => $this->prestadores,
            'convenios' => $this->convenios,
            'guias' => $this->guias,
        ]);
    }

    private function performQuery(): void
    {

        
        //PEGANDO AS GUIAS
        $this->guias = $this->faturamento->guias()->with([
                                                'agendamento_paciente',
                                                'agendamento_paciente.pessoa',
                                                'agendamento_paciente.instituicoesAgenda.prestadores.prestador',
                                            ])
                                         ->get();

                                        //  dd($this->guias->toArray());

        //PEGANDO PRESTADORES INSTITUIÇÃO
        $this->prestadores = $this->instituicao->prestadores()
                                               ->with('prestador')
                                               ->get();


        //PEGANDO CONVÊNIOS INSTITUIÇÃO
        $this->convenios = $this->instituicao->convenios()
                                             ->get();

        // dd($this->guias->toArray());
        // exit;
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
