<?php

namespace App;

use App\Support\GerarFaturamentoSUS;
use Illuminate\Support\Facades\DB;

class ImportacaoSUS
{
    /**
     * @var string O nome da tabela que será inserida
     */
    public $name;

    /**
     * @var string O prefixo da tabela que será inserida
     */
    public $prefix;

    /**
     * @var string A pasta onde o arquivo foi extraído
     */
    private $folder;

    /**
     * @var array A especificação do formato dos dados no arquivo
     */
    public $format;

    /**
     * @var array Os dados que serão inseridos
     */
    private $data = [];

    /**
     * @var Instituicao A instituicao aonde estes dados serão inseridos
     */
    private $instituicao;

    /**
     * Pega um array do tipo [['nome' => $nome_coluna, 'inicio' => $inicio, 'fim' => $fim], ...]
     * lê o arquivo referente a tabela e a partir desse array analisa os dados e insere no banco de dados
     * @param string $folder A pasta onde o arquivo foi extraído
     * @param string $name O nome da tabela que será inserida
     * @param array $format A especificação do formato dos dados no arquivo
     */
    public function __construct(string $folder, Instituicao $instituicao, string $prefix = '', string $name = '', array $format = [])
    {
        $this->name = $name;
        $this->instituicao = $instituicao;
        $this->prefix = $prefix;
        $this->format = $format;
        $this->folder = $folder;
    }

    /**
     * Método que retorna o nome da tabela
     * @return string o nome da tabela
     */
    public function tableName()
    {
        return !empty($this->prefix) ? $this->prefix . '_' . $this->name : $this->name;
    }

    /**
     * Um simples array_push
     * @param array $format O formato de coluna a ser inserido neste objeto
     */
    public function pushFormat(array $format)
    {
        array_push($this->format, $format);
    }

    /**
     * Analisa se este faturamento foi utilizado
     */
    public function isValid(): bool
    {
        return !empty($this->name) && !empty($this->format);
    }

    /**
     * Converte a linha em um array de strings contendo os dados
     * analizados a partir do $format e insere no atributo $data
     * @param string $line A linha a ser processada
     */
    public function parseLine($line)
    {
        $line = mb_convert_encoding($line, 'UTF-8', 'ISO-8859-1');
        $aux = [];
        // Buffer para caso tenha caracteres de 2 bytes
        $buffer = '';
        $line_size = strlen($line) - 2;
        $ammount_columns = count($this->format);
        // Conta quantos bytes extras os caracteres de 2 bytes incrementaram em $i
        $extra_byte_offset = 0;
        for ($i = 0, $j = 0; $i < $line_size; $i++) {
            $buffer .= $line[$i];
            // Caso este byte
            if ($i + 1 < $line_size && mb_strlen($line[$i] . $line[$i + 1], 'UTF-8') != strlen($line[$i] . $line[$i + 1])) {
                $extra_byte_offset++;
                continue;
            }
            if (($i - $extra_byte_offset + 1) > $this->format[$j]['fim']) {
                $j++;
            }
            if ($j >= $ammount_columns) {
                break;
            }
            $aux[$this->format[$j]['nome']] = ($aux[$this->format[$j]['nome']] ?? '') . $buffer;
            $buffer = '';
        }
        $aux['instituicoes_id'] = $this->instituicao->id;
        array_push($this->data, $aux);
    }

    /**
     * Analiza todas as linhas do arquivo e insere no banco de dados
     * de acordo com o $format
     */
    public function generate()
    {
        $file = null;
        try {
            $file = fopen($this->folder . '/' . $this->name . '.txt', 'rb');
            $line = '';
            while (false !== ($line = fgets($file))) {
                $this->parseLine($line);
            }
        } catch (\Exception $e) {
        } finally {
            if (!empty($file)) {
                fclose($file);
            }
        }

        // Dividindo as inserções em chunks para não afogar o banco de dados
        $insertions = collect($this->data)->chunk(300);
        foreach ($insertions as $insertion) {
            DB::table($this->tableName())->insert($insertion->toArray());
        }
    }
}
