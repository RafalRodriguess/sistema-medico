<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use App\Pessoa;
use App\Agendamentos;
use App\Prestador;
use App\ReceituarioPaciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ReceituariosMemed extends Controller
{
    
    const API_KEY = "$2y$10\$RsZNjHRxeSBZOT0h8uRRqOKeff0TZ5NBPkreF.H/rnvUGnbbFiYIm";
    const SECRET_KEY = "$2y$10\$t.WLpYTPVj0.WRpCPAtn3uoFY1h9PXHfq/tI.gmVJSM1JjAZL9BAa";
    const URL_API = "https://api.memed.com.br";
    const URL_API_FRONT = "https://api.memed.com.br";
    const PREFIX = "";
    
    public function receituarioPaciente(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'prescrever_receituario_memed');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        // $paciente = $agendamento->pessoa()->first();

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $paciente->cpf =  str_replace(['.','-'],['',''], $paciente->cpf);
        $paciente->telefone1 = str_replace(['(','-',')',' '],['','','',''], $paciente->telefone1);

        $user = $request->user('instituicao');

        $prestador = $instituicao->prestadores()->where('prestadores_id', $user->prestador()->first()->prestadores_id)->with('prestador')->first();
        $prestador->prestador->cpf =  str_replace(['.','-'],['',''], $prestador->prestador->cpf);

        // $idPrestador =  $prestador->crm.$prestador->conselho_uf; //CRM+UFCRM
        $idPrestador =  str_replace(['.','-'],['',''], $prestador->prestador->cpf); //CPF
        // dd($this->deleteCadastroProfissional($idPrestador));

        $consulta = $this->getCadastroProfissional($idPrestador);

        if($consulta === false){
            $cadProfissional = $this->setCadastroProfissional($prestador);
            
            if(!empty($cadProfissional->errors)){
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Erro.',
                    'text' => "ocorreu o seguinte erro ao tentar a solicitação: {$cadProfissional->errors[0]->detail}"
                ]);
            }else if(!empty($cadProfissional->data->attributes->token)){
                $token = $cadProfissional->data->attributes->token;
            }else if( $cadProfissional === false){
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Erro.',
                    'text' => 'Dados importantes estão faltando no cadastro do prestador!'
                ]);
            }
        }else if(!empty($consulta->data->attributes->token)){
            if($consulta->data->attributes->status == 'Inativo'){
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Erro.',
                    'text' => "O cadastro do prestador '{$consulta->data->attributes->nome} {$consulta->data->attributes->sobrenome}' na MEMED não esta ativo! Status atual: '{$consulta->data->attributes->status}'"
                ]);
            }else{
                $token = $consulta->data->attributes->token;
            }     
           
        }

        $url = self::URL_API_FRONT;

        // return response()->json(['token' => $token, 'url' => self::URL_API_FRONT]);
        return view("instituicao.prontuarios.receituarios_memed.index", compact('token', 'url', 'paciente'));
    }

    private function getCadastroProfissional($idConsulta){
        // $idConsulta = '047373';
        // $idConsulta = 'test123';
        // $idConsulta = str_replace(['.','-'], ['',''], $idConsulta);
        $link = self::URL_API."/v1/sinapse-prescricao/usuarios/".self::PREFIX.$idConsulta;
        
        $conn = Http::acceptJson()->get($link, ['api-key' => self::API_KEY, 'secret-key' => self::SECRET_KEY]);

        if($conn->status() == 404){
            // dd($conn);
            return false;
        }else{
            // dd($conn->body());
            return json_decode($conn->body());
        }
    }

    private function setCadastroProfissional($prestador){
        // $idConsulta = '047373';
        $link = self::URL_API."/v1/sinapse-prescricao/usuarios/?".http_build_query([
            'api-key' => self::API_KEY,
            'secret-key' => self::SECRET_KEY
        ]);

        $data["data"]["type"] = "usuarios";
        $data["data"]["attributes"]["external_id"] = self::PREFIX.$prestador->prestadores_id;
        $data["data"]["attributes"]["nome"] = explode(" ", $prestador->prestador->nome, 2)[0];
        $data["data"]["attributes"]["sobrenome"] = (explode(" ", $prestador->prestador->nome, 2)[1]) ? explode(" ", $prestador->prestador->nome, 2)[1] : "";
        $data["data"]["attributes"]["data_nascimento"] = $prestador->prestador->nascimento;
        $data["data"]["attributes"]["cpf"] = str_replace(['.','-'], ['',''], $prestador->prestador->cpf);
        
        if($prestador->conselho_uf){
            $data["data"]["attributes"]["uf"] = $prestador->conselho_uf; //Uf do crm do médico
        }
        
        if($prestador->prestador->sexo == 1){
            $data["data"]["attributes"]["sexo"] = "M";
        }else if($prestador->prestador->sexo == 2){
            $data["data"]["attributes"]["sexo"] = "F";
        }
        
        if($prestador->crm){
            $data["data"]["attributes"]["crm"] = $prestador->crm;
        }

        if(empty($data["data"]["attributes"]["crm"]) || empty($data["data"]["attributes"]["cpf"])){
            return false;
        }
        
        $conn = Http::withBody(json_encode($data), 'application/json')
            ->accept('application/vnd.api+json')
            ->withHeaders(['Cache-Control' => 'no-cache'])
        ->post($link);

        return json_decode($conn->body());
        
    }

    private function deleteCadastroProfissional($idConsulta){
        // $idConsulta = '047373MG';

        $link = self::URL_API."/v1/sinapse-prescricao/usuarios/{$idConsulta}";
        
        $conn = Http::acceptJson()->delete($link, ['api-key' => self::API_KEY, 'secret-key' => self::SECRET_KEY]);

        dd($conn);

        return json_decode($conn->body());
        
    }


    public function salvaReceituario(Request $request, Agendamentos $agendamento, Pessoa $paciente){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $user = $request->user('instituicao');

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        
        $receituario = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $presquicao = $request->input('prescricao')['prescricao'];            

            $medicamentos = collect($presquicao["medicamentos"])
                ->map(function ($item) {
                    return [
                        'posologia' => strip_tags($item['posologia']),
                        'quantidade' => $item['quantidade'],
                        'medicamento' => ['nome' => $item['nome'], 'descricao' => $item['descricao'], 'composicao' => null, "medicamento_id" => null],
                    ];
                }
            );

            $dados = [
                'paciente_id' => $presquicao['paciente']['external_id'],
                'agendamento_id' => $agendamento->id,
                'usuario_id' => $user->id,
                'receituario' => $medicamentos,
                'tipo' => "simples",
                'estrutura' => "memed",
                'codigo_acesso' => $presquicao['id'],
            ];
        
            $receituario = $paciente->receituario()->create($dados);

            $receituario->criarLogCadastro($user, $instituicao->id);

            return $receituario;
        });

        // dd($receituario);
        return response()->json($receituario);
    }


    public function getReceituario(Request $request, Agendamentos $agendamento, Pessoa $paciente, ReceituarioPaciente $receituario)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $prestador = $instituicao->prestadores()->where('prestadores_id', $user->prestador()->first()->prestadores_id)->with('prestador')->first();
        $consulta = $this->getCadastroProfissional($prestador->prestador->cpf);

        if($consulta === false){
            $cadProfissional = $this->setCadastroProfissional($prestador);

            if(!empty($cadProfissional->errors)){
                return $cadProfissional->errors;
            }else if(!empty($cadProfissional->data->attributes->token)){
                $token = $cadProfissional->data->attributes->token;
            }
        }else if(!empty($consulta->data->attributes->token)){
           $token = $consulta->data->attributes->token;
        }

        // dd($receituario);

        $link = self::URL_API."/v1/prescricoes/{$receituario->codigo_acesso}/url-document/full";
        
        $conn = Http::acceptJson()->get($link, ['api-key' => self::API_KEY, 'secret-key' => self::SECRET_KEY, 'token' => $token]);

        if($conn->status() == 200){
            $link = json_decode($conn->body())->data[0]->attributes->link;
            return view("instituicao.prontuarios.receituarios_memed.resumoReceituario", compact('agendamento', 'receituario', 'paciente', 'link'));
        }else{
            $erros = json_decode($conn->body())->errors;
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro.',
                'text' => 'ocorreu o seguinte erro ao tentar a solicitação: {$erros}'
            ]);
        }

        // // return response()->json(['token' => $token, 'url' => self::URL_API_FRONT]);
        // return view("instituicao.prontuarios.receituarios_memed.resumoReceituario", compact('agendamento', 'receituario', 'paciente', 'link'));
    } 
}
