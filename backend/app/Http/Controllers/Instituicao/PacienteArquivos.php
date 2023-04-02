<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\PacienteArquivo\CriarPacienteArquivoRequest;
use App\Instituicao;
use App\PacienteArquivo;
use App\PacientePasta;
use App\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PacienteArquivos extends Controller
{
    public function index(Request $request, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_arquivo');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);

        $pastas = $paciente->pastas()->get();

        return view('instituicao.prontuarios.arquivos.index', \compact('pastas'));
    }

    public function getModalUpload(Request $request, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);

        $pastas = $paciente->pastas()->get();

        return view('instituicao.prontuarios.arquivos.modalUpload', \compact('pastas'));
    }

    public function novoArquvo(CriarPacienteArquivoRequest $request, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);

        DB::transaction(function() use($paciente, $request, $instituicao){
            $dados = $request->validated();
            
            $usuario_logado = $request->user('instituicao');

            $pasta = $this->findBySlug($dados['nome_pasta'], $usuario_logado, $paciente);
            // $data['paciente_pasta_id'] = $pasta->id;

            $arquivo_nome = str_replace(' ', '_',$dados['nome_arquivo']);
        
            for ($i=0; $i < count($dados['arquivo_upload']); $i++) { 
                $arquivo_venda = $arquivo_nome.'_'.$i.".".$request->arquivo_upload[$i]->extension();
    
                $path = Storage::disk('public')->putFileAs(
                    'pacientes/'.$pasta->nome, $request->file('arquivo_upload')[$i], $arquivo_venda
                );
    
                $data['usuario_id'] = $usuario_logado->id;
                $data['nome'] = $dados['nome_arquivo'].' '.$i;
                $data['diretorio'] = $path;
    
                $arquivo = $pasta->arquivo()->create($data);
                $arquivo->criarLogCadastro($usuario_logado, $instituicao->id);
            }

        });

        $pastas = $paciente->pastas()->get();

        return view('instituicao.prontuarios.arquivos.lista_pasta', \compact('pastas'));
    }

    function findBySlug($nome, $usuario_logado, $paciente) {
        $slug = Str::slug($nome, '-', 'pt_BR');
        return PacientePasta::firstOrCreate([
            'slug' => $slug,
            'usuario_id' => $usuario_logado->id,
            'paciente_id' => $paciente->id,
        ], [
            'nome' => $nome,
        ]);
    }

    public function getArquivosPasta(Request $request, Pessoa $paciente, PacientePasta $pasta)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($paciente->id === $pasta->paciente_id, 403);

        $arquivos = $pasta->arquivo()->get();

        return view('instituicao.prontuarios.arquivos.lista_arquivos', \compact('paciente', 'arquivos'));
    }
    
    public function excluirPasta(Request $request, Pessoa $paciente, PacientePasta $pasta)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($paciente->id === $pasta->paciente_id, 403);

        if(count($pasta->arquivo) == 0){
            DB::transaction(function() use($request, $pasta, $instituicao){
                $pasta->delete();
                $usuario_logado = $request->user('instituicao');
                $pasta->criarLogExclusao($usuario_logado, $instituicao->id);
            });
        }

        return response()->json(true);
    }

    public function baixarArquivo(Request $request, Pessoa $paciente, PacienteArquivo $arquivo)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($paciente->id === $arquivo->pasta->paciente_id, 403);

        if(Storage::disk('public')->exists($arquivo->diretorio)){
            return Storage::disk('public')->download($arquivo->diretorio);
        }else if(Storage::exists($arquivo->diretorio)){
            return Storage::download($arquivo->diretorio);
        }

    }
    
    public function excluirArquivo(Request $request, Pessoa $paciente, PacienteArquivo $arquivo)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($paciente->id === $arquivo->pasta->paciente_id, 403);

        DB::transaction(function() use($request, $arquivo, $instituicao){
            
            if(Storage::disk('public')->exists($arquivo->diretorio)){
                Storage::disk('public')->delete($arquivo->diretorio);
            }else if(Storage::exists($arquivo->diretorio)){
                Storage::delete($arquivo->diretorio);
            }

            $arquivo->delete();
            $usuario_logado = $request->user('instituicao');
            $arquivo->criarLogExclusao($usuario_logado, $instituicao->id);
        });

        $pastas = $paciente->pastas()->get();

        return view('instituicao.prontuarios.arquivos.lista_pasta', \compact('pastas'));
    }

    public function visualizarArquivo(Request $request, Pessoa $paciente, PacienteArquivo $arquivo)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($paciente->id === $arquivo->pasta->paciente_id, 403);
        
        return view('instituicao.prontuarios.arquivos.modal_visualizar', \compact('arquivo', 'paciente'));
    }


}
