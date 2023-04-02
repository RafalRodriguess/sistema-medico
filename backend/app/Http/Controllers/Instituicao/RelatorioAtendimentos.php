<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\ContaPagar;
use App\Exports\RelatorioAtendimentoConvenioExport;
use App\GruposProcedimentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContasPagar\CreateContasPagarGerarFinanceiroRequest;
use App\Http\Requests\ContasPagar\CriarContasPagarRequest;
use App\Http\Requests\RelatorioAtendimento\PesquisaRelatorioAtendimentoRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Excel;

use function Complex\rho;

class RelatorioAtendimentos extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_atendimento');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $convenios = $instituicao->convenios()->get();
        // $usuario_logado = $request->user('instituicao');
        
        // $usuario_prestador = $usuario_logado->prestadorMedico()->get();
        // if(count($usuario_prestador) > 0 && $usuario_prestador[0]->tipo == 2){
        //     $prestador_especialidade_id = $usuario_prestador[0]->id;
        // }
        // $procedimentos = $instituicao->procedimentos()->get();
        $profissionais = $instituicao->medicosRelatorioAtendimentos()->get();
        $status = ['pendente', 'agendado', 'confirmado', 'cancelado', 'finalizado', 'excluir', 'ausente'];
        $grupos = GruposProcedimentos::get();
        $setores = $instituicao->setoresExame()->get();
        $solicitantes = $instituicao->solicitantes()->get();

        return view('instituicao.relatorios.atendimentos.lista', \compact('convenios', 'procedimentos', 'profissionais', 'status', 'grupos', 'setores', 'instituicao', 'solicitantes'));
    }

    public function tabela(PesquisaRelatorioAtendimentoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_atendimento');
        $dados = $request->validated();
        
        $agendamentos = Agendamentos::getRelatorioAgendamento($dados)->orderBy('data')->get();
        
        
        
        if($request->input('tipo_relatorio') == "detalhado"){
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            return view('instituicao.relatorios.atendimentos.tabela', \compact('agendamentos', 'instituicao'));
        }else if($request->input('tipo_relatorio') == "simples"){
            return view('instituicao.relatorios.atendimentos.tabela_simples', \compact('agendamentos'));
        }else if($request->input('tipo_relatorio') == "convenios"){
            return view('instituicao.relatorios.atendimentos.tabela_convenios', \compact('agendamentos'));
        }else if($request->input('tipo_relatorio') == "atendimento"){
            return view('instituicao.relatorios.atendimentos.tabela_atendimento', \compact('agendamentos'));
        }else if($request->input('tipo_relatorio') == "simples_valor"){
            return view('instituicao.relatorios.atendimentos.tabela_simples_valor', \compact('agendamentos'));
        }else if($request->input('tipo_relatorio') == "atendimento_valor"){
            return view('instituicao.relatorios.atendimentos.tabela_atendimento_valor', \compact('agendamentos'));
        }
    }
    
    public function exportExcel(PesquisaRelatorioAtendimentoRequest $request, Excel $excel)
    {
        $this->authorize('habilidade_instituicao_sessao', 'exporta_excel_relatorio_atendimento');
        $dados = $request->validated();
        
        $agendamentos = Agendamentos::getRelatorioAgendamento($dados)->orderBy('data')->get();
        if($request->input('tipo_relatorio') == "detalhado"){
            // return view('instituicao.relatorios.atendimentos.tabela', \compact('agendamentos'));
        }else if($request->input('tipo_relatorio') == "simples"){
            // return view('instituicao.relatorios.atendimentos.tabela_simples', \compact('agendamentos'));
        }else if($request->input('tipo_relatorio') == "convenios"){
            return $this->getExcel(new RelatorioAtendimentoConvenioExport($agendamentos), 'Atendimentos convenio', $excel);
        }else if($request->input('tipo_relatorio') == "atendimento"){
            // return view('instituicao.relatorios.atendimentos.tabela_atendimento', \compact('agendamentos'));
        }
    }

    public function getExcel($export, $titulo, $excel)
    {   
        return $excel->download($export, "{$titulo} ".date('YmdHis').".xlsx");
    }

    public function verFinanceiro(PesquisaRelatorioAtendimentoRequest $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = $request->validated();

        if(count($dados["profissionais"]) == 1){
            $agendamentos = Agendamentos::getRelatorioAgendamento($dados)->get();
            $repasse = [];
            $valor_total = 0;
            
            if(count($agendamentos) > 0){
                $repasse['prestador_id'] = $agendamentos[0]->instituicoesAgenda->prestadores->prestador->id;
                $repasse['prestador_nome'] = $agendamentos[0]->instituicoesAgenda->prestadores->prestador->nome;
                $a = implode(", ", array_column($agendamentos->toArray(), 'id'));
                $repasse['descricao'] = "Repasse do prestador {$agendamentos[0]->instituicoesAgenda->prestadores->prestador->nome} no periodo de ".date("d/m/Y", strtotime($dados['data_inicio']))." até ".date("d/m/Y", strtotime($dados['data_fim']));
                $repasse['obs'] = "Repasse das agendas: {$a}";
                
                foreach($agendamentos as $value){
                    foreach($value->agendamentoProcedimento as $k => $v){
                        if($v->tipo != null){
                            $valor_total += ($v->tipo == 'porcentagem') ? ($v->valor_atual * $v->valor_repasse/100) : $v->valor_repasse;
                        }
                    }
                }

                $repasse['valor_total'] = number_format($valor_total, 2, ",", "");
                
                $tipos = ContaPagar::tipos();
                $contas = $instituicao->contas()->get();
                $planosConta = $instituicao->planosContas()->where('padrao', 1)->get();
                $metodos_pagamento = ContaPagar::formas_pagamento();
                $centroCustos = $instituicao->centrosCustos()->get();
                $cartoes = $instituicao->cartoesCredito()->get();
                // $pessoas = $instituicao->instituicaoPessoas()->get();
                // $prestadores = $instituicao->prestadores()->with('prestador')->get();
                // $fornecedores = $instituicao->fornecedores()->get();
                // dd($repasse);
                return view('instituicao.relatorios.atendimentos.verFinanceiroModal', \compact('repasse', 'contas', 'planosConta', 'metodos_pagamento', 'centroCustos', 'cartoes', 'tipos'));
            }else{
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Erro',
                    'text' => 'Não foram encontrados agendamentos para este filtro'
                ]);
            }
        }else{
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Escolha apenas um profissional para gerar o relatório'
            ]);
        }
    }

    public function salvaFinanceiro(CreateContasPagarGerarFinanceiroRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_contas_pagar');
        $instituicao = instituicao::find($request->session()->get('instituicao'));

        $parcelas = null;
        $tipo_parcelamento = null;
        $num_parcelas = 0;
        $tipo_divisao = null;

        // dd($request->validated());
        $dados_form = $request->validated();
        $contas = $dados_form['conta_caixa'];

        if(str_replace(['.', ","],['', "."], $request->validated()["total"]) == 0){
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'O valor total da conta não pode ser zerado'
            ]);
        }


        if(count($contas) <= 0){
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Escolha ao menos uma conta'
            ]);
        }

        foreach($contas as $k => $v){
            
            $dados = $dados_form;
            $dados['nf_imposto'] = $request->boolean('nf_imposto');

            $dados['num_parcela'] = 1;
            $dados['total'] = str_replace(['.', ","],['', "."], $v['valor']);
            $dados['valor_parcela'] = str_replace(['.', ","],['', "."], $v['valor']);
            // $dados['total'] = str_replace(',','.',$v['valor']);
            $dados['conta_id'] = $v['conta_id'];
            $dados['status'] = (!empty($dados['status'])) ? $dados['status'] : 0 ;
            $dados['data_pago'] = (!empty($dados['status'])) ? $dados['data_pago'] : null ;
            // dd($dados['total'], $request->validated()["total"]);

            $p = $dados['total'] / str_replace(['.', ","], ['', "."], $request->validated()["total"]);

            $dados['valor_pago'] = (!empty($dados['status'])) ? number_format(str_replace(['.', ","],['', "."], $dados['valor_pago']) * $p, 2, ".", "") : null ;
            $num_parcelas = $dados['num_parcelas'];
            $tipo_parcelamento = $dados['tipo_parcelamento'];
            $tipo_divisao = $dados['tipo_divisao'];
            unset($dados['num_parcelas'], $dados['tipo_parcelamento'], $dados['tipo_divisao'], $dados['conta_caixa']);

            if($tipo_parcelamento){
                $parcelas = $dados['parcelas'];
                unset($dados['parcelas']);
            }

            unset($dados['cc']);

            $cc = collect($request->validated()['cc'])
            ->filter(function ($cc) {
                return !is_null($cc['centro_custo_id']);
            })
            ->map(function ($cc){
                $valor = str_replace('.','',$cc['valor']);
                $valor = str_replace(',','.',$valor);
                return [
                    'centro_custo_id' => $cc['centro_custo_id'],
                    'valor' => $valor,
                ];
            });

            $total_cc = 0;

            foreach ($cc as $key => $value) {
                $total_cc += $value['valor'];
            }

            if($total_cc != $dados['total'] && $total_cc > 0){
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Erro',
                    'text' => 'Valor total centro de custo diferente do valor total!'
                ]);
            }

            // dd($dados);
            DB::transaction(function() use($dados, $instituicao, $request, $num_parcelas, $tipo_parcelamento, $parcelas, $tipo_divisao, $cc, $k, $v){
                $usuario_logado = $request->user('instituicao');

                if($dados['status'] == 1){
                    $dados['valor_pago'] = number_format(str_replace(",", ".", $dados['valor_pago']), 2, ".", "");
                    $dados['desc_juros_multa'] = number_format(str_replace(",", ".", $dados['desc_juros_multa']), 2, ".", "");
                }

                if($tipo_parcelamento){
                    $idPai = 0;

                    if($tipo_divisao == 'dividir'){

                        $valor_parcela = $dados['total']/$num_parcelas;

                        $valor_parcela = number_format($valor_parcela, 2, '.', '');

                        for ($i=0; $i < $num_parcelas; $i++) {
                            
                            $dados['num_parcela'] = $i+1;
                            $dados['data_vencimento'] = $parcelas[$i]['data_vencimento'];
                            $dados['valor_parcela'] = $parcelas[$i]['valor'];
                            $dados['valor_parcela'] = str_replace('.','',$dados['valor_parcela']);
                            $dados['valor_parcela'] = str_replace(',','.',$dados['valor_parcela']);

                            $contaPagar = $instituicao->contasPagar()->create($dados);
                            $contaPagar->criarLogCadastro($usuario_logado, $instituicao->id);

                            if($idPai == 0){
                                $idPai = $contaPagar->id;
                                $contaPagar->update(['conta_pai' => $idPai]);
                            }else{
                                $contaPagar->update(['conta_pai' => $idPai]);
                            }

                            $contaPagar->centroCusto()->attach($cc);
                        }
                    }else{
                        for ($i=0; $i < $num_parcelas; $i++) {
                            $dados['num_parcela'] = $i+1;
                            $dados['data_vencimento'] = $parcelas[$i]['data_vencimento'];
                            $dados['valor_parcela'] = $parcelas[$i]['valor'];
                            $dados['valor_parcela'] = str_replace('.','',$dados['valor_parcela']);
                            $dados['valor_parcela'] = str_replace(',','.',$dados['valor_parcela']);

                            $contaPagar = $instituicao->contasPagar()->create($dados);
                            $contaPagar->criarLogCadastro($usuario_logado, $instituicao->id);

                            if($idPai == 0){
                                $idPai = $contaPagar->id;
                                $contaPagar->update(['conta_pai' => $idPai]);
                            }else{
                                $contaPagar->update(['conta_pai' => $idPai]);
                            }

                            $contaPagar->centroCusto()->attach($cc);
                        }
                    }
                }else{
                    $dados['valor_parcela'] = $dados['total'];

                    $contaPagar = $instituicao->contasPagar()->create($dados);

                    $contaPagar->criarLogCadastro($usuario_logado, $instituicao->id);

                    $contaPagar->centroCusto()->attach($cc);
                }

            });
        }

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Conta a pagar cadastrada com sucesso!'
        ]);
    }

    public function getProcedimentos(Request $request){
        if($request->ajax()){
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            
            // dd($request->page);
            $procedimentos = $instituicao->procedimentos()->where('descricao', 'like', "%{$nome}%")->simplePaginate(100);
    
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
}
