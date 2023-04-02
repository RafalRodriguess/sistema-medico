<?php

use App\InstituicaoHabilidade;
use App\InstituicaoHabilidadeGrupo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductionHabilidadesInstituicao extends Seeder
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
            // [ 'nome_unico' => 'visualizar_conclusões' ],
        ];
        foreach ($habilidades as $habilidade) {
            InstituicaoHabilidade::query()->where($habilidade)->delete();
        }

        $grupos = [
            // Adicionar array de wheres para remover grupos
            // Exemplo:
            // ['name' => 'Administração > Administradores']
        ];
        foreach ($grupos as $grupo) {
            InstituicaoHabilidadeGrupo::query()->where($grupo)->delete();
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
                ['nome' => 'Administração > Instituição'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Dashboard'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Administração > Usuário'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Administração > Horario Funcionamento'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Cadastros > Especialidades'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Prestadores'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Exames > Entrega de exames'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Especializações'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Relatório > Pacientes'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Relatório > Pacientes'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Cadastros > Procedimentos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Cadastro de procedimentos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Procedimentos > Modalidades exame'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Procedimentos > Setores exame'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Procedimentos > Motivos de cancelamento'],
                ['categoria' => '2']
            ],
            [
                ['nome' => 'Cadastros > Pessoas'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Fornecedores'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Fornecedores > Documentos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Pessoas > Documentos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Pessoas > Integração Asaplan'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Pessoas > Carteirinha convenio'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Atendimentos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Escalas Médicas'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Setores'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Internação'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Internação > Unidade de Internação'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Internação > Acomodações'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Internação > Motivos de Altas'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Internação > Motivos de Cancelamento de Altas'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Internação > Instituições para transferência'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Internação > Alta hospitalar'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Centros Cirúrgicos e Obstétricos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Centros Cirúrgicos e Obstétricos > Centros Cirúrgicos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Centros Cirúrgicos e Obstétricos > Centros Cirúrgicos > Salas Cirúrgicas'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Centros Cirúrgicos e Obstétricos > Equipe Cirúrgica'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Financeiro'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Origem'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Financeiro > Centro de Custo'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Relatório > Convênios'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Vinculação > Prestadores'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Administração > Agendamentos'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Administração > Atendimentos de urgência'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Cadastros > Centros Cirúrgicos e Obstétricos > Tipo de partos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Estoque > Estoque'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Estoque > Unidades'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Estoque > Classe'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Estoque > Especie'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Estoque > Produto'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Estoque > Solicitações'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Estoque > Motivos de divergência'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Estoque > Entrada'],
                ['categoria' => '6'],
            ],
            [
                ['nome' => 'Cadastros > Compras > Tipo de Compras'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Compras > Comprador'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Compras > Motivo de Cancelamento'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Compras > Motivo de Pedido'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Centros Cirúrgicos e Obstétricos > Tipos de anastesia'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Cadastros > Centros Cirúrgicos e Obstétricos > Motivos de partos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Centros Cirúrgicos e Obstétricos > Motivos de mortes Recem Nascido'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Cadastros > Financeiro > Forma de Pagamento'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Financeiro > Tipo de Documento'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Cadastros > Financeiro > Contas'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Cadastros > Financeiro > Cartão de credito'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Financeiro > Plano de Contas'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Financeiro > Contas a Pagar'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Financeiro > Contas a Receber'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Triagem'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Triagem > Totens'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Triagem > Filas'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Triagem > Classificações'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Triagem > Senhas'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Internação > Pre Internações'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Triagem > Processos'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Cadastros > Centros Cirúrgicos > Grupos de Cirurgias'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Cadastros > Centros Cirúrgicos > Cirurgias'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Cadastros > Centros Cirúrgicos > Vias Acesso'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Cadastros > Centros Cirúrgicos > Equipamentos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Internação > Internação'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Relatório > Demonstrativo Financeiro'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Relatório > Atendimento'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Relatório > Estatísticos'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Relatório > Sancoop'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Pessoas > Convênios Planos'],
                ['categoria' => '5'],
            ],
            [
                ['nome' => 'Pessoas > Convênios'],
                ['categoria' => '5'],
            ],
            [
                ['nome' => 'Pessoas > Convênios Apresentação'],
                ['categoria' => '5'],
            ],
            [
                ['nome' => 'Convênios > Faturamento lotes'],
                ['categoria' => '5'],
            ],

            [
                ['nome' => 'Estoque > Baixa Produtos'],
                ['categoria' => '6'],
            ],
            [
                ['nome' => 'Estoque > Inventário'],
                ['categoria' => '6'],
            ],
            [
                ['nome' => 'Estoque > Saídas de estoque'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Centros Cirúrgicos > Caixas Cirúrgicos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Centros Cirúrgicos > Sangue e Derivados'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Tipos de chamadas dos totens'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Paineis de totem'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Agendamento > Prontuários'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Configurações > Modelo de impressão'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Medicamentos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Administração > Automação Whatsapp Atendimento Ambulatorial'],
                ['categoria' => '1'],
            ],
            [
                ['nome' => 'Cadastros > Procedimentos > Grupos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Procedimentos > Pacotes'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Configurações > Modelo de atestado'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Configurações > Modelo de encaminhamento'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Configurações > Modelo de laudo'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Configurações > Modelo de relatório'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Configurações > Modelo de exame'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Configurações > Modelo de receituário'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Configurações > Modelo de recibo'],
                ['categoria' => '2'],
            ],
            // [
            //     ['nome' => 'Configurações > Configurações de prontuário'],
            //     ['categoria' => '2'],
            // ],
            [
                ['nome' => 'Configurações > Modelo de prontuário'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Prestadores > Agenda ausente'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Faturamento SUS'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Instituição > Configuração fiscal'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Nota Fiscal'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Financeiro > Movimentação'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Agendamento > Odontológico'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastro > Prestadore Solicitantes'],
                ['categoria' => '2'],
            ],            
            [
                ['nome' => 'Cadastro > Atividades médicas'],
                ['categoria' => '2'],
            ],            
            [
                ['nome' => 'Odontológico > Dashboard'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Relatório > Odontológico'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Relatório > Auditoria'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Convênio > Grupos de Faturamento'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Procedimento > Faturamentos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Procedimento > Regras de Cobrança'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Procedimento > Regras de Cobrança Itens'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Procedimento > Procedimentos dos Atendimento'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Chat'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Configurações > Modelo de Arquivos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Procedimento > Vincular Tuss'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Procedimento > Vincular Brasíndice'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Financeiro > Maquinas de catão'],
                ['categoria' => '2'],
            ],

            [
                ['nome' => 'Relatório > Financeiro'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Atendimento > Compromissos'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Paciente > Motivos Atendimento'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Paciente > Atendimento Paciente'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Atendimento > Motivos para baixa de estoque'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Instituição > Motivos Conclusões'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Configurações > Modelos de Conclusões'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Relatório > Conclusão'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Financeiro > Importar XML nota'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Cadastros > Agendamentos Lista de Espera'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Configurações > Modelos de Termos e Folha de Sala'],
                ['categoria' => '2'],
            ],
            [
                ['nome' => 'Relatório > Registro de Log'],
                ['categoria' => '2'],
            ],
            //MENU EXAMES
            [
                ['nome' => 'Exames'],
                ['categoria' => '2'],
            ],
        ];

        $entities = [];
        foreach ($grupos as $grupo) {
            $wheres = $grupo[0];
            $attributes = $grupo[1];
            $entity = InstituicaoHabilidadeGrupo::query()->where($wheres)->firstOrNew();
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
                ['nome_unico' => 'visualizar_documentos_fornecedores'],
                [
                    'nome' => 'Visualizar Documentos dos Fornecedores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Fornecedores > Documentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_documentos_fornecedores'],
                [
                    'nome' => 'Cadastrar Documentos dos Fornecedores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Fornecedores > Documentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_documentos_fornecedores'],
                [
                    'nome' => 'Editar Documentos dos Fornecedores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Fornecedores > Documentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_documentos_fornecedores'],
                [
                    'nome' => 'Excluir Documentos dos Fornecedores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Fornecedores > Documentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_fornecedores'],
                [
                    'nome' => 'Visualizar Fornecedores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Fornecedores']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_fornecedores'],
                [
                    'nome' => 'Cadastrar Fornecedores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Fornecedores']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_fornecedores'],
                [
                    'nome' => 'Editar Fornecedores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Fornecedores']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_fornecedores'],
                [
                    'nome' => 'Excluir Fornecedores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Fornecedores']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_documentos_pessoas'],
                [
                    'nome' => 'Visualizar Documentos das Pessoas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas > Documentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_documentos_pessoas'],
                [
                    'nome' => 'Cadastrar Documentos das Pessoas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas > Documentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_documentos_pessoas'],
                [
                    'nome' => 'Editar Documentos das Pessoas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas > Documentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_documentos_pessoas'],
                [
                    'nome' => 'Excluir Documentos das Pessoas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas > Documentos']->id,
                ]
            ],
            /*INTEGRAÇÃO ASAPLAN*/
            // [
            //     ['nome_unico' => 'sincronizar_pacientes_asaplan'],
            //     [
            //         'nome' => 'Sincronizar pacientes com filiais Asaplan',
            //         'habilidade_grupo_id' => $grupos['Cadastros > Pessoas > Integração Asaplan']->id,
            //     ]
            // ],
            [
                ['nome_unico' => 'editar_dados_integracao_pacientes_asaplan'],
                [
                    'nome' => 'Editar dados de integração de clientes Asaplan',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas > Integração Asaplan']->id,
                ]
            ],
            /*FIM INTEGRAÇÃO ASAPLAN*/
            [
                ['nome_unico' => 'visualizar_pessoas'],
                [
                    'nome' => 'Visualizar Pessoas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_pessoas'],
                [
                    'nome' => 'Cadastrar Pessoas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_pessoas'],
                [
                    'nome' => 'Editar Pessoas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_pessoas'],
                [
                    'nome' => 'Excluir Pessoas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_equipes_cirurgicas'],
                [
                    'nome' => 'Visualizar Equipes Cirúrgicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Equipe Cirúrgica']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_equipes_cirurgicas'],
                [
                    'nome' => 'Cadastrar Equipes Cirúrgicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Equipe Cirúrgica']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_equipes_cirurgicas'],
                [
                    'nome' => 'Editar Equipes Cirúrgicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Equipe Cirúrgica']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_equipes_cirurgicas'],
                [
                    'nome' => 'Excluir Equipes Cirúrgicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Equipe Cirúrgica']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_instituicoes_transferencia'],
                [
                    'nome' => 'Visualizar Instituicoes para transferência',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Instituições para transferência']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_instituicoes_transferencia'],
                [
                    'nome' => 'Cadastrar Instituicoes para transferência',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Instituições para transferência']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_instituicoes_transferencia'],
                [
                    'nome' => 'Editar Instituicoes para transferência',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Instituições para transferência']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_instituicoes_transferencia'],
                [
                    'nome' => 'Excluir Instituicoes para transferência',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Instituições para transferência']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_escalas_medicas'],
                [
                    'nome' => 'Visualizar Escalas Médicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Escalas Médicas']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_escalas_medicas'],
                [
                    'nome' => 'Cadastrar Escalas Médicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Escalas Médicas']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_escalas_medicas'],
                [
                    'nome' => 'Editar Escalas Médicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Escalas Médicas']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_escalas_medicas'],
                [
                    'nome' => 'Excluir Escalas Médicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Escalas Médicas']->id,
                ]
            ],

            [
                ['nome_unico' => 'duplicar_escalas_medicas'],
                [
                    'nome' => 'Duplicar Escalas Médicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Escalas Médicas']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_salas_cirurgicas'],
                [
                    'nome' => 'Visualizar Salas Cirúrgicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Centros Cirúrgicos > Salas Cirúrgicas']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_salas_cirurgicas'],
                [
                    'nome' => 'Cadastrar Salas Cirúrgicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Centros Cirúrgicos > Salas Cirúrgicas']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_salas_cirurgicas'],
                [
                    'nome' => 'Editar Salas Cirúrgicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Centros Cirúrgicos > Salas Cirúrgicas']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_salas_cirurgicas'],
                [
                    'nome' => 'Excluir Salas Cirúrgicas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Centros Cirúrgicos > Salas Cirúrgicas']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_centros_cirurgicos'],
                [
                    'nome' => 'Visualizar Centros Cirúrgicos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Centros Cirúrgicos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_centros_cirurgicos'],
                [
                    'nome' => 'Cadastrar Centros Cirúrgicos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Centros Cirúrgicos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_centros_cirurgicos'],
                [
                    'nome' => 'Editar Centros Cirúrgicos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Centros Cirúrgicos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_centros_cirurgicos'],
                [
                    'nome' => 'Excluir Centros Cirúrgicos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Centros Cirúrgicos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_motivos_cancelamento_altas'],
                [
                    'nome' => 'Visualizar Motivos de Cancelamento de Altas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Motivos de Cancelamento de Altas']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_motivos_cancelamento_altas'],
                [
                    'nome' => 'Cadastrar Motivos de Cancelamento de Altas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Motivos de Cancelamento de Altas']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_motivos_cancelamento_altas'],
                [
                    'nome' => 'Editar Motivos de Cancelamento de Altas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Motivos de Cancelamento de Altas']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_motivos_cancelamento_altas'],
                [
                    'nome' => 'Excluir Motivos de Cancelamento de Altas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Motivos de Cancelamento de Altas']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_motivos_altas'],
                [
                    'nome' => 'Visualizar Motivos de Altas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Motivos de Altas']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_motivos_altas'],
                [
                    'nome' => 'Cadastrar Motivos de Altas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Motivos de Altas']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_motivos_altas'],
                [
                    'nome' => 'Editar Motivos de Altas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Motivos de Altas']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_motivos_altas'],
                [
                    'nome' => 'Excluir Motivos de Altas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Motivos de Altas']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_leitos'],
                [
                    'nome' => 'Visualizar Leitos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Unidade de Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_leitos'],
                [
                    'nome' => 'Cadastrar Leitos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Unidade de Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_leitos'],
                [
                    'nome' => 'Editar Leitos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Unidade de Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_leitos'],
                [
                    'nome' => 'Excluir Leitos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Unidade de Internação']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_acomodacoes'],
                [
                    'nome' => 'Visualizar Acomodações',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Acomodações']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_acomodacoes'],
                [
                    'nome' => 'Cadastrar Acomodações',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Acomodações']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_acomodacoes'],
                [
                    'nome' => 'Editar Acomodações',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Acomodações']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_acomodacoes'],
                [
                    'nome' => 'Excluir Acomodações',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Acomodações']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_origem'],
                [
                    'nome' => 'Visualizar Origem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Origem']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_origem'],
                [
                    'nome' => 'Cadastrar Origem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Origem']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_origem'],
                [
                    'nome' => 'Editar Origem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Origem']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_origem'],
                [
                    'nome' => 'Excluir Origem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Origem']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_unidade_internacao'],
                [
                    'nome' => 'Visualizar Unidade de Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Unidade de Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_unidade_internacao'],
                [
                    'nome' => 'Cadastrar Unidade de Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Unidade de Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_unidade_internacao'],
                [
                    'nome' => 'Editar Unidade de Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Unidade de Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_unidade_internacao'],
                [
                    'nome' => 'Excluir Unidade de Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Unidade de Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_centro_de_custo'],
                [
                    'nome' => 'Visualizar Centro de Custo',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Centro de Custo']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_centro_de_custo'],
                [
                    'nome' => 'Cadastrar Centro de Custo',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Centro de Custo']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_centro_de_custo'],
                [
                    'nome' => 'Editar Centro de Custo',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Centro de Custo']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_centro_de_custo'],
                [
                    'nome' => 'Excluir Centro de Custo',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Centro de Custo']->id,
                ]
            ],

            [
                ['nome_unico' => 'editar_instituicao'],
                [
                    'nome' => 'Editar Instituição',
                    'habilidade_grupo_id' => $grupos['Administração > Instituição']->id,
                ]
            ],

            [
                ['nome_unico' => 'config_instituicao'],
                [
                    'nome' => 'Configurar Instituição',
                    'habilidade_grupo_id' => $grupos['Administração > Instituição']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_usuario'],
                [
                    'nome' => 'Visualizar Usuário',
                    'habilidade_grupo_id' => $grupos['Administração > Usuário']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_usuario'],
                [
                    'nome' => 'Cadastrar Usuário',
                    'habilidade_grupo_id' => $grupos['Administração > Usuário']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_usuario'],
                [
                    'nome' => 'Editar Usuário',
                    'habilidade_grupo_id' => $grupos['Administração > Usuário']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_usuario'],
                [
                    'nome' => 'Excluir Usuário',
                    'habilidade_grupo_id' => $grupos['Administração > Usuário']->id,
                ]
            ],
            [
                ['nome_unico' => 'habilidade_usuario'],
                [
                    'nome' => 'Habilidades Usuário',
                    'habilidade_grupo_id' => $grupos['Administração > Usuário']->id,
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
                    'habilidade_grupo_id' => $grupos['Administração > Instituição']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_prestador'],
                [
                    'nome' => 'Visualizar Prestadores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_prestador'],
                [
                    'nome' => 'Cadastrar Prestadores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_prestador'],
                [
                    'nome' => 'Editar Prestadores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_documento_prestador'],
                [
                    'nome' => 'Visualizar Documentos de Prestadores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_documento_prestador'],
                [
                    'nome' => 'Cadastrar Documentos de Prestadores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_documento_prestador'],
                [
                    'nome' => 'Editar Documentos de Prestadores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_documento_prestador'],
                [
                    'nome' => 'Excluir Documentos de Prestadores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_agenda_prestador'],
                [
                    'nome' => 'Editar Agenda dos Prestadores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_prestador'],
                [
                    'nome' => 'Excluir Prestadores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_setores'],
                [
                    'nome' => 'Visualizar Setores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Setores']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_setores'],
                [
                    'nome' => 'Cadastrar Setores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Setores']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_setores'],
                [
                    'nome' => 'Editar Setores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Setores']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_setores'],
                [
                    'nome' => 'Excluir Setores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Setores']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_especialidade'],
                [
                    'nome' => 'Visualizar Especialidades',
                    'habilidade_grupo_id' => $grupos['Cadastros > Especialidades']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_especialidade'],
                [
                    'nome' => 'Cadastrar Especialidades',
                    'habilidade_grupo_id' => $grupos['Cadastros > Especialidades']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_especialidade'],
                [
                    'nome' => 'Editar Especialidades',
                    'habilidade_grupo_id' => $grupos['Cadastros > Especialidades']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_especialidade'],
                [
                    'nome' => 'Excluir Especialidades',
                    'habilidade_grupo_id' => $grupos['Cadastros > Especialidades']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_paciente'],
                [
                    'nome' => 'Visualizar Pacientes',
                    'habilidade_grupo_id' => $grupos['Relatório > Pacientes']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_cadastro_procedimentos'],
                [
                    'nome' => 'Visualizar cadastro de procedimento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Cadastro de procedimentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_cadastro_procedimentos'],
                [
                    'nome' => 'Cadastrar cadastro de procedimento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Cadastro de procedimentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_cadastro_procedimentos'],
                [
                    'nome' => 'Editar cadastro de procedimento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Cadastro de procedimentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_cadastro_procedimentos'],
                [
                    'nome' => 'Excluir cadastro de procedimento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Cadastro de procedimentos']->id,
                ]
            ],

            //Pacotes de procedimentos
            [
                ['nome_unico' => 'visualizar_pacotes'],
                [
                    'nome' => 'Visualizar pacotes',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Pacotes']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_pacotes'],
                [
                    'nome' => 'Cadastrar pacotes',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Pacotes']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_pacotes'],
                [
                    'nome' => 'Editar pacotes',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Pacotes']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_pacotes'],
                [
                    'nome' => 'Excluir pacotes',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Pacotes']->id,
                ]
            ],

            [
                ['nome_unico' => 'vincular_pacotes'],
                [
                    'nome' => 'Vincular pacotes',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Pacotes']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_procedimentos'],
                [
                    'nome' => 'Visualizar Procedimentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_procedimentos'],
                [
                    'nome' => 'Cadastrar Procedimentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_agenda_procedimento'],
                [
                    'nome' => 'Editar Agenda dos Procedimentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_agenda_grupo'],
                [
                    'nome' => 'Editar Agenda dos grupos dos Procedimentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'retirar_procedimento'],
                [
                    'nome' => 'Retirar Procedimentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_atendimentos'],
                [
                    'nome' => 'Visualizar Caráter de Atendimentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Atendimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_atendimentos'],
                [
                    'nome' => 'Cadastrar Caráter de Atendimentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Atendimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_atendimentos'],
                [
                    'nome' => 'Editar Caráter de Atendimentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Atendimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_atendimentos'],
                [
                    'nome' => 'Excluir Caráter de Atendimentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Atendimentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_convenios'],
                [
                    'nome' => 'Cadastrar Convênios',
                    'habilidade_grupo_id' => $grupos['Relatório > Convênios']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_convenios'],
                [
                    'nome' => 'Visualizar Convênios',
                    'habilidade_grupo_id' => $grupos['Relatório > Convênios']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_convenios'],
                [
                    'nome' => 'Editar Convênios',
                    'habilidade_grupo_id' => $grupos['Relatório > Convênios']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_convenios'],
                [
                    'nome' => 'Excluir Convênios',
                    'habilidade_grupo_id' => $grupos['Relatório > Convênios']->id,
                ]
            ],
            [
                ['nome_unico' => 'vincular_procedimentos'],
                [
                    'nome' => 'Vincular procedimento ao profissional',
                    'habilidade_grupo_id' => $grupos['Vinculação > Prestadores']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_agendamentos'],
                [
                    'nome' => 'Visualizar agendamentos',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_saldo_pagarme'],
                [
                    'nome' => 'Visualizar Saldo Pagarme',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cancelar_reativar_horario'],
                [
                    'nome' => 'Cancelar / Reativar horário',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'realizar_encaixe'],
                [
                    'nome' => 'Realizar encaixe',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'ocultar_valor_proc_imprime_agendamento'],
                [
                    'nome' => 'Ocultar valor de procedimento ao imprimir agendamento',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'agendar_paciente_debito_asaplan'],
                [
                    'nome' => 'Permite agendar o paciente com débito(s) Asaplan',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_tipo_partos'],
                [
                    'nome' => 'Visualizar Tipo de Partos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Tipo de partos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_tipo_partos'],
                [
                    'nome' => 'Cadastrar Tipo de Partos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Tipo de partos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_tipo_partos'],
                [
                    'nome' => 'Editar Tipo de Partos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Tipo de partos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_tipo_partos'],
                [
                    'nome' => 'Excluir Tipo de Partos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Tipo de partos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_estoques'],
                [
                    'nome' => 'Visualizar Estoques',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Estoque']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_estoques'],
                [
                    'nome' => 'Cadastrar Estoques',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Estoque']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_estoques'],
                [
                    'nome' => 'Editar Estoques',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Estoque']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_estoques'],
                [
                    'nome' => 'Excluir Estoques',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Estoque']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_unidade'],
                [
                    'nome' => 'Visualizar Unidades',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Unidades']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_unidade'],
                [
                    'nome' => 'Cadastrar Unidades',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Unidades']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_unidade'],
                [
                    'nome' => 'Editar Unidades',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Unidades']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_unidade'],
                [
                    'nome' => 'Excluir Unidades',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Unidades']->id,
                ]
            ],


            [
                ['nome_unico' => 'visualizar_classes'],
                [
                    'nome' => 'Visualizar Classes',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Classe']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_classes'],
                [
                    'nome' => 'Cadastrar Classes',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Classe']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_classes'],
                [
                    'nome' => 'Editar Classes',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Classe']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_classes'],
                [
                    'nome' => 'Excluir Classes',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Classe']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_especies'],
                [
                    'nome' => 'Visualizar Especies',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Especie']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_especies'],
                [
                    'nome' => 'Cadastrar Especies',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Especie']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_especies'],
                [
                    'nome' => 'Editar Especies',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Especie']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_especies'],
                [
                    'nome' => 'Excluir Especies',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Especie']->id,
                ]
            ],


            [
                ['nome_unico' => 'visualizar_produtos'],
                [
                    'nome' => 'Visualizar Produtos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Produto']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_produtos'],
                [
                    'nome' => 'Cadastrar Produtos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Produto']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_produtos'],
                [
                    'nome' => 'Editar Produtos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Produto']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_produtos'],
                [
                    'nome' => 'Excluir Produtos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Produto']->id,
                ]
            ],



            [
                ['nome_unico' => 'visualizar_estoque_baixa_produtos'],
                [
                    'nome' => 'Visualizar Estoque Baixa Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Baixa Produtos']->id,
                ]
            ],

            [
                ['nome_unico' => 'cadastrar_estoque_baixa_produtos'],
                [
                    'nome' => 'Cadastrar Estoque Baixa Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Baixa Produtos']->id,
                ]
            ],

            [
                ['nome_unico' => 'editar_estoque_baixa_produtos'],
                [
                    'nome' => 'Editar Estoque Baixa Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Baixa Produtos']->id,
                ]
            ],

            [
                ['nome_unico' => 'excluir_estoque_baixa_produtos'],
                [
                    'nome' => 'Excluir Estoque Baixa Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Baixa Produtos']->id,
                ]
            ],


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
                ['nome_unico' => 'visualizar_tipos_anestesia'],
                [
                    'nome' => 'Visualizar Tipos de Anestesia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Tipos de anastesia']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_tipos_anestesia'],
                [
                    'nome' => 'Cadastrar Tipos de Anestesia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Tipos de anastesia']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_tipos_anestesia'],
                [
                    'nome' => 'Editar Tipos de Anestesia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Tipos de anastesia']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_tipos_anestesia'],
                [
                    'nome' => 'Excluir Tipos de Anestesia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Tipos de anastesia']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_motivos_partos'],
                [
                    'nome' => 'Visualizar Motivos de Partos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Motivos de partos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_motivos_partos'],
                [
                    'nome' => 'Cadastrar Motivos de Partos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Motivos de partos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_motivos_partos'],
                [
                    'nome' => 'Editar Motivos de Partos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Motivos de partos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_motivos_partos'],
                [
                    'nome' => 'Excluir Motivos de Partos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Motivos de partos']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_motivos_mortes_rn'],
                [
                    'nome' => 'Visualizar Motivos de mortes de recem nascidos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Motivos de mortes Recem Nascido']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_motivos_mortes_rn'],
                [
                    'nome' => 'Cadastrar Motivos de mortes de recem nascidos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Motivos de mortes Recem Nascido']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_motivos_mortes_rn'],
                [
                    'nome' => 'Editar Motivos de mortes de recem nascidos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Motivos de mortes Recem Nascido']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_motivos_mortes_rn'],
                [
                    'nome' => 'Excluir Motivos de mortes de recem nascidos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos e Obstétricos > Motivos de mortes Recem Nascido']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_forma_pagamento'],
                [
                    'nome' => 'Visualizar Forma de Pagamento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Forma de Pagamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_forma_pagamento'],
                [
                    'nome' => 'Cadastrar Forma de Pagamento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Forma de Pagamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_forma_pagamento'],
                [
                    'nome' => 'Editar Forma de Pagamento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Forma de Pagamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_forma_pagamento'],
                [
                    'nome' => 'Excluir Forma de Pagamento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Forma de Pagamento']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_tipos_documentos'],
                [
                    'nome' => 'Visualizar Tipos de Documentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Tipo de Documento']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_tipos_documentos'],
                [
                    'nome' => 'Cadastrar Tipos de Documentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Tipo de Documento']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_tipos_documentos'],
                [
                    'nome' => 'Editar Tipos de Documentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Tipo de Documento']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_tipos_documentos'],
                [
                    'nome' => 'Excluir Tipos de Documentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Tipo de Documento']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_contas'],
                [
                    'nome' => 'Visualizar contas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_contas'],
                [
                    'nome' => 'Cadastrar contas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_contas'],
                [
                    'nome' => 'Editar contas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_contas'],
                [
                    'nome' => 'Excluir contas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_cartao_credito'],
                [
                    'nome' => 'Visualizar Cartão decredito',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Cartão de credito']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_cartao_credito'],
                [
                    'nome' => 'Cadastrar Cartão decredito',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Cartão de credito']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_cartao_credito'],
                [
                    'nome' => 'Editar Cartão decredito',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Cartão de credito']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_cartao_credito'],
                [
                    'nome' => 'Excluir Cartão decredito',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Cartão de credito']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_plano_contas'],
                [
                    'nome' => 'Visualizar contas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Plano de Contas']->id,
                ]
            ],

            [
                ['nome_unico' => 'cadastrar_plano_contas'],
                [
                    'nome' => 'Cadastrar contas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Plano de Contas']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_plano_contas'],
                [
                    'nome' => 'Editar contas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Plano de Contas']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_plano_contas'],
                [
                    'nome' => 'Excluir contas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Plano de Contas']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_tipo_compras'],
                [
                    'nome' => 'Visualizar Tipo de Compras',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Tipo de Compras']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_tipo_compras'],
                [
                    'nome' => 'Cadastrar Tipo de Compras',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Tipo de Compras']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_tipo_compras'],
                [
                    'nome' => 'Editar Tipo de Compras',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Tipo de Compras']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_tipo_compras'],
                [
                    'nome' => 'Excluir Tipo de Compras',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Tipo de Compras']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_comprador'],
                [
                    'nome' => 'Visualizar Comprador',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Comprador']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_comprador'],
                [
                    'nome' => 'Cadastrar Comprador',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Comprador']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_comprador'],
                [
                    'nome' => 'Editar Comprador',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Comprador']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_comprador'],
                [
                    'nome' => 'Excluir Comprador',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Comprador']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_motivo_cancelamento'],
                [
                    'nome' => 'Visualizar Motivo de Cancelamento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Motivo de Cancelamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_motivo_cancelamento'],
                [
                    'nome' => 'Cadastrar Motivo de Cancelamento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Motivo de Cancelamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_motivo_cancelamento'],
                [
                    'nome' => 'Editar Motivo de Cancelamento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Motivo de Cancelamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_motivo_cancelamento'],
                [
                    'nome' => 'Excluir Motivo de Cancelamento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Motivo de Cancelamento']->id,
                ]
            ],


            [
                ['nome_unico' => 'visualizar_motivo_pedido'],
                [
                    'nome' => 'Visualizar Motivo de Pedido',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Motivo de Pedido']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_motivo_pedido'],
                [
                    'nome' => 'Cadastrar Motivo de Pedido',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Motivo de Pedido']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_motivo_pedido'],
                [
                    'nome' => 'Editar Motivo de Pedido',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Motivo de Pedido']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_motivo_pedido'],
                [
                    'nome' => 'Excluir Motivo de Pedido',
                    'habilidade_grupo_id' => $grupos['Cadastros > Compras > Motivo de Pedido']->id,
                ]
            ],
            // Modalidades de exame
            [
                ['nome_unico' => 'visualizar_modalidades_exame'],
                [
                    'nome' => 'Visualizar modalidades de exame',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Modalidades exame']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_modalidades_exame'],
                [
                    'nome' => 'Cadastrar modalidades de exame',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Modalidades exame']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_modalidades_exame'],
                [
                    'nome' => 'Editar modalidades de exame',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Modalidades exame']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_modalidades_exame'],
                [
                    'nome' => 'Excluir modalidades de exame',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Modalidades exame']->id,
                ],
            ],
            // Setores de exame
            [
                ['nome_unico' => 'visualizar_setores_exame'],
                [
                    'nome' => 'Visualizar setores de exame',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Setores exame']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_setores_exame'],
                [
                    'nome' => 'Cadastrar setores de exame',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Setores exame']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_setores_exame'],
                [
                    'nome' => 'Editar setores de exame',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Setores exame']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_setores_exame'],
                [
                    'nome' => 'Excluir setores de exame',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Setores exame']->id,
                ],
            ],
            // Motivos de cancelamento de exame
            [
                ['nome_unico' => 'visualizar_motivos_cancelamento_exame'],
                [
                    'nome' => 'Visualizar motivos de cancelamento',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Motivos de cancelamento']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_motivos_cancelamento_exame'],
                [
                    'nome' => 'Cadastrar motivos de cancelamento de exame',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Motivos de cancelamento']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_motivos_cancelamento_exame'],
                [
                    'nome' => 'Editar motivos de cancelamento de exame',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Motivos de cancelamento']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_motivos_cancelamento_exame'],
                [
                    'nome' => 'Excluir motivos de cancelamento de exame',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Motivos de cancelamento']->id,
                ],
            ],
            // Totens
            [
                ['nome_unico' => 'visualizar_totens'],
                [
                    'nome' => 'Visualizar totens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Totens']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_totens'],
                [
                    'nome' => 'Cadastrar totens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Totens']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_totens'],
                [
                    'nome' => 'Editar totens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Totens']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_totens'],
                [
                    'nome' => 'Excluir totens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Totens']->id,
                ],
            ],
            // Filas de triagem
            [
                ['nome_unico' => 'visualizar_filas_triagem'],
                [
                    'nome' => 'Visualizar filas de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Filas']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_filas_triagem'],
                [
                    'nome' => 'Cadastrar filas de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Filas']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_filas_triagem'],
                [
                    'nome' => 'Editar filas de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Filas']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_filas_triagem'],
                [
                    'nome' => 'Excluir filas de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Filas']->id,
                ],
            ],
            // Classificações de triagem
            [
                ['nome_unico' => 'visualizar_classificacoes_triagem'],
                [
                    'nome' => 'Visualizar classificações de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Classificações']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_classificacoes_triagem'],
                [
                    'nome' => 'Cadastrar classificações de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Classificações']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_classificacoes_triagem'],
                [
                    'nome' => 'Editar classificações de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Classificações']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_classificacoes_triagem'],
                [
                    'nome' => 'Excluir classificações de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Classificações']->id,
                ],
            ],
            // Triagens
            [
                ['nome_unico' => 'visualizar_triagens'],
                [
                    'nome' => 'Visualizar triagens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_triagens'],
                [
                    'nome' => 'Editar triagens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_triagens'],
                [
                    'nome' => 'Visualizar triagens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_triagens'],
                [
                    'nome' => 'Excluir triagens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem']->id,
                ],
            ],
            // Retirar senhas triagens
            [
                ['nome_unico' => 'cadastrar_triagens'],
                [
                    'nome' => 'Retirar senhas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem']->id,
                ],
            ],
            // Chamar senhas
            [
                ['nome_unico' => 'chamar_senhas'],
                [
                    'nome' => 'Chamar senhas',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem']->id,
                ],
            ],

            [
                ['nome_unico' => 'visualizar_pre_internacao'],
                [
                    'nome' => 'Visualizar Pre Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Pre Internações']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_pre_internacao'],
                [
                    'nome' => 'Cadastrar Pre Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Pre Internações']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_pre_internacao'],
                [
                    'nome' => 'Editar Pre Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Pre Internações']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_pre_internacao'],
                [
                    'nome' => 'Excluir Pre Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Pre Internações']->id,
                ]
            ],

            // Processos de triagem
            [
                ['nome_unico' => 'visualizar_processos_triagem'],
                [
                    'nome' => 'Visualizar processos de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Processos']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_processos_triagem'],
                [
                    'nome' => 'Cadastrar processos de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Processos']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_processos_triagem'],
                [
                    'nome' => 'Editar processos de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Processos']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_processos_triagem'],
                [
                    'nome' => 'Excluir processos de triagem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Triagem > Processos']->id,
                ],
            ],

            // Grupos Cirurgics
            [
                ['nome_unico' => 'visualizar_grupos_cirurgias'],
                [
                    'nome' => 'Visualizar grupo de cirurgia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Grupos de Cirurgias']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_grupos_cirurgias'],
                [
                    'nome' => 'Cadastrar grupo de cirurgia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Grupos de Cirurgias']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_grupos_cirurgias'],
                [
                    'nome' => 'Editar grupo de cirurgia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Grupos de Cirurgias']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_grupos_cirurgias'],
                [
                    'nome' => 'Excluir grupo de cirurgia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Grupos de Cirurgias']->id,
                ],
            ],

            // Cirurgias
            [
                ['nome_unico' => 'visualizar_cirurgias'],
                [
                    'nome' => 'Visualizar cirurgia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Cirurgias']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_cirurgias'],
                [
                    'nome' => 'Cadastrar cirurgia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Cirurgias']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_cirurgias'],
                [
                    'nome' => 'Editar cirurgia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Cirurgias']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_cirurgias'],
                [
                    'nome' => 'Excluir cirurgia',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Cirurgias']->id,
                ],
            ],

            // Vias Acesso
            [
                ['nome_unico' => 'visualizar_vias_acesso'],
                [
                    'nome' => 'Visualizar Vias de Acesso',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Vias Acesso']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_vias_acesso'],
                [
                    'nome' => 'Cadastrar Vias de Acesso',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Vias Acesso']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_vias_acesso'],
                [
                    'nome' => 'Editar Vias de Acesso',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Vias Acesso']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_vias_acesso'],
                [
                    'nome' => 'Excluir Vias de Acesso',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Vias Acesso']->id,
                ],
            ],

            // Equipamentos
            [
                ['nome_unico' => 'visualizar_equipamentos'],
                [
                    'nome' => 'Visualizar Equipamentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Equipamentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_equipamentos'],
                [
                    'nome' => 'Cadastrar Equipamentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Equipamentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_equipamentos'],
                [
                    'nome' => 'Editar Equipamentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Equipamentos']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_equipamentos'],
                [
                    'nome' => 'Excluir Equipamentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Centros Cirúrgicos > Equipamentos']->id,
                ],
            ],

            [
                ['nome_unico' => 'visualizar_contas_pagar'],
                [
                    'nome' => 'Visualizar Contas a pagar',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Pagar']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_contas_pagar'],
                [
                    'nome' => 'Cadastrar Contas a pagar',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Pagar']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_contas_pagar'],
                [
                    'nome' => 'Editar Contas a pagar',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Pagar']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_contas_pagar'],
                [
                    'nome' => 'Excluir Contas a pagar',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Pagar']->id,
                ]
            ],

            [
                ['nome_unico' => 'pagar_contas_pagar'],
                [
                    'nome' => 'Pagar Contas a pagar',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Pagar']->id,
                ]
            ],

            [
                ['nome_unico' => 'estornar_contas_pagar'],
                [
                    'nome' => 'Estornar Contas a pagar',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Pagar']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_contas_receber'],
                [
                    'nome' => 'Visualizar Contas a receber',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Receber']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_contas_receber'],
                [
                    'nome' => 'Cadastrar Contas a receber',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Receber']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_contas_receber'],
                [
                    'nome' => 'Editar Contas a receber',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Receber']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_contas_receber'],
                [
                    'nome' => 'Excluir Contas a receber',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Receber']->id,
                ]
            ],

            [
                ['nome_unico' => 'receber_contas_receber'],
                [
                    'nome' => 'Receber Contas a receber',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Receber']->id,
                ]
            ],

            [
                ['nome_unico' => 'estornar_contas_receber'],
                [
                    'nome' => 'Estornar Contas a receber',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Receber']->id,
                ]
            ],



            // SOLICITAÇÕES DE ESTOQUE
            [
                ['nome_unico' => 'visualizar_solicitacoes_estoque'],
                [
                    'nome' => 'Visualizar Solicitações de estoque',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Solicitações']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_solicitacoes_estoque'],
                [
                    'nome' => 'Cadastrar Solicitações de estoque',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Solicitações']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_solicitacoes_estoque'],
                [
                    'nome' => 'Editar Solicitações de estoque',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Solicitações']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_solicitacoes_estoque'],
                [
                    'nome' => 'Excluir Solicitações de estoque',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Solicitações']->id,
                ]
            ],

            // Atender solicitacoes de estoque
            [
                ['nome_unico' => 'atender_solicitacoes_estoque'],
                [
                    'nome' => 'Atender solicitações de estoque',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Solicitações']->id,
                ]
            ],

            //Internação
            [
                ['nome_unico' => 'visualizar_internacao'],
                [
                    'nome' => 'Visualizar Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_internacao'],
                [
                    'nome' => 'Cadastrar Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_internacao'],
                [
                    'nome' => 'Editar Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_internacao'],
                [
                    'nome' => 'Excluir Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'realizar_alta_internacao'],
                [
                    'nome' => 'Realizar Alta Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Internação']->id,
                ]
            ],
            [
                ['nome_unico' => 'cancelar_alta_internacao'],
                [
                    'nome' => 'Cancelar Alta Internação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Internação']->id,
                ]
            ],

            [
                ['nome_unico' => 'troca_leito_internacao'],
                [
                    'nome' => 'Transferencia de leito',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Internação']->id,
                ]
            ],

            [
                ['nome_unico' => 'troca_medico_internacao'],
                [
                    'nome' => 'Transferencia de medico',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Internação']->id,
                ]
            ],

            [
                ['nome_unico' => 'transferir_instituicao_internacao'],
                [
                    'nome' => 'Transferencia de instituicão',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Internação']->id,
                ]
            ],

            //Carteirinha Convenio
            [
                ['nome_unico' => 'visualizar_carteirinha'],
                [
                    'nome' => 'Visualizar Carteirnha Convenio',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas > Carteirinha convenio']->id,
                ]
            ],

            [
                ['nome_unico' => 'cadastrar_carteirinha'],
                [
                    'nome' => 'Cadastrar Carteirnha Convenio',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas > Carteirinha convenio']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_carteirinha'],
                [
                    'nome' => 'Editar Carteirnha Convenio',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas > Carteirinha convenio']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_carteirinha'],
                [
                    'nome' => 'Excluir Carteirnha Convenio',
                    'habilidade_grupo_id' => $grupos['Cadastros > Pessoas > Carteirinha convenio']->id,
                ]
            ],


            // Atendimentos de urgência
            [
                ['nome_unico' => 'visualizar_atendimentos_urgencia'],
                [
                    'nome' => 'Visualizar atendimentos de urgência',
                    'habilidade_grupo_id' => $grupos['Administração > Atendimentos de urgência']->id,
                ]
            ],
            [
                ['nome_unico' => 'chamar_pacientes_atendimentos_urgencia'],
                [
                    'nome' => 'Chamar pacientes para atendimento de urgência',
                    'habilidade_grupo_id' => $grupos['Administração > Atendimentos de urgência']->id,
                ]
            ],
            [
                ['nome_unico' => 'iniciar_atendimentos_urgencia'],
                [
                    'nome' => 'Iniciar um atendimento de urgência',
                    'habilidade_grupo_id' => $grupos['Administração > Atendimentos de urgência']->id,
                ]
            ],
            [
                ['nome_unico' => 'Finalizar_atendimentos_urgencia'],
                [
                    'nome' => 'Finalizar um atendimento de urgência',
                    'habilidade_grupo_id' => $grupos['Administração > Atendimentos de urgência']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_atendimentos_urgencia'],
                [
                    'nome' => 'Visualizar um atendimento de urgência',
                    'habilidade_grupo_id' => $grupos['Administração > Atendimentos de urgência']->id,
                ]
            ],

            //Relatórios Desmonstrativo Financeiro
            [
                ['nome_unico' => 'visualizar_relatorios_demonstrativo_financeiro'],
                [
                    'nome' => 'Visualizar Relatório Demonstrativo Financeiro',
                    'habilidade_grupo_id' => $grupos['Relatório > Demonstrativo Financeiro']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_relatorios_fluxo_caixa'],
                [
                    'nome' => 'Visualizar Relatório de fluxo de caixa',
                    'habilidade_grupo_id' => $grupos['Relatório > Demonstrativo Financeiro']->id,
                ]
            ],

            [
                ['nome_unico' => 'exportar_caixa'],
                [
                    'nome' => 'Realizar alteração de caixa no fluxo de caixa',
                    'habilidade_grupo_id' => $grupos['Relatório > Demonstrativo Financeiro']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_relatorio_estoque'],
                [
                    'nome' => 'Visualizar Relatórios de estoque',
                    'habilidade_grupo_id' => $grupos['Relatório > Demonstrativo Financeiro']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_relatorio_cartao'],
                [
                    'nome' => 'Visualizar Relatórios de cartões',
                    'habilidade_grupo_id' => $grupos['Relatório > Demonstrativo Financeiro']->id,
                ]
            ],

            //Relatórios Sancoop
            [
                ['nome_unico' => 'visualizar_relatorios_sancoop'],
                [
                    'nome' => 'Visualizar Relatório Sancoop',
                    'habilidade_grupo_id' => $grupos['Relatório > Sancoop']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_agendamentos_centro_cirurgico'],
                [
                    'nome' => 'Visualizar Agendamentos Centro Cirúrgico',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_convenio'],
                [
                    'nome' => 'Visualizar Convênios',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_convenio'],
                [
                    'nome' => 'Cadastrar Convênios',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_convenio'],
                [
                    'nome' => 'Editar Convênios',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_convenio'],
                [
                    'nome' => 'Excluir Convênios',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_convenio_planos'],
                [
                    'nome' => 'Visualizar Convênios Planos',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios Planos']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_convenio_planos'],
                [
                    'nome' => 'Cadastrar Convênios Planos',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios Planos']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_convenio_planos'],
                [
                    'nome' => 'Editar Convênios Planos',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios Planos']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_convenio_planos'],
                [
                    'nome' => 'Excluir Convênios Planos',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios Planos']->id,
                ],
            ],

            // Apresentações convênio
            [
                ['nome_unico' => 'visualizar_apresentacoes_convenio'],
                [
                    'nome' => 'Visualizar Formas de apresentação de Convênios',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios Apresentação']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_apresentacoes_convenio'],
                [
                    'nome' => 'Cadastrar Formas de apresentação de Convênios',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios Apresentação']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_apresentacoes_convenio'],
                [
                    'nome' => 'Editar Formas de apresentação de Convênios',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios Apresentação']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_apresentacoes_convenio'],
                [
                    'nome' => 'Excluir Formas de apresentação de Convênios',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios Apresentação']->id,
                ],
            ],

            /* Faturamento - lotes / guias */
            [
                ['nome_unico' => 'visualizar_lotes'],
                [
                    'nome' => 'Visualizar Lotes de Faturamento',
                    'habilidade_grupo_id' => $grupos['Convênios > Faturamento lotes']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_lotes'],
                [
                    'nome' => 'Cadastrar Lotes de Faturamento',
                    'habilidade_grupo_id' => $grupos['Convênios > Faturamento lotes']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_lotes'],
                [
                    'nome' => 'Editar Lotes de Faturamento',
                    'habilidade_grupo_id' => $grupos['Convênios > Faturamento lotes']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_lotes'],
                [
                    'nome' => 'Excluir Lotes de Faturamento',
                    'habilidade_grupo_id' => $grupos['Convênios > Faturamento lotes']->id,
                ],
            ],

            [
                ['nome_unico' => 'visualizar_lotes_guias'],
                [
                    'nome' => 'Visualizar Guias de Lotes de Faturamento',
                    'habilidade_grupo_id' => $grupos['Convênios > Faturamento lotes']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_lotes_guias'],
                [
                    'nome' => 'Cadastrar Guias de Lotes de Faturamento',
                    'habilidade_grupo_id' => $grupos['Convênios > Faturamento lotes']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_lotes_guias'],
                [
                    'nome' => 'Editar Guias de Lotes de Faturamento',
                    'habilidade_grupo_id' => $grupos['Convênios > Faturamento lotes']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_lotes_guias'],
                [
                    'nome' => 'Excluir Guias de Lotes de Faturamento',
                    'habilidade_grupo_id' => $grupos['Convênios > Faturamento lotes']->id,
                ],
            ],
            /* Fim Faturamento - lotes / guias */

            [
                ['nome_unico' => 'visualizar_caixas_cirurgicos'],
                [
                    'nome' => 'Visualizar Caixas Cirúrgicos',
                    'habilidade_grupo_id' => $grupos['Centros Cirúrgicos > Caixas Cirúrgicos']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_caixas_cirurgicos'],
                [
                    'nome' => 'Cadastrar Caixas Cirúrgicos',
                    'habilidade_grupo_id' => $grupos['Centros Cirúrgicos > Caixas Cirúrgicos']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_caixas_cirurgicos'],
                [
                    'nome' => 'Editar Caixas Cirúrgicos',
                    'habilidade_grupo_id' => $grupos['Centros Cirúrgicos > Caixas Cirúrgicos']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_caixas_cirurgicos'],
                [
                    'nome' => 'Excluir Caixas Cirúrgicos',
                    'habilidade_grupo_id' => $grupos['Centros Cirúrgicos > Caixas Cirúrgicos']->id,
                ],
            ],
            [
                ['nome_unico' => 'visualizar_sangues_derivados'],
                [
                    'nome' => 'Visualizar Sangues e Derivados',
                    'habilidade_grupo_id' => $grupos['Centros Cirúrgicos > Sangue e Derivados']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_sangues_derivados'],
                [
                    'nome' => 'Cadastrar Sangues e Derivados',
                    'habilidade_grupo_id' => $grupos['Centros Cirúrgicos > Sangue e Derivados']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_sangues_derivados'],
                [
                    'nome' => 'Editar Sangues e Derivados',
                    'habilidade_grupo_id' => $grupos['Centros Cirúrgicos > Sangue e Derivados']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_sangues_derivados'],
                [
                    'nome' => 'Excluir Sangues e Derivados',
                    'habilidade_grupo_id' => $grupos['Centros Cirúrgicos > Sangue e Derivados']->id,
                ],
            ],

            // Motivos divergencia
            [
                ['nome_unico' => 'visualizar_motivos_divergencia'],
                [
                    'nome' => 'Visualizar motivos de divergência',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Motivos de divergência']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_motivos_divergencia'],
                [
                    'nome' => 'Cadastrar motivos de divergência',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Motivos de divergência']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_motivos_divergencia'],
                [
                    'nome' => 'Editar motivos de divergência',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Motivos de divergência']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_motivos_divergencia'],
                [
                    'nome' => 'Excluir motivos de divergência',
                    'habilidade_grupo_id' => $grupos['Cadastros > Estoque > Motivos de divergência']->id,
                ]
            ],

            // Estoque de inventário
            [
                ['nome_unico' => 'visualizar_estoque_inventario'],
                [
                    'nome' => 'Visualizar Estoque Inventário',
                    'habilidade_grupo_id' => $grupos['Estoque > Inventário']->id,
                ]
            ],

            [
                ['nome_unico' => 'cadastrar_estoque_inventario'],
                [
                    'nome' => 'Cadastrar Estoque Inventário',
                    'habilidade_grupo_id' => $grupos['Estoque > Inventário']->id,
                ]
            ],

            [
                ['nome_unico' => 'editar_estoque_inventario'],
                [
                    'nome' => 'Editar Estoque Inventário',
                    'habilidade_grupo_id' => $grupos['Estoque > Inventário']->id,
                ]
            ],

            [
                ['nome_unico' => 'excluir_estoque_inventario'],
                [
                    'nome' => 'Excluir Estoque Inventário',
                    'habilidade_grupo_id' => $grupos['Estoque > Inventário']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_estoque_inventario_produtos'],
                [
                    'nome' => 'Visualizar Estoque Inventário Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Inventário']->id,
                ]
            ],

            [
                ['nome_unico' => 'cadastrar_estoque_inventario_produtos'],
                [
                    'nome' => 'Cadastrar Estoque Inventário Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Inventário']->id,
                ]
            ],

            [
                ['nome_unico' => 'editar_estoque_inventario_produtos'],
                [
                    'nome' => 'Editar Estoque Inventário Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Inventário']->id,
                ]
            ],

            [
                ['nome_unico' => 'excluir_estoque_inventario_produtos'],
                [
                    'nome' => 'Excluir Estoque Inventário Produtos',
                    'habilidade_grupo_id' => $grupos['Estoque > Inventário']->id,
                ]
            ],

            // Saidas de estoque
            [
                ['nome_unico' => 'visualizar_saida_estoque'],
                [
                    'nome' => 'Visualizar saídas de estoque',
                    'habilidade_grupo_id' => $grupos['Estoque > Saídas de estoque']->id,
                ]
            ],

            [
                ['nome_unico' => 'cadastrar_saida_estoque'],
                [
                    'nome' => 'Cadastrar saídas de estoque',
                    'habilidade_grupo_id' => $grupos['Estoque > Saídas de estoque']->id,
                ]
            ],

            [
                ['nome_unico' => 'editar_saida_estoque'],
                [
                    'nome' => 'Editar saídas de estoque',
                    'habilidade_grupo_id' => $grupos['Estoque > Saídas de estoque']->id,
                ]
            ],

            [
                ['nome_unico' => 'excluir_saida_estoque'],
                [
                    'nome' => 'Excluir saídas de estoque',
                    'habilidade_grupo_id' => $grupos['Estoque > Saídas de estoque']->id,
                ]
            ],

            // Tipos de chamada de totens
            [
                ['nome_unico' => 'visualizar_tipos_chamada_totem'],
                [
                    'nome' => 'Visualizar Tipos de chamadas dos totens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Tipos de chamadas dos totens']->id,
                ]
            ],

            [
                ['nome_unico' => 'cadastrar_tipos_chamada_totem'],
                [
                    'nome' => 'Cadastrar Tipos de chamadas dos totens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Tipos de chamadas dos totens']->id,
                ]
            ],

            [
                ['nome_unico' => 'editar_tipos_chamada_totem'],
                [
                    'nome' => 'Editar Tipos de chamadas dos totens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Tipos de chamadas dos totens']->id,
                ]
            ],

            [
                ['nome_unico' => 'excluir_tipos_chamada_totem'],
                [
                    'nome' => 'Excluir Tipos de chamadas dos totens',
                    'habilidade_grupo_id' => $grupos['Cadastros > Tipos de chamadas dos totens']->id,
                ]
            ],

            // Paineis totem
            [
                ['nome_unico' => 'visualizar_paineis_totem'],
                [
                    'nome' => 'Visualizar Paineis de totem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Paineis de totem']->id,
                ]
            ],

            [
                ['nome_unico' => 'cadastrar_paineis_totem'],
                [
                    'nome' => 'Cadastrar Paineis de totem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Paineis de totem']->id,
                ]
            ],

            [
                ['nome_unico' => 'editar_paineis_totem'],
                [
                    'nome' => 'Editar Paineis de totem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Paineis de totem']->id,
                ]
            ],

            [
                ['nome_unico' => 'excluir_paineis_totem'],
                [
                    'nome' => 'Excluir Paineis de totem',
                    'habilidade_grupo_id' => $grupos['Cadastros > Paineis de totem']->id,
                ]
            ],

            // INICIO RELATORIOS

            [
                ['nome_unico' => 'visualizar_relatorio_atendimento'],
                [
                    'nome' => 'Visualizar Relatório Atendimento',
                    'habilidade_grupo_id' => $grupos['Relatório > Atendimento']->id,
                ]
            ],
            [
                ['nome_unico' => 'exporta_excel_relatorio_atendimento'],
                [
                    'nome' => 'Exporta Excel Relatório Atendimento',
                    'habilidade_grupo_id' => $grupos['Relatório > Atendimento']->id,
                ]
            ],

            //  Alta Hospitalar
            [
                ['nome_unico' => 'visualizar_alta_hospitalar'],
                [
                    'nome' => 'Visualizar alta hospitalar',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Alta hospitalar']->id,
                ]
            ],
            [
                ['nome_unico' => 'realizar_alta_hospitalar'],
                [
                    'nome' => 'Realizar alta hospitalar',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Alta hospitalar']->id,
                ]
            ],
            [
                ['nome_unico' => 'cancelar_alta_hospitalar'],
                [
                    'nome' => 'Cancelar alta hospitalar',
                    'habilidade_grupo_id' => $grupos['Cadastros > Internação > Alta hospitalar']->id,
                ]
            ],

            [
                ['nome_unico' => 'desconto_agendamentos'],
                [
                    'nome' => 'Desconto agendamentos',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],

            [
                ['nome_unico' => 'salvar_pagamento_agendamentos'],
                [
                    'nome' => 'Salvar/Editar pagamento agendamentos',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],

            [
                ['nome_unico' => 'abrir_prontuario'],
                [
                    'nome' => 'Abrir o prontuário do paciente',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_prontuario_compartilhado'],
                [
                    'nome' => 'Visualizar prontuários compartilhados',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],

            [
                ['nome_unico' => 'prescrever_receituario_memed'],
                [
                    'nome' => 'Abrir prescrição integrada MEMED',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_modelo_impressao'],
                [
                    'nome' => 'Visualizar modelo de impressão',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de impressão']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_impressao'],
                [
                    'nome' => 'Cadastrar modelo de impressão',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de impressão']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_impressao'],
                [
                    'nome' => 'Editar modelo de impressão',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de impressão']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_all_modelo_impressao'],
                [
                    'nome' => 'Visualizar todos modelos de impressão',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de impressão']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_especializacao'],
                [
                    'nome' => 'visualizar especialização',
                    'habilidade_grupo_id' => $grupos['Cadastros > Especializações']->id,
                ]
            ],

            [
                ['nome_unico' => 'cadastrar_especializacao'],
                [
                    'nome' => 'Cadastrar especialização',
                    'habilidade_grupo_id' => $grupos['Cadastros > Especializações']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_especializacao'],
                [
                    'nome' => 'editar especialização',
                    'habilidade_grupo_id' => $grupos['Cadastros > Especializações']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_especializacao'],
                [
                    'nome' => 'Excluir especialização',
                    'habilidade_grupo_id' => $grupos['Cadastros > Especializações']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_especializacao'],
                [
                    'nome' => 'visualizar especialização',
                    'habilidade_grupo_id' => $grupos['Cadastros > Especializações']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_medicamentos'],
                [
                    'nome' => 'visualizar medicamentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Medicamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_medicamentos'],
                [
                    'nome' => 'Cadastrar medicamentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Medicamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_medicamentos'],
                [
                    'nome' => 'editar medicamentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Medicamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_medicamentos'],
                [
                    'nome' => 'Excluir medicamentos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Medicamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'automacao_whatsapp_atendimento_ambulatorial'],
                [
                    'nome' => 'Automação Whatsapp Atendimento Ambulatorial',
                    'habilidade_grupo_id' => $grupos['Administração > Automação Whatsapp Atendimento Ambulatorial']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_grupos'],
                [
                    'nome' => 'visualizar grupos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Grupos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_grupos'],
                [
                    'nome' => 'Cadastrar grupos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Grupos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_grupos'],
                [
                    'nome' => 'editar grupos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Grupos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_grupos'],
                [
                    'nome' => 'Excluir grupos',
                    'habilidade_grupo_id' => $grupos['Cadastros > Procedimentos > Grupos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_modelo_atestado'],
                [
                    'nome' => 'Visualizar modelos de atestado',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de atestado']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_atestado'],
                [
                    'nome' => 'Cadastrar modelo de atestado',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de atestado']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_atestado'],
                [
                    'nome' => 'Editar modelo de atestado',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de atestado']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_modelo_atestado'],
                [
                    'nome' => 'Excluir modelo de atestado',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de atestado']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_all_modelo_atestado'],
                [
                    'nome' => 'Visualizar todos modelos de atestado',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de atestado']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_modelo_relatorio'],
                [
                    'nome' => 'Visualizar modelos de relatório',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de relatório']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_relatorio'],
                [
                    'nome' => 'Cadastrar modelo de relatório',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de relatório']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_relatorio'],
                [
                    'nome' => 'Editar modelo de relatório',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de relatório']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_modelo_relatorio'],
                [
                    'nome' => 'Excluir modelo de relatório',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de relatório']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_all_modelo_relatorio'],
                [
                    'nome' => 'Visualizar todos modelos de relatório',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de relatório']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_modelo_exame'],
                [
                    'nome' => 'Visualizar modelos de exame',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de exame']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_exame'],
                [
                    'nome' => 'Cadastrar modelo de exame',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de exame']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_exame'],
                [
                    'nome' => 'Editar modelo de exame',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de exame']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_modelo_exame'],
                [
                    'nome' => 'Excluir modelo de exame',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de exame']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_all_modelo_exame'],
                [
                    'nome' => 'Visualizar todos modelos de exame',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de exame']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_modelo_receituario'],
                [
                    'nome' => 'Visualizar modelos de receituário',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de receituário']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_receituario'],
                [
                    'nome' => 'Cadastrar modelo de receituário',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de receituário']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_receituario'],
                [
                    'nome' => 'Editar modelo de receituário',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de receituário']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_modelo_receituario'],
                [
                    'nome' => 'Excluir modelo de receituário',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de receituário']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_all_modelo_receituario'],
                [
                    'nome' => 'Visualizar todos modelos de receituário',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de receituário']->id,
                ]
            ],
            // [
            //     ['nome_unico' => 'visualizar_configuracao_prontuario'],
            //     [
            //         'nome' => 'Visualizar configurações de prontuário',
            //         'habilidade_grupo_id' => $grupos['Configurações > Configurações de prontuário']->id,
            //     ]
            // ],
            // [
            //     ['nome_unico' => 'cadastrar_configuracao_prontuario'],
            //     [
            //         'nome' => 'Cadastrar configurações de prontuário',
            //         'habilidade_grupo_id' => $grupos['Configurações > Configurações de prontuário']->id,
            //     ]
            // ],
            // [
            //     ['nome_unico' => 'editar_configuracao_prontuario'],
            //     [
            //         'nome' => 'Editar configurações de prontuário',
            //         'habilidade_grupo_id' => $grupos['Configurações > Configurações de prontuário']->id,
            //     ]
            // ],
            // [
            //     ['nome_unico' => 'desativar_configuracao_prontuario'],
            //     [
            //         'nome' => 'Ativar/Desativar configurações de prontuário',
            //         'habilidade_grupo_id' => $grupos['Configurações > Configurações de prontuário']->id,
            //     ]
            // ],
            [
                ['nome_unico' => 'visualizar_modelo_prontuario'],
                [
                    'nome' => 'Visualizar modelos de prontuário',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de prontuário']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_prontuario'],
                [
                    'nome' => 'Cadastrar modelo de prontuário',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de prontuário']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_prontuario'],
                [
                    'nome' => 'Editar modelo de prontuário',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de prontuário']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_modelo_prontuario'],
                [
                    'nome' => 'Excluir modelo de prontuário',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de prontuário']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_all_modelo_prontuario'],
                [
                    'nome' => 'Visualizar todos modelos de prontuário',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de prontuário']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_agenda_ausente'],
                [
                    'nome' => 'Visualizar agenda ausente',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores > Agenda ausente']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_agenda_ausente'],
                [
                    'nome' => 'Cadastrar agenda ausente',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores > Agenda ausente']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_agenda_ausente'],
                [
                    'nome' => 'Editar agenda ausente',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores > Agenda ausente']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_agenda_ausente'],
                [
                    'nome' => 'Excluir agenda ausente',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores > Agenda ausente']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_procedimento_pagamanto'],
                [
                    'nome' => 'Editar procedimento/pagamento após atendimento agendado',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'atualizar_faturamento_sus'],
                [
                    'nome' => 'Atualizar faturamento SUS',
                    'habilidade_grupo_id' => $grupos['Cadastros > Faturamento SUS']->id,
                ]
            ],
            [
                ['nome_unico' => 'atualizar_vinculos_sus'],
                [
                    'nome' => 'Atualizar vínculos SUS',
                    'habilidade_grupo_id' => $grupos['Cadastros > Faturamento SUS']->id,
                ]
            ],

            //Visualizar Dashboard
            [
                ['nome_unico' => 'visualizar_dashboard'],
                [
                    'nome' => 'Visualizar Dashboard',
                    'habilidade_grupo_id' => $grupos['Dashboard']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_all_agenda_prestador'],
                [
                    'nome' => 'Visualizar Todos os Agendamento dos Prestadores',
                    'habilidade_grupo_id' => $grupos['Cadastros > Prestadores']->id,
                ]
            ],

            //Visualizar relatórios estatisticos
            [
                ['nome_unico' => 'visualizar_relatorio_estatistico_financeiro_ambulatorial'],
                [
                    'nome' => 'Visualizar Relatório financeiro ambulatorial',
                    'habilidade_grupo_id' => $grupos['Relatório > Estatísticos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_estatistico_agenda_ambulatorial'],
                [
                    'nome' => 'Visualizar Relatório agenda ambulatorial',
                    'habilidade_grupo_id' => $grupos['Relatório > Estatísticos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_estatistico_procedimentos_ambulatorial'],
                [
                    'nome' => 'Visualizar Relatório procedimentos ambulatorial',
                    'habilidade_grupo_id' => $grupos['Relatório > Estatísticos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_estatistico_convenios_ambulatorial'],
                [
                    'nome' => 'Visualizar Relatório convênios ambulatorial',
                    'habilidade_grupo_id' => $grupos['Relatório > Estatísticos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_odontologico'],
                [
                    'nome' => 'Visualizar Odontologico',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'aprovar_orcamento_odontologico'],
                [
                    'nome' => 'Aprovar Orçamento Odontologico',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'desconto_orcamento_odontologico'],
                [
                    'nome' => 'Desconto Orçamento Odontologico',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'cancelar_aprovado_orcamento_odontologico'],
                [
                    'nome' => 'Cancelar Orçamento Odontologico Aprovado',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'cancelar_procedimento_concluido_odontologico'],
                [
                    'nome' => 'Cancelar Procedimento Odontologico Concluido',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'concluir_procedimento_odontologico'],
                [
                    'nome' => 'Concluir Procedimento Odontologico',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_orcamento_odontologico'],
                [
                    'nome' => 'Cadastrar Orçamento Odontologico',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_orcamento_odontologico'],
                [
                    'nome' => 'Editar Orçamento Odontologico',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_orcamento_odontologico'],
                [
                    'nome' => 'Excluir Orçamento Odontologico',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_orcamento_odontologico'],
                [
                    'nome' => 'Visualizar Orçamento Odontologico',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],


            //Cadastro fiscais
            [
                ['nome_unico' => 'visualizar_configuracao_fiscal'],
                [
                    'nome' => 'Visualizar configuração fiscal',
                    'habilidade_grupo_id' => $grupos['Cadastros > Instituição > Configuração fiscal']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_configuracao_fiscal'],
                [
                    'nome' => 'Cadastrar configuração fiscal',
                    'habilidade_grupo_id' => $grupos['Cadastros > Instituição > Configuração fiscal']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_configuracao_fiscal'],
                [
                    'nome' => 'Editar configuração fiscal',
                    'habilidade_grupo_id' => $grupos['Cadastros > Instituição > Configuração fiscal']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_configuracao_fiscal'],
                [
                    'nome' => 'Excluir configuração fiscal',
                    'habilidade_grupo_id' => $grupos['Cadastros > Instituição > Configuração fiscal']->id,
                ]
            ],


            //Notas fiscais
            [
                ['nome_unico' => 'visualizar_nota_fiscal'],
                [
                    'nome' => 'Visualizar nota fiscal',
                    'habilidade_grupo_id' => $grupos['Nota Fiscal']->id,
                ],
            ],
            [
                ['nome_unico' => 'cadastrar_nota_fiscal'],
                [
                    'nome' => 'Cadastrar nota fiscal',
                    'habilidade_grupo_id' => $grupos['Nota Fiscal']->id,
                ],
            ],
            [
                ['nome_unico' => 'editar_nota_fiscal'],
                [
                    'nome' => 'Editar nota fiscal',
                    'habilidade_grupo_id' => $grupos['Nota Fiscal']->id,
                ],
            ],
            [
                ['nome_unico' => 'excluir_nota_fiscal'],
                [
                    'nome' => 'Excluir nota fiscal',
                    'habilidade_grupo_id' => $grupos['Nota Fiscal']->id,
                ],
            ],
            [
                ['nome_unico' => 'emitir_nota_fiscal'],
                [
                    'nome' => 'Emitir nota fiscal',
                    'habilidade_grupo_id' => $grupos['Nota Fiscal']->id,
                ]
            ],
            [
                ['nome_unico' => 'cancelar_nota_fiscal'],
                [
                    'nome' => 'Cancelar nota fiscal',
                    'habilidade_grupo_id' => $grupos['Nota Fiscal']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastro_empresa_enotas'],
                [
                    'nome' => 'Incluir / editar empresa no eNotas',
                    'habilidade_grupo_id' => $grupos['Nota Fiscal']->id,
                ]
            ],

            //Boleto
            [
                ['nome_unico' => 'emitir_boleto'],
                [
                    'nome' => 'Emitir boleto',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Contas a Receber']->id,
                ]
            ],

            //Movimentações
            [
                ['nome_unico' => 'visualizar_movimentacoes'],
                [
                    'nome' => 'Visualizar Movimentações',
                    'habilidade_grupo_id' => $grupos['Financeiro > Movimentação']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_movimentacoes'],
                [
                    'nome' => 'Cadastrar Movimentações',
                    'habilidade_grupo_id' => $grupos['Financeiro > Movimentação']->id,
                ]
            ],
            [
                ['nome_unico' => 'duplicar_movimentacoes'],
                [
                    'nome' => 'Duplicar Movimentações',
                    'habilidade_grupo_id' => $grupos['Financeiro > Movimentação']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_movimentacoes'],
                [
                    'nome' => 'Excluir Movimentações',
                    'habilidade_grupo_id' => $grupos['Financeiro > Movimentação']->id,
                ]
            ],


            //Prestadores Solicitantes
            [
                ['nome_unico' => 'visualizar_solicitantes'],
                [
                    'nome' => 'Visualizar prestadores solicitantes',
                    'habilidade_grupo_id' => $grupos['Cadastro > Prestadore Solicitantes']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_solicitantes'],
                [
                    'nome' => 'Cadastrar prestadore solicitante',
                    'habilidade_grupo_id' => $grupos['Cadastro > Prestadore Solicitantes']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_solicitantes'],
                [
                    'nome' => 'Editar prestadore solicitante',
                    'habilidade_grupo_id' => $grupos['Cadastro > Prestadore Solicitantes']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_solicitantes'],
                [
                    'nome' => 'Excluir prestadore solicitante',
                    'habilidade_grupo_id' => $grupos['Cadastro > Prestadore Solicitantes']->id,
                ]
            ],
            
            // Prestadores / Atividades médicas
            [
                ['nome_unico' => 'visualizar_atividades_medicas'],
                [
                    'nome' => 'Visualizar prestadores solicitantes',
                    'habilidade_grupo_id' => $grupos['Cadastro > Atividades médicas']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_atividades_medicas'],
                [
                    'nome' => 'Cadastrar prestadore solicitante',
                    'habilidade_grupo_id' => $grupos['Cadastro > Atividades médicas']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_atividades_medicas'],
                [
                    'nome' => 'Editar prestadore solicitante',
                    'habilidade_grupo_id' => $grupos['Cadastro > Atividades médicas']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_atividades_medicas'],
                [
                    'nome' => 'Excluir prestadore solicitante',
                    'habilidade_grupo_id' => $grupos['Cadastro > Atividades médicas']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_prontuario'],
                [
                    'nome' => 'Visualizar aba de prontuario',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_receituario'],
                [
                    'nome' => 'Visualizar aba de receituário',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_refracao'],
                [
                    'nome' => 'Visualizar aba de refração',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_atestado'],
                [
                    'nome' => 'Visualizar aba de atestado',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_laudo'],
                [
                    'nome' => 'Visualizar aba de laudo',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_avaliacao'],
                [
                    'nome' => 'Visualizar aba de Avaliação',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_solicitacao_estoque'],
                [
                    'nome' => 'Visualizar aba solicitação de estoque',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],

            [
                ['nome_unico' => 'visualizar_encaminhamento'],
                [
                    'nome' => 'Visualizar aba de encaminhamento',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio'],
                [
                    'nome' => 'Visualizar aba de relatório',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_exame'],
                [
                    'nome' => 'Visualizar aba de exame',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_arquivo'],
                [
                    'nome' => 'Visualizar aba de arquivo',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_resumo'],
                [
                    'nome' => 'Visualizar aba de resumo',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_historico'],
                [
                    'nome' => 'Visualizar histórico do paciente',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'dashboard_odontologico'],
                [
                    'nome' => 'Visualizar dashboard odontológico',
                    'habilidade_grupo_id' => $grupos['Odontológico > Dashboard']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_demonstrativo_odontologico'],
                [
                    'nome' => 'Visualizar relatório demonstrativo odontológico',
                    'habilidade_grupo_id' => $grupos['Relatório > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_repasse_odontologico'],
                [
                    'nome' => 'Visualizar relatório repasse odontológico',
                    'habilidade_grupo_id' => $grupos['Relatório > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_valor_procedimento'],
                [
                    'nome' => 'Visualizar valor procedimento',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_odontologico_grupo'],
                [
                    'nome' => 'Visualizar relatório demonstrativo odontológico grupo',
                    'habilidade_grupo_id' => $grupos['Relatório > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_orcamentos'],
                [
                    'nome' => 'Visualizar relatório orçamentos',
                    'habilidade_grupo_id' => $grupos['Relatório > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_orcamentos_aprovados'],
                [
                    'nome' => 'Visualizar relatório orçamentos aprovados',
                    'habilidade_grupo_id' => $grupos['Relatório > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_procedimentos_nao_realizados'],
                [
                    'nome' => 'Visualizar relatório procedimentos não realizados',
                    'habilidade_grupo_id' => $grupos['Relatório > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_orcamentos_concluidos'],
                [
                    'nome' => 'Visualizar relatório orçamentos concluídos',
                    'habilidade_grupo_id' => $grupos['Relatório > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'remarcar_para_outro_prestador'],
                [
                    'nome' => 'Remarcar horario para outro prestador',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'vincular_convenio_agendas'],
                [
                    'nome' => 'Vincular convênio a prestadores agenda',
                    'habilidade_grupo_id' => $grupos['Pessoas > Convênios']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_auditoria_agendamento'],
                [
                    'nome' => 'Visualizar relatorio auditoria agendamentos',
                    'habilidade_grupo_id' => $grupos['Relatório > Auditoria']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_modelo_encaminhamento'],
                [
                    'nome' => 'Visualizar modelos de encaminhamento',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de encaminhamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_encaminhamento'],
                [
                    'nome' => 'Cadastrar modelo de encaminhamento',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de encaminhamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_encaminhamento'],
                [
                    'nome' => 'Editar modelo de encaminhamento',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de encaminhamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_modelo_encaminhamento'],
                [
                    'nome' => 'Excluir modelo de encaminhamento',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de encaminhamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_all_modelo_encaminhamento'],
                [
                    'nome' => 'Visualizar todos modelos de encaminhamento',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de encaminhamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_modelo_laudo'],
                [
                    'nome' => 'Visualizar modelos de laudo',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de laudo']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_laudo'],
                [
                    'nome' => 'Cadastrar modelo de laudo',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de laudo']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_laudo'],
                [
                    'nome' => 'Editar modelo de laudo',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de laudo']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_modelo_laudo'],
                [
                    'nome' => 'Excluir modelo de laudo',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de laudo']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_all_modelo_laudo'],
                [
                    'nome' => 'Visualizar todos modelos de laudo',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de laudo']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_grupo_faturamento'],
                [
                    'nome' => 'Visualizar grupos de faturamento',
                    'habilidade_grupo_id' => $grupos['Convênio > Grupos de Faturamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_grupo_faturamento'],
                [
                    'nome' => 'Cadastrar grupos de faturamento',
                    'habilidade_grupo_id' => $grupos['Convênio > Grupos de Faturamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_grupo_faturamento'],
                [
                    'nome' => 'Editar grupos de faturamento',
                    'habilidade_grupo_id' => $grupos['Convênio > Grupos de Faturamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_grupo_faturamento'],
                [
                    'nome' => 'Excluir grupos de faturamento',
                    'habilidade_grupo_id' => $grupos['Convênio > Grupos de Faturamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'ativar_grupo_faturamento'],
                [
                    'nome' => 'Ativar/Desativar grupos de faturamento',
                    'habilidade_grupo_id' => $grupos['Convênio > Grupos de Faturamento']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_faturamentos'],
                [
                    'nome' => 'Visualizar faturamento',
                    'habilidade_grupo_id' => $grupos['Procedimento > Faturamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_faturamentos'],
                [
                    'nome' => 'Cadastrar faturamento',
                    'habilidade_grupo_id' => $grupos['Procedimento > Faturamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_faturamentos'],
                [
                    'nome' => 'Editar faturamento',
                    'habilidade_grupo_id' => $grupos['Procedimento > Faturamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_faturamentos'],
                [
                    'nome' => 'Excluir faturamento',
                    'habilidade_grupo_id' => $grupos['Procedimento > Faturamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_faturamentos_procedimentos'],
                [
                    'nome' => 'Visualizar faturamento procedimentos',
                    'habilidade_grupo_id' => $grupos['Procedimento > Faturamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'importar_faturamentos_convenios'],
                [
                    'nome' => 'Importar faturamento dos convênios',
                    'habilidade_grupo_id' => $grupos['Procedimento > Faturamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_regras_cobranca'],
                [
                    'nome' => 'Visualizar Regras de Cobrança',
                    'habilidade_grupo_id' => $grupos['Procedimento > Regras de Cobrança']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_regras_cobranca'],
                [
                    'nome' => 'Cadastrar Regras de Cobrança',
                    'habilidade_grupo_id' => $grupos['Procedimento > Regras de Cobrança']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_regras_cobranca'],
                [
                    'nome' => 'Editar Regras de Cobrança',
                    'habilidade_grupo_id' => $grupos['Procedimento > Regras de Cobrança']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_regras_cobranca'],
                [
                    'nome' => 'Excluir Regras de Cobrança',
                    'habilidade_grupo_id' => $grupos['Procedimento > Regras de Cobrança']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_regras_cobranca_itens'],
                [
                    'nome' => 'Visualizar Regras de Cobrança Itens',
                    'habilidade_grupo_id' => $grupos['Procedimento > Regras de Cobrança Itens']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_regras_cobranca_itens'],
                [
                    'nome' => 'Cadastrar Regras de Cobrança Itens',
                    'habilidade_grupo_id' => $grupos['Procedimento > Regras de Cobrança Itens']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_regras_cobranca_itens'],
                [
                    'nome' => 'Editar Regras de Cobrança Itens',
                    'habilidade_grupo_id' => $grupos['Procedimento > Regras de Cobrança Itens']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_regras_cobranca_itens'],
                [
                    'nome' => 'Excluir Regras de Cobrança Itens',
                    'habilidade_grupo_id' => $grupos['Procedimento > Regras de Cobrança Itens']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_procedimentos_atendimentos'],
                [
                    'nome' => 'Visualizar Procedimentos dos Atendimento',
                    'habilidade_grupo_id' => $grupos['Procedimento > Procedimentos dos Atendimento']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_procedimentos_atendimentos'],
                [
                    'nome' => 'Cadastrar Procedimentos dos Atendimento',
                    'habilidade_grupo_id' => $grupos['Procedimento > Procedimentos dos Atendimento']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_procedimentos_atendimentos'],
                [
                    'nome' => 'Editar Procedimentos dos Atendimento',
                    'habilidade_grupo_id' => $grupos['Procedimento > Procedimentos dos Atendimento']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_procedimentos_atendimentos'],
                [
                    'nome' => 'Excluir Procedimentos dos Atendimento',
                    'habilidade_grupo_id' => $grupos['Procedimento > Procedimentos dos Atendimento']->id,
                ]
            ],

            //Modelos de recibo
            [
                ['nome_unico' => 'visualizar_modelo_recibo'],
                [
                    'nome' => 'Visualizar modelos de recibo',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de recibo']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_recibo'],
                [
                    'nome' => 'Cadastrar Modelo de recibo',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de recibo']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_recibo'],
                [
                    'nome' => 'Editar Modelo de recibo',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de recibo']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_modelo_recibo'],
                [
                    'nome' => 'Excluir Modelo de recibo',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de recibo']->id,
                ]
            ],
            [
                ['nome_unico' => 'utilizar_chat'],
                [
                    'nome' => 'Visualizar e postar mensagens no chat',
                    'habilidade_grupo_id' => $grupos['Chat']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_modelo_arquivo'],
                [
                    'nome' => 'Visualizar Modelos de Arquivos',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de Arquivos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_arquivo'],
                [
                    'nome' => 'Cadastrar Modelo de Arquivos',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de Arquivos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_arquivo'],
                [
                    'nome' => 'Editar Modelo de Arquivos',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de Arquivos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_modelo_arquivo'],
                [
                    'nome' => 'Excluir Modelo de Arquivos',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelo de Arquivos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_vincular_tuss'],
                [
                    'nome' => 'Visualizar Vinculo Tuss',
                    'habilidade_grupo_id' => $grupos['Procedimento > Vincular Tuss']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_vincular_tuss'],
                [
                    'nome' => 'Cadastrar Vinculo Tuss',
                    'habilidade_grupo_id' => $grupos['Procedimento > Vincular Tuss']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_vincular_tuss'],
                [
                    'nome' => 'Excluir Vinculo Tuss',
                    'habilidade_grupo_id' => $grupos['Procedimento > Vincular Tuss']->id,
                ]
            ],
            [
                ['nome_unico' => 'importar_vincular_tuss'],
                [
                    'nome' => 'Importar Vinculo Tuss',
                    'habilidade_grupo_id' => $grupos['Procedimento > Vincular Tuss']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_vincular_brasindice'],
                [
                    'nome' => 'Visualizar Vinculo Brasíndice',
                    'habilidade_grupo_id' => $grupos['Procedimento > Vincular Brasíndice']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_vincular_brasindice'],
                [
                    'nome' => 'Cadastrar Vinculo Brasíndice',
                    'habilidade_grupo_id' => $grupos['Procedimento > Vincular Brasíndice']->id,
                ]
            ],
            [
                ['nome_unico' => 'importar_vincular_brasindice'],
                [
                    'nome' => 'Importar Vinculo Brasíndice',
                    'habilidade_grupo_id' => $grupos['Procedimento > Vincular Brasíndice']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_vincular_brasindice'],
                [
                    'nome' => 'Excluir Vinculo Brasíndice',
                    'habilidade_grupo_id' => $grupos['Procedimento > Vincular Brasíndice']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_maquina_cartao'],
                [
                    'nome' => 'Visualizar Maquinas de cartão',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Maquinas de catão']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_maquina_cartao'],
                [
                    'nome' => 'Cadastrar Maquina de cartão',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Maquinas de catão']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_maquina_cartao'],
                [
                    'nome' => 'Editar Maquina de cartão',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Maquinas de catão']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_maquina_cartao'],
                [
                    'nome' => 'Excluir Maquina de cartão',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Maquinas de catão']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_agendamento_procedimento_finalizado'],
                [
                    'nome' => 'Editar informações do agendamento finalizado',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_contas_a_pagar'],
                [
                    'nome' => 'Visualizar relatorio de contas a pagar',
                    'habilidade_grupo_id' => $grupos['Relatório > Financeiro']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_contas_a_receber'],
                [
                    'nome' => 'Visualizar relatorio de contas a receber',
                    'habilidade_grupo_id' => $grupos['Relatório > Financeiro']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_contas_pagas'],
                [
                    'nome' => 'Visualizar relatorio de contas pagas',
                    'habilidade_grupo_id' => $grupos['Relatório > Financeiro']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_contas_recebidas'],
                [
                    'nome' => 'Visualizar relatorio de contas recebidas',
                    'habilidade_grupo_id' => $grupos['Relatório > Financeiro']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_fluxo_de_caixa'],
                [
                    'nome' => 'Visualizar relatorio de fluxo de caixa',
                    'habilidade_grupo_id' => $grupos['Relatório > Financeiro']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_compromissos'],
                [
                    'nome' => 'Visualizar Compromissos',
                    'habilidade_grupo_id' => $grupos['Atendimento > Compromissos']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_compromissos'],
                [
                    'nome' => 'Cadastrar Compromissos',
                    'habilidade_grupo_id' => $grupos['Atendimento > Compromissos']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_compromissos'],
                [
                    'nome' => 'Editar Compromissos',
                    'habilidade_grupo_id' => $grupos['Atendimento > Compromissos']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_compromissos'],
                [
                    'nome' => 'Excluir Compromissos',
                    'habilidade_grupo_id' => $grupos['Atendimento > Compromissos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_entrega_exames'],
                [
                    'nome' => 'Visualizar entrega de exames',
                    'habilidade_grupo_id' => $grupos['Exames > Entrega de exames']->id,
                ]
            ],
            [
                ['nome_unico' => 'criar_entrega_exames'],
                [
                    'nome' => 'Fazer a entrega de exames',
                    'habilidade_grupo_id' => $grupos['Exames > Entrega de exames']->id,
                ]
            ],
            [
                ['nome_unico' => 'atualizar_entrega_exames'],
                [
                    'nome' => 'Atualizar status da entrega de exames',
                    'habilidade_grupo_id' => $grupos['Exames > Entrega de exames']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_locais_entrega_exames'],
                [
                    'nome' => 'Visualizar locais para entregas de exame',
                    'habilidade_grupo_id' => $grupos['Exames > Entrega de exames']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_locais_entrega_exames'],
                [
                    'nome' => 'Cadastrar locais para entregas de exame',
                    'habilidade_grupo_id' => $grupos['Exames > Entrega de exames']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_locais_entrega_exames'],
                [
                    'nome' => 'Editar locais para entregas de exame',
                    'habilidade_grupo_id' => $grupos['Exames > Entrega de exames']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_locais_entrega_exames'],
                [
                    'nome' => 'Excluir locais para entregas de exame',
                    'habilidade_grupo_id' => $grupos['Exames > Entrega de exames']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_obs_opcionais'],
                [
                    'nome' => 'Visualizar/Editar Observação Agendamento',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_motivos_atendimento'],
                [
                    'nome' => 'Visualizar Motivos Atendimentos',
                    'habilidade_grupo_id' => $grupos['Paciente > Motivos Atendimento']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_motivos_atendimento'],
                [
                    'nome' => 'Cadastrar Motivos Atendimentos',
                    'habilidade_grupo_id' => $grupos['Paciente > Motivos Atendimento']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_motivos_atendimento'],
                [
                    'nome' => 'Editar Motivos Atendimentos',
                    'habilidade_grupo_id' => $grupos['Paciente > Motivos Atendimento']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_motivos_atendimento'],
                [
                    'nome' => 'Excluir Motivos Atendimentos',
                    'habilidade_grupo_id' => $grupos['Paciente > Motivos Atendimento']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_atendimento_paciente'],
                [
                    'nome' => 'Visualizar Atendimento Paciente',
                    'habilidade_grupo_id' => $grupos['Paciente > Atendimento Paciente']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_atendimento_paciente'],
                [
                    'nome' => 'Cadastrar Atendimento Paciente',
                    'habilidade_grupo_id' => $grupos['Paciente > Atendimento Paciente']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_atendimento_paciente'],
                [
                    'nome' => 'Editar Atendimento Paciente',
                    'habilidade_grupo_id' => $grupos['Paciente > Atendimento Paciente']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_atendimento_paciente'],
                [
                    'nome' => 'Excluir Atendimento Paciente',
                    'habilidade_grupo_id' => $grupos['Paciente > Atendimento Paciente']->id,
                ]
            ],
            [
                ['nome_unico' => 'retorno_agendamento_pendente'],
                [
                    'nome' => 'Retorna o agendamento para pendente',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_motivos_baixa'],
                [
                    'nome' => 'Visualizar Motivos para baixa de estoque',
                    'habilidade_grupo_id' => $grupos['Atendimento > Motivos para baixa de estoque']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_motivos_baixa'],
                [
                    'nome' => 'Cadastrar Motivos para baixa de estoque',
                    'habilidade_grupo_id' => $grupos['Atendimento > Motivos para baixa de estoque']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_motivos_baixa'],
                [
                    'nome' => 'Editar Motivos para baixa de estoque',
                    'habilidade_grupo_id' => $grupos['Atendimento > Motivos para baixa de estoque']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_motivos_baixa'],
                [
                    'nome' => 'Excluir Motivos para baixa de estoque',
                    'habilidade_grupo_id' => $grupos['Atendimento > Motivos para baixa de estoque']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_visualizar_prestadores'],
                [
                    'nome' => 'Visualizar Prestadores Específicos',
                    'habilidade_grupo_id' => $grupos['Administração > Usuário']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_motivos_conclusoes'],
                [
                    'nome' => 'Visualizar Motivos de Conclusões',
                    'habilidade_grupo_id' => $grupos['Instituição > Motivos Conclusões']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_motivos_conclusoes'],
                [
                    'nome' => 'Cadastrar Motivos de Conclusões',
                    'habilidade_grupo_id' => $grupos['Instituição > Motivos Conclusões']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_motivos_conclusoes'],
                [
                    'nome' => 'Editar Motivos de Conclusões',
                    'habilidade_grupo_id' => $grupos['Instituição > Motivos Conclusões']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_motivos_conclusoes'],
                [
                    'nome' => 'Excluir Motivos de Conclusões',
                    'habilidade_grupo_id' => $grupos['Instituição > Motivos Conclusões']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_modelo_conclusao'],
                [
                    'nome' => 'Visualizar Modelos de Conclusões',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelos de Conclusões']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_conclusao'],
                [
                    'nome' => 'Cadastrar Modelos de Conclusões',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelos de Conclusões']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_conclusao'],
                [
                    'nome' => 'Editar Modelos de Conclusões',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelos de Conclusões']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_modelo_conclusao'],
                [
                    'nome' => 'Excluir Modelos de Conclusões',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelos de Conclusões']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_all_modelo_conclusao'],
                [
                    'nome' => 'Visualizar todos modelos de conclusão',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelos de Conclusões']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_conclusao'],
                [
                    'nome' => 'Visualizar aba de conclusões',
                    'habilidade_grupo_id' => $grupos['Agendamento > Prontuários']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_conclusao'],
                [
                    'nome' => 'Visualizar relatório de conclusão',
                    'habilidade_grupo_id' => $grupos['Relatório > Conclusão']->id,
                ]
            ],
            [
                ['nome_unico' => 'add_procedimento_agendamento_momento'],
                [
                    'nome' => 'Adicionar procedimento a qualquer momento no agendamento',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'remover_procedimento_agendamento_momento'],
                [
                    'nome' => 'Remover procedimento a qualquer momento no agendamento',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_import_xml'],
                [
                    'nome' => 'Visualizar importação de xmls',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Importar XML nota']->id,
                ]
            ],
            [
                ['nome_unico' => 'importar_xml'],
                [
                    'nome' => 'Importar xmls',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Importar XML nota']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_import_xml'],
                [
                    'nome' => 'Excluir importação',
                    'habilidade_grupo_id' => $grupos['Cadastros > Financeiro > Importar XML nota']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_visualizar_setores_usuario'],
                [
                    'nome' => 'Visualizar Setores Específicos',
                    'habilidade_grupo_id' => $grupos['Administração > Usuário']->id,
                ]
            ],
            [
                ['nome_unico' => 'desconto_procedimento_agendamentos'],
                [
                    'nome' => 'Desconto agendamento por procedimento',
                    'habilidade_grupo_id' => $grupos['Administração > Agendamentos']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_laboratorio_odontologico'],
                [
                    'nome' => 'Visualizar LaboratórioOrçamento Odontologico',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_laboratorio_odontologico'],
                [
                    'nome' => 'Editar Laboratório Orçamento Odontologico',
                    'habilidade_grupo_id' => $grupos['Agendamento > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_agendamentos_lista_espera'],
                [
                    'nome' => 'Visualizar Agendamentos Lista de Espera',
                    'habilidade_grupo_id' => $grupos['Cadastros > Agendamentos Lista de Espera']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_agendamentos_lista_espera'],
                [
                    'nome' => 'Cadastrar Agendamentos Lista de Espera',
                    'habilidade_grupo_id' => $grupos['Cadastros > Agendamentos Lista de Espera']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_agendamentos_lista_espera'],
                [
                    'nome' => 'Editar Agendamentos Lista de Espera',
                    'habilidade_grupo_id' => $grupos['Cadastros > Agendamentos Lista de Espera']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_agendamentos_lista_espera'],
                [
                    'nome' => 'Excluir Agendamentos Lista de Espera',
                    'habilidade_grupo_id' => $grupos['Cadastros > Agendamentos Lista de Espera']->id,
                ]
            ],
            [
                ['nome_unico' => 'agendar_agendamentos_lista_espera'],
                [
                    'nome' => 'Agendar Agendamentos Lista de Espera',
                    'habilidade_grupo_id' => $grupos['Cadastros > Agendamentos Lista de Espera']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_modelo_termo_folha_sala'],
                [
                    'nome' => 'Visualizar Modelo de Termos e Folha de Sala',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelos de Termos e Folha de Sala']->id,
                ]
            ],
            [
                ['nome_unico' => 'cadastrar_modelo_termo_folha_sala'],
                [
                    'nome' => 'Cadastrar Modelo de Termos e Folha de Sala',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelos de Termos e Folha de Sala']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_modelo_termo_folha_sala'],
                [
                    'nome' => 'Editar Modelo de Termos e Folha de Sala',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelos de Termos e Folha de Sala']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_modelo_termo_folha_sala'],
                [
                    'nome' => 'Excluir Modelo de Termos e Folha de Sala',
                    'habilidade_grupo_id' => $grupos['Configurações > Modelos de Termos e Folha de Sala']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_registro_log'],
                [
                    'nome' => 'Visualizar Relatório de Registro de Log',
                    'habilidade_grupo_id' => $grupos['Relatório > Registro de Log']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_contas_pagar_relatorio_demonstrativo_odontologico'],
                [
                    'nome' => 'Visualizar contas a pagar no relatório demonstrativo odontológico',
                    'habilidade_grupo_id' => $grupos['Relatório > Odontológico']->id,
                ]
            ],
            [
                ['nome_unico' => 'visualizar_relatorio_atendimento_somente_repasses'],
                [
                    'nome' => 'Visualizar Relatório Atendimento Somente Repasses',
                    'habilidade_grupo_id' => $grupos['Relatório > Atendimento']->id,
                ]
            ],
            //EXAMES
            [
                ['nome_unico' => 'visualizar_pedido_exame'],
                [
                    'nome' => 'Visualizar Pedidos de Exames',
                    'habilidade_grupo_id' => $grupos['Exames']->id,
                ]
            ],
            [
                ['nome_unico' => 'novo_pedido_exame'],
                [
                    'nome' => 'Casatrar Pedidos de Exames',
                    'habilidade_grupo_id' => $grupos['Exames']->id,
                ]
            ],
            [
                ['nome_unico' => 'editar_pedido_exame'],
                [
                    'nome' => 'Editar Pedidos de Exames',
                    'habilidade_grupo_id' => $grupos['Exames']->id,
                ]
            ],
            [
                ['nome_unico' => 'excluir_pedido_exame'],
                [
                    'nome' => 'Excluir Pedidos de Exames',
                    'habilidade_grupo_id' => $grupos['Exames']->id,
                ]
            ],
        ];
        $entities = [];
        foreach ($habilidades as $habilidade) {
            $wheres = $habilidade[0];
            $attributes = $habilidade[1];
            $entity = InstituicaoHabilidade::query()->where($wheres)->firstOrNew();

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
