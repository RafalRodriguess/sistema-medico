<?php

namespace App;

use App\Support\ModelAceitaGanchos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class GanchoModel extends Model
{
    protected $table = 'ganchos';
    protected $fillable = [
        'descricao',
        'class',
    ];
    public $timestamps = false;

    /**
     * Variavel que define como os dados do gancho
     * serão inicializados, altere nas classes que herdarem
     * @var array
     */
    protected $MODELO_DADOS = [];

    /**
     * @var int Tempo em que um registro é considerado novo para o monitor
     * @var bool $model_only
     * que acessar, depois desse tempo qualquer monitor que acessar recebe
     * o registro como não recente (para que múltiplos monitores
     * peguem é recomendado valores maiores que 9)
     */
    protected const TEMPO_DE_ESPERA = 14;

    public function __construct(array $dados = [])
    {
        array_walk($this->fillable, function ($key) use ($dados) {
            $this->attributes[$key] = $dados[$key] ?? null;
        });
    }

    /**
     * Os dados do gancho na instituicao atual
     * @param ModelAceitaGanchos|string Ou um model que extende ModelAceitaGanchos
     * ou o nome da classe de um model que implementa ModelAceitaGanchos
     * @param string $key Chave que identifica um unico gancho, caso vazio
     * retorna dados de todos eles
     */
    public function getDados($model, ?string $key = null): DadosGancho
    {
        if (!is_string($model)) {
            $model = get_class($model);
        }
        $this->instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $dados = $this->hasMany(DadosGancho::class, 'ganchos_id')->where('instituicoes_id', $this->instituicao->id)->where('model', $model)->first();
        // Caso o manipulamento é em todos
        if (empty($key)) {
            if (!empty($dados)) {
                $dados->dados = is_array($dados->dados) ? $dados->dados : json_decode($dados->dados ?? '');
                return $dados;
            } else {
                return DadosGancho::create([
                    'ganchos_id' => $this->id,
                    'instituicoes_id' => $this->instituicao->id,
                    'model' => $model,
                    'dados' => $this->MODELO_DADOS
                ]);
            }
        } else {
            if (!empty($dados)) {
                $dados->dados = is_array($dados->dados) ? $dados->dados : json_decode($dados->dados ?? '');
                if (!empty($dados->dados[$key])) {
                    $dados->dados = $dados->dados[$key];
                } else {
                    $dados->dados = $this->MODELO_DADOS;
                }
                return $dados;
            } else {
                return DadosGancho::create([
                    'ganchos_id' => $this->id,
                    'instituicoes_id' => $this->instituicao->id,
                    'model' => $model,
                    'dados' => [$key => $this->MODELO_DADOS]
                ]);
            }
        }
    }

    /**
     * Salva os dados do gancho novamente no sistema
     * @param DadosGancho $dados_atuais Os dados a serem atualizados
     * @param array $dados Os dados a serem inseridos
     * @param string|null $key A chave onde os dados serão inseridos
     */
    public function setDados(DadosGancho $dados_atuais, array $dados, ?string $key = null)
    {
        if (empty($key)) {
            return $dados_atuais->update(['dados' => json_encode($dados)]);
        } else {
            $dados_atuais = DadosGancho::find($dados_atuais->id);
            $novos_dados = is_array($dados_atuais->dados) ? $dados_atuais->dados : json_decode($dados_atuais->dados ?? '');
            if (is_array($novos_dados)) {
                $novos_dados[$key] = $dados;
            } else {
                $novos_dados->$key = $dados;
            }
            return $dados_atuais->update(['dados' => json_encode($novos_dados)]);
        }
    }

    /**
     * Gera um gancho da classe necessária
     * @return GanchoModel|null Uma classe que implementa a classe GanchoModel ou null
     */
    public static function make(int $id)
    {
        $model = (array)(DB::table('ganchos')->where('id', $id)->first() ?? []);
        $class = $model['class'] ?? null;
        if (!empty($class)) {
            $class = new $class($model);
            $class->id = $id;
            $class->exists = true;
        }
        return $class;
    }

    /**
     * Executa o gancho e retorna o valor esperado,
     * no caso de um gancho que verifica o ultimo registro
     * adicionado, retorna um registro ou null
     * @param ModelAceitaGanchos $model Model onde o gancho irá ser executado
     * @return mixed Um array com o resultado (em forma de booleano) e o que foi encontrado,
     * caso nada tenha sido encontrado retorna false
     */
    public abstract function handle($model = null);


    /**
     * Executa o gancho e retorna o valor esperado,
     * no caso de um gancho que verifica o ultimo registro
     * adicionado, retorna um registro ou null
     * @param string $model o classname da classe onde o gancho irá ser executado
     * @return mixed Resultado encontrado pelo gancho
     */
    public function autoHandle(string $class = null)
    {
        if (!empty($class))
            return $this->handle(new $class([]));
        else
            return $this->handle();
    }
}
