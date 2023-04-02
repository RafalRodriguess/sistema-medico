<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\Especialidade;
use App\Agendamentos as Agendamento;
use App\InstituicoesAgenda;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use carbon\carbon;
use App\Libraries\PagarMe;

class AgendamentosToolbarInfo extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $agenda;
    private $agendamentos_geral;


    public $saldo;
    public $instituicao;
    public $saldo_a_receber;
    public $qtdAgendamentos;
    
    public $data = '';


    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'data' => ['except' => ''],
    ];

    public function mount(Request $request)
    {

        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));

        $pagarMe = new PagarMe();
        if($this->instituicao->id_recebedor){
            $saldo = $pagarMe->saldoRecebedor($this->instituicao->id_recebedor);
            $this->saldo = $saldo->available->amount;
            $this->saldo_a_receber = $saldo->waiting_funds->amount;
        }else{
            $this->saldo = 0;
            $this->saldo_a_receber = 0;
        }

        if($request->data){
            $this->data = \Carbon\Carbon::createFromFormat('d/m/Y', $request->data)->format('d/m/Y');
        }else{
            $this->data = \Carbon\Carbon::now()->format('d/m/Y');
        }
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_agendamentos');

        $this->performQuery();
        $this->emit('reset_icheck');
        return view('livewire.instituicao.agendamentos-toolbar-info');
    }

    private function performQuery(): void
    {

        // dd($this->data);
        $this->qtdAgendamentos = Agendamento::
        whereHas('instituicoesAgenda',function($q){
            $q->where(function($q){
                $q->whereHas('prestadores',function($q){
                    $q->where('instituicoes_id',$this->instituicao->id);
                });
            })
            ->orWhere(function($q){
                $q->whereHas('procedimentos',function($q){
                    $q->where('instituicoes_id',$this->instituicao->id);
                });
            });
        })
        ->whereNotNull('instituicoes_agenda_id')
        // ->whereHas('usuario')
        ->selectRaw('count(*) as total')
            // ->selectRaw("count(case when status = 'pendente' then 1 end) as pendente")
            ->selectRaw("count(case when status = 'confirmado' then 1 end) as confirmado")
            // ->selectRaw("count(case when status = 'cancelado' then 1 end) as cancelado")
            ->selectRaw("count(case when status = 'agendado' then 1 end) as agendado")
            ->selectRaw("count(case when status = 'em_atendimento' then 1 end) as em_atendimento")
            ->selectRaw("count(case when status = 'finalizado' then 1 end) as finalizado")
            // ->selectRaw("count(case when status = 'ausente' then 1 end) as ausente")
            ->whereDate('data',\Carbon\Carbon::createFromFormat('d/m/Y', $this->data)->format('Y-m-d'))
            ->get()->first();

    }

    public function updatingData($value): void
    {
        $this->data = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('d/m/Y');
        
        $this->resetPage();
    }

    public function refresh($value) : void {
        $this->resetPage();
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
