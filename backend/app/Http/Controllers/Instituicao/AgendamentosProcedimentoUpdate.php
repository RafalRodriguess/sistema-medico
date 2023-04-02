<?php

namespace App\Http\Controllers\Instituicao;

use App\AgendamentoProcedimento;
use App\Agendamentos;
use App\ConveniosProcedimentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agendamentos\EditarAgendamentoProcedimentoUpdateRequest;
use App\Http\Requests\Agendamentos\SalvarAgendamentoProcedimentoUpdateRequest;
use App\Instituicao;
use App\Procedimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\ConverteValor;


class AgendamentosProcedimentoUpdate extends Controller
{
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_agendamento_procedimento_finalizado');

        return view('instituicao.agendamentos.editar_procedimento.index');
    }

    public function tabela(EditarAgendamentoProcedimentoUpdateRequest $request)
    {
        $dados = $request->validated();
        $dados['cod_agendamentos'] = explode(',', $dados['cod_agendamentos']);
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $agendamentos = Agendamentos::getAgendamentosUpdate($dados, $instituicao->id)->get();
        $agendamentos->load('pessoa', 'agendamentoProcedimento', 'agendamentoProcedimento.procedimentoInstituicaoConvenio', 'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao');
        // dd($agendamentos->toArray());
        $convenios = $instituicao->convenios()->get();

        return view('instituicao.agendamentos.editar_procedimento.tabela', \compact('agendamentos', 'convenios'));
    }

    public function getProcedimentos(Request $request)
    {
        if ($request->ajax())
        {
            $instituicao_id = $request->session()->get('instituicao');
            $nome = ($request->input('q')) ? $request->input('q') : '';
            $convenio_id = ($request->input('convenio_id')) ? $request->input('convenio_id') : '';

            $procedimentos = Procedimento::where('descricao', 'like', "%{$nome}%")->whereHas('procedimentoInstituicao', function($q) use($instituicao_id, $convenio_id){
                $q->where('instituicoes_id', $instituicao_id);
                $q->whereHas('conveniosProcedimentos', function($qu) use($convenio_id){
                    $qu->where('convenios_id', $convenio_id);
                });
            })->with(['procedimentoInstituicao'=> function($q) use($instituicao_id, $convenio_id){
                $q->where('instituicoes_id', $instituicao_id);
                $q->whereHas('conveniosProcedimentos', function($qu) use($convenio_id){
                    $qu->where('convenios_id', $convenio_id);
                });
            }])->simplePaginate(100);
            
            $morePages=true;
            if (empty($procedimentos->nextPageUrl())){
                $morePages=false;
            }

            $results = array(
                "results" => $procedimentos->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($pacientes->per_page());
            return response()->json($results);
        }
    }

    public function salvar(SalvarAgendamentoProcedimentoUpdateRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_agendamento_procedimento_finalizado');
        
        DB::transaction(function() use($request){
            $procedimentosRequest = $request->validated()['agendamento'];
            $dados = collect($request->validated()['agendamentos'])
                ->filter(function($agendamento) use($procedimentosRequest){
                    return array_key_exists($agendamento['agendamento_id'], $procedimentosRequest);
                })
                ->map(function($agendamento) use($procedimentosRequest){
                    $data = [];
                    $data['agendamento_id'] = $agendamento['agendamento_id'];
                    foreach ($procedimentosRequest[$agendamento['agendamento_id']] as $key => $value) {
                        $data[] = [
                            'agendamento_procedimento_id' => $value['agendamento_procedimento_id'],
                            'convenio_id' => $value['convenio_id'],
                            'procedimento_id' => $value['procedimento_id'],
                            'valor_atual' => $value['valor_atual'],
                            'valor_repasse' => $value['valor_repasse'],
                            'valor_convenio' => $value['valor_convenio'],
                        ];
                    }

                    return $data;

                });

            // $dados = $request->validated();
            $instituicao_id = $request->session()->get('instituicao');
            $usuario_logado = $request->user('instituicao');
            
            foreach ($dados as $key => $value) {
                $agendamento = Agendamentos::where('id', $value['agendamento_id'])->whereHas('instituicoesAgenda',function($q) use ($instituicao_id) {
                    $q->where(function($q) use ($instituicao_id){
                        $q->whereHas('prestadores',function($q)  use ($instituicao_id){
                            $q->where('instituicoes_id', $instituicao_id);
                        });
                    })
                    ->orWhere(function($q) use ($instituicao_id){
                        $q->whereHas('procedimentos',function($q)  use ($instituicao_id){
                            $q->where('instituicoes_id', $instituicao_id);
                        });
                    });
        
                })->first();

                $procedimentos = $value;
                unset($procedimentos['agendamento_id']);

                foreach ($procedimentos as $key => $proc) {
                    $procedimento = AgendamentoProcedimento::where('id', $proc['agendamento_procedimento_id'])->where('agendamentos_id', $agendamento->id)->first();
                    $procedimentoInstituicaoConvenio = ConveniosProcedimentos::where('convenios_id', $proc['convenio_id'])->where('procedimentos_instituicoes_id', $proc['procedimento_id'])->whereHas('convenios', function($q) use($instituicao_id){
                        $q->where('instituicao_id', $instituicao_id);
                    })->first();

                    $data = [];
                    // dd($procedimento->toArray(), $procedimentoInstituicaoConvenio->toArray());
                    if($procedimento->procedimentos_instituicoes_convenios_id != $procedimentoInstituicaoConvenio->id){
                        $data['procedimentos_instituicoes_convenios_id'] = $procedimentoInstituicaoConvenio->id;
                    }

                    $data['valor_atual'] = ConverteValor::converteDecimal($proc['valor_atual']);
                    $data['valor_repasse'] = ConverteValor::converteDecimal($proc['valor_repasse']);
                    $data['valor_convenio'] = ConverteValor::converteDecimal($proc['valor_convenio']);

                    $procedimento->update($data);
                    $procedimento->criarLogEdicao($usuario_logado, $instituicao_id);
                }

                $agendamento->criarLog($usuario_logado, 'Tabela agendamentos_procedimentos editada', $procedimentos, $instituicao_id );
            }
        });

        return redirect()->route('instituicao.agendamentosProcedimento.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Agendamentos atualizados com sucesso!'
        ]);
    }
}
