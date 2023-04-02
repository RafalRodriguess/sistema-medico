<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\Relatorio\PesquisaRegistroLogRequest;
use App\Instituicao;
use Illuminate\Http\Request;

class RelatorioRegistroLogs extends Controller
{

    protected  $tipo = [
        'Agendamentos' => "Agendamentos",
        'ContaReceber' => "Contas a Receber",
        'ContaPagar' => "Contas a Pagar",
        'Pessoa' => "Paciente",
        'InstituicaoUsuario' => "Usuarios",
        'OdontologicoPaciente' => "Orçamentos",
        'OdontologicoItemPaciente' => "Orçamentos Item",
    ];

    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_registro_log');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $tipo = $this->tipo;
        return view('instituicao.relatorios.registro_log.index', \compact('instituicao', 'tipo'));
    }

    public function tabela(PesquisaRegistroLogRequest $request)
    {
        $dados = $request->validated();
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        if($dados['tipo'] == 'Agendamentos'){
            $data = $this->getAgendamentos($dados, $instituicao);
        }
        if($dados['tipo'] == 'ContaReceber'){
            $data = $this->getContaReceber($dados, $instituicao);
        }
        if($dados['tipo'] == 'ContaPagar'){
            $data = $this->getContaPagar($dados, $instituicao);
        }
        if($dados['tipo'] == 'Pessoa'){
            $data = $this->getPessoa($dados, $instituicao);
        }
        if($dados['tipo'] == 'InstituicaoUsuario'){
            $data = $this->getInstituicaoUsuario($dados, $instituicao);
        }
        if($dados['tipo'] == 'OdontologicoPaciente'){
            $data = $this->getOdontologicoPaciente($dados, $instituicao);
        }
        if($dados['tipo'] == 'OdontologicoItemPaciente'){
            $data = $this->getOdontologicoItemPaciente($dados, $instituicao);
        }

        $dados = $data['dados'];
        $colunas = $data['colunas'];
        $posicao = $data['posicao'];
        return view('instituicao.relatorios.registro_log.tabela', \compact('dados', 'colunas', 'posicao'));
    }

    private function getAgendamentos($dados, $instituicao)
    {

        $data = $instituicao->logInstituicao()->getDados($dados)->get();

        $colunas = [
            'Data',
            'Usuario',
            'Agendamento data',
            'Agendamento Paciente',
            'Descricao',
            'Alterações',
        ];

        $dados = [];

        if(count($data) > 0){
            foreach ($data as $key => $value) {
                $dados[] = [
                    date('d/m/Y H:i', strtotime($value->created_at)),
                    $value->usuarios->nome,
                    date('d/m/Y H:i', strtotime($value->agendamento->data)),
                    ($value->agendamento->pessoa) ? $value->agendamento->pessoa->nome : "",
                    $value->descricao,
                    $value->dados
                ];
            }
        }

        $posicao = 5;

        return ['dados' => $dados, 'colunas' => $colunas, 'posicao' => $posicao];
    }
    
    private function getContaReceber($dados, $instituicao)
    {

        $data = $instituicao->logInstituicao()->getDados($dados)->get();

        $colunas = [
            'Data',
            'Usuario',
            'Conta',
            'Data Vencimento',
            'Descrição',
            'Alterações',
        ];

        $dados = [];

        if(count($data) > 0){
            foreach ($data as $key => $value) {
                $nome = "";
                if ($value->contaReceber->tipo == "paciente") {
                    $nome = ($value->contaReceber->pessoa_id) ? "Paciente: ".$value->contaReceber->pacienteTrashed->nome : "Paciente Avulso";
                } else if($value->contaReceber->tipo == 'convenio'){
                    $nome = "Convenio: ".$value->contaReceber->convenio->nome;
                }

                $dados[] = [
                    date('d/m/Y H:i', strtotime($value->created_at)),
                    $value->usuarios->nome,
                    $nome,
                    date('d/m/Y H:i', strtotime($value->contaReceber->data_vencimento)),
                    $value->descricao,
                    $value->dados
                ];
            }
        }

        $posicao = 5;

        return ['dados' => $dados, 'colunas' => $colunas, 'posicao' => $posicao];
    }
    
    private function getContaPagar($dados, $instituicao)
    {

        $data = $instituicao->logInstituicao()->getDados($dados)->get();

        $colunas = [
            'Data',
            'Usuario',
            'Conta',
            'Data Vencimento',
            'Descrição',
            'Alterações',
        ];

        $dados = [];

        if(count($data) > 0){
            foreach ($data as $key => $value) {
                $nome = "";

                if ($value->contaPagar->tipo == 'paciente'){
                    $nome = "Paciente: ".(!empty($value->contaPagar->paciente)) ? $value->contaPagar->paciente->nome : "";
                }
                
                if ($value->contaPagar->tipo == 'prestador'){
                    $nome = "Prestador: ".$value->contaPagar->prestador->nome;
                }
                
                if ($value->contaPagar->tipo == 'fornecedor')                            {
                    $nome = "Fornecedor: ".(!empty($value->contaPagar->fornecedor['nome_fantasia'])) ? $value->contaPagar->fornecedor['nome_fantasia'] : $value->contaPagar->fornecedor['nome'];
                }

                $dados[] = [
                    date('d/m/Y H:i', strtotime($value->created_at)),
                    $value->usuarios->nome,
                    $nome,
                    date('d/m/Y H:i', strtotime($value->contaPagar->data_vencimento)),
                    $value->descricao,
                    $value->dados
                ];
            }
        }

        $posicao = 5;

        return ['dados' => $dados, 'colunas' => $colunas, 'posicao' => $posicao];
    }
    
    private function getPessoa($dados, $instituicao)
    {

        $data = $instituicao->logInstituicao()->getDados($dados)->get();

        $colunas = [
            'Data',
            'Usuario',
            'Nome',
            'Descrição',
            'Alterações',
        ];

        $dados = [];

        if(count($data) > 0){
            foreach ($data as $key => $value) {
                $dados[] = [
                    date('d/m/Y H:i', strtotime($value->created_at)),
                    $value->usuarios->nome,
                    $value->pessoa->nome,
                    $value->descricao,
                    $value->dados
                ];
            }
        }

        $posicao = 4;

        return ['dados' => $dados, 'colunas' => $colunas, 'posicao' => $posicao];
    }
    
    private function getInstituicaoUsuario($dados, $instituicao)
    {

        $data = $instituicao->logInstituicao()->getDados($dados)->get();

        $colunas = [
            'Data',
            'Usuario',
            'Nome',
            'Descrição',
            'Alterações',
        ];

        $dados = [];

        if(count($data) > 0){
            foreach ($data as $key => $value) {
                if($value->descricao == "Edição" || $value->descricao == "Exclusão" || $value->descricao == "Cadastro"){
                    $alteracoes = $value->dados;
                }else{
                    $alteracoes = [
                        "Habilidade" => "Habilidades alteradas"
                    ];
                }

                $dados[] = [
                    date('d/m/Y H:i', strtotime($value->created_at)),
                    $value->usuarios->nome,
                    $value->usuarioEditado->nome,
                    $value->descricao,
                    $alteracoes
                ];
            }
        }
        // dd($dados);
        $posicao = 4;

        return ['dados' => $dados, 'colunas' => $colunas, 'posicao' => $posicao];
    }
    
    private function getOdontologicoPaciente($dados, $instituicao)
    {

        $data = $instituicao->logInstituicao()->getDados($dados)->get();

        $colunas = [
            'Data',
            'Usuario',
            'Nome Paciente',
            'Descrição',
            'Alterações',
        ];

        $dados = [];

        if(count($data) > 0){
            foreach ($data as $key => $value) {               

                $dados[] = [
                    date('d/m/Y H:i', strtotime($value->created_at)),
                    $value->usuarios->nome,
                    $value->odontologico->paciente->nome,
                    $value->descricao,
                    $value->dados
                ];
            }
        }
         
        $posicao = 4;

        return ['dados' => $dados, 'colunas' => $colunas, 'posicao' => $posicao];
    }
    
    private function getOdontologicoItemPaciente($dados, $instituicao)
    {

        $data = $instituicao->logInstituicao()->getDados($dados)->get();

        $colunas = [
            'Data',
            'Usuario',
            'Nome Paciente',
            'Descrição',
            'Alterações',
        ];

        $dados = [];

        if(count($data) > 0){
            foreach ($data as $key => $value) {               

                $dados[] = [
                    date('d/m/Y H:i', strtotime($value->created_at)),
                    $value->usuarios->nome,
                    $value->odontologicoItem->odontologico->paciente->nome,
                    $value->descricao,
                    $value->dados
                ];
            }
        }
        
        $posicao = 4;

        return ['dados' => $dados, 'colunas' => $colunas, 'posicao' => $posicao];
    }
}
