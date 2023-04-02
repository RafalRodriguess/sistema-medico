<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\Especialidade;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Instituicao\Agendamentos as InstituicaoAgendamentos;
use App\Instituicao;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function getEspecialidades(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $Especialidades = $instituicao->especialidades()->get();

        return response()->json($Especialidades);
    }

    public function getMedicos(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $medicos = $instituicao->medicos()
            ->whereHas('prestadoresInstituicoes', function ($q) {
                $q->where('ativo', 1);
            })
        ->get();

        return response()->json($medicos);
    }
    
    public function getAgendamentos(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $data = [
            date("Y-m-d 00:00:00", strtotime($request->input('start'))),
            date("Y-m-d 23:59:59", strtotime($request->input('end')))
        ];
        
        $status = [
            'confirmados' => 0,
            'atendidos' => 0,
            'agendados' => 0,
            'ausentes' => 0,
        ];
        

        $agendamento = Agendamentos::groupBy('status')
            ->whereDate('data', '>=', $data[0])
            ->whereDate('data', '<=', $data[1])
            ->selectRaw('status, count(*) as total')
            ->join('instituicoes_agenda', "agendamentos.instituicoes_agenda_id", "instituicoes_agenda.id")
            ->join("instituicoes_prestadores", "instituicoes_agenda.instituicoes_prestadores_id", "instituicoes_prestadores.id")
            ->where("instituicoes_id", $instituicao->id)
        ;

        if(!empty($request->input('medico_id'))){
            $agendamento = $agendamento
                ->where('prestadores_id', $request->input('medico_id'))
                ->get();
        }else{
            $agendamento = $agendamento->get();
        }

        foreach($agendamento->toArray() as $k => $v){
            switch($v['status']){
                case 'pendente':
                    $status['agendados'] = $v['total'];
                break;
                case 'finalizado':
                    $status['atendidos'] = $v['total'];
                break;
                case 'confirmado':
                    $status['confirmados'] = $v['total'];
                break;
                case 'ausente':
                    $status['ausentes'] = $v['total'];
                break;

            }
        }

        return view('instituicao.dashboard.quantidades', compact('status'));

    }

    public function getAtendimentos(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $data = [
            date("Y-m-d 00:00:00", strtotime($request->input('start'))),
            date("Y-m-d 23:59:59", strtotime($request->input('end')))
        ];
        
        $status = [
            'em_atendimento' => 0,
            'atendidos' => 0,
            'ausentes' => 0,
            'desmarcados' => 0,
        ];

        
        $agendamento = Agendamentos::groupBy('status')
            ->whereDate('data', '>=', $data[0])
            ->whereDate('data', '<=', $data[1])
            ->selectRaw('status, count(*) as total')
            ->join('instituicoes_agenda', "agendamentos.instituicoes_agenda_id", "instituicoes_agenda.id")
            ->join("instituicoes_prestadores", "instituicoes_agenda.instituicoes_prestadores_id", "instituicoes_prestadores.id")
            ->where("instituicoes_id", $instituicao->id)
        ;

        if(!empty($request->input('medico_id'))){
            $agendamento = $agendamento
                ->where('prestadores_id', $request->input('medico_id'))
                ->get();
        }else{
            $agendamento = $agendamento->get();
        }

        // 'agendado','confirmado','cancelado','pendente','finalizado','excluir'
        foreach($agendamento->toArray() as $k => $v){
            switch($v['status']){
                case 'em_atendimento':
                    $status['em_atendimento'] = $v['total'];
                break;
                case 'finalizado':
                    $status['atendidos'] = $v['total'];
                break;
                case 'cancelado':
                    $status['desmarcados'] = $v['total'];
                break;
                case 'ausente':
                    $status['ausentes'] = $v['total'];
                break;

            }
        }

        return response()->json($status);
    }

    public function getConvenios(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $data = [
            date("Y-m-d 00:00:00", strtotime($request->input('start'))),
            date("Y-m-d 23:59:59", strtotime($request->input('end')))
        ];

        $agendamento = Agendamentos::
            join('agendamentos_procedimentos', 'agendamentos.id', '=', 'agendamentos_procedimentos.agendamentos_id')
            ->join('procedimentos_instituicoes_convenios', 'agendamentos_procedimentos.procedimentos_instituicoes_convenios_id', '=', 'procedimentos_instituicoes_convenios.id')
            ->join('convenios', 'procedimentos_instituicoes_convenios.convenios_id', '=', 'convenios.id')
            ->join('instituicoes_agenda', "agendamentos.instituicoes_agenda_id", "instituicoes_agenda.id")
            ->join("instituicoes_prestadores", "instituicoes_agenda.instituicoes_prestadores_id", "instituicoes_prestadores.id")
            ->whereDate('data', '>=', $data[0])
            ->whereDate('data', '<=', $data[1])
            ->selectRaw('convenios.nome, count(*) as total')
            ->where('instituicao_id', $instituicao->id)
            ->groupBy('nome')
        ;
        if(!empty($request->input('medico_id'))){
            $agendamento = $agendamento
                ->where('prestadores_id', $request->input('medico_id'))
                ->get();
        }else{
            $agendamento = $agendamento->get();
        }

        foreach($agendamento as $k => $v){
            $agendamento[$k]->cor = $this->getbackground($k);
        }
        
        return response()->json($agendamento);
    }

    public function getPacientes(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao')); 
        
        $data = [
            date("Y-m-d 00:00:00", strtotime($request->input('start'))),
            date("Y-m-d 23:59:59", strtotime($request->input('end')))
        ];
        
        $agendamento = Agendamentos::
            whereDate('data', '>=', $data[0])
            ->whereDate('data', '<=', $data[1])
            ->join('instituicoes_agenda', "agendamentos.instituicoes_agenda_id", "instituicoes_agenda.id")
            ->join("instituicoes_prestadores", "instituicoes_agenda.instituicoes_prestadores_id", "instituicoes_prestadores.id")
            ->where("instituicoes_id", $instituicao->id)
        ;


        if(!empty($request->input('medico_id'))){
            $agendamento = $agendamento
                ->where('prestadores_id', $request->input('medico_id'))
                ->get();
        }else{
            $agendamento = $agendamento->get();
        }

        $pacientes = [
            'novos' => 0,
            'recorrentes' => 0
        ];

        foreach($agendamento as $k => $v){
            
            
            $pessoa = $instituicao->instituicaoPessoas()
                ->where('id', $v->pessoa_id)
                ->with('agendamentos')
                ->first();
    
            $n_agendas = (!empty($pessoa->agendamentos)) ? count($pessoa->agendamentos) : 0;
            
            if($n_agendas <= 1){ 
                $pacientes['novos']++;
            }else{
                $pacientes['recorrentes']++;
            }
        }

        return response()->json($pacientes);
        
    }

    public function getbackground($posicao)
    {
        
        $dados  = array(
            '#26c6da',
            '#1e88e5',
            '#ffcf8e',
            '#745af2',
            "#CD5C5C",
            "#1ab394",   //inspinia green
            "#dcdcdc",
            "#b5b8cf",
            "#a3e1d4",
            "#B0D602",
            "#FFC300",
            "#900C3F",
            "#1194F5",
            "#EA7FFF",
            "#800000",
            "#FA8072",
            "#FF6347",
            "#FF7F50",
            "#FF4500",
            "#8B0000",
            "#B22222",
            "#FF0000",
            "#D2691E",
            "#F4A460",
            "#FF8C00",
            "#FFA500",
            "#B8860B",
            "#008000",
            "#00FF00",
            "#32CD32",
            "#00FF7F",
            "#00FA9A",
            "#40E0D0",
            "#20B2AA",
            "#48D1CC",
            "#008080",
            "#008B8B",
            "#00FFFF",
            "#00FFFF",
            "#00CED1",
            "#00BFFF",
            "#1E90FF",
            "#4169E1",
            "#000080",
            "#00008B",
            "#DAA520",
            "#FFD700",
            "#808000",
            "#FFFF00",
            "#9ACD32",
            "#ADFF2F",
            "#7FFF00",
            "#7CFC00",
            "#0000CD",
            "#0000FF",
            "#8A2BE2",
            "#9932CC",
            "#9400D3",
            "#800080",
            "#8B008B",
            "#FF00FF",
            "#FF00FF",
            "#C71585",
            "#FF1493",
            "#FF69B4",
            "#DC143C",
            "#A52A2A",
            "#BC8F8F",
            "#F08080",
            "#FFFAFA",
            "#FFE4E1",
            "#E9967A",
            "#FFA07A",
            "#A0522D",
            "#FFF5EE",
            "#8B4513",
            "#FFDAB9",
            "#CD853F",
            "#FAF0E6",
            "#FFE4C4",
            "#DEB887",
            "#D2B48C",
            "#FAEBD7",
            "#FFDEAD",
            "#FFEBCD",
            "#FFEFD5",
            "#FFE4B5",
            "#F5DEB3",
            "#FDF5E6",
            "#FFFAF0",
            "#FFF8DC",
            "#F0E68C",
            "#FFFACD",
            "#EEE8AA",
            "#BDB76B",
            "#F5F5DC",
            "#FAFAD2",
            "#FFFFE0",
            "#FFFFF0",
            "#6B8E23",
            "#556B2F",
            "#8FBC8F",
            "#006400",
            "#228B22",
            "#90EE90",
            "#98FB98",
            "#F0FFF0",
            "#2E8B57",
            "#3CB371",
            "#F5FFFA",
            "#66CDAA",
            "#7FFFD4",
            "#2F4F4F",
            "#AFEEEE",
            "#E0FFFF",
            "#F0FFFF",
            "#5F9EA0",
            "#B0E0E6",
            "#ADD8E6",
            "#87CEEB",
            "#87CEFA",
            "#4682B4",
            "#F0F8FF",
            "#708090",
            "#778899",
            "#B0C4DE",
            "#6495ED",
            "#E6E6FA",
            "#F8F8FF",
            "#191970",
            "#6A5ACD",
            "#483D8B",
            "#7B68EE",
            "#9370DB",
            "#4B0082",
            "#BA55D3",
            "#DDA0DD",
            "#EE82EE",
            "#D8BFD8",
            "#DA70D6",
            "#FFF0F5",
            "#DB7093",
            "#FFC0CB",
            "#FFB6C1",
            "#000000",
            "#696969",
            "#808080",
            "#A9A9A9",
            "#C0C0C0",
            "#D3D3D3",
            "#DCDCDC",
            "#F5F5F5",
            "#FFFFFF"
        );

        return $dados[$posicao];
    }
}
