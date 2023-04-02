<?php

namespace App\Support;

use App\ImportacaoSUS;
use App\Instituicao;

class GerarImportacaoSUS
{
    /**
     * Define qual arquivo vai ser utilizado para analizar as inserções
     */
    private const layout = 'layout.txt';

    /**
     * Array de strings que serão ignoradas pelo interpretador
     * @var array
     */
    protected const ignored_lines = [
        'Coluna,Tamanho,Inicio,Fim,Tipo',
    ];

    /**
     * Array de caracteres que serão removidos das strings lidas
     * @var array
     */
    protected const filtered_chars = [
        "\r",
        "\n"
    ];

    /**
     * @var int
     * Tamanho máximo de uma linha (em bytes)
     */
    protected $max_length;

    /**
     * @var string A pasta que contém todos os arquivos
     * do faturamento SUS devidamente extraídos
     */
    private $folder;

    /**
     * @var string O prefixo da tabela no banco de dados
     */
    private $prefix;

    /**
     * @param string A pasta que contém todos os arquivos
     * do faturamento SUS devidamente extraídos
     */
    public function __construct(string $folder, string $prefix = '', int $max_length = 1024)
    {
        $this->folder = $folder;
        $this->prefix = $prefix;
        $this->max_length = $max_length;
    }

    /**
     * Converte as informações de colunas de tabela que chegam do arquivo
     * em um formato que a classe Migration entende
     * @param string $line A linha já processada pelo trim() que veio do
     * arquivo
     * @return array O array formatado para uso na Migration
     */
    public static function parseLine(string $line): array
    {
        $aux = explode(',', $line);
        $result = [];
        try {
            $result = [
                'nome' => $aux[0],
                'inicio' => $aux[2],
                'fim' => $aux[3],
            ];
        } catch (\Exception $e) {
        }

        return $result;
    }

    /**
     * Verifica se a linha é para informar humanos, sendo inútil para
     * o computador
     * @return bool Retorna se pode ou não ignorar
     */
    public static function canIgnore(string $line): bool
    {
        return in_array($line, self::ignored_lines);
    }

    /**
     * Método que faz o parser iniciar sua funcionalidade
     */
    public function start(Instituicao $instituicao)
    {
        $line = '';
        $file = fopen($this->folder . '/' . self::layout, 'r');
        $awaiting_table = true;
        $faturamento = new ImportacaoSUS($this->folder, $instituicao, $this->prefix);
        set_time_limit(720);

        try {
            while (false !== ($line = fgets($file))) {
                $line = str_replace(self::filtered_chars, '', $line);
                if (empty($line)) {
                    if ($faturamento->isValid()) {
                        $faturamento->generate();
                        $faturamento = new ImportacaoSUS($this->folder, $instituicao, $this->prefix);
                    }
                    $awaiting_table = true;
                } else {
                    if ($awaiting_table) {
                        $faturamento->name = $line;
                        $awaiting_table = false;
                    } else if (!self::canIgnore($line)) {
                        $faturamento->pushFormat(self::parseLine($line));
                    }
                }
            }
        } catch (\Exception $e) {
        } finally {
            fclose($file);
        }
        set_time_limit(30);
    }
}
