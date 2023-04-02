<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faturamento\ImportarFaturamentoRequest;
use App\Http\Requests\VinculoTuss\CriarVinculoTussRequest;
use App\Imports\VinculoTussImport;
use App\Instituicao;
use App\VinculoTuss;
use App\VinculoTussTerminologia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class VinculosTuss extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_vincular_tuss');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $terminologias = VinculoTussTerminologia::all();

        $tabelaTuss = $instituicao->vinculoTuss()
            ->orderBy('terminologia_id', 'ASC')
            ->orderBy('data_vigencia', 'DESC')
            ->orderBy('termo', 'ASC')
            ->paginate(25);

        return view('instituicao.vinculo_tuss.lista', \compact('tabelaTuss', 'terminologias'));
    }

    public function selecionarImportacao(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'importar_vincular_tuss');
        
        $terminologias = VinculoTussTerminologia::all();

        return view('instituicao.vinculo_tuss.importar', \compact('terminologias'));
    }

    public function importar(ImportarFaturamentoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'importar_vincular_tuss');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $terminologia = VinculoTussTerminologia::find($request->get('terminologia_id'));

        if ($this->checkIfHeaderIsInvalid($terminologia)) {
            return redirect()->back()->withErrors([
                'arquivo' => 'Cabeçalho diferente da Terminologia selecionada!'
            ]);
        }

        set_time_limit(0);

        Excel::import(new VinculoTussImport($instituicao, $terminologia), $request->file('arquivo'));

        $instituicao->criarLog($request->user('instituicao'), 'Vinculo tuss importação');

        return redirect()->route('instituicao.vinculoTuss.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Faturamentos importados com sucesso!'
        ]);
    }

    private function checkIfHeaderIsInvalid(VinculoTussTerminologia $terminologia)
    {
        HeadingRowFormatter::default('none');

        try {
            return $terminologia->cabecalho != implode(";", (new HeadingRowImport)->toArray(request()->file('arquivo'))[0][0]);
        } catch (\Throwable $th) {
            return true;
        }
    }

    public function getVinculoTuss(Request $request)
    {
        if ($request->ajax()) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $termo = ($request->input('q')) ? $request->input('q') : '';
            // dd($request->page);
            $pacientes = $instituicao->vinculoTuss()->search($termo)->simplePaginate(100);

            $morePages = true;
            if (empty($pacientes->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $pacientes->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($pacientes->per_page());
            return response()->json($results);
        }
    }

    public function destroy(Request $request, VinculoTuss  $vinculo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_vincular_tuss');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $vinculo->instituicao_id, 403);
        DB::transaction(function () use ($vinculo, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $vinculo->delete();
            $vinculo->criarLogExclusao($usuario_logado, $instituicao);

            return $vinculo;
        });

        return redirect()->route('instituicao.vinculoTuss.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Vinculo tuss excluído com sucesso!'
        ]);
    }
}
