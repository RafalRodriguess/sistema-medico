<?php

namespace App\Http\Controllers\Instituicao;

use App\Convenio;
use App\Especialidade;
use App\ConveniosProcedimentos;
use App\DocumentoPrestador;
use App\Especializacao;
use App\EspecializacaoEspecialidade;
use App\InstituicaoProcedimentos;
use App\ProcedimentosConveniosInstituicoesPrestadores;
use App\Procedimento;
use App\Http\Controllers\Controller;
use App\Http\Requests\Prestadores\{
    InstituicaoCreatePrestadorRequest,
};
use App\Http\Requests\Prestadores\EditarPrestadorRequest;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\Prestador;
use App\PrestadorVinculo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function Clue\StreamFilter\fun;

class Prestadores extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_prestador');

        return view('instituicao.prestadores/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $especialidades = $instituicao->especialidadesInstituicao()->get();
        $especializacoes = $instituicao->especializacoes()->get();

        $usuarios = $instituicao->instituicaoUsuarios()->doesntHave('prestador')->get();

        $prestadores = $instituicao->prestadores()->get();


        return view('instituicao.prestadores/criar', [
            'especialidades' => $especialidades,
            'opcoes_sexo' => Prestador::opcoes_sexo,
            'usuarios' => $usuarios,
            'especializacoes' => $especializacoes,
            'prestadores' => $prestadores,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreatePrestadorRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_prestador');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        $continue = (!empty($dados['continue'])) ? true : false;
        unset($dados['continue']);

        // Verifica se foi escolhido pessoa física ou jurídica, caso nenhuma delas retorna erro
        if(!in_array('5', $dados['vinculos']) && !in_array('6', $dados['vinculos']))
            return redirect()->back()->withErrors(['vinculos' => 'escolha um vínculo de tipo de pessoa']);
        $usuario_logado = $request->user('instituicao');

        $returned = DB::transaction(function () use ($dados, $instituicao, $usuario_logado, $request) {
            $excessao = null;
            if(isset($dados['excessao'])){
                $excessao = collect($request->validated()['excessao'])
                    ->filter(function($excessao){
                        return !is_null($excessao['procedimento_id']);
                    })
                    ->map(function($excessao){
                        return [
                            'procedimento_id' => $excessao['procedimento_id'],
                            'prestador_faturado_id' => $excessao['prestador_faturado_id'],
                        ];
                    });
            }

            $prestador = null;

            if(!empty($dados['cpf'])) $prestador_registrado = Prestador::query()
                ->where('cpf', $dados['cpf'])->first();
            else if(!empty($dados['cnpj'])) $prestador_registrado = Prestador::query()
                ->where('cnpj', $dados['cnpj'])->first();
            else{
                $prestador_registrado = null;
            }

            if($prestador_registrado){

                // $instituicao_prestador_registrado = InstituicoesPrestadores::query()
                //     ->where('prestadores_id', $prestador_registrado->id)
                //     ->where('instituicoes_id', $instituicao->id)
                //     ->first();
                // if($instituicao_prestador_registrado){
                //     return false;
                // }

                // $dados['prestadores_id'] = $prestador_registrado->id;
                $prestador = $prestador_registrado;
            } else {
                $novo_prestador = Prestador::create($dados);
                // $dados['prestadores_id'] = $novo_prestador->id;
                $novo_prestador->criarLogCadastro($usuario_logado, $instituicao->id);
                $prestador = $novo_prestador;
            }

            $prestador_id = $prestador->id;

            $dados['instituicoes_id'] = $instituicao->id;
            $dados['ativo'] = $request->boolean('ativo');
            if(isset($dados['auxiliar'])) $dados['auxiliar'] = true;
            if(isset($dados['anestesista'])) $dados['anestesista'] = true;

            if(isset($dados['especialidades'])){
                $especialidadesInseridas = [];
                if (is_array($dados['especialidades'])) {
                    foreach ($dados['especialidades'] as $value) {


                        $especialidade = $value;
                        array_push($especialidadesInseridas, $especialidade);

                        $data = [
                            'instituicoes_id' => $instituicao->id,
                            'prestadores_id' => $prestador->id,
                            'especialidade_id' => $especialidade
                        ];

                        $jaInserido = InstituicoesPrestadores::where($data)->first();

                        if (!$jaInserido) {
                            $data = [
                                'instituicoes_id' => $instituicao->id,
                                'prestadores_id' => $prestador->id,
                                'nome' => $dados['nome'],
                                'especialidade_id' => $especialidade,
                                'ativo' => $request->boolean('ativo'),
                                'tipo' => $dados['tipo'],
                                'tipo_conselho_id' => $dados['tipo_conselho_id'],
                                'conselho_uf' => $dados['conselho_uf'],
                                'anestesista'=> $request->boolean('anestesista'),
                                'auxiliar' => $request->boolean('auxiliar'),
                                'vinculos' => $dados['vinculos'],
                                'carga_horaria_mensal' => $dados['carga_horaria_mensal'],
                                'pis' => $dados['pis'],
                                'pasep' => $dados['pasep'],
                                'nir' => $dados['nir'],
                                'proe' => $dados['proe'],
                                'numero_cooperativa' => $dados['numero_cooperativa'],
                                'tipo_prontuario' => $dados['tipo_prontuario'],
                                'resumo_tipo' => $dados['resumo_tipo'],
                                'crm' => $dados['crm'],
                                'telefone' => $dados['telefone'],
                                'telefone2' => $dados['telefone2'],
                                'exibir_data' => $request->boolean('exibir_data'),
                                'exibir_titulo_paciente' => $request->boolean('exibir_titulo_paciente'),
                                'whatsapp_enviar_confirm_agenda' => $request->boolean('whatsapp_enviar_confirm_agenda'),
                                'whatsapp_receber_agenda' => $request->boolean('whatsapp_receber_agenda'),
                                'email' => $dados['email'],
                                // 'agencia' => $dados['agencia'],
                                // 'conta_bancaria' => $dados['conta_bancaria'],
                            ];
                            $data['instituicao_usuario_id'] = $dados['instituicao_usuario_id'];
                            $InstituicoesPrestadores = InstituicoesPrestadores::create($data);

                            $InstituicoesPrestadores->criarLogCadastro(
                                $usuario_logado,
                                $instituicao->id
                            );
                            if($excessao){
                                $InstituicoesPrestadores->procedimentosExcessoes()->attach($excessao);
                            }
                        }
                    }
                } else {
                    $data = [
                        'instituicoes_id' => $instituicao->id,
                        'prestadores_id' => $prestador->id,
                        'nome' => $dados['nome'],
                        'especialidade_id' => $dados['especialidades'],
                        'ativo' => $request->boolean('ativo'),
                        'tipo' => $dados['tipo'],
                        'tipo_conselho_id' => $dados['tipo_conselho_id'],
                        'conselho_uf' => $dados['conselho_uf'],
                        'anestesista'=> $request->boolean('anestesista'),
                        'auxiliar' => $request->boolean('auxiliar'),
                        'vinculos' => $dados['vinculos'],
                        'carga_horaria_mensal' => $dados['carga_horaria_mensal'],
                        'pis' => $dados['pis'],
                        'pasep' => $dados['pasep'],
                        'nir' => $dados['nir'],
                        'proe' => $dados['proe'],
                        'numero_cooperativa' => $dados['numero_cooperativa'],
                        'instituicao_usuario_id' => $dados['instituicao_usuario_id'],
                        'tipo_prontuario' => $dados['tipo_prontuario'],
                        'resumo_tipo' => $dados['resumo_tipo'],
                        'crm' => $dados['crm'],
                        'telefone' => $dados['telefone'],
                        'telefone2' => $dados['telefone2'],
                        'exibir_data' => $request->boolean('exibir_data'),
                        'exibir_titulo_paciente' => $request->boolean('exibir_titulo_paciente'),
                        // 'nome_banco' => $dados['nome_banco'],
                        // 'agencia' => $dados['agencia'],
                        'whatsapp_enviar_confirm_agenda' => $request->boolean('whatsapp_enviar_confirm_agenda'),
                        'whatsapp_receber_agenda' => $request->boolean('whatsapp_receber_agenda'),
                        'email' => $dados['email'],
                    ];

                    $InstituicoesPrestadores = InstituicoesPrestadores::create($data);

                    $InstituicoesPrestadores->criarLogCadastro(
                        $usuario_logado,
                        $instituicao->id
                    );
                    if($excessao){
                        $InstituicoesPrestadores->procedimentosExcessoes()->attach($excessao);
                    }
                }
            }else{
                $data = [
                    'instituicoes_id' => $instituicao->id,
                    'prestadores_id' => $prestador->id,
                    'nome' => $dados['nome'],
                    'ativo' => $request->boolean('ativo'),
                    'tipo' => $dados['tipo'],
                    'tipo_conselho_id' => $dados['tipo_conselho_id'],
                    'conselho_uf' => $dados['conselho_uf'],
                    'anestesista'=> $request->boolean('anestesista'),
                    'auxiliar' => $request->boolean('auxiliar'),
                    'vinculos' => $dados['vinculos'],
                    'carga_horaria_mensal' => $dados['carga_horaria_mensal'],
                    'pis' => $dados['pis'],
                    'pasep' => $dados['pasep'],
                    'nir' => $dados['nir'],
                    'proe' => $dados['proe'],
                    'numero_cooperativa' => $dados['numero_cooperativa'],
                    'instituicao_usuario_id' => $dados['instituicao_usuario_id'],
                    'tipo_prontuario' => $dados['tipo_prontuario'],
                    'resumo_tipo' => $dados['resumo_tipo'],
                    'crm' => $dados['crm'],
                    'telefone' => $dados['telefone'],
                    'telefone2' => $dados['telefone2'],
                    'exibir_data' => $request->boolean('exibir_data'),
                    'exibir_titulo_paciente' => $request->boolean('exibir_titulo_paciente'),
                    'whatsapp_enviar_confirm_agenda' => $request->boolean('whatsapp_enviar_confirm_agenda'),
                    'whatsapp_receber_agenda' => $request->boolean('whatsapp_receber_agenda'),
                    'email' => $dados['email'],
                    // 'nome_banco' => $dados['nome_banco'],
                    // 'agencia' => $dados['agencia'],
                    // 'conta_bancaria' => $dados['conta_bancaria'],
                ];

                $InstituicoesPrestadores = InstituicoesPrestadores::create($data);

                $InstituicoesPrestadores->criarLogCadastro(
                    $usuario_logado,
                    $instituicao->id
                );
                if($excessao){
                    $InstituicoesPrestadores->procedimentosExcessoes()->attach($excessao);
                }
            }

            // Validando se as especializacoes e as epecialidades estão corretas
            // if(EspecializacaoEspecialidade::whereIn('especialidades_id', $dados['especialidades'])->whereIn('especializacoes_id', collect($dados['especializacoes'] ?? [])->pluck('especializacoes_id'))->count() != collect($dados['especializacoes'] ?? [])->count()) {
            //     return redirect()->back()->withError(['especializacoes' => 'Uma ou mais especializações não estão disponíveis pois sua especialidade não foi escolhida']);
            // }
            // Cadastrando as especializacoes deste prestador agora já cadastrado
            $instituicao_prestador = $prestador->instituicaoPrestador($instituicao->id)->first();
            $instituicao_prestador->overwrite($instituicao_prestador->prestadorEspecializacoes(), collect($dados)->get('especializacoes', []));

            if(isset($dados['documentos'])){
                for ($i=0; $i < count($dados['documentos']); $i++) {
                    $documento = $dados['documentos'][$i];
                    $arquivo = $documento['arquivo'];
                    $arquivo_path = "documentos/prestadores/prestador"."_"."$prestador->id"."/";
                    $current_time = Carbon::now()->timestamp;
                    $arquivo_nome = $arquivo->getClientOriginalName();
                    $arquivo_new_nome = "$current_time"."_"."$arquivo_nome";
                    $upload = $arquivo->storeAs($arquivo_path, $arquivo_new_nome, 'public');
                    if(!$upload){
                        redirect()->back()->with('message', [
                            'icon' => 'error',
                            'title' => 'Falha.',
                            'text' => 'Falha ao fazer upload de arquivo'
                        ]);
                    }
                    $novo_documento = DocumentoPrestador::create([
                        'file_path_name' => "$arquivo_path"."$arquivo_new_nome",
                        'tipo' => $documento['tipo'],
                        'descricao' => $documento['descricao'],
                        'prestador_id' => $prestador->id,
                        'instituicao_id' => $instituicao->id,
                        'instituicao_prestador_id' => $InstituicoesPrestadores->id,
                    ]);
                    $novo_documento->criarLogCadastro($usuario_logado, $instituicao->id);
                }
            }

            return [true, $prestador];
        });


        //PRECISA OLHAR PQ NÂO DA ERRO
        // if($returned[0] == false){
        //     return redirect()->back()->with('mensagem', [
        //         'icon' => 'error',
        //         'title' => 'Falha.',
        //         'text' => 'Prestador já cadastrado!'
        //     ]);
        // }

        if($continue){
            return redirect()->route('instituicao.prestadores.getAgenda', $returned[1]->id)->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Prestador criado com sucesso!'
            ]);
        }else{
            return redirect()->route('instituicao.prestadores.index')->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Prestador criado com sucesso!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function getPrestadoresByEspecialidade(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $prestadores = Prestador::searchByInstituicao('',
            $request->especialidade_id, $instituicao->id)->get()->toArray();

        return response()->json($prestadores);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Prestador $prestador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $prestador_instituicao = $prestador->instituicaoPrestador($instituicao->id);

        abort_unless($prestador_instituicao[0]->instituicoes_id === $instituicao->id, 403);

        $especialidade = $prestador->especialidade()->wherePivot('instituicoes_id', $instituicao->id)->get();
        $especialidades = $instituicao->especialidadesInstituicao()->get();
        $especializacoes = $instituicao->especializacoes()->get();
        $especializacoes_escolhidas = $prestador_instituicao[0]->especializacoes()->get();
        $usuarios = $instituicao->instituicaoUsuarios()->whereDoesntHave('prestador', function ($query) use($prestador_instituicao) {
            if($prestador_instituicao[0]->instituicao_usuario_id != null){
                $query->where('instituicao_usuario_id', '<>', $prestador_instituicao[0]->instituicao_usuario_id);
            }
        })->get();

        $especializacoes_escolhidas = array_column($especializacoes_escolhidas->toArray(), 'id');

        $prestadores = $instituicao->prestadores()->get();

        return view('instituicao.prestadores/editar', array_merge(compact(
            'prestador',
            'prestador_instituicao',
            'especialidade',
            'especializacoes_escolhidas',
            'especialidades',
            'especializacoes',
            'instituicao',
            'prestadores'
        ), [
            'opcoes_sexo' => Prestador::opcoes_sexo,
            'instituicao_id' => $instituicao->id,
            'usuarios' => $usuarios,
        ]));
    }

    /**
     * Update the specified resource in storage.
     *f
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoCreatePrestadorRequest $request, Prestador $prestador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $prestador_instituicao = $prestador->instituicaoPrestador($instituicao->id);

        // dd($prestador_instituicao)->toArray();

        abort_unless($prestador_instituicao[0]->instituicoes_id === $instituicao->id, 403);

        $dados = $request->validated();
        DB::transaction(function () use ($request, $dados, $instituicao, $prestador_instituicao, $prestador) {
            $excessao = null;
            if(isset($dados['excessao'])){
                $excessao = collect($request->validated()['excessao'])
                    ->filter(function($excessao){
                        return !is_null($excessao['procedimento_id']);
                    })
                    ->map(function($excessao){
                        return [
                            'procedimento_id' => $excessao['procedimento_id'],
                            'prestador_faturado_id' => $excessao['prestador_faturado_id'],
                        ];
                    });
            }
         //CASO POSSUA FATURAMENTO SANCOOP IREMOS ATUALIZAR O CÓDIGO DA INSTITUIÇÃO NA SANCOOP
         if($instituicao->possui_faturamento_sancoop == 1 && $prestador->sancoop_cod_coperado == null):

            $sancoop_coperado =  $this->consultarCodCooperadoSancoop($prestador->cpf);

         else:

            $sancoop_coperado = null;

         endif;



            $dados['ativo'] = (isset($dados['ativo'])) ? 1 : 0;

            $dataPrestador = [
                "razao_social" => $dados['razao_social'],
                "numero" => $dados['numero'],
                "cep" => $dados['cep'],
                "estado" => (!empty($dados['estado'])) ? $dados['estado'] : null,
                "cidade" => $dados['cidade'],
                "bairro" => $dados['bairro'],
                "rua" => $dados['rua'],
                "sexo" => $dados['sexo'],
                "nascimento" => $dados['nascimento'],
                "cpf" => $dados['cpf'],
                "identidade" => $dados['identidade'],
                "identidade_orgao_expedidor" => $dados['identidade_orgao_expedidor'],
                "identidade_uf" => $dados['identidade_uf'],
                "identidade_data_expedicao" => $dados['identidade_data_expedicao'],
                "nome_da_mae" => $dados['nome_da_mae'],
                "nome_do_pai" => $dados['nome_do_pai'],
                "naturalidade" => $dados['naturalidade'],
                "nacionalidade" => $dados['nacionalidade'],
                "numero_cartao_sus" => $dados['numero_cartao_sus'],
                "cnpj" => $dados['cnpj'],
                // "nome" => $dados['nome'],
                'exibir_data' => $request->boolean('exibir_data'),
                'exibir_titulo_paciente' => $request->boolean('exibir_titulo_paciente'),
                'sancoop_user_coperado' => !empty($dados['sancoop_user_coperado']) ? $dados['sancoop_user_coperado'] : null,
                'email' => $dados['email'],

            ];


            /* SANCOOP */
            if(!empty($sancoop_coperado)):
                $dataPrestador['sancoop_cod_coperado'] =   $sancoop_coperado['codigo'];
                $dataPrestador['sancoop_desc_prestador'] =  $sancoop_coperado['descricao'];
            endif;
            /* FIM SANCOOP */

            /*CASO POSSUA TELEATENDIMENTO VAMOS FAZER VALIDAÇÕS DO EVIDA/BUSCARE */
            if(isset($dados['telemedicina_integrado']) && $dados['telemedicina_integrado'] == 1 && $prestador->teleatendimento_id_prestador == null):

                //CONFERINDO SE JÁ EXISTE VINCULO NO EVIDA/BUSCARE
                $prestador_evida = $this->consultaPrestadorEvida($prestador->cpf);
    
                //SE EXISTIR VAMOS APENAS ATUALIZAR
                if(!empty($prestador_evida)):
                    $dataPrestador['teleatendimento_id_prestador'] = $prestador_evida;
                //SE NÃO TEMOS QUE CRIAR
                else:
                    if($novo_prestador_id = $this->criarPrestadorEvida($prestador,$prestador_instituicao)):
                        $dataPrestador['teleatendimento_id_prestador'] = $novo_prestador_id;
                    endif;
                endif;
    
            endif;
            /*FIM EVIDA/BUSCARE*/


            $prestador->update($dataPrestador);
            $prestador->criarLogEdicao(
                $request->user('instituicao'),
                $instituicao->id
            );

            if(isset($dados['especialidades'])){
                $especialidadesInseridas = [];
                if (is_array($dados['especialidades'])) {
                    foreach ($dados['especialidades'] as $value) {

                        // if (!is_numeric($value)) {
                        //     $especialidade = Especialidade::create(['nome' => $value]);
                        //     $especialidade->criarLogCadastro(
                        //         $usuario_logado,
                        //         $instituicao->id
                        //     );
                        //     $especialidade = $especialidade->id;
                        // } else {
                            $especialidade = $value;
                        // }

                        array_push($especialidadesInseridas, $especialidade);

                        $data = [
                            'instituicoes_id' => $instituicao->id,
                            'prestadores_id' => $prestador->id,
                            'especialidade_id' => $especialidade
                        ];


                        $jaInserido = InstituicoesPrestadores::where($data)->first();
                        $data = [
                            'instituicoes_id' => $instituicao->id,
                            'prestadores_id' => $prestador->id,
                            'nome' => $dados['nome'],
                            'especialidade_id' => $especialidade,
                            'ativo' => $dados['ativo'],
                            'tipo' => $dados['tipo'],
                            'tipo_conselho_id' => $dados['tipo_conselho_id'],
                            'conselho_uf' => $dados['conselho_uf'],
                            'anestesista'=> $request->boolean('anestesista'),
                            'auxiliar' => $request->boolean('auxiliar'),
                            'vinculos' => $dados['vinculos'],
                            'carga_horaria_mensal' => $dados['carga_horaria_mensal'],
                            'pis' => $dados['pis'],
                            'pasep' => $dados['pasep'],
                            'nir' => $dados['nir'],
                            'proe' => $dados['proe'],
                            'numero_cooperativa' => $dados['numero_cooperativa'],
                            'instituicao_usuario_id' => $dados['instituicao_usuario_id'],
                            'tipo_prontuario' => $dados['tipo_prontuario'],
                            'resumo_tipo' => $dados['resumo_tipo'],
                            'crm' => $dados['crm'],
                            'telefone' => $dados['telefone'],
                            'telefone2' => $dados['telefone2'],
                            'exibir_data' => $request->boolean('exibir_data'),
                            'exibir_titulo_paciente' => $request->boolean('exibir_titulo_paciente'),
                            'whatsapp_enviar_confirm_agenda' => $request->boolean('whatsapp_enviar_confirm_agenda'),
                            'whatsapp_receber_agenda' => $request->boolean('whatsapp_receber_agenda'),
                            'telemedicina_integrado' => $request->boolean('telemedicina_integrado'),
                            // 'nome_banco' => $dados['nome_banco'],
                            // 'agencia' => $dados['agencia'],
                            // 'conta_bancaria' => $dados['conta_bancaria'],
                        ];

                        if (!$jaInserido) {

                            $InstituicoesPrestadores = InstituicoesPrestadores::create($data);

                            $InstituicoesPrestadores->criarLogCadastro(
                                $request->user('instituicao'),
                                $instituicao->id
                            );

                            if($excessao){
                                $InstituicoesPrestadores->procedimentosExcessoes()->attach($excessao);
                            }
                        }else{

                            $InstituicoesPrestadores = $jaInserido->update($data);

                            $jaInserido->criarLogEdicao(
                                $request->user('instituicao'),
                                $instituicao->id
                            );

                            if($excessao){
                                $jaInserido->procedimentosExcessoes()->detach();
                                $jaInserido->procedimentosExcessoes()->attach($excessao);
                            }
                        }
                    }
                } else {

                    $data = [
                        'instituicoes_id' => $instituicao->id,
                        'prestadores_id' => $prestador->id,
                        'especialidade_id' => $dados['especialidades'],
                    ];

                    $jaInserido = InstituicoesPrestadores::where($data)->first();

                    $data = [
                        'instituicoes_id' => $instituicao->id,
                        'prestadores_id' => $prestador->id,
                        'nome' => $dados['nome'],
                        'especialidade_id' => $dados['especialidades'],
                        'ativo' => $dados['ativo'],
                        'tipo' => $dados['tipo'],
                        'tipo_conselho_id' => $dados['tipo_conselho_id'],
                        'conselho_uf' => $dados['conselho_uf'],
                        'anestesista'=> $request->boolean('anestesista'),
                        'auxiliar' => $request->boolean('auxiliar'),
                        'vinculos' => $dados['vinculos'],
                        'carga_horaria_mensal' => $dados['carga_horaria_mensal'],
                        'pis' => $dados['pis'],
                        'pasep' => $dados['pasep'],
                        'nir' => $dados['nir'],
                        'proe' => $dados['proe'],
                        'numero_cooperativa' => $dados['numero_cooperativa'],
                        'nome_banco' => $dados['nome_banco'],
                        'agencia' => $dados['agencia'],
                        'conta_bancaria' => $dados['conta_bancaria'],
                        'instituicao_usuario_id' => $dados['instituicao_usuario_id'],
                        'tipo_prontuario' => $dados['tipo_prontuario'],
                        'resumo_tipo' => $dados['resumo_tipo'],
                        'crm' => $dados['crm'],
                        'telefone' => $dados['telefone'],
                        'telefone2' => $dados['telefone2'],
                        'exibir_data' => $request->boolean('exibir_data'),
                        'exibir_titulo_paciente' => $request->boolean('exibir_titulo_paciente'),
                        'whatsapp_enviar_confirm_agenda' => $request->boolean('whatsapp_enviar_confirm_agenda'),
                        'whatsapp_receber_agenda' => $request->boolean('whatsapp_receber_agenda'),
                    ];

                    if (!$jaInserido) {
                        $InstituicoesPrestadores = InstituicoesPrestadores::create($data);

                        $InstituicoesPrestadores->criarLogCadastro(
                            $request->user('instituicao'),
                            $instituicao->id
                        );

                        if($excessao){
                            $InstituicoesPrestadores->procedimentosExcessoes()->attach($excessao);
                        }
                    }else{
                        $InstituicoesPrestadores = $jaInserido->update($data);
                        $jaInserido->criarLogEdicao(
                            $request->user('instituicao'),
                            $instituicao->id
                        );
                        if($excessao){
                            $jaInserido->procedimentosExcessoes()->detach();
                            $jaInserido->procedimentosExcessoes()->attach($excessao);
                        }
                    }
                }

                $instituicaoPrestadorEpNull = $prestador->prestadoresInstituicoesLocal()->whereNull('especialidade_id')->first();
                if(!empty($instituicaoPrestadorEpNull)){
                    $data = [
                        'instituicoes_id' => $instituicao->id,
                        'prestadores_id' => $prestador->id,
                        'nome' => $dados['nome'],
                        'ativo' => $dados['ativo'],
                        'tipo' => $dados['tipo'],
                        'tipo_conselho_id' => $dados['tipo_conselho_id'],
                        'conselho_uf' => $dados['conselho_uf'],
                        'anestesista'=> $request->boolean('anestesista'),
                        'auxiliar' => $request->boolean('auxiliar'),
                        'vinculos' => $dados['vinculos'],
                        'carga_horaria_mensal' => $dados['carga_horaria_mensal'],
                        'pis' => $dados['pis'],
                        'pasep' => $dados['pasep'],
                        'nir' => $dados['nir'],
                        'proe' => $dados['proe'],
                        'numero_cooperativa' => $dados['numero_cooperativa'],
                        'nome_banco' => $dados['nome_banco'],
                        'agencia' => $dados['agencia'],
                        'conta_bancaria' => $dados['conta_bancaria'],
                        'instituicao_usuario_id' => $dados['instituicao_usuario_id'],
                        'tipo_prontuario' => $dados['tipo_prontuario'],
                        'resumo_tipo' => $dados['resumo_tipo'],
                        'crm' => $dados['crm'],
                        'telefone' => $dados['telefone'],
                        'telefone2' => $dados['telefone2'],
                        'exibir_data' => $request->boolean('exibir_data'),
                        'exibir_titulo_paciente' => $request->boolean('exibir_titulo_paciente'),
                        'whatsapp_enviar_confirm_agenda' => $request->boolean('whatsapp_enviar_confirm_agenda'),
                        'whatsapp_receber_agenda' => $request->boolean('whatsapp_receber_agenda'),
                    ];

                    $instituicaoPrestadorEpNull->update($data);

                    $instituicaoPrestadorEpNull->criarLogCadastro(
                        $request->user('instituicao'),
                        $instituicao->id
                    );
                }
            }else{

                $data = [
                    'instituicoes_id' => $instituicao->id,
                    'prestadores_id' => $prestador->id,
                ];


                $jaInserido = InstituicoesPrestadores::where($data)->first();
                $data = [
                    'instituicoes_id' => $instituicao->id,
                    'prestadores_id' => $prestador->id,
                    'nome' => $dados['nome'],
                    'ativo' => $dados['ativo'],
                    'tipo' => $dados['tipo'],
                    'tipo_conselho_id' => $dados['tipo_conselho_id'],
                    'conselho_uf' => $dados['conselho_uf'],
                    'anestesista'=> $request->boolean('anestesista'),
                    'auxiliar' => $request->boolean('auxiliar'),
                    'vinculos' => $dados['vinculos'],
                    'carga_horaria_mensal' => $dados['carga_horaria_mensal'],
                    'pis' => $dados['pis'],
                    'pasep' => $dados['pasep'],
                    'nir' => $dados['nir'],
                    'proe' => $dados['proe'],
                    'numero_cooperativa' => $dados['numero_cooperativa'],
                    'instituicao_usuario_id' => $dados['instituicao_usuario_id'],
                    'tipo_prontuario' => $dados['tipo_prontuario'],
                    'resumo_tipo' => $dados['resumo_tipo'],
                    'crm' => $dados['crm'],
                    'telefone' => $dados['telefone'],
                    'telefone2' => $dados['telefone2'],
                    'exibir_data' => $request->boolean('exibir_data'),
                    'exibir_titulo_paciente' => $request->boolean('exibir_titulo_paciente'),
                    'whatsapp_enviar_confirm_agenda' => $request->boolean('whatsapp_enviar_confirm_agenda'),
                    'whatsapp_receber_agenda' => $request->boolean('whatsapp_receber_agenda'),
                    // 'nome_banco' => $dados['nome_banco'],
                    // 'agencia' => $dados['agencia'],
                    // 'conta_bancaria' => $dados['conta_bancaria'],
                ];

                if (!$jaInserido) {
                    $InstituicoesPrestadores = InstituicoesPrestadores::create($data);

                    $InstituicoesPrestadores->criarLogCadastro(
                        $request->user('instituicao'),
                        $instituicao->id
                    );
                    if($excessao){
                        $InstituicoesPrestadores->procedimentosExcessoes()->attach($excessao);
                    }
                }else{
                    $InstituicoesPrestadores = $jaInserido->update($data);
                    $jaInserido->criarLogEdicao(
                        $request->user('instituicao'),
                        $instituicao->id
                    );
                    if($excessao){
                        $jaInserido->procedimentosExcessoes()->detach();
                        $jaInserido->procedimentosExcessoes()->attach($excessao);
                    }
                }
            }

            $instituicao_prestador = $prestador->instituicaoPrestador($instituicao->id)->first();
            $instituicao_prestador->overwrite($instituicao_prestador->prestadorEspecializacoes(), collect($dados)->get('especializacoes', []));
        });


        return redirect()->route('instituicao.prestadores.edit', [$prestador])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Prestador atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Prestador $prestador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $prestador_instituicao = $prestador->instituicaoPrestador($instituicao->id);

        abort_unless($prestador_instituicao[0]->instituicoes_id === $instituicao->id, 403);

        $usuario_logado = $request->user('instituicao');

        DB::transaction(function () use ($instituicao, $prestador_instituicao, $usuario_logado){

            foreach ($prestador_instituicao as $key => $value) {
                $value->delete();

                $documentos = $value->documentos()->get();

                foreach($documentos as $documento) {

                    Storage::disk('public')->delete($documento->file_path_name);

                    $documento->delete();

                    $documento->criarLogExclusao($usuario_logado, $instituicao->id);
                }

                $value->criarLogExclusao($usuario_logado, $instituicao->id);
            }

        });

        return redirect()->route('instituicao.prestadores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Prestador excluído com sucesso!'
        ]);
    }

    public function getPrestador(Request $request)
    {
        $prestador = null;
        if($request->documento=='cpf') $prestador = Prestador::where('cpf', $request->valor)->first();
        if($request->documento=='cnpj') $prestador = Prestador::where('cnpj', $request->valor)->first();
        $instituicao_prestador = null;
        if($prestador) $instituicao_prestador = InstituicoesPrestadores::query()
            ->where('prestadores_id', $prestador->id)
            ->where('instituicoes_id', Instituicao::find($request->session()->get('instituicao'))->id)
            ->first();

        $response = null;
        if($prestador && $instituicao_prestador) $response = ['status' => 0,'data' => null];
        if($prestador && !$instituicao_prestador) $response = ['status' => 1,'data' => $prestador];
        if(!$prestador) $response = ['status' => 2,'data' => null];

        return response()->json($response);

    }

    public function procedimentos(Request $request, int $id_prestador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'vincular_procedimentos');

        $where = array(
            'prestadores_id' => $id_prestador,
            'instituicoes_id' => $request->session()->get('instituicao')
        );

        $instituicao_prestador = InstituicoesPrestadores::where($where)->first();

        $prestador = Prestador::where('id', $id_prestador)->first();

        return view('instituicao.prestadores_procedimentos/lista', compact('instituicao_prestador', 'prestador'));
    }

    public function criar_vinculacao(Request $request, int $id_instituicao_prestador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'vincular_procedimentos');

        $instituicao_prestador = InstituicoesPrestadores::where('id', $id_instituicao_prestador)->first();

        $prestador = Prestador::where('id', $instituicao_prestador->prestadores_id)->first();

        return view('instituicao.prestadores_procedimentos/criar', compact('instituicao_prestador', 'prestador'));
    }


    public function getprocedimentos(Request $request)
    {

        $termo = $request->input('q');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $procedimentos = Procedimento::whereHas('procedimentoInstituicao', function ($q) use ($instituicao, $termo) {
            $q->where('descricao', 'like', "%" . $termo . "%");
            $q->where('instituicoes_id', $instituicao->id);
            $q->has('instituicaoProcedimentosConvenios');
        })->get();
        return $procedimentos->toJson();
    }

    public function getConvenios(Request $request)
    {

        $procedimento = $request->input('procedimento');

        $where = array(
            'instituicoes_prestadores_id' => $request->input('instituicao_prestador'),
            'procedimentos_id' => $request->input('procedimento')
        );
        //verifica se ja existe vinculo com esse procedimento
        $check = ProcedimentosConveniosInstituicoesPrestadores::where($where)->count();

        if ($check == 0) {
            //busca todo os convenios daquele procedimento naquela instituicao
            $convenios = Convenio::whereHas('procedimentoConvenioInstuicao', function ($q) use ($request, $procedimento) {
                $q->where('instituicoes_id', $request->session()->get('instituicao'));
                $q->where('procedimentos_id', $procedimento);
            })->get()->toArray();

            echo json_encode($convenios);
        } else {
            echo json_encode(false);
        }
    }


    public function salvar_vinculacao(Request $request)
    {

        $this->authorize('habilidade_instituicao_sessao', 'vincular_procedimentos');
        $id_procedimento = $request->input('id_procedimento');

        $where = array(
            'prestadores_id' => $request->input('id_prestador'),
            'instituicoes_id' => $request->session()->get('instituicao')
        );

        $instituicao_prestador = InstituicoesPrestadores::where($where)->first();


        $where = array(
            'procedimentos_id' => $id_procedimento,
            'instituicoes_id' => $request->session()->get('instituicao')
        );

        $procedimento_instituicao = InstituicaoProcedimentos::where($where)->first();

        $convenios = $request->input('input_procedimento');


        foreach ($convenios as $key => $value) {

            //busca o id do convenio insituiocao
            $ConveniosPrestadores = ConveniosProcedimentos::where(['convenios_id' => $value, 'procedimentos_instituicoes_id' => $procedimento_instituicao->id])->first();

            //salva relacao convenios intistuicao prestador
            $novoVinculo[] = array(
                'instituicoes_prestadores_id' => $instituicao_prestador->id,
                'procedimentos_convenios_id' => $ConveniosPrestadores->id,
                'procedimentos_id' => $id_procedimento,
            );
        }


        if (isset($novoVinculo)) {
            $usuario_logado = $request->user('instituicao');
            foreach ($novoVinculo as $key => $value) {
                $cadastro = ProcedimentosConveniosInstituicoesPrestadores::create($value);
                $cadastro->criarLogCadastroVinculacao(
                    $usuario_logado,
                    $request->session()->get('instituicao')
                );
            }
        }


        return redirect()->route('instituicao.prestadores.procedimentos', $request->input('id_prestador'))->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Convênios vinculados com sucesso!'
        ]);
    }


    public function edita_vinculacao(Request $request, Int $procedimento, Int $instituicao_prestador)
    {

        $this->authorize('habilidade_instituicao_sessao', 'vincular_procedimentos');
        $instituicao_prestador = InstituicoesPrestadores::where('id', $instituicao_prestador)->first();
        $where = array(
            'procedimentos_id' => $procedimento,
            'instituicoes_id' => $request->session()->get('instituicao')
        );
        $procedimento_instituicao = InstituicaoProcedimentos::where($where)->first();
        $prestador = Prestador::where('id', $instituicao_prestador->prestadores_id)->first();
        $procedimento = Procedimento::where('id', $procedimento)->first();
        $where = array(
            'instituicoes_prestadores_id' => $instituicao_prestador->id,
            'procedimentos_id' => $procedimento->id
        );
        $convenios = Convenio::whereHas('procedimentoConvenioInstuicao', function ($query) use ($request, $procedimento_instituicao) {
            $query->where('procedimentos_instituicoes_convenios.procedimentos_instituicoes_id', $procedimento_instituicao->id);
        })->with(['procedimentoConvenioInstuicao' => function ($query) use ($procedimento) {
            $query->where('procedimentos_id', $procedimento->id);
        }])->orderBy('nome', 'asc')->get()->toArray();
        $convenios_ativo = ProcedimentosConveniosInstituicoesPrestadores::where($where);
        $arrayconvenios_ativo = array();
        foreach ($convenios_ativo->get()->toArray() as $key => $value) {
            $arrayconvenios_ativo[$value['procedimentos_convenios_id']] = $value['id'];
        }

        // dd($convenios);

        return view('instituicao.prestadores_procedimentos/editar', compact('instituicao_prestador', 'prestador', 'procedimento', 'convenios', 'convenios_ativo', 'arrayconvenios_ativo'));
    }


    public function salvar_editar_vinculacao(Request $request)
    {

        $this->authorize('habilidade_instituicao_sessao', 'vincular_procedimentos');

        $convenios = $request->input('input_procedimento');
        $instituicoes_prestadores_id = $request->input("instituicao_prestador");
        $procedimentos_id = $request->input("procedimento");
        $desativados = $request->input("desativados");
        $instituicao_prestador = InstituicoesPrestadores::where('id', $instituicoes_prestadores_id)->first();
        $desativados = explode(",", $desativados);


        //desativa as vinculacoes
        $vinculacoes = ProcedimentosConveniosInstituicoesPrestadores::whereIn('id', $desativados)
            ->where('instituicoes_prestadores_id', $instituicao_prestador->id)
            ->where('procedimentos_id', $procedimentos_id)->get();
        $usuario_logado = $request->user('instituicao');
        foreach ($vinculacoes as $value) {
            $value->delete();
            $value->criarLogExclusaoVinculacao(
                $usuario_logado,
                $request->session()->get('instituicao')
            );
        }


        $where = array(
            'procedimentos_id' => $procedimentos_id,
            'instituicoes_id' => $request->session()->get('instituicao')
        );
        $procedimento_instituicao = InstituicaoProcedimentos::where($where)->first();


        $where = array(
            'instituicoes_prestadores_id' => $instituicoes_prestadores_id,
            'procedimentos_id' => $procedimentos_id,
        );

        if ($convenios) {
            foreach ($convenios as $key => $value) {
                //busca o id do convenio insituiocao
                $ConveniosPrestadores = ConveniosProcedimentos::where(['convenios_id' => $value, 'procedimentos_instituicoes_id' => $procedimento_instituicao->id])->first();
                //salva relacao convenios intistuicao prestador
                $where = array(
                    'instituicoes_prestadores_id' => $instituicao_prestador->id,
                    'procedimentos_convenios_id' => $ConveniosPrestadores->id,
                    'procedimentos_id' => $procedimentos_id,
                );
                $ativos = ProcedimentosConveniosInstituicoesPrestadores::where($where)->count();
                if (!$ativos) {
                    $novoVinculo[] = array(
                        'instituicoes_prestadores_id' => $instituicao_prestador->id,
                        'procedimentos_convenios_id' => $ConveniosPrestadores->id,
                        'procedimentos_id' => $procedimentos_id,
                    );
                }
            }
        }

        if (isset($novoVinculo)) {

            foreach ($novoVinculo as $key => $value) {
                $cadastro = ProcedimentosConveniosInstituicoesPrestadores::create($value);

                $cadastro->criarLogCadastroVinculacao(
                    $usuario_logado,
                    $request->session()->get('instituicao')
                );
            }
        }

        return redirect()->route('instituicao.prestadores.procedimentos', $instituicao_prestador->prestadores_id)->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Convênios vinculados com sucesso!'
        ]);
    }

    /**
     * Busca em instituicao_prestadores, logo o id é da relação e não
     * do prestador
     * @param string search Enviado na query, a busca que será feita no
     * nome do prestador que está vinculado à instituição do usuario logado
     * @param int id Enviado na query, quando não nulo, busca por id ao
     * invés de por nome, ainda retorna o prestador e não um array
     * @return mixed Caso id seja nulo, retorna um array com 0 ou mais InsitituicaoPrestadores
     * com um campo extra (nome), caso o id não seja nulo, retorna o objeto instituicaoPrestador
     * relativo ao id caso este exista, do contrário retorna vazio
     */
    public function buscaPrestadorInstituicao(Request $request)
    {
        $busca = $request->get('search', '');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $query = InstituicoesPrestadores::with([
            'prestador'
        ])->where('instituicoes_id', $instituicao->id)
        ->whereHas('prestador', function(Builder $query) use ($busca) {
            $query->where('nome', 'like', "%{$busca}%");
        });

        if($request->get('id', false)) {
            $query->where('prestadores_id', $request->get('id'));
        }
        
        $results = $query->simplePaginate(30);
        return response()->json([
            "results" => $results->items(),
            "pagination" => array(
                "more" => !empty($results->nextPageUrl()),
            )
        ]);
    }

    /**
     * Busca as especializacoes de um prestador a partir de suas especialidades
     */
    public function getEspecializacoes(Request $request)
    {
        if(empty($request->get('ids')))
            return response()->json([]);

       $especializacoes = Especializacao::join('especializacoes_especialidade', 'especializacoes_especialidade.especializacoes_id', '=', 'especializacoes.id')
                                        ->select('especializacoes.*')
                                        ->whereIn('especialidades_id', $request->get('ids', []))
                                        ->get();

        return response()->json($especializacoes);
    }

    /**
     * Busca as especialidades do prestador por nome
     * @param Prestador prestador O Id do prestador que será filtrado (Opcional)
     * @param string search A string de busca (Opcional)
     */
    public function getEspecialidades(Request $request)
    {
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));

        $especialidades = Especialidade::select('especialidades.*')
            ->distinct()
            ->join('instituicoes_prestadores', 'instituicoes_prestadores.especialidade_id','especialidades.id')
            ->where('instituicoes_prestadores.instituicoes_id', '=', $instituicao->id);
        if(!empty($request->get('prestador'))) {
            $especialidades->where('prestadores_id', $request->get('prestador'));
        }

        return response()->json($especialidades->where('especialidades.descricao','like', "%{$request->get('search')}%")->get());
    }



    public function consultarCodCooperadoSancoop($cpf)
    {


        //OBTENDO TOKEN DE AUTORIZAÇÃO
        // CODIGOS INSTITUICOES NA SANCOOP - 79 angios - 3623 santa casa
        $parameters['ID'] = 181;
        $parameters['Hash'] = 'TWVkLlNpb3MyMkAhQCM';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://zltecnologia.ddns.net:8902/Token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        $headers = [
            "Content-Type: application/json"
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        /* CASO TENHA AUTENTICADO */
        if(!empty($return['result']->token)):

            //CONSULTANDO API SANCOOP PARA VERIFICAR SE EXISTE O COOPERADO
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://zltecnologia.ddns.net:8902/Cooperado?CPF='.$cpf.'');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

            $headers = [
                "Content-Type: application/json",
                "Authorization: Bearer {$return['result']->token}"
            ];

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $return['result'] = json_decode(curl_exec($ch));
            $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);


            // echo '<pre>';
            // print_r($return);
            // exit;

            if(!empty($return['result']->cooperado)):
                $result_api['codigo'] =    $return['result']->cooperado[0]->CodFornecedor;
                $result_api['descricao'] = $return['result']->cooperado[0]->Nome;
                return $result_api;
            else:
                return false;
            endif;

        endif;

    }

    public function consultaPrestadorEvida($cpf)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.v2.doutoraovivo.com.br/professional/'.$cpf.'');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        $headers = [
            "Content-Type: application/json",
            "x-api-key: cCuWVa12Xo2ZOml2JMFuT4o3DwvKB3Ke3O79L2zH"
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        // echo '<pre>';
        // print_r($return);
        // exit;

        //47ad4603-7334-4126-8d2a-d546f5e4da92

       if(!empty($return['result']) && !empty($return['result']->id)):

        return $return['result']->id;

       else:

        return false;

       endif;


    }

    /* APIS EVIDA / BUSCARE */

    public function criarPrestadorEvida($prestador,$prestador_instituicao)
    {

        // echo '<pre>';
        // print_r($prestador);
        // exit;

        //FORMATANDO CPF
        $cpf = str_replace('.','',$prestador->cpf);
        $cpf = str_replace('-','',$cpf);

        //FORMATANDO TELEFONE
        $telefone = str_replace('(','',$prestador_instituicao[0]->telefone);
        $telefone = str_replace(')','',$telefone);
        $telefone = str_replace(' ','',$telefone);
        $telefone = str_replace('-','',$telefone);

        //CONSELHO ***************VERIFICAR COM BUSCARE SE ACEITA TODOS DO ASA
        if($prestador_instituicao[0]->tipo_conselho_id == 1):
            $conselho = 'CRM';
        endif;

        //SEXO
        if($prestador->sexo == 1):
            $sexo = 'M';
        else:
            $sexo = 'F';
        endif;


        $params['id'] = $prestador->cpf;
        $params['name'] = $prestador->nome;
		$params['email'] = $prestador->email;
		$params['cpf'] = $cpf ;
		$params['license_number'] = $prestador_instituicao[0]->crm;
		$params['license_council'] = $conselho;
		$params['license_region'] = $prestador_instituicao[0]->conselho_uf;
		$params['birth_date'] = $prestador->nascimento;
		$params['gender'] = $sexo;
		$params['cell_phone'] = $telefone;


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.v2.doutoraovivo.com.br/professional/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    
        $headers = [
            "Content-Type: application/json",
            "x-api-key: cCuWVa12Xo2ZOml2JMFuT4o3DwvKB3Ke3O79L2zH"
        ];
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        curl_close($ch);

        if(!empty($return['result']) && !empty($return['result']->id)):

        return $return['result']->id;

        else:

        return false;

        endif;

    }

    /* FIM APIS EVIDA / BUSCARE */


}
