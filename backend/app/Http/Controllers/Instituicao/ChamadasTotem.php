<?php

namespace App\Http\Controllers\Instituicao;

use App\ChamadaTotem;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChamadasTotem\ChamarRequest;
use Illuminate\Http\Request;
use App\Instituicao;
use App\SenhaTriagem;
use Illuminate\Support\Facades\DB;

class ChamadasTotem extends Controller
{
    /**
     * Rota para chamar senha a partir de requisição
     * @param SenhaTriagem $senha A senha a ser chamada
     * @param int $origem O destino da senha (origem do chamado)
     * @param string $local A informação adicional do local da senha
     * e.g.: $local = 3 quando $origem = Guichê, indica que o portador
     * da senha deve ir ao guichê 3
     */
    public function chamar(ChamarRequest $request)
    {
        $dados = collect($request->validated());
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $senha = SenhaTriagem::findOrFail($dados->get('senha'));
        abort_unless($instituicao->id == $senha->instituicao()->first()->id && $this->authorize('habilidade_instituicao_sessao', 'chamar_senhas'), 403);
        ChamadaTotem::chamarSenha($senha, $dados->get('origem', 0), $dados->get('local', '') ?? '', !empty($dados->get('completada')) ? true : false);
    }

    /**
     * Rota para comcluir uma chamada
     * @param SenhaTriagem $senha A senha cuja chamada será concluída
     */
    public function concluir(Request $request, SenhaTriagem $senha)
    {
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id == $senha->instituicoes_id && $this->authorize('habilidade_instituicao_sessao', 'chamar_senhas'), 403);
        ChamadaTotem::completarChamada($senha);
        
        if($request->method() == 'GET') {
            return redirect()->back()->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Chamada concluída com sucesso!'
            ]);
        }
    }
}
