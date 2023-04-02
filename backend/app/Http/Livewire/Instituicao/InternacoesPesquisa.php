<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\Prestador;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class InternacoesPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $medico_id = 0;

    public $paciente_id = 0;

    public $especialidade_id = 0;

    public $acomodacao_id = 0;
    public $unidade_id = 0;
    public $leito_id = 0;

    public $data = null;
    
    public $pesquisa = '';
    
    public $previsao_alta = 0;

    public $tipo_internacao = 0;

    public $convenio_id = 0;

    public $instituicao;

    private $internacoes;

    public $profissional = null;

    public $events = '';

    protected $updatesQueryString = [
        'pesquisa' => [ 'except' => '', ],
        'paciente_id' => [ 'except' => 0 ] ,
        'medico_id' => [ 'except' => 0],
        'data' => ['except' => ""],
        'especialidade_id' => ['except' => 0],
        'previsao_alta' => ['except' => 0],
        'tipo_internacao' => ['except' => 0],
        'convenio_id' => ['execept' => 0],
        'acomodacao_id' => ['execept' => 0],
        'unidade_id' => ['execept' => 0],
        'leito_id' => ['execept' => 0],
    ];

    protected $listeners = [
        'data' => 'updatingData',
        'refresh' => 'refresh',
        'pacienteId' => 'updatingPacienteId',
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        // $this->data = date("Y-m-d");
        $this->events = $this->getEvents();
        $this->profissional = $request->user('instituicao')->prestador()->first();
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_pre_internacao');
        $this->performQuery();

        $pacientes =  $this->instituicao->instituicaoPessoas()->where('tipo', '2')->get();
        // $medicos =  $this->instituicao->prestadores()->with('prestador')->where('tipo', '2')->get();
        
        $medicos = Prestador::whereHas('prestadoresInstituicoes', function($q) {
            $q->where('ativo', 1);
            $q->where('instituicoes_id',$this->instituicao->id);
        })->get();

        $convenios = $this->instituicao->convenios()->get();

        $acomodacoes = $this->instituicao->acomodacoes()->get();        
        $unidades = $this->instituicao->unidadesInternacoes()->get();
        $especialidades = $this->instituicao->especialidadesInstituicao()->get();

        $this->events = $this->getEvents();

        return view('livewire.instituicao.internacoes-pesquisa', [
            'internacoes' => $this->internacoes,
            'pacientes' =>  $pacientes,
            'medicos' => $medicos,
            'convenios' => $convenios,
            'especialidades' => $especialidades,
            'acomodacoes' => $acomodacoes,
            'unidades' => $unidades
        ]);
    }

    private function performQuery(): void
    {
        // dd($this->paciente_id);
        
        $query = $this->instituicao->internacoes()->search(
            $this->pesquisa,
            $this->paciente_id, 
            $this->medico_id, 
            $this->data, 
            $this->previsao_alta, 
            $this->tipo_internacao, 
            $this->convenio_id,
            $this->acomodacao_id,
            $this->unidade_id,
            $this->leito_id            
        )->with('alta')->orderBy('id','DESC');
        $this->internacoes = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
    
    public function updatingMedicoId(): void
    {
        $this->resetPage();
    }

    public function updatingPacienteId($id): void
    {
        $this->paciente_id = $id;
        $this->resetPage();
    }

    public function updatingTipoInternacao(): void
    {
        $this->resetPage();
    }

    public function updatingConvenioId(): void
    {
        $this->resetPage();
    }

    public function updatingAcomodacaoId(): void
    {
        $this->resetPage();
    }
    
    public function updatingUnidadeId(): void
    {
        $this->resetPage();
    }

    public function updatingLeitoId(): void
    {
        $this->resetPage();
    }

    public function updatingData($data): void
    {
        $this->data = $data;
        $this->events = $this->getEvents();
        $this->resetPage();
    }

    public function getEvents(){
        if(!empty($this->data)){
            $intervalo = [
                date("Y-m-01 00:00:00", strtotime($this->data)),
                date("Y-m-t 23:59:59", strtotime($this->data))
            ];
        }else{
            $intervalo = [
                date("Y-m-01 00:00:00"),
                date("Y-m-t 23:59:59")
            ];
        }

        $collum = $this->previsao_alta ? 'previsao_alta' : 'created_at';

        $query = $this->instituicao->internacoes()->selectRaw("DISTINCT DATE_FORMAT({$collum}, '%Y-%m-%d') as data");
        if($this->paciente_id != 0){
            $paciente_id = $this->paciente_id;
            $query->whereHas('paciente', function($q) use($paciente_id){
                $q->where('id', $paciente_id);
            });
        }

        if($this->medico_id != 0){
            $medico_id = $this->medico_id;
            $query->whereHas('medico', function($q) use($medico_id){
                $q->where('id', $medico_id);
            });
        }

        if($this->tipo_internacao != 0){
           $query->where('tipo_internacao', $this->tipo_internacao);
        }

        if(!empty($this->data)){
            
            if($this->previsao_alta == 0){
                $query->whereBetween('created_at', $intervalo);
            }else if($this->previsao_alta == 1){
                $query->whereBetween('previsao_alta', $intervalo);
            }
        }

        if($this->convenio_id != 0){
            $convenio_id = $this->convenio_id;
            $query->whereHas('procedimentos', function($q) use($convenio_id){
                $q->where('convenio_id', $convenio_id);
            });
        }
        
        return $query->get()->toJson();
    }
    
    public function refresh(): void
    {
        $this->resetPage();
    }
}
