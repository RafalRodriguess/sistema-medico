<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\Convenios\CriarRequestConvenio;
use App\Http\Requests\Convenios\EditarRequestConvenio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\{
    Convenio,
    ApresentacaoConvenio,
    ConveniosControleRetorno,
    ConveniosProcedimentos,
    Instituicao,
    Pessoa,
    Procedimento,
    VersaoTiss
};
use App\Http\Requests\Convenios\BuscarConveniosProcedimentos;
use App\Http\Requests\Convenios\VincularPrestadorRequest;

class Convenios extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_convenio');

        return view('instituicao.convenios/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_convenio');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $apresentacoes = $instituicao->apresentacaoConvenios()->get();
        $fornecedores = $instituicao->instituicaoPessoas()->where('tipo', '=', 3)->get();
        $procedimentos = $instituicao->procedimentos()->get();

        return view('instituicao.convenios/criar', [
            'apresentacoes' => $apresentacoes,
            'opcoes_tipo_convenio' => Convenio::opcoes_tipo_convenio,
            'opcoes_guia_obrigatoria' => Convenio::opcoes_guia_obrigatoria,
            'opcoes_forma_agrupamento' => Convenio::opcoes_forma_agrupamento,
            'opcoes_fonte_de_remuneracao' => Convenio::opcoes_fonte_de_remuneracao,
            'opcoes_tipo_cobranca_oncologia' => Convenio::opcoes_tipo_cobranca_oncologia,
            'opcoes_campos_retorno' => ConveniosControleRetorno::opcoes_campos_retorno,
            'tipos_grupos_atendimento' => ConveniosControleRetorno::tipos_grupos_atendimento,
            'fornecedores' => $fornecedores,
            'procedimentos' => $procedimentos,
            'versoes_tiss' => VersaoTiss::all(),
            'instituicao' => $instituicao
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarRequestConvenio $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_convenio');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = collect($request->validated());

        $dados['imagem'] = $this->uploadImage($request);

        if ($instituicao->convenios()->where('nome', $dados->get('nome'))->exists()) {
            throw ValidationException::withMessages([
                'nome' => ['Convênio já cadastrado'],
            ]);
        }

        $dados['ativo'] = (!empty($dados['ativo'])) ? 1 : 0;
        $dados['carteirinha_obg'] = $request->boolean('carteirinha_obg');
        // if(array_key_exists('possui_terceiros', $dados)){
            $dados['possui_terceiros'] = $request->boolean('possui_terceiros');
        // }

        DB::transaction(function () use ($request, $dados, $instituicao) {

            $convenio = $instituicao->convenios()->create($dados->except(['configuracoes_retorno', 'excecoes'])->toArray());
            $convenio->overwrite($convenio->controlesRetorno(), $dados->get('configuracoes_retorno', []));
            $convenio->overwrite($convenio->excecoes(), $dados->get('excecoes', []));

            $usuario_logado = $request->user('instituicao');

            $convenio->criarLogCadastro(
                $usuario_logado,
                $instituicao->id
            );

            return $convenio;
        });

        return redirect()->route('instituicao.convenio.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Convênio criado com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Convenio $convenio)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_convenio');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($convenio->instituicao_id === $instituicao->id, 403);
        $apresentacoes = $instituicao->apresentacaoConvenios()->get();
        $fornecedores = $instituicao->instituicaoPessoas()->where('tipo', '=', 3)->get();
        $procedimentos = $instituicao->procedimentos()->get();

        return view('instituicao.convenios.editar', [
            'convenio' => $convenio,
            'apresentacoes' => $apresentacoes,
            'opcoes_tipo_convenio' => Convenio::opcoes_tipo_convenio,
            'opcoes_guia_obrigatoria' => Convenio::opcoes_guia_obrigatoria,
            'opcoes_forma_agrupamento' => Convenio::opcoes_forma_agrupamento,
            'opcoes_fonte_de_remuneracao' => Convenio::opcoes_fonte_de_remuneracao,
            'opcoes_tipo_cobranca_oncologia' => Convenio::opcoes_tipo_cobranca_oncologia,
            'opcoes_campos_retorno' => ConveniosControleRetorno::opcoes_campos_retorno,
            'tipos_grupos_atendimento' => ConveniosControleRetorno::tipos_grupos_atendimento,
            'fornecedores' => $fornecedores,
            'procedimentos' => $procedimentos,
            'instituicao' => $instituicao,
            'versoes_tiss' => VersaoTiss::all(),
        ]);
    }

    public function show()
    {
        # code...
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarRequestConvenio $request, Convenio $convenio)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_convenio');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($convenio->instituicao_id === $instituicao->id, 403);
        $dados = collect($request->validated());

        $dados['imagem'] = $this->uploadImage($request, $convenio);

        $dados['ativo'] = (!empty($dados['ativo'])) ? 1 : 0;
        $dados['carteirinha_obg'] = $request->boolean('carteirinha_obg');
        $dados['aut_obrigatoria'] = $request->boolean('aut_obrigatoria');
        // if(array_key_exists('possui_terceiros', $dados)){
            $dados['possui_terceiros'] = $request->boolean('possui_terceiros');
        // }
        // $dados['aut_obrigatoria'] = $request->boolean('aut_obrigatoria');
        // dd($dados);
        if ($instituicao->convenios()->where('id', '<>', $convenio->id)->where('nome', $dados->get('nome'))->exists()) {
            throw ValidationException::withMessages([
                'nome' => ['Convênio já cadastrado'],
            ]);
        }


        DB::transaction(function () use ($request, $convenio, $dados, $instituicao) {

            //CASO POSSUA FATURAMENTO SANCOOP IREMOS ATUALIZAR O CÓDIGO DA INSTITUIÇÃO NA SANCOOP
            if ($instituicao->possui_faturamento_sancoop == 1 && $convenio->sancoop_cod_convenio == null) :

                $sancoop_convenio =  $this->consultarCodConvenioSancoop($convenio->cnpj);
                $dados['sancoop_cod_convenio'] =  $sancoop_convenio['codigo'];
                $dados['sancoop_desc_convenio'] =  $sancoop_convenio['descricao'];

            else :

                $sancoop_convenio = null;

            endif;

            $convenio->update($dados->except(['configuracoes_retorno', 'excecoes'])->toArray());
            $convenio->overwrite($convenio->controlesRetorno(), $dados->get('configuracoes_retorno', []));
            $convenio->overwrite($convenio->excecoes(), $dados->get('excecoes', []));
            $usuario_logado = $request->user('instituicao');
            $convenio->criarLogEdicao(
                $usuario_logado,
                $instituicao->id
            );

            return $convenio;
        });


        //return redirect()->route('instituicaoistradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('instituicao.convenio.edit', [$convenio])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Convênio atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Convenio $convenio)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_convenio');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($convenio->instituicao_id === $instituicao_id, 403);

        DB::transaction(function () use ($request, $convenio, $instituicao_id) {
            $convenio->delete();

            $usuario_logado = $request->user('instituicao');
            $convenio->criarLogExclusao(
                $usuario_logado,
                $instituicao_id
            );

            return $convenio;
        });

        return redirect()->route('instituicao.convenio.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Convênio excluído com sucesso!'
        ]);
    }

    public function consultarCodConvenioSancoop($cnpj)
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
        if (!empty($return['result']->token)) :

            //TRATANDO CNPJ PARA CONSULTA
            $cnpj_consulta = str_replace('.', '', $cnpj);
            $cnpj_consulta = str_replace('/', '', $cnpj_consulta);
            $cnpj_consulta = str_replace('-', '', $cnpj_consulta);

            //CONSULTANDO API SANCOOP PARA VERIFICAR SE EXISTE O COOPERADO
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://zltecnologia.ddns.net:8902/Convenio?CNPJ=' . $cnpj_consulta . '');
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

            if (!empty($return['result']->convenio)) :

                $result_api['codigo'] =    $return['result']->convenio[0]->CodConvenio;
                $result_api['descricao'] = $return['result']->convenio[0]->Convenio;
                return $result_api;

            else :
                return false;
            endif;

        endif;
    }

    public function convenioPrestador(VincularPrestadorRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'vincular_convenio_agendas');

        $dados = $request->validated();
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $convenio = $instituicao->convenios()->where('id', $dados['convenio_id'])->first();

        $prestadores = $instituicao->prestadoresEspecialidades()->where(function ($q) use ($dados) {
            $q->whereIn('id', $dados['prestadores']);
        })
            ->whereHas('agenda')->with('agenda')->get();

        DB::transaction(function () use ($prestadores, $request, $convenio, $instituicao, $dados) {
            $convenioData = [
                'convenio_id' => $convenio->id
            ];
            $usuario_logado = $request->user('instituicao');

            foreach ($prestadores as $key => $value) {
                foreach ($value->agenda as $key => $agenda) {
                    $diasUnicosArray = [];
                    if ($agenda->tipo == 'unico') {
                        $unicos = JSON_DECODE($agenda->dias_unicos);
                        foreach ($unicos as $key => $dia) {
                            if (!in_array($convenio->id, $dia->convenio_id_unico)) {
                                $dia->convenio_id_unico[] = "{$convenio->id}";
                            }
                            $diasUnicosArray[] = $dia;
                        }
                        $agenda->update(['dias_unicos' => json_encode($diasUnicosArray)]);
                    } else {

                        $convenioVinculado = $agenda->convenios()->where('id', $convenio->id)->first();
                        if (empty($convenioVinculado)) {
                            $agenda->convenios()->attach($convenioData);
                        }
                    }
                }
            }

            $convenio->criarLog($usuario_logado, 'Vinculação do convenio aos prestadores', $dados, $instituicao->id);
        });
    }

    public function buscarConvenios(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $busca = $request->get('search', '');

        $convenios_instituicao = $instituicao->convenios()
            ->where('convenios.nome', 'like', "%$busca%")
            ->whereNull('deleted_at')
            ->simplePaginate(50);

        return response()->json([
            'result' => true,
            'convenios' => $convenios_instituicao,
            'next_page' => $convenios_instituicao->nextPageUrl()
        ]);
    }

    private function uploadImage($request, $convenio = null)
    {
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            if ($convenio && Storage::cloud()->exists($convenio->imagem)) {
                Storage::cloud()->delete($convenio->imagem);
            }

            $nameFile = null;
            $name = uniqid(date('HisYmd'));
            $extension = $request->imagem->getClientOriginalExtension();
            $nameFile = "{$name}.{$extension}";
            $upload = $request->imagem->storeAs('/instituicoes/convenios', $nameFile, config('filesystems.cloud'));

            if (!$upload) {
                return redirect()
                    ->back()
                    ->with('error', 'Falha ao fazer upload')
                    ->withInput();
            }

            return $upload;
        }

        return $convenio->imagem ?? null;
    }
}
