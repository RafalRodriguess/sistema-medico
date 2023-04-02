<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

/**
 * Trait utilizada para relações N para N onde
 * esta permite substituir todos os membros da relação
 * por novos membros especificados por um array, só podem ser
 * substituidos Models listados no array allowed_overwrites
 */
trait ModelOverwrite
{
    /**
     * Variavel que define os models relacionados
     * cuja sobrescrita é permitida, e.g.: protected $allowed_overwrite = [Produtos::class];
     * @var array $allowed_overwrite
     */

    /**
     * Método que substitui todos os registros cujo $main_relation_key
     * é igual ao valor em $id, deletando todos os antigos e inserindo os
     * registros que estão em $data.
     * @param HasOneOrMany $relation A relação, cujos membro(s) serão substituidos pelos
     * membros especificados, deve ser HasOne ou HasMany
     * @param array[] $data Caso HasMany  deve ser um array com as entradas (novos membros) que serão
     * inseridas no lugar das removidas, estes membros devem ser um array de chave valor representando as 
     * colunas e seus respectivos valores. Caso a relação seja 1 para 1 somente a entrada é necessária
     * (não será necesário o array de entradas, somente a entrada com seus respectivos valores associados
     * a chaves com o nome da coluna)
     * @param callback $callback O callback a cada inserção ou atualização
     * feita por esse método, possúi como primeiro parâmetro $new, o model inserido no
     * banco de dados e como segundo parâmetro $key, a chave de iteração do array de
     * dados passado no parâmetro $data
     * @param bool $set_new Quando true atualiza o campo created_at fazendo parecer
     * que a entrada foi criada neste momento
     * @return mixed Caso a relação alterada seja HasOne ele retorna o 
     */
    public function overwrite(HasOneOrMany $relation, array $data, $callback = null, $set_new = true)
    {
        // Verifica se a sobrescrita é permitida
        $related = $relation->getRelated();
        if (!in_array(get_class($related), $this->allowed_overwrite ?? [])) {
            throw new \Exception('Tentativa de substituição de registros não permitida, verifique configuração do model');
        }
        // Adaptando o dados para funcionar na metodologia HasMany
        $is_has_one = get_class($relation) === HasOne::class;
        if($is_has_one) {
            $data = [$data];
        } else if(get_class($relation) !== HasMany::class) { // Validando classe de entrada
            throw new \Exception('Só são permitidas relações de 1 para 1 (HasOne) ou 1 para n (HasMany)');
        }
        
        return DB::transaction(function () use ($relation, $data, $callback, $set_new, $related, $is_has_one) {
            // Avalia as quantidades
            $current_entries = $relation->get();
            $current_entries_ammount = $current_entries->count();
            $data_ammount = count($data);
            $i = 0;
            // Atualiza os atuais ou cria caso os atuais não sejam suficientes
            foreach ($data as $index => $entry) {
                if ($i < $current_entries_ammount) {
                    if ($set_new && ($related->timestamps ?? false)) {
                        $entry['created_at'] = date('Y-m-d H:i:s');
                    }
                    if(!empty($entry['id'])) {
                        unset($entry['id']);
                    }
                    $current_entries[$i]->update($entry);
                    $new = $current_entries[$i++];
                    $last_method = 'update';
                } else {
                    $new = $relation->create($entry);
                    $last_method = 'create';
                }

                if (!empty($callback)) {
                    $callback($new, $index, $last_method);
                }

                if($is_has_one) {
                    return $new;
                }
            }
            // Remove caso os atuais excedam os desejados
            if ($current_entries_ammount > $data_ammount) {
                $current_entries->slice($i)->map(function ($item) {
                    $item->forceDelete();
                });
            }

            return true;
        });
    }
}
