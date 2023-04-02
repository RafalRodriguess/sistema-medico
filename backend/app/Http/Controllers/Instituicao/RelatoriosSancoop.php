<?php

namespace App\Http\Controllers\Instituicao;

use App\Exports\RelatorioSancoopExport;
use App\FaturamentoLote;
use App\Http\Controllers\Controller;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Excel;

class RelatoriosSancoop extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorios_sancoop');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $convenios = $instituicao->convenios()->get();

        $profissionais = $instituicao->medicosRelatorioAtendimentos()->get();
        $status = FaturamentoLote::getStatus();

        return view('instituicao.relatorios.sancoop/lista', compact('profissionais', 'status', 'convenios'));
    }

    public function tabela(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorios_sancoop');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $guias = $instituicao->faturamento_lotes()->searchSancoop()
            ->whereIn('status', $request->input('status'))
            ->whereIn('prestadores_id', $request->input('profissionais'))
            ->whereHas('guias.agendamento_paciente.agendamentoProcedimento.procedimentoInstituicaoConvenio', function($q) use ($request){
                $q->whereIn("convenios_id", $request->input('convenios'));
                $q->whereHas('procedimentoInstituicao', function($query) use($request){
                    if(!$request->input('procedimentos')[0] == 'todos'){
                        $query->whereIn("procedimentos_id", $request->input('procedimentos'));
                    }
                });
            })
            ->whereHas('guias.agendamento_paciente', function($q) use ($request){
                if($request->input('data_de') == 'atendimento'){
                    $q->whereBetween('data', [$request->input('data_inicio')." 00:00:00", $request->input('data_fim')." 23:59:59"]);
                };
                $q->whereHas('agendamentoGuias', function($query) use ($request){
                    if(!empty($request->input('tipo_guia'))){
                        $query->where('tipo_guia', $request->input('tipo_guia'));
                    };
                });
            })
            ->with([
                'guias.agendamento_paciente' => function($q) use ($request){
                    if($request->input('data_de') == 'atendimento'){
                        $q->whereBetween('data', [$request->input('data_inicio')." 00:00:00", $request->input('data_fim')." 23:59:59"]);
                    };
                },
                'guias.agendamento_paciente.agendamentoGuias' => function($q) use ($request){
                    if(!empty($request->input('tipo_guia'))){
                        $q->where('tipo_guia', $request->input('tipo_guia'));
                    };
                },                
                'prestador'
            ]);
        
        if($request->input('data_de') == 'envio'){
            $guias->whereBetween('created_at', [$request->input('data_inicio')." 00:00:00", $request->input('data_fim')." 23:59:59"]);
        }

        $guias = $guias->get();

        // dd($request->input());

        return view('instituicao.relatorios.sancoop/tabela', compact('guias'));
    }

    public function exportExcel(Request $request, Excel $excel){
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorios_sancoop');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $guias = $instituicao->faturamento_lotes()->searchSancoop()
            ->whereIn('status', $request->input('status'))
            ->whereIn('prestadores_id', $request->input('profissionais'))
            ->whereHas('guias.agendamento_paciente.agendamentoProcedimento.procedimentoInstituicaoConvenio', function($q) use ($request){
                $q->whereIn("convenios_id", $request->input('convenios'));
                $q->whereHas('procedimentoInstituicao', function($query) use($request){
                    if(!$request->input('procedimentos')[0] == 'todos'){
                        $query->whereIn("procedimentos_id", $request->input('procedimentos'));
                    }
                });
            })
            ->whereHas('guias.agendamento_paciente', function($q) use ($request){
                if($request->input('data_de') == 'atendimento'){
                    $q->whereBetween('data', [$request->input('data_inicio')." 00:00:00", $request->input('data_fim')." 23:59:59"]);
                };
                $q->whereHas('agendamentoGuias', function($query) use ($request){
                    if(!empty($request->input('tipo_guia'))){
                        $query->where('tipo_guia', $request->input('tipo_guia'));
                    };
                });
            })
            ->with([
                'guias.agendamento_paciente' => function($q) use ($request){
                    if($request->input('data_de') == 'atendimento'){
                        $q->whereBetween('data', [$request->input('data_inicio')." 00:00:00", $request->input('data_fim')." 23:59:59"]);
                    };
                },
                'guias.agendamento_paciente.agendamentoGuias' => function($q) use ($request){
                    if(!empty($request->input('tipo_guia'))){
                        $q->where('tipo_guia', $request->input('tipo_guia'));
                    };
                },                
                'prestador'
            ]);
        
        if($request->input('data_de') == 'envio'){
            $guias->whereBetween('created_at', [$request->input('data_inicio')." 00:00:00", $request->input('data_fim')." 23:59:59"]);
        }

        $guias = $guias->get();
                
        $export = new RelatorioSancoopExport($guias, 'instituicao.relatorios.sancoop/tabela');

        return $excel->download($export, "Relatório guias sancoop ".date('YmdHis').".xlsx");
    }

    public function exportPdf(Request $request){
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorios_sancoop');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $guias = $instituicao->faturamento_lotes()->searchSancoop()
            ->whereIn('status', $request->input('status'))
            ->whereIn('prestadores_id', $request->input('profissionais'))
            ->whereHas('guias.agendamento_paciente.agendamentoProcedimento.procedimentoInstituicaoConvenio', function($q) use ($request){
                $q->whereIn("convenios_id", $request->input('convenios'));
                $q->whereHas('procedimentoInstituicao', function($query) use($request){
                    if(!$request->input('procedimentos')[0] == 'todos'){
                        $query->whereIn("procedimentos_id", $request->input('procedimentos'));
                    }
                });
            })
            ->whereHas('guias.agendamento_paciente', function($q) use ($request){
                if($request->input('data_de') == 'atendimento'){
                    $q->whereBetween('data', [$request->input('data_inicio')." 00:00:00", $request->input('data_fim')." 23:59:59"]);
                };
                $q->whereHas('agendamentoGuias', function($query) use ($request){
                    if(!empty($request->input('tipo_guia'))){
                        $query->where('tipo_guia', $request->input('tipo_guia'));
                    };
                });
            })
            ->with([
                'guias.agendamento_paciente' => function($q) use ($request){
                    if($request->input('data_de') == 'atendimento'){
                        $q->whereBetween('data', [$request->input('data_inicio')." 00:00:00", $request->input('data_fim')." 23:59:59"]);
                    };
                },
                'guias.agendamento_paciente.agendamentoGuias' => function($q) use ($request){
                    if(!empty($request->input('tipo_guia'))){
                        $q->where('tipo_guia', $request->input('tipo_guia'));
                    };
                },                
                'prestador'
            ]);
        
        if($request->input('data_de') == 'envio'){
            $guias->whereBetween('created_at', [$request->input('data_inicio')." 00:00:00", $request->input('data_fim')." 23:59:59"]);
        }

        $guias = $guias->get();
        
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view('instituicao.relatorios.sancoop.tabela_export', \compact('guias', 'instituicao')))->setPaper('a4', 'landscape');;
        return $pdf->stream();
        
    }

}
