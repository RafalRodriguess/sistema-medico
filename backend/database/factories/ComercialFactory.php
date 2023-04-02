<?php

use App\Comercial;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Comercial::class, function (Faker $faker) {
    $nome = $faker->company;

    return [
        'nome_fantasia' => $nome,
        'cnpj' => $faker->cnpj,
        'razao_social' => $nome,
        'email' => $faker->safeEmail,
        'telefone' => $faker->phoneNumber,
        'cep' => $faker->postcode,
        'rua' => "{$faker->streetPrefix} {$faker->streetName}",
        'numero' => preg_replace('/[^\d]+/', '', $faker->streetAddress),
        'bairro' => $faker->city,
        'cidade' => $faker->city,
        'estado' => $faker->stateAbbr,
    ];
});

$factory->state(Comercial::class, 'realiza_entrega', [ 'realiza_entrega' => true ]);
$factory->state(Comercial::class, 'nao_realiza_entrega', [ 'realiza_entrega' => false ]);

$factory->state(Comercial::class, 'retirada_loja', [ 'retirada_loja' => true ]);
$factory->state(Comercial::class, 'sem_retirada_loja', [ 'retirada_loja' => false ]);

$factory->state(Comercial::class, 'exibir', ['exibir' => true]);

$factory->state(Comercial::class, 'aceita_cartao_credito_debito', [ 'pagamento_cartao' => 'ambos' ]);
$factory->state(Comercial::class, 'aceita_apenas_cartao_credito', [ 'pagamento_cartao' => 'credito' ]);
$factory->state(Comercial::class, 'aceita_apenas_cartao_debito', [ 'pagamento_cartao' => 'debito' ]);
$factory->state(Comercial::class, 'nao_aceita_cartao', [ 'pagamento_cartao' => null ]);
