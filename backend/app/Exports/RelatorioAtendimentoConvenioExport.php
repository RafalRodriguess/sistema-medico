<?php

namespace App\Exports;

use App\Agendamentos;
use App\Instituicao;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RelatorioAtendimentoConvenioExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $agendamentos;
    
    public function __construct($agendamentos)
    {
        $this->agendamentos = $agendamentos;
    }

    public function view(): View
    {
        return view('instituicao.relatorios.atendimentos.export_tabela_convenios', [
            'agendamentos' => $this->agendamentos
        ]);
    }

    // public function headings(): array
    // {
    //     return [
    //         'Cod Agenda',
    //         'Paciente',
    //         'Data',
    //         'Convênio',
    //         'Serviço / Procedimento',
    //         'Valor procedimento',
    //         'Valor convênio',
    //     ];
    // }

    
    // public function collection()
    // {
    //     $retorno = collect($this->agendamentos)
    //     ->map(function ($item) {

    //         return collect($item->agendamentoProcedimento)
    //             ->map(function($proc) use($item){
    //                 return [
    //                     'Cod Agenda' => $item->id,
    //                     'Paciente' => $item->pessoa->nome,
    //                     'Data' => date('d/m/Y H:i', strtotime($item->data)),
    //                     'Convênio' => $proc->procedimentoInstituicaoConvenio->convenios->nome,
    //                     'Serviço / Procedimento' => $proc->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao,
    //                     'Valor procedimento' => number_format($proc->valor_atual, 2, ',', '.'),
    //                     'Valor convênio' => number_format($proc->valor_convenio, 2, ',', '.'),

    //                 ];
    //             });
    //     });
        
    //     return $retorno;
    // }
}
