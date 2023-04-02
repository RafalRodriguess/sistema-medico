<?php

namespace App\Exports;

use App\ContaPagar;
use App\Instituicao;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FluxoCaixaExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data_inicio;
    protected $data_fim;
    protected $contas;
    
    public function __construct(array $dados)
    {
        $this->data_inicio = $dados['data_inicio'];
        $this->data_fim = $dados['data_fim'];
        $this->contas = explode(",", $dados['contas']);
    }

    public function headings(): array
    {
        return [
            'Tipo',
            'Data pagaomento',
            'Fornecedor / Paciente',
            'Convênio',
            'Procedimento',
            'Conta Caixa',
            'Forma de pagamento',
            'Nº Parcelas',
            'Valor Pago',
        ];
    }

    public function collection()
    {
        $instituicao = Instituicao::find(request()->session()->get('instituicao'));
        
        $contasReceber = $instituicao->contasReceber()
            ->where('cancelar_parcela', "0")
            ->whereBetween('data_pago', [$this->data_inicio, $this->data_fim])
            ->whereIn('conta_id', $this->contas)
            ->with(['contaCaixa', 'paciente', 'convenio', 'agendamentos' => function($q){
                $q->with(['agendamentoProcedimento' => function($q){
                    $q->with(['procedimentoInstituicaoConvenioTrashed' => function($q){
                        $q->with(['convenios', 'procedimentoInstituicao' => function($q){
                            $q->with(['procedimento'])->withTrashed();
                        }]);
                    }]);
                }]);
            }])            
        ->get();

        $contasPagar = $instituicao->ContasPagar()
            ->whereBetween('data_pago', [$this->data_inicio, $this->data_fim])
            ->whereIn('conta_id', $this->contas)
            ->with(['contaCaixa', 'paciente', 'prestador', 'fornecedor' => function ($q) {
                $q->withTrashed();
            },])
        ->get();
        
        $dadosReceber = collect($contasReceber)
            ->map(function ($item) {
                $a["tipo"] = "entrada";
                $a["data_pagamento"] = date("d/m/Y", strtotime($item->data_pago));
                
                if ($item->tipo == 'paciente'){
                    if($item->paciente){
                        $a["Fornecedor_Paciente"] = "Paciente: {$item->paciente->nome}";
                    }elseif(!$item->pessoa_id){
                        $a["Fornecedor_Paciente"] = "Paciente avulso!";
                    }else{
                        $a["Fornecedor_Paciente"] = "";
                    }
                }elseif($item->tipo == 'convenio'){
                    $a["Fornecedor_Paciente"] = "Convênio: {$item->convenio->nome}";
                }elseif($item->tipo == "movimentacao"){
                    $a["Fornecedor_Paciente"] = "Movimentação: {$item->descricao}";
                }else{
                    $a["Fornecedor_Paciente"] = "";
                }
                
                $texto_convenios = array();
                $texto_procedimentos = array();

                if($item->agendamentos){
                    foreach ($item->agendamentos->agendamentoProcedimento as $procedimentos) {
                        $part = ($item->agendamentos->valor_total > 0) ? $procedimentos->valor_atual * ($item->valor_pago/$item->agendamentos->valor_total) : 0;

                        $convenio = $procedimentos->procedimentoInstituicaoConvenioTrashed->convenios->nome;

                        $convenios[$convenio] = (!empty($convenios[$convenio])) ? $convenios[$convenio] + $part : $part;

                        $proc = $procedimentos->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->descricao;
                        
                        $itens_procedimentos[$proc] = (!empty($itens_procedimentos[$proc])) ? $itens_procedimentos[$proc] + $part : $part;

                        if(in_array($convenio, $texto_convenios)){
                            $texto_convenios[] = $convenio;
                        }

                        if(in_array($proc, $texto_procedimentos)){
                            $texto_procedimentos[] = $proc;
                        }
                    }
                }

                $a["convenio"] = (!empty($item->agendamentos)) ? $item->agendamentos->agendamentoProcedimento[0]->procedimentoInstituicaoConvenioTrashed->convenios->nome : "-";                
                $a["procedimento"] = (!empty($item->agendamentos)) ? $item->agendamentos->agendamentoProcedimento[0]->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->descricao : "-";
                
                if(!empty($item->agendamentos) && $item->agendamentos->agendamentoProcedimento->count() > 1){
                    $a["procedimento"] .= " (+". ($item->agendamentos->agendamentoProcedimento->count() - 1) .")";
                }
                
                $a["conta_caixa"] = (!empty($item->contaCaixa)) ? $item->contaCaixa->descricao : "-";
                
                if(!empty($item->forma_pagamento)){
                    $a["forma_pagamento"] = ContaPagar::forma_pagamento_texto($item->forma_pagamento);
                }elseif($item->tipo == "movimentacao"){
                    $a["forma_pagamento"] = "Movimentação";
                }else{
                    $a["forma_pagamento"] = "-";
                }
                
                $a["parcela"] = (!empty($item->agendamentos)) ? $item->agendamentos->parcelas : $item->num_parcela;

                $a["valor_pago"] = number_format($item->valor_pago, 2, ",", ".");

                return $a;
            });


            $dadosPagar = collect($contasPagar)
            ->map(function ($item) {
                
                $a["tipo"] = "saida";
                $a["data_pagamento"] = date("d/m/Y", strtotime($item->data_pago));
                
                if ($item->tipo == 'paciente'){
                    if($item->paciente){
                        $a["Fornecedor_Paciente"] = "Paciente: {$item->paciente->nome}";
                    }elseif(!$item->pessoa_id){
                        $a["Fornecedor_Paciente"] = "Paciente avulso!";
                    }else{
                        $a["Fornecedor_Paciente"] = "";
                    }
                }elseif($item->tipo == 'convenio'){
                    $a["Fornecedor_Paciente"] = "Convênio: {$item->convenio->nome}";
                }elseif($item->tipo == "movimentacao"){
                    $a["Fornecedor_Paciente"] = "Movimentação: {$item->descricao}";
                }else{
                    $a["Fornecedor_Paciente"] = "";
                }
                
                $a["convenio"] = "";                
                $a["procedimento"] = "";
                
                if(!empty($item->agendamentos) && $item->agendamentos->agendamentoProcedimento->count() > 1){
                    $a["procedimento"] .= " (+". ($item->agendamentos->agendamentoProcedimento->count() - 1) .")";
                }
                
                $a["conta_caixa"] = (!empty($item->contaCaixa)) ? $item->contaCaixa->descricao : "-";
                
                if(!empty($item->forma_pagamento)){
                    $a["forma_pagamento"] = ContaPagar::forma_pagamento_texto($item->forma_pagamento);
                }elseif($item->tipo == "movimentacao"){
                    $a["forma_pagamento"] = "Movimentação";
                }else{
                    $a["forma_pagamento"] = "-";
                }
                
                $a["parcela"] = (!empty($item->agendamentos)) ? $item->agendamentos->parcelas : $item->num_parcela;

                $a["valor_pago"] = number_format($item->valor_pago, 2, ",", ".");

                return $a;
            });

        $merge = $dadosReceber->merge($dadosPagar);
        
        return $merge;
    }
}
