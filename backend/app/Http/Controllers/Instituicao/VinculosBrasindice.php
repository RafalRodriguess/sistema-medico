<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faturamento\Brasindice;
use App\Imports\VinculoBrasindiceImport;
use App\Repositories\VinculoBrasindiceRepository;
use App\VinculoBrasindice;
use App\VinculoBrasindiceImportacao;
use App\VinculoBrasindiceTipo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class VinculosBrasindice extends Controller
{
    protected $vinculoBrasindiceRepository;

    public function __construct(VinculoBrasindiceRepository $vinculoBrasindiceRepository)
    {
        $this->vinculoBrasindiceRepository = $vinculoBrasindiceRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_vincular_brasindice');

        $tipos = VinculoBrasindiceTipo::all();

        $vinculos = $this->vinculoBrasindiceRepository->get($request);
        
        $filters = $request->all();

        return view('instituicao.vinculos_brasindice.index', \compact('tipos', 'vinculos', 'filters'));
    }

    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_vincular_brasindice');

        $tipos = VinculoBrasindiceTipo::all();

        return view('instituicao.vinculos_brasindice.create', \compact('tipos'));
    }

    public function store(Brasindice $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_vincular_brasindice');

        $importacao = VinculoBrasindiceImportacao::create([
            'instituicao_id' => $request->session()->get("instituicao"),
            'usuario_id' => $request->user('instituicao')->id,
            'tipo_id' => $request->get('tipo_id'),
            'edicao' => $request->get('edicao'),
            'vigencia' => $request->get('vigencia')
        ]);

        $arquivoCSV = self::getTemFileCSV($request->file('arquivo'), $importacao);

        set_time_limit(0);

        Excel::import(new VinculoBrasindiceImport($importacao), $arquivoCSV);

        unlink($arquivoCSV);

        return redirect()->route('instituicao.vinculoBrasindice.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Medicamentos importados com sucesso!'
        ]);
    }

    public function destroy()
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_vincular_brasindice');
    }

    private static function getTemFileCSV(\Illuminate\Http\UploadedFile $file, $importacao): string
    {
        try {
            return self::getTempFile($file, $importacao);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'arquivo' => "Erro, não foi possível ler o arquivo"
            ]);
        }
    }

    private static function getTempFile(\Illuminate\Http\UploadedFile $file, VinculoBrasindiceImportacao $importacao): string
    {
        $fileName = 'temp_upload_brasindice_' . time() . '.txt';
        $fileUploadPath = storage_path('app/temp');

        $file = $file->move($fileUploadPath, $fileName);

        return self::getDataFile("$fileUploadPath/$fileName", $importacao);
    }

    private static function getDataFile(string $filePatchTXT, VinculoBrasindiceImportacao $importacao): string
    {
        $fileTXT = fopen($filePatchTXT, 'r');

        $filePatchCSV = storage_path('app/temp') . "/temp_brasindice_import_" . time() . ".csv";

        $fileCSV = fopen($filePatchCSV, 'w');

        fputcsv($fileCSV, [
            'importacao_id',
            'instituicao_id',
            'tipo_id',
            'laboratorio_cod',
            'laboratorio',
            'medicamento_cod',
            'medicamento',
            'apresentacao_cod',
            'apresentacao',
            'preco_medicamento',
            'qtd_fracionamento',
            'tipo_preco',
            'valor_fracionado',
            'edicao',
            'ipi_medicamento',
            'flag_pis_confins',
            'ean',
            'tiss',
            'flag_generico',
            'tuss'
        ]);

        for ($key = 0; $row = fgets($fileTXT); $key++) {
            $dataRow = [
                'importacao_id'       => $importacao->id,
                'instituicao_id'      => $importacao->instituicao_id,
                'tipo_id'             => $importacao->tipo_id,
                'laboratorio_cod'     => trim(substr($row, 0, 4)) ?? null,
                'laboratorio'         => trim(mb_convert_encoding(substr($row, 4, 40), 'UTF-8', 'ISO-8859-1')) ?? null,
                'medicamento_cod'     => trim(substr($row, 44, 5)) ?? null,
                'medicamento'         => trim(mb_convert_encoding(substr($row, 49, 80), 'UTF-8', 'ISO-8859-1')) ?? null,
                'apresentacao_cod'    => trim(substr($row, 129, 4)) ?? null,
                'apresentacao'        => trim(mb_convert_encoding(substr($row, 133, 150), 'UTF-8', 'ISO-8859-1')) ?? null,
                'preco_medicamento'   => trim(substr($row, 283, 15)) ?? null,
                'qtd_fracionamento'   => trim(substr($row, 298, 4)) ?? null,
                'tipo_preco'          => trim(substr($row, 302, 3)) ?? null,
                'valor_fracionado'    => trim(substr($row, 305, 15)) ?? null,
                'edicao'              => trim(substr($row, 320, 5)) ?? null,
                'ipi_medicamento'     => trim(substr($row, 325, 5)) ?? null,
                'flag_pis_confins'    => trim(substr($row, 330, 1)) ?? null,
                'ean'                 => trim(substr($row, 331, 13)) ?? null,
                'tiss'                => trim(substr($row, 344, 10)) ?? null,
                'flag_generico'       => null,
                'tuss'                => trim(substr($row, 354, 8)) ?? null,
            ];

            fputcsv($fileCSV, $dataRow);
        }

        fclose($fileCSV);

        fclose($fileTXT);

        unlink($filePatchTXT);

        return $filePatchCSV;
    }
}
