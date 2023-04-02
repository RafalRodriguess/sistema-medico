<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use App\InstituicaoPaciente;
use App\Usuario;
use Illuminate\Http\Request;

class Pacientes_instituicao extends Controller
{
    public function pacientes(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_paciente');

        return view('instituicao.pacientes/lista');
    }

    public function visualizarPaciente(Request $request, Usuario $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_paciente');
        abort_unless($paciente->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        // $paciente->load('usuarioEnderecos');

        return view('instituicao.pacientes/visualizar', [
            'paciente' => $paciente,
        ]);
    }
}
