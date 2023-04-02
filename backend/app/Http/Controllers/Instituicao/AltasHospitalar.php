<?php

namespace App\Http\Controllers\Instituicao;

use App\AgendamentoAtendimento;
use App\AltaHospitalar;
use App\Http\Controllers\Controller;
use App\Http\Requests\AltaHospitalar\CancelarAltaHospitalarRequest;
use App\Http\Requests\AltaHospitalar\CriarAltaHospitalarRequest;
use App\Instituicao;
use App\Internacao;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AltasHospitalar extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_alta_hospitalar');

        return view('instituicao.altas_hospitalar.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'realizar_alta_hospitalar');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $motivoAlta = $instituicao->motivosAltas()->get();
        $procedimentos = $instituicao->procedimentos()->get();
        $especialidades = $instituicao->especialidades()->get();
        
        return view('instituicao.altas_hospitalar.criar', compact('motivoAlta', 'procedimentos', 'especialidades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarAltaHospitalarRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'realizar_alta_internacao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $dados = $request->validated();
        // :dd($dados['atendimento_id']);

        $internacao = $instituicao->internacoes()->find($dados['internacao_id']);

        DB::transaction(function() use ($request, $instituicao, $dados, $internacao){
            unset($dados['id']);
            $usuario_logado = $request->user('instituicao');
            $alta = AltaHospitalar::create($dados);
            $internacao->update(['alta_hospitalar' => 1]);
            $alta->criarLogCadastro($usuario_logado, $instituicao->id);
            $internacao->criarLogEdicao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.altasHospitalar.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Alta hospitalar criado com sucesso!'
        ]);  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, AltaHospitalar $alta_hospitalar)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cancelar_alta_hospitalar');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $motivoAlta = $instituicao->motivosAltas()->get();
        $procedimentos = $instituicao->procedimentos()->get();
        $especialidades = $instituicao->especialidades()->get();
        $internacao = $instituicao->internacoes()->where('id', $alta_hospitalar->internacao_id)->first();
        $alta_hospitalar->data_internacao = str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($alta_hospitalar->created_at)));
        $alta_hospitalar->data_alta = str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($alta_hospitalar->data_alta)));
        
        // dd($alta_hospitalar->toArray());

        // abort_unless($instituicao->id === $pessoa->instituicao_id, 403);
        // dd($convenios->toArray());

        return view('instituicao.altas_hospitalar.editar', compact('alta_hospitalar', 'motivoAlta', 'procedimentos', 'especialidades', 'internacao'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CancelarAltaHospitalarRequest $request, AltaHospitalar $alta_hospitalar)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cancelar_alta_hospitalar');
        $instituicao = $request->session()->get("instituicao");
        $internacao = Internacao::find($alta_hospitalar->internacao_id);
            
        $dados = $request->Validated();
        $dados['data_cancel_alta'] = date("Y-m-d H:i:s");
        DB::transaction(function() use ($dados, $instituicao, $alta_hospitalar, $request, $internacao){
            $usuario_logado = $request->user('instituicao');            
            
            $alta_hospitalar->update($dados);
            $alta_hospitalar->criarLogEdicao($usuario_logado, $instituicao);
            $internacao->update(['alta_hospitalar' => 0]);
            $internacao->criarLogEdicao($usuario_logado, $instituicao);

        });

        return redirect()->route('instituicao.altasHospitalar.index', [$alta_hospitalar])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Alta hopitalar cancelada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function pesquisaPaciente()
    {
        return view('instituicao.altas_hospitalar.modal_paciente');
    }

    public function getPaciente(Request $request){
        $pacientes = [];
       
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);

        
        if(!empty($request->input('paciente_id'))){
            $pacientes = $instituicao->instituicaoPessoas()
                ->where('tipo', '2')
                ->where('id', $request->input('paciente_id'))
                ->first();
            
            return response()->json($pacientes);
        }else{
            if(!empty($request->input('cpf'))){
                $cpf = $request->input('cpf');
                
                $pacientes = $instituicao->instituicaoPessoas()
                    ->where('tipo', '2')
                    ->where('cpf', $cpf)
                    ->get();
            }

            if(!empty($request->input('nome'))){
                $nome = "%".$request->input('nome')."%";

                $pacientes = $instituicao->instituicaoPessoas()
                    ->where('tipo', '2')
                    ->where('nome', 'like', $nome)
                    ->get();
            }

            // dd( $pacientes->toArray());

            return view('instituicao.altas_hospitalar.tabela_paciente', \compact('pacientes'));
        }
    }

    public function verPaciente(Request $request)
    {
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);

        $paciente = [];
        
        if(!empty($request->input('paciente_id'))){
            $paciente = $instituicao->instituicaoPessoas()
                ->where('tipo', '2')
                ->where('id', $request->input('paciente_id'))
                ->first();
        }
        
        return view('instituicao.altas_hospitalar.modal_ver_paciente', compact('paciente'));
    }

    public function verInternacao(Request $request)
    {
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);

        $internacao = [];
        
        if(!empty($request->input('internacao_id'))){
            $internacao = $instituicao->internacoes()->find($request->input('internacao_id'));
        }

        // dd($internacao->especialidade);
        
        return view('instituicao.altas_hospitalar.modal_ver_internacao', compact('internacao'));
    }

    public function getAtendimento(Request $request)
    {
        $pacientes = [];
       
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);

        
        if(!empty($request->input('paciente_id'))){
            
            // $atendimento = AgendamentoAtendimento::where('pessoa_id', $request->input('paciente_id'))->where('status', 1)->first();
            $internacao = $instituicao->internacoes()
                ->where('paciente_id', $request->input('paciente_id'))
                ->where('alta_internacao', 1)
                ->with([
                    'alta' => function($q){
                        $q->whereRaw('data_cancel_alta IS NULL');
                    
                    },
                    'internacaoLeitos' => function($q){
                        $q->orderBy('created_at', 'DESC')
                        ->with('leito', 'unidade', 'acomodacao')
                        ->first();
                    },
                    'internacaoMedicos' => function($q){
                        $q->orderBy('created_at', 'DESC')
                        ->with('medico')
                        ->first();
                    }
                ])
                ->first();

            if($internacao === null){
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Erro.',
                    'text' => 'Paciente não possui alta de internação ativa!'
                ]);
            }else if(!$internacao->alta->count()){
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Erro.',
                    'text' => 'A interncação (#'.$internacao->id.') deste paciente não possui alta médica!'
                ]);
            
            }else if($internacao->alta->count()){
                // dd($internacao->alta->toArray());
                $dados_internacao = array(
                    'internacao_id' => $internacao->id,
                    'data_internacao' => str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($internacao->created_at))),
                    'ultimo_medico' => $internacao->internacaoMedicos[0]->medico->nome,
                    "alta_internacao" => $internacao->alta[0]->data_alta,
                    "acomodadao" => $internacao->internacaoLeitos[0]->acomodacao->descricao,
                    "unidade" => $internacao->internacaoLeitos[0]->unidade->nome,
                    "leito" => $internacao->internacaoLeitos[0]->leito->descricao,
                );

                return response()->json($dados_internacao);
            }
        }
    }
}
