<?php

namespace App\Http\Livewire\Instituicao;

use App\EntregaExame;
use App\Instituicao;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class EntregasExamePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';
    public $setor_id = null;
    public $local_entrega_id = null;
    public $status = null;
    public $start = null;
    public $end = null;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'setor_id' => ['except' => ''],
        'local_entrega_id' => ['except' => ''],
        'status' => ['except' => ''],
        'start' => ['except' => ''],
        'end' => ['except' => '']
    ];

    private $entregas = [];

    public function mount()
    {
        $this->start = ((new \DateTime($this->start))->format('Y-m-d') . ' 00:00:00') ?? ((new Carbon('first day of this month'))->format('Y-m-d') . ' 00:00:00');
        $this->end = ((new \DateTime($this->end))->format('Y-m-d') .' 23:59:59') ?? ((new Carbon('last day of this month'))->format('Y-m-d') .' 23:59:59');
    }

    public function render()
    {
        $instituicao = Instituicao::find(request()->session()->get('instituicao'));
        $statuses = EntregaExame::statuses;
        $locais_entrega = $instituicao->locaisEntregaExame()->get();
        $setores = $instituicao->setoresExame()->where('ativo', 1)->get();
        $this->performQuery();
        $entregas = $this->entregas;
        return view('livewire.instituicao.entregas-exame-pesquisa', \compact(
            'statuses',
            'locais_entrega',
            'setores',
            'entregas'
        ));
    }

    private function performQuery() {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $pesquisa = $this->pesquisa;
        $query = $instituicao->entregasExame()
            ->join('pessoas', 'pessoas.id', 'pessoa_id')
            ->where('pessoas.nome', 'like', "%$pesquisa%")
            ->orderBy('entregas_exame.updated_at', 'desc');

        if(!empty($this->setor_id)) {
            $query->where('entregas_exame.setor_exame_id', $this->setor_id);
        }
        if(!empty($this->local_entrega_id)) {
            $query->where('entregas_exame.local_entrega_id', $this->local_entrega_id);
        }
        if((int)($this->status ?? 0) > 0) {
            $query->where('entregas_exame.status', $this->status);
        }
        if(!empty($this->start) && !empty($this->end)) {
            $this->mount();
            $query->where('entregas_exame.updated_at', '>=', $this->start);
            $query->where('entregas_exame.updated_at', '<=', $this->end);
        }

        $this->entregas = $query->get();
    }
}
