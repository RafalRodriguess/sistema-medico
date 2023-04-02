<?php

use App\Habilidade;
use App\HabilidadeGrupo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductionHabilidadesAdmin extends Seeder
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
            [ 'nome_unico' => 'editar_conta_bancaria_instituicao' ],
            [ 'nome_unico' => 'visualizar_convenio' ],
            [ 'nome_unico' => 'cadastrar_convenio' ],
            [ 'nome_unico' => 'editar_convenio' ],
            [ 'nome_unico' => 'visualizar_convenio_planos' ],
            [ 'nome_unico' => 'cadastrar_convenio_planos' ],
            [ 'nome_unico' => 'editar_convenio_planos' ],
            [ 'nome_unico' => 'excluir_convenio_planos' ],
            [ 'nome_unico' => 'visualizar_apresentacoes_convenio' ],
            [ 'nome_unico' => 'cadastrar_apresentacoes_convenio' ],
            [ 'nome_unico' => 'editar_apresentacoes_convenio' ],
            [ 'nome_unico' => 'excluir_apresentacoes_convenio' ],
        ];
        foreach ($habilidades as $habilidade) {
            Habilidade::query()->where($habilidade)->delete();
        }

        $grupos = [
            // Adicionar array de wheres para remover grupos
            // Exemplo:
            ['nome' => 'Instituição > Convênios'],
            ['nome' => 'Instituição > Convênios Planos'],
            ['nome' => 'Instituição > Convênios > Apresentação']
        ];
        foreach ($grupos as $grupo) {
            HabilidadeGrupo::query()->where($grupo)->delete();
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
                ['nome' => 'Administração > Administradores'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Administração > Perfis de Usuários'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Comercial > Comerciais > Usuários'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Comercial > Comerciais'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Clinica > Medicamentos'],
                ['categoria' => '3'],
            ],
            [
                ['nome' => 'Aplicativo > Usuarios'],
                ['categoria' => '4'],
            ],
            [
                ['nome' => 'Instituição > Instituições'],
                ['categoria' => '5'],
            ],
            [
                ['nome' => 'Instituição > Instituições > Usuarios'],
                ['categoria' => '5'],
            ],
            [
                ['nome' => 'Instituição > Especialidades'],
                ['categoria' => '5'],
            ],
            [
                ['nome' => 'Instituição > Especializações'],
                ['categoria' => '5'],
            ],
            [
                ['nome' => 'Instituição > Prestadores'],
                ['categoria' => '5'],
            ],
            [
                ['nome' => 'Aplicativo > Marcas'],
                ['categoria' => '4'],
            ],
            [
                ['nome' => 'Clinica > Procedimentos'],
                ['categoria' => '4'],
            ],
            [
                ['nome' => 'Clinica > Atendimentos'],
                ['categoria' => '4'],
            ],
            [
                ['nome' => 'Clinica > Grupos'],
                ['categoria' => '4'],
            ],
            [
                ['nome' => 'Logs > Geral'],
                ['categoria' => '1'],
            ],

            [
                ['nome' => 'Estoque > Entrada'],
                ['categoria' => '6'],
            ],
            [
                ['nome' => 'Instituição > Ramos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Instituição > Setores Exame'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Instituição > Perfil de usuario'],
                ['categoria' => '2'],
            ],
        ];

        $entities = [];
        foreach ($grupos as $grupo) {
            $wheres = $grupo[0];
            $attributes = $grupo[1];
            $entity = HabilidadeGrupo::query()->where($wheres)->firstOrNew();
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
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_estoque_entrada'],
                [
                    'nome' => 'Cadastrar Estoque Entrada',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ],
            ],

            [
                ['nome_unico' => 'editar_estoque_entrada'],
                [
                    'nome' => 'Editar Estoque Entrada',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ],
            ],

            [
                ['nome_unico' => 'excluir_estoque_entrada'],
                [
                    'nome' => 'Excluir Estoque Entrada',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ],
            ],



            [
                ['nome_unico' => 'visualizar_estoque_entrada_produtos'],
                [
                    'nome' => 'Visualizar Estoque Entrada Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_estoque_entrada_produtos'],
                [
                    'nome' => 'Cadastrar Estoque Entrada Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ],
            ],

            [
                ['nome_unico' => 'editar_estoque_entrada_produtos'],
                [
                    'nome' => 'Editar Estoque Entrada Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ],
            ],

            [
                ['nome_unico' => 'excluir_estoque_entrada_produtos'],
                [
                    'nome' => 'Excluir Estoque Entrada Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Entrada']->id,
                ],
            ],


            [
                ['nome_unico' => 'visualizar_administrador'],
                [
                    'nome' => 'Visualizar Administradores',
                    'habilidade_grupo_id' => $grupos['Administração > Administradores']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_administrador'],
                [
                    'nome' => 'Cadastrar Administradores',
                    'habilidade_grupo_id' => $grupos['Administração > Administradores']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_administrador'],
                [
                    'nome' => 'Editar Administradores',
                    'habilidade_grupo_id' => $grupos['Administração > Administradores']->id,
                ],
            ],
            [
                ['nome_unico' => 'habilidades_administrador'],
                [
                    'nome' => 'Habilidades Administradores',
                    'habilidade_grupo_id' => $grupos['Administração > Administradores']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_administrador'],
                [
                    'nome' => 'Excluir Administradores',
                    'habilidade_grupo_id' => $grupos['Administração > Administradores']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_atendimentos'],
                [
                    'nome' => 'Visualizar Caráter de Atendimentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Atendimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_atendimentos'],
                [
                    'nome' => 'Cadastrar Caráter de Atendimentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Atendimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_atendimentos'],
                [
                    'nome' => 'Editar Caráter de Atendimentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Atendimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_atendimentos'],
                [
                    'nome' => 'Excluir Caráter de Atendimentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Atendimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_perfis_usuarios'],
                [
                    'nome' => 'Visualizar Perfis de Usuarios',
                    'habilidade_grupo_id' => $grupos['Administração > Perfis de Usuários']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_perfis_usuarios'],
                [
                    'nome' => 'Cadastrar Perfis de Usuarios',
                    'habilidade_grupo_id' => $grupos['Administração > Perfis de Usuários']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_perfis_usuarios'],
                [
                    'nome' => 'Editar Perfis de Usuarios',
                    'habilidade_grupo_id' => $grupos['Administração > Perfis de Usuários']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_perfis_usuarios'],
                [
                    'nome' => 'Excluir Perfis de Usuarios',
                    'habilidade_grupo_id' => $grupos['Administração > Perfis de Usuários']->id,
                ],
            ],
            [
                ['nome_unico' => 'habilidades_perfis_usuarios'],
                [
                    'nome' => 'Habilidades Perfis de Usuarios',
                    'habilidade_grupo_id' => $grupos['Administração > Perfis de Usuários']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_usuario_comercial'],
                [
                    'nome' => 'Visualizar Usuários Comercial',
                    'habilidade_grupo_id' => $grupos['Comercial > Comerciais > Usuários']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_usuario_comercial'],
                [
                    'nome' => 'Cadastrar Usuários Comercial',
                    'habilidade_grupo_id' => $grupos['Comercial > Comerciais > Usuários']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_usuario_comercial'],
                [
                    'nome' => 'Editar Usuários Comercial',
                    'habilidade_grupo_id' => $grupos['Comercial > Comerciais > Usuários']->id,
                ],
            ],
            [
                ['nome_unico' => 'habilidades_usuario_comercial'],
                [
                    'nome' => 'Habilidades Usuários Comercial',
                    'habilidade_grupo_id' => $grupos['Comercial > Comerciais > Usuários']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_usuario_comercial'],
                [
                    'nome' => 'Excluir Usuários Comercial',
                    'habilidade_grupo_id' => $grupos['Comercial > Comerciais > Usuários']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_comercial'],
                [
                    'nome' => 'Visualizar Comercial',
                    'habilidade_grupo_id' => $grupos['Comercial > Comerciais']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_comercial'],
                [
                    'nome' => 'Cadastrar Comercial',
                    'habilidade_grupo_id' => $grupos['Comercial > Comerciais']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_comercial'],
                [
                    'nome' => 'Editar Comercial',
                    'habilidade_grupo_id' => $grupos['Comercial > Comerciais']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_comercial'],
                [
                    'nome' => 'Excluir Comercial',
                    'habilidade_grupo_id' => $grupos['Comercial > Comerciais']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_conta_bancaria_comercial'],
                [
                    'nome' => 'Editar Conta Bancária Comercial',
                    'habilidade_grupo_id' => $grupos['Comercial > Comerciais']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_medicamentos'],
                [
                    'nome' => 'Visualizar Medicamentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Medicamentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_medicamentos'],
                [
                    'nome' => 'Cadastrar Medicamentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Medicamentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_medicamentos'],
                [
                    'nome' => 'Editar Medicamentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Medicamentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_medicamentos'],
                [
                    'nome' => 'Excluir Medicamentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Medicamentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_usuario'],
                [
                    'nome' => 'Visualizar Usuarios Aplicativo',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_usuario'],
                [
                    'nome' => 'Cadastrar Usuarios Aplicativo',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_usuario'],
                [
                    'nome' => 'Editar Usuarios Aplicativo',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_usuario'],
                [
                    'nome' => 'Excluir Usuarios Aplicativo',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'dispositivo_usuario'],
                [
                    'nome' => 'Visualizar Dispositivos Usuarios Aplicativo',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_endereco_usuario'],
                [
                    'nome' => 'Visualizar Endereços Usuario Aplicativo',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_endereco_usuario'],
                [
                    'nome' => 'Cadastrar Endereços Usuario Aplicativo',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_endereco_usuario'],
                [
                    'nome' => 'Editar Endereços Usuario Aplicativo',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_endereco_usuario'],
                [
                    'nome' => 'Excluir Endereços Usuario Aplicativo',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_marcas'],
                [
                    'nome' => 'Visualizar Marcas',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Marcas']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_marcas'],
                [
                    'nome' => 'Cadastrar Marcas',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Marcas']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_marcas'],
                [
                    'nome' => 'Editar Marcas',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Marcas']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_marcas'],
                [
                    'nome' => 'Excluir Marcas',
                    'habilidade_grupo_id' => $grupos['Aplicativo > Marcas']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_instituicao'],
                [
                    'nome' => 'Visualizar Instituições',
                    'habilidade_grupo_id' => $grupos['Instituição > Instituições']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_instituicao'],
                [
                    'nome' => 'Cadastrar Instituições',
                    'habilidade_grupo_id' => $grupos['Instituição > Instituições']->id,
                ],
            ],
            [
                ['nome_unico' => 'habilitar_instituicao'],
                [
                    'nome' => 'Habilitar/Desabilitar Instituições',
                    'habilidade_grupo_id' => $grupos['Instituição > Instituições']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_instituicao'],
                [
                    'nome' => 'Editar Instituições',
                    'habilidade_grupo_id' => $grupos['Instituição > Instituições']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_usuario_instituicao'],
                [
                    'nome' => 'Visualizar Usuários Instituição',
                    'habilidade_grupo_id' => $grupos['Instituição > Instituições > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_usuario_instituicao'],
                [
                    'nome' => 'Cadastrar Usuários Instituição',
                    'habilidade_grupo_id' => $grupos['Instituição > Instituições > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_usuario_instituicao'],
                [
                    'nome' => 'Editar Usuários Instituição',
                    'habilidade_grupo_id' => $grupos['Instituição > Instituições > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'habilidades_usuario_instituicao'],
                [
                    'nome' => 'Habilidades Usuários Instituição',
                    'habilidade_grupo_id' => $grupos['Instituição > Instituições > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_usuario_instituicao'],
                [
                    'nome' => 'Excluir Usuários Instituição',
                    'habilidade_grupo_id' => $grupos['Instituição > Instituições > Usuarios']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_prestador'],
                [
                    'nome' => 'Visualizar Prestadores',
                    'habilidade_grupo_id' => $grupos['Instituição > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_prestador'],
                [
                    'nome' => 'Cadastrar Prestadores',
                    'habilidade_grupo_id' => $grupos['Instituição > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_prestador'],
                [
                    'nome' => 'Editar Prestadores',
                    'habilidade_grupo_id' => $grupos['Instituição > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_prestador'],
                [
                    'nome' => 'Excluir Prestadores',
                    'habilidade_grupo_id' => $grupos['Instituição > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_documento_prestador'],
                [
                    'nome' => 'Visualizar Documentos dos Prestadores',
                    'habilidade_grupo_id' => $grupos['Instituição > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_documento_prestador'],
                [
                    'nome' => 'Editar Documentos dos Prestadores',
                    'habilidade_grupo_id' => $grupos['Instituição > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_documento_prestador'],
                [
                    'nome' => 'Cadastrar Documentos dos Prestadores',
                    'habilidade_grupo_id' => $grupos['Instituição > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_documento_prestador'],
                [
                    'nome' => 'Excluir Documentos dos Prestadores',
                    'habilidade_grupo_id' => $grupos['Instituição > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_especialidade'],
                [
                    'nome' => 'Visualizar Especialidades',
                    'habilidade_grupo_id' => $grupos['Instituição > Especialidades']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_especialidade'],
                [
                    'nome' => 'Cadastrar Especialidades',
                    'habilidade_grupo_id' => $grupos['Instituição > Especialidades']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_especialidade'],
                [
                    'nome' => 'Editar Especialidades',
                    'habilidade_grupo_id' => $grupos['Instituição > Especialidades']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_especialidade'],
                [
                    'nome' => 'Excluir Especialidades',
                    'habilidade_grupo_id' => $grupos['Instituição > Especialidades']->id,
                ]
            ],
            [  // ESPECIALIZAÇOES
                ['nome_unico' => 'visualizar_especializacao'],
                [
                    'nome' => 'Visualizar Especializações',
                    'habilidade_grupo_id' => $grupos['Instituição > Especializações']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_especializacao'],
                [
                    'nome' => 'Cadastrar Especializações',
                    'habilidade_grupo_id' => $grupos['Instituição > Especializações']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_especializacao'],
                [
                    'nome' => 'Editar Especializações',
                    'habilidade_grupo_id' => $grupos['Instituição > Especializações']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_especializacao'],
                [
                    'nome' => 'Excluir Especializações',
                    'habilidade_grupo_id' => $grupos['Instituição > Especializações']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_procedimentos'],
                [
                    'nome' => 'Visualizar Procedimentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Procedimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_procedimentos'],
                [
                    'nome' => 'Cadastrar Procedimentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Procedimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_procedimentos'],
                [
                    'nome' => 'Editar Procedimentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Procedimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_procedimentos'],
                [
                    'nome' => 'Excluir Procedimentos',
                    'habilidade_grupo_id' => $grupos['Clinica > Procedimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_grupos'],
                [
                    'nome' => 'Visualizar Grupos',
                    'habilidade_grupo_id' => $grupos['Clinica > Grupos']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_grupos'],
                [
                    'nome' => 'Cadastrar Grupos',
                    'habilidade_grupo_id' => $grupos['Clinica > Grupos']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_grupos'],
                [
                    'nome' => 'Editar Grupos',
                    'habilidade_grupo_id' => $grupos['Clinica > Grupos']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_grupos'],
                [
                    'nome' => 'Excluir Grupos',
                    'habilidade_grupo_id' => $grupos['Clinica > Grupos']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_logs'],
                [
                    'nome' => 'Visualizar Logs',
                    'habilidade_grupo_id' => $grupos['Logs > Geral']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_ramo'],
                [
                    'nome' => 'Visualizar Ramos',
                    'habilidade_grupo_id' => $grupos['Instituição > Ramos']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_ramo'],
                [
                    'nome' => 'Cadastrar Ramos',
                    'habilidade_grupo_id' => $grupos['Instituição > Ramos']->id,
                ],
            ],

            [
                ['nome_unico' => 'editar_ramo'],
                [
                    'nome' => 'Editar Ramos',
                    'habilidade_grupo_id' => $grupos['Instituição > Ramos']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_ramo'],
                [
                    'nome' => 'Excluir Ramos',
                    'habilidade_grupo_id' => $grupos['Instituição > Ramos']->id,
                ],
            ],
            [
                ['nome_unico' => 'habilidades_ramo'],
                [
                    'nome' => 'Habilidades Ramos',
                    'habilidade_grupo_id' => $grupos['Instituição > Ramos']->id,
                ],
            ],

            [
                ['nome_unico' => 'visualizar_perfil_instituicao'],
                [
                    'nome' => 'Visualizar Perfil de usuario da instituicao',
                    'habilidade_grupo_id' => $grupos['Instituição > Perfil de usuario']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_perfil_instituicao'],
                [
                    'nome' => 'Cadastrar Perfil de usuario da instituicao',
                    'habilidade_grupo_id' => $grupos['Instituição > Perfil de usuario']->id,
                ],
            ],

            [
                ['nome_unico' => 'editar_perfil_instituicao'],
                [
                    'nome' => 'Editar Perfil de usuario da instituicao',
                    'habilidade_grupo_id' => $grupos['Instituição > Perfil de usuario']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_perfil_instituicao'],
                [
                    'nome' => 'Excluir Perfil de usuario da instituicao',
                    'habilidade_grupo_id' => $grupos['Instituição > Perfil de usuario']->id,
                ],
            ],
            [
                ['nome_unico' => 'habilidades_perfil_instituicao'],
                [
                    'nome' => 'Habilidades Perfil de usuario da instituicao',
                    'habilidade_grupo_id' => $grupos['Instituição > Perfil de usuario']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_setor_exame'],
                [
                    'nome' => 'Visualizar Setor Exame',
                    'habilidade_grupo_id' => $grupos['Instituição > Setores Exame']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_setor_exame'],
                [
                    'nome' => 'Cadastrar Setor Exame',
                    'habilidade_grupo_id' => $grupos['Instituição > Setores Exame']->id,
                ],
            ],

            [
                ['nome_unico' => 'editar_setor_exame'],
                [
                    'nome' => 'Editar Setor Exame',
                    'habilidade_grupo_id' => $grupos['Instituição > Setores Exame']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_setor_exame'],
                [
                    'nome' => 'Excluir Setor Exame',
                    'habilidade_grupo_id' => $grupos['Instituição > Setores Exame']->id,
                ],
            ],
        ];

        $entities = [];
        foreach ($habilidades as $habilidade) {
            $wheres = $habilidade[0];
            $attributes = $habilidade[1];
            $entity = Habilidade::query()->where($wheres)->firstOrNew();
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
