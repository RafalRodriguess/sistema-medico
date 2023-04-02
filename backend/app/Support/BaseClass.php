<?php
namespace App\Support;

use stdClass;

class BaseClass extends stdClass {
    // Atrributes
    private $attributes = [];

    /**
     * Método que gera uma classe genérica a partir de um array chave valor
     * @param array $data Um array chave-valor onde a chave é o atributo na
     * classe e o valor é o valor desse atributo
     * @param array $nullAttributes Array de strings que representam os
     * Campos que serão preenchidos com null, seu propósito é a compatibilidade,
     * caso este objeto seja utilizado no lugar de outro objeto que tenha
     * atributos que serão necessários
     * @return BaseClass
     */
    public static function make(array $data = [], array $nullAttributes = [])
    {
        $object = new BaseClass();
        foreach($nullAttributes as $attribute) {
            $object->$attribute = null;
        }
        $final_attributes = [];
        foreach($data as $key => $value) {
            $object->$key = $value;
            array_push($final_attributes, $key);
        }
        $object->attributes = $final_attributes;
        return $object;
    }

    public function exists()
    {
        return false;
    }

    public function toArray()
    {
        $values = [];
        foreach($this->attributes as $attr) {
            $values[$attr] = $this->$attr ?? null;
        }
        return $values;
    }

    public function __get($name)
    {
        if(in_array($name, $this->attributes)) {
            return $this->$name ?? null;
        }
        return null;
    }
}
