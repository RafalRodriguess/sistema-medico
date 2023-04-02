<?php

use App\ComercialHabilidade;
use App\ComercialHabilidadeGrupo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductionHabilidadesComercial extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->removerObsoletos();
        $grupos = $this->sincronizarGrupos();
        $this->sincronizarHabilidades($grupos);
    }

    private function removerObsoletos()
    {
        $habilidades = [
            // Adicionar array de wheres para remover habilidades
            // Exemplo:
            // [ 'nome_unico' => 'visualizar_usuario' ],
        ];
        foreach ($habilidades as $habilidade) {
            ComercialHabilidade::query()->where($habilidade)->delete();
        }

        $grupos = [
            // Adicionar array de wheres para remover grupos
            // Exemplo:
            // ['name' => 'Administração > Usuários']
        ];
        foreach ($grupos as $grupo) {
            ComercialHabilidadeGrupo::query()->where($grupo)->delete();
        }
    }

    private function sincronizarGrupos()
    {
        $grupos = [
            // Para cada entrada do array,
            // o primeiro array é utilizado em um where para identificar
            // se o grupo já existe
            // devem ser informações que nunca serão atualizadas
            // o segundo array são informações adicionais
            // estas serão atualizadas sempre que o seeder rodar
            [
                ['nome' => 'Estoque > Entrada'],
                ['categoria' => '6'],
            ],

            [
                ['nome' => 'Administração > Usuários'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Administração > Comerciais'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Administração > Horario Funcionamento'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Loja > Categorias'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Loja > Sub Categorias'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Loja > Produtos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Fretes > Entrega'],
                ['categoria' => '3'],
            ],
            [
                ['nome' => 'Pedidos > Meus Pedidos'],
                ['categoria' => '4'],
            ],
        ];

        $entities = [];
        foreach ($grupos as $grupo) {
            $wheres = $grupo[0];
            $attributes = $grupo[1];
            $entity = ComercialHabilidadeGrupo::query()->where($wheres)->firstOrNew();
            foreach ($wheres as $attribute => $value) {
                $entity->{$attribute} = $value;
            }
            foreach ($attributes as $attribute => $value) {
                $entity->{$attribute} = $value;
            }
            $entity->saveOrFail();

            $entities[Arr::first($wheres)] = $entity;
        }

        return $entities;
    }

    private function sincronizarHabilidades(array $grupos)
    {
        $habilidades = [
            // Para cada entrada do array,
            // o primeiro array é utilizado em um where para identificar
            // se a habilidade já existe
            // devem ser informações que nunca serão atualizadas
            // o segundo array são informações adicionais
            // estas serão atualizadas sempre que o seeder rodar
            [
                ['nome_unico' => 'visualizar_estoque_entrada'],
                [
                    'nome' => 'Visualizar Estoque Entrada',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ]
            ],

            [
                ['nome_unico' => 'cadastrar_estoque_entrada'],
                [
                    'nome' => 'Cadastrar Estoque Entrada',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ]
            ],

            [
                ['nome_unico' => 'editar_estoque_entrada'],
                [
                    'nome' => 'Editar Estoque Entrada',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_estoque_entrada'],
                [
                    'nome' => 'Excluir Estoque Entrada',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ]
            ],



            [
                ['nome_unico' => 'visualizar_estoque_entrada_produtos'],
                [
                    'nome' => 'Visualizar Estoque Entrada Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ]
            ],

            [
                ['nome_unico' => 'cadastrar_estoque_entrada_produtos'],
                [
                    'nome' => 'Cadastrar Estoque Entrada Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ]
            ],

            [
                ['nome_unico' => 'editar_estoque_entrada_produtos'],
                [
                    'nome' => 'Editar Estoque Entrada Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ]
            ],

            [
                ['nome_unico' => 'excluir_estoque_entrada_produtos'],
                [
                    'nome' => 'Excluir Estoque Entrada Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ]
            ],





            [
                ['nome_unico' => 'visualizar_usuario'],
                [
                    'nome' => 'Visualizar Usuários',
                    'habilidade_grupo_id' => $grupos['Administração > Usuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_usuario'],
                [
                    'nome' => 'Cadastrar Usuários',
                    'habilidade_grupo_id' => $grupos['Administração > Usuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_usuario'],
                [
                    'nome' => 'Editar Usuários',
                    'habilidade_grupo_id' => $grupos['Administração > Usuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'habilidades_usuario'],
                [
                    'nome' => 'Habilidades Usuários',
                    'habilidade_grupo_id' => $grupos['Administração > Usuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_usuario'],
                [
                    'nome' => 'Excluir Usuários',
                    'habilidade_grupo_id' => $grupos['Administração > Usuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_comercial'],
                [
                    'nome' => 'Editar Comerciais',
                    'habilidade_grupo_id' => $grupos['Administração > Comerciais']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_horarios_funcionamento'],
                [
                    'nome' => 'Editar Horarios Funcionamento',
                    'habilidade_grupo_id' => $grupos['Administração > Horario Funcionamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_parcelas'],
                [
                    'nome' => 'Editar Parcelas',
                    'habilidade_grupo_id' => $grupos['Administração > Comerciais']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_categoria'],
                [
                    'nome' => 'Visualizar Categoria',
                    'habilidade_grupo_id' => $grupos['Loja > Categorias']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_categoria'],
                [
                    'nome' => 'Cadastrar Categoria',
                    'habilidade_grupo_id' => $grupos['Loja > Categorias']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_categoria'],
                [
                    'nome' => 'Editar Categoria',
                    'habilidade_grupo_id' => $grupos['Loja > Categorias']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_categoria'],
                [
                    'nome' => 'Excluir Categoria',
                    'habilidade_grupo_id' => $grupos['Loja > Categorias']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_sub_categoria'],
                [
                    'nome' => 'Visualizar Sub Categoria',
                    'habilidade_grupo_id' => $grupos['Loja > Sub Categorias']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_sub_categoria'],
                [
                    'nome' => 'Cadastrar Sub Categoria',
                    'habilidade_grupo_id' => $grupos['Loja > Sub Categorias']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_sub_categoria'],
                [
                    'nome' => 'Editar Sub Categoria',
                    'habilidade_grupo_id' => $grupos['Loja > Sub Categorias']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_sub_categoria'],
                [
                    'nome' => 'Excluir Sub Categoria',
                    'habilidade_grupo_id' => $grupos['Loja > Sub Categorias']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_produto'],
                [
                    'nome' => 'Visualizar Produto',
                    'habilidade_grupo_id' => $grupos['Loja > Produtos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_produto'],
                [
                    'nome' => 'Cadastrar Produto',
                    'habilidade_grupo_id' => $grupos['Loja > Produtos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_produto'],
                [
                    'nome' => 'Editar Produto',
                    'habilidade_grupo_id' => $grupos['Loja > Produtos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_produto'],
                [
                    'nome' => 'Excluir Produto',
                    'habilidade_grupo_id' => $grupos['Loja > Produtos']->id,
                ]
            ],
            [
                ['nome_unico' => 'promocao_produto'],
                [
                    'nome' => 'Promoção Produto',
                    'habilidade_grupo_id' => $grupos['Loja > Produtos']->id,
                ]
            ],
            [
                ['nome_unico' => 'estoque_produto'],
                [
                    'nome' => 'Estoque Produto',
                    'habilidade_grupo_id' => $grupos['Loja > Produtos']->id,
                ]
            ],



            [
                ['nome_unico' => 'desativar_produto'],
                [
                    'nome' => 'Desativar/Ativar Produto',
                    'habilidade_grupo_id' => $grupos['Loja > Produtos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_perguntas'],
                [
                    'nome' => 'Visualizar Perguntas',
                    'habilidade_grupo_id' => $grupos['Loja > Produtos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_perguntas'],
                [
                    'nome' => 'Cadastrar Perguntas',
                    'habilidade_grupo_id' => $grupos['Loja > Produtos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_perguntas'],
                [
                    'nome' => 'Editar Perguntas',
                    'habilidade_grupo_id' => $grupos['Loja > Produtos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_perguntas'],
                [
                    'nome' => 'Excluir Perguntas',
                    'habilidade_grupo_id' => $grupos['Loja > Produtos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_fretes'],
                [
                    'nome' => 'Visualizar Fretes',
                    'habilidade_grupo_id' => $grupos['Fretes > Entrega']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_fretes'],
                [
                    'nome' => 'Cadastrar Fretes',
                    'habilidade_grupo_id' => $grupos['Fretes > Entrega']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_fretes'],
                [
                    'nome' => 'Editar Fretes',
                    'habilidade_grupo_id' => $grupos['Fretes > Entrega']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_fretes'],
                [
                    'nome' => 'Excluir Fretes',
                    'habilidade_grupo_id' => $grupos['Fretes > Entrega']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_pedidos'],
                [
                    'nome' => 'Visualizar Pedidos',
                    'habilidade_grupo_id' => $grupos['Pedidos > Meus Pedidos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_saldo_pagarme'],
                [
                    'nome' => 'Visualizar Saldo Pagarme',
                    'habilidade_grupo_id' => $grupos['Pedidos > Meus Pedidos']->id,
                ]
            ],
        ];

        $entities = [];
        foreach ($habilidades as $habilidade) {
            $wheres = $habilidade[0];
            $attributes = $habilidade[1];
            $entity = ComercialHabilidade::query()->where($wheres)->firstOrNew();
            foreach ($wheres as $attribute => $value) {
                $entity->{$attribute} = $value;
            }
            foreach ($attributes as $attribute => $value) {
                $entity->{$attribute} = $value;
            }
            $entity->saveOrFail();

            $entities[Arr::first($wheres)] = $entity;
        }

        return $entities;
    }
}
