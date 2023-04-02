<?php

namespace App\Http\Controllers\Instituicao;

use App\ConfiguracaoFiscal;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfiguracoesFiscais\CreateConfiguracaoFiscalRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConfiguracoesFiscais extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_configuracao_fiscal');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $configuracao = $instituicao->configuracaoFiscal()->first();
        
        if($configuracao === null){
            return redirect()->route('instituicao.configuracaoFiscal.create');
        }else{
            return view('instituicao.configuracoes_fiscais.lista', compact('configuracao'));
        }       

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_configuracao_fiscal');

        $regimes = ConfiguracaoFiscal::regime();
        return view('instituicao.configuracoes_fiscais.criar', compact('regimes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateConfiguracaoFiscalRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_configuracao_fiscal');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $dados['aliquota_iss'] = str_replace(",", ".", $dados['aliquota_iss']);
            $dados['p_pis'] = str_replace(",", ".", $dados['p_pis']);
            $dados['p_cofins'] = str_replace(",", ".", $dados['p_cofins']);
            $dados['p_inss'] = str_replace(",", ".", $dados['p_inss']);
            $dados['p_ir'] = str_replace(",", ".", $dados['p_ir']);

            if(!empty($request->file('certificado_upload'))){
                $arquivo_nome = $instituicao->id."_".$instituicao->nome."_".str_replace([" ",".","/","-"], ["","","",""], $instituicao->cnpj);
                $arquivo_venda = $arquivo_nome.".".$request->certificado_upload->getClientOriginalExtension();

                $path = Storage::disk('public')->putFileAs(
                    'certificados/', $request->file('certificado_upload'), $arquivo_venda
                );

                $dados['certificado'] = $path;
            }

            $configuracao = $instituicao->configuracaoFiscal()->create($dados);
            $configuracao->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.configuracaoFiscal.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'configuração criada com sucesso!'
        ]);
        dd($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ConfiguracaoFiscal $configuracao_fiscal)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_configuracao_fiscal');

        $regimes = ConfiguracaoFiscal::regime();
        // $pefilCidade = new NotasFiscais;
        // $pefilCidade = $pefilCidade->perfilPrefeitura(Instituicao::find($request->session()->get("instituicao")));
        
        return view('instituicao.configuracoes_fiscais.editar', compact('regimes', 'configuracao_fiscal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateConfiguracaoFiscalRequest $request, ConfiguracaoFiscal $configuracao_fiscal)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_configuracao_fiscal');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao, $configuracao_fiscal){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $dados['aliquota_iss'] = str_replace(",", ".", $dados['aliquota_iss']);
            $dados['p_pis'] = str_replace(",", ".", $dados['p_pis']);
            $dados['p_cofins'] = str_replace(",", ".", $dados['p_cofins']);
            $dados['p_inss'] = str_replace(",", ".", $dados['p_inss']);
            $dados['p_ir'] = str_replace(",", ".", $dados['p_ir']);

            // dd($dados);

            if(!empty($request->file('certificado_upload'))){
                $arquivo_nome = $instituicao->id."_".$instituicao->nome."_".str_replace([" ",".","/","-"], ["","","",""], $instituicao->cnpj);
                $arquivo_venda = $arquivo_nome.".".$request->certificado_upload->getClientOriginalExtension();

                $path = Storage::disk('public')->putFileAs(
                    'certificados/', $request->file('certificado_upload'), $arquivo_venda
                );

                $dados['certificado'] = $path;
            }

            $configuracao_fiscal->update($dados);
            $configuracao_fiscal->criarLogEdicao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.configuracaoFiscal.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'configuração alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
