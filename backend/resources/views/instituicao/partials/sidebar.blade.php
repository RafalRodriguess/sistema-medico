<ul id="sidebarnav">

    {{-- @foreach ($menus as $menu)
        <li>
            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="{{ $menu['icone'] }}"></i>
                <span class="hide-menu">{{ $menu['titulo'] }}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                @foreach ($menu['submenus'] as $titulo => $rota)
                    <li class="{{ request()->route()->getName() === $rota ? 'active' : '' }}">
                        <a href="{{ route($rota) }}">{{ $titulo }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
        @endforeach --}}

    {{-- <li class="nav-small-cap">MENUS</li> --}}
    {{-- @if (\session()->get('instituicao')) --}}

    {{-- <li class="nav-small-cap">ADMINISTRAÇÃO</li> --}}




    <!--  NOVO MENU -->
    {{-- <li class="nav-devider"></li> --}}
    <li class="nav-small-cap">MÓDULOS</li>

    <!--  PROCEDIMENTOS -->
    @if (\Gate::check('habilidade_instituicao_sessao', 'editar_triagens') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_totens') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_filas_triagem') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_classificacoes_triagem') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_processos_triagem'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="mdi mdi-account-edit"></i><span class="hide-menu">Triagem </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'editar_triagens')
                    <li><a href="{{ route('instituicao.triagens.index') }}">Triagens</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_totens')
                    <li><a href="{{ route('instituicao.triagem.totens.index') }}">Totens</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_filas_triagem')
                    <li><a href="{{ route('instituicao.triagem.filas.index') }}">Filas</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_classificacoes_triagem')
                    <li><a href="{{ route('instituicao.triagem.classificacoes.index') }}">Classificações</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_processos_triagem')
                    <li><a href="{{ route('instituicao.triagem.processos.index') }}">Processos</a></li>
                @endcan
            </ul>
        </li>

        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="mdi mdi-account-edit"></i><span class="hide-menu">Painel Escala Médica </span></a>
            <ul aria-expanded="false" class="collapse">
                {{-- @can('habilidade_instituicao_sessao', 'editar_triagens') --}}
                <li><a href="{{ route('instituicao.painel_escala_medica.index') }}">Painel Escala Médica</a></li>
                {{-- @endcan --}}
            </ul>
        </li>
    @endif

    <!--  ATENDMENTO AMBULATORIAL -->


    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_agendamentos')
       || \Gate::check('habilidade_instituicao_sessao', 'visualizar_agendamentos_centro_cirurgico')
       || \Gate::check('habilidade_instituicao_sessao', 'visualizar_dashboard')
       || \Gate::check('habilidade_instituicao_sessao', 'editar_compromissos')
       || \Gate::check('habilidade_instituicao_sessao', 'visualizar_entrega_exames')
       || \Gate::check('habilidade_instituicao_sessao', 'visualizar_agendamentos_lista_espera')
       )
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="ti-calendar"></i><span
            class="hide-menu">At. Ambulatorial </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_agendamentos')
                    <li><a href="{{ route('instituicao.agendamentos.index') }}">Agenda</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_dashboard')
                    <li><a href="{{ route('instituicao.dashboard') }}">Dashboard</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_atendimentos_urgencia')
                    <li><a href="{{ route('instituicao.atendimentos-urgencia.index') }}">Urgência</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_agendamentos_centro_cirurgico')
                    <li><a href="{{ route('instituicao.agendamentoCentroCirurgico.index') }}">Agenda Centro Cirúrgico</a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'editar_agendamento_procedimento_finalizado')
                    <li><a href="{{ route('instituicao.agendamentosProcedimento.index') }}">Editar Agendamentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'editar_compromissos')
                    <li><a href="{{ route('instituicao.compromissos.index') }}">Etiquetas</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_entrega_exames')
                    <li><a href="{{ route('instituicao.entregas-exame.index') }}">Entrega de exames</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_locais_entrega_exames')
                <li><a href="{{route('instituicao.locais-entrega-exames.index')}}">Locais para entrega de exames</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_agendamentos_lista_espera')
                <li><a href="{{route('instituicao.agendamentosListaEspera.index')}}">Lista de espera</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_locais_entrega_exames')
                <li><a href="{{route('instituicao.locais-entrega-exames.index')}}">Locais para entrega de exames</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_agendamentos_lista_espera')
                <li><a href="{{route('instituicao.agendamentosListaEspera.index')}}">Lista de espera</a></li>
                @endcan
            </ul>
        </li>
    @endif

    @if (\Gate::check('habilidade_instituicao_sessao', 'dashboard_odontologico'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-calendar"></i><span class="hide-menu">Odontológico </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'dashboard_odontologico')
                    <li><a href="{{ route('instituicao.dashboardOdontologico.index') }}">Dashboard</a></li>
                @endcan
            </ul>
        </li>
    @endif



    <!--  ESTOQUE-->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_solicitacoes_estoque') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_estoque_entrada') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_estoque_inventario') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_estoque_baixa_produtos') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_saida_estoque'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="mdi mdi-cart-outline"></i><span class="hide-menu">Estoque </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_solicitacoes_estoque')
                    <li><a href="{{ route('instituicao.solicitacoes-estoque.index') }}">Solicitações</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_saida_estoque')
                    <li><a href="{{ route('instituicao.saidas-estoque.index') }}">Saídas de estoque</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_estoque_entrada')
                    <li><a href="{{ route('instituicao.estoque_entrada.index') }}">Estoque Entrada</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_estoque_baixa_produtos')
                    <li><a href="{{ route('instituicao.estoque_baixa_produtos.index') }}">Estoque Baixa Produtos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_estoque_inventario')
                    <li><a href="{{ route('instituicao.estoque_inventario.index') }}">Estoque Inventário</a></li>
                @endcan
            </ul>
        </li>
    @endif

    <!--  EXAMES-->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_pedido_exame') ||
        \Gate::check('habilidade_instituicao_sessao', 'novo_pedido_exame') ||
        \Gate::check('habilidade_instituicao_sessao', 'editar_pedido_exame') ||
        \Gate::check('habilidade_instituicao_sessao', 'excluir_pedido_exame'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="mdi mdi-cart-outline"></i><span class="hide-menu">Exames </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_pedido_exame')
                    <li><a href="{{ route('instituicao.solicitacoes-estoque.index') }}">Pedido de exames</a></li>
                @endcan
            </ul>
        </li>
    @endif




    @can('habilidade_instituicao_sessao', 'utilizar_chat')
        <li> <a class="has-arrow waves-effect waves-dark" href="{{ route('instituicao.chat.index') }}"
                aria-expanded="false"><i class="ti-comment-alt"></i>
                <span class="hide-menu">App Chat </span>
            </a></li>
    @endcan


    <!--  NOVO MENU -->
    <li class="nav-devider"></li>
    <li class="nav-small-cap">CADASTROS</li>

    <!-- Paineis de totem -->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_tipos_chamada_totem'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="fas fa-desktop"></i><span class="hide-menu">Paineis de totem </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_paineis_totem')
                    <li><a href="{{ route('instituicao.totens.paineis.index') }}">Paineis</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_tipos_chamada_totem')
                    <li><a href="{{ route('instituicao.totens.tipos-chamada.index') }}">Tipos de chamada</a></li>
                @endcan
            </ul>
        </li>
    @endif

    <!--  PESSOAS -->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_pessoas') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_atendimento'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-user"></i><span class="hide-menu">Pacientes </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_pessoas')
                    <li><a href="{{ route('instituicao.pessoas.index') }}">Pacientes</a></li>
                @endcan
                {{-- @can('habilidade_instituicao_sessao', 'visualizar_pessoas') --}}
                <li><a href="{{route('instituicao.contasAmbulatorial.index')}}">Contas Ambulatorial</a></li>
                @can('habilidade_instituicao_sessao', 'visualizar_motivos_atendimento')
                    <li><a href="{{ route('instituicao.motivos_atendimento.index') }}">Motivos de Atendimento</a></li>
                @endcan
                {{-- @endcan --}}


            </ul>
        </li>
    @endif

    <!--  FINANCEIRO-->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_centro_de_custo') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_tipos_documentos') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_fornecedores') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_forma_pagamento') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_contas') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_cartao_credito') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_plano_contas') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_contas_pagar') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_contas_receber') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_movimentacoes'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-money"></i><span class="hide-menu">Financeiro </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_fornecedores')
                    <li><a href="{{ route('instituicao.fornecedores.index') }}">Fornecedores</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_contas_pagar')
                    <li><a href="{{ route('instituicao.contasPagar.index') }}">Contas a Pagar</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_contas_receber')
                    <li><a href="{{ route('instituicao.contasReceber.index') }}">Contas a Receber</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_nota_fiscal')
                    <li><a href="{{ route('instituicao.notasFiscais.index') }}">Notas Fiscais</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_centro_de_custo')
                    <li><a href="{{ route('instituicao.financeiro.cc.index') }}">Centro de Custo</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_tipos_documentos')
                    <li><a href="{{ route('instituicao.formasPagamentos.index') }}">Formas de Pagamentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_forma_pagamento')
                    <li><a href="{{ route('instituicao.tiposDocumentos.index') }}">Tipos de Documentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_contas')
                    <li><a href="{{ route('instituicao.contas.index') }}">Contas</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_cartao_credito')
                    <li><a href="{{ route('instituicao.cartoesCredito.index') }}">Cartões de Crédito</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_plano_contas')
                    <li><a href="{{ route('instituicao.planosContas.index') }}">Plano de Contas</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_movimentacoes')
                    <li><a href="{{ route('instituicao.movimentacoes.index') }}">Movimentação</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_maquina_cartao')
                    <li><a href="{{ route('instituicao.maquinasCartoes.index') }}">Maquinas de cartão</a></li>
                @endcan
           </ul>
       </li>
       @endif
    <!--  PRESTADORES -->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_prestador') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_especialidade') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_especializacao'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-id-badge"></i><span class="hide-menu">Prestadores </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_prestador')
                    <li><a href="{{ route('instituicao.prestadores.index') }}">Prestadores</a></li>
                @endcan

                @can('habilidade_instituicao_sessao', 'visualizar_especialidade')
                    <li><a href="{{ route('instituicao.especialidades.index') }}">Especialidades</a></li>
                @endcan

                @can('habilidade_instituicao_sessao', 'visualizar_especializacao')
                    <li><a href="{{ route('instituicao.especializacoes.index') }}">Especializações</a></li>
                @endcan

                @can('habilidade_instituicao_sessao', 'visualizar_solicitantes')
                    <li><a href="{{ route('instituicao.solicitantes.index') }}">Solicitantes</a></li>
                @endcan
                
                @can('habilidade_instituicao_sessao', 'visualizar_atividades_medicas')
                    <li><a href="{{ route('instituicao.atividadesMedicas.index') }}">Atividades médicas</a></li>
                @endcan
            </ul>
        </li>
    @endif

        @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_impressao') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_atestado') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_relatorio') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_exame') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_receituario') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_configuracao_prontuario') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_prontuario') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_encaminhamento') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_laudo') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_arquivo') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_recibo') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_conclusao') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modelo_termo_folha_sala'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="ti-pencil-alt"></i><span
            class="hide-menu">Modelos </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_impressao')
                    <li><a href="{{ route('instituicao.modeloImpressao.index') }}">Modelos de impressão</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_atestado')
                    <li><a href="{{ route('instituicao.modeloAtestado.index') }}">Modelos de atestado</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_relatorio')
                    <li><a href="{{ route('instituicao.modeloRelatorio.index') }}">Modelos de relatório</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_exame')
                    <li><a href="{{ route('instituicao.modeloExame.index') }}">Modelos de exame</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_receituario')
                    <li><a href="{{ route('instituicao.modeloReceituario.index') }}">Modelos de receituário</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_prontuario')
                    <li><a href="{{ route('instituicao.modeloProntuario.index') }}">Modelo de prontuário</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_encaminhamento')
                    <li><a href="{{ route('instituicao.modeloEncaminhamento.index') }}">Modelos de encaminhamento</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_laudo')
                    <li><a href="{{ route('instituicao.modeloLaudo.index') }}">Modelos de laudo</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_recibo')
                    <li><a href="{{ route('instituicao.modelosRecibo.index') }}">Modelos de recibo</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_arquivo')
                    <li><a href="{{ route('instituicao.modeloArquivo.index') }}">Modelos de arquivo</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_conclusao')
                    <li><a href="{{ route('instituicao.modeloConclusao.index') }}">Modelos de conclusão</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_termo_folha_sala')
                    <li><a href="{{route('instituicao.modelosTermoFolhaSala.index')}}">Modelos de Termo e folha de sala</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modelo_termo_folha_sala')
                    <li><a href="{{route('instituicao.modelosTermoFolhaSala.index')}}">Modelos de Termo e folha de sala</a></li>
                @endcan
                {{-- @can('habilidade_instituicao_sessao', 'visualizar_configuracao_prontuario')
                    <li><a href="{{route('instituicao.configuracaoProntuario.index')}}">Configuração de prontuário</a></li>
                @endcan --}}
            </ul>
        </li>
    @endif


    <!--  CONVÊNIOS -->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_convenios') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_convenio') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_apresentacoes_convenio') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_grupo_faturamento') ||
        \Gate::check('habilidade_instituicao_sessao', 'atualizar_faturamento_sus'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-hand-stop"></i><span class="hide-menu">Convênios </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_convenio')
                    <li>
                        <a href="{{ route('instituicao.convenio.index') }}">
                            Cadastros
                        </a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_convenios')
                    <li><a href="{{ route('instituicao.convenios.index') }}">Vinculações</a></li>
                @endcan

                @can('habilidade_instituicao_sessao', 'visualizar_apresentacoes_convenio')
                    <li>
                        <a href="{{ route('instituicao.convenios.apresentacoes.index') }}">
                            Apresentações
                        </a>
                    </li>
                @endcan

                @can('habilidade_instituicao_sessao', 'visualizar_lotes')
                    <li>
                        <a href="{{ route('instituicao.faturamento.lotes.index') }}">
                            Faturamento
                        </a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_grupo_faturamento')
                    <li>
                        <a href="{{ route('instituicao.grupoFaturamento.index') }}">
                            Grupo de Faturamento
                        </a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'atualizar_vinculos_sus')
                    <li><a href="{{route('instituicao.faturamento-sus.bindings')}}">Vínculos SUS</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'atualizar_faturamento_sus')
                    <li><a href="{{route('instituicao.faturamento-sus.import')}}">Atualizar faturamento SUS</a></li>
                @endcan
            </ul>
        </li>
    @endif

    <!--  FORNECEDORES -->
    {{-- @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_fornecedores'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account-settings-variant"></i><span
            class="hide-menu">Fornecedores </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_fornecedores')
                <li><a href="{{route('instituicao.fornecedores.index')}}">Fornecedores</a></li>
                @endcan
            </ul>
        </li>
        @endif --}}

    <!--  PROCEDIMENTOS -->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_procedimentos') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_modalidades_exame') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_setores_exame') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_medicamentos') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_grupos') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_faturamentos') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_regras_cobranca') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_procedimentos_atendimentos') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_vincular_tuss') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_vincular_brasindice'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-heart-broken"></i><span class="hide-menu">Procedimentos </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_cadastro_procedimentos')
                    <li><a href="{{ route('instituicao.cadastro-procedimentos.index') }}">Procedimentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_tipo_compras')
                    <li><a href="{{route('instituicao.tipoCompras.index')}}">Tipo de Compras</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_procedimentos')
                    <li><a href="{{ route('instituicao.procedimentos.index') }}">Vinculações</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_pacotes')
                    <li><a href="{{ route('instituicao.pacotesProcedimentos.index') }}">Pacotes</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_grupos')
                    <li><a href="{{ route('instituicao.gruposProcedimentos.index') }}">Grupos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modalidades_exame')
                    <li><a href="{{ route('instituicao.modalidades.index') }}">Modalidades</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_setores_exame')
                    <li><a href="{{ route('instituicao.setores.index') }}">Setores</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_exame')
                    <li><a href="{{ route('instituicao.motivoscancelamentoexame.index') }}">Motivos de Cancelamento</a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_medicamentos')
                    <li><a href="{{ route('instituicao.medicamentos.index') }}">Medicamentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_faturamentos')
                    <li><a href="{{ route('instituicao.faturamento.index') }}">Faturamentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_regras_cobranca')
                    <li><a href="{{ route('instituicao.regrasCobranca.index') }}">Regras de Cobrança</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_procedimentos_atendimentos')
                    <li>
                        <a href="{{ route('instituicao.procedimentoAtendimentos.index') }}">
                            Procedimentos do Atendimento
                        </a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_vincular_tuss')
                    <li><a href="{{ route('instituicao.vinculoTuss.index') }}">Vincular Tuss</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_vincular_brasindice')
                    <li>
                        <a href="{{ route('instituicao.vinculoBrasindice.index') }}">Vincular Brasíndice</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endif

    <!--  TIPO DE ATENDIMENTO -->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_atendimentos') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_escalas_medicas') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_origem'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="mdi mdi-account-convert"></i><span class="hide-menu">Tipos de Aten. </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_atendimentos')
                    <li><a href="{{ route('instituicao.atendimentos.index') }}">Caráter de Atendimento</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_escalas_medicas')
                    <li><a href="{{ route('instituicao.escalas-medicas.index') }}">Escalas Médicas</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_origem')
                    <li><a href="{{ route('instituicao.origem.index') }}">Origem</a></li>
                @endcan
            </ul>
        </li>
    @endif

    <!--  ESTOQUE-->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_estoques') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_classes') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_especies') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_unidade') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_produtos') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_divergencia') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_baixa'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-shopping-cart"></i><span class="hide-menu">Estoque </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_estoques')
                    <li><a href="{{ route('instituicao.estoques.index') }}">Estoques</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_classes')
                    <li><a href="{{ route('instituicao.classes.index') }}">Classes</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_especies')
                    <li><a href="{{ route('instituicao.especies.index') }}">Especies</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_unidade')
                    <li><a href="{{ route('instituicao.unidades.index') }}">Unidade</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_produtos')
                    <li><a href="{{ route('instituicao.produtos.index') }}">Produtos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_motivos_divergencia')
                    <li><a href="{{ route('instituicao.motivos-divergencia.index') }}">Motivos divergência</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_motivos_baixa')
                    <li><a href="{{ route('instituicao.motivos-baixa.index') }}">Motivos baixa</a></li>
                @endcan
            </ul>
        </li>
    @endif

    <!--  COMPRAS-->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_tipo_compras') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_comprador') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_motivo_cancelamento') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_motivo_pedido'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="mdi mdi-cart-plus"></i><span class="hide-menu">Compras </span></a>
            <ul aria-expanded="false" class="collapse">
                <li><a href="{{route('instituicao.solicitacaoCompras.index')}}">Solicitação de Compras</a></li>
                @can('habilidade_instituicao_sessao', 'visualizar_tipo_compras')
                    <li><a href="{{ route('instituicao.tipoCompras.index') }}">Tipo de Compras</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_comprador')
                    <li><a href="{{ route('instituicao.compradores.index') }}">Comprador</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_motivo_cancelamento')
                    <li><a href="{{ route('instituicao.motivoCancelamentos.index') }}">Motivo de Cancelamento</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_motivo_pedido')
                    <li><a href="{{ route('instituicao.motivoPedidos.index') }}">Motivo de Pedido</a></li>
                @endcan
            </ul>
        </li>
    @endif

    <!--  INTERNAÇÃO-->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_unidade_internacao') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_acomodacoes') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_altas') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_altas') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_instituicoes_transferencia') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_pre_internacao') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_internacao') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_alta_hospitalar'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="fas fa-bed"></i><span class="hide-menu">Internação </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_internacao')
                    <li><a href="{{ route('instituicao.internacoes.index') }}">Internação</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_alta_hospitalar')
                    <li><a href="{{ route('instituicao.altasHospitalar.index') }}">Alta Hospitalar</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_centro_de_custo')
                    <li><a href="{{ route('instituicao.internacao.unidade-internacao.index') }}">Unidades de
                            Internação</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_tipos_documentos')
                    <li><a href="{{ route('instituicao.internacao.acomodacoes.index') }}">Acomodações</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_forma_pagamento')
                    <li><a href="{{ route('instituicao.internacao.motivos-altas.index') }}">Motivos de Altas</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_contas')
                    <li><a href="{{ route('instituicao.internacao.motivos-cancelamento-altas.index') }}">Motivos de
                            Cancelamento de Altas</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_cartao_credito')
                    <li><a href="{{ route('instituicao.internacao.instituicoes-transferencia.index') }}">Instituições para
                            transferência</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_pre_internacao')
                    <li><a href="{{ route('instituicao.preInternacoes.index') }}">Pré Internações</a></li>
                @endcan
            </ul>
        </li>
    @endif

    <!--  CENTRO CIRURGICO E OBSTETRICO-->
    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_centros_cirurgicos') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_equipes_cirurgicas') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_tipo_partos') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_partos') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_mortes_rn') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_tipos_anestesia') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_grupos_cirurgias') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_cirurgias') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_vias_acesso') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_equipamentos'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="mdi mdi-hospital-building"></i><span class="hide-menu">Centros Cirúrgicos</span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_centros_cirurgicos')
                    <li><a href="{{ route('instituicao.centros.cirurgicos.index') }}">Centros Cirúrgicos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_equipes_cirurgicas')
                    <li><a href="{{ route('instituicao.centros.equipes.index') }}">Equipes Cirúrgicas</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_caixas_cirurgicos')
                    <li><a href="{{ route('instituicao.caixasCirurgicos.index') }}">Caixas Cirúrgicos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_sangues_derivados')
                    <li><a href="{{ route('instituicao.sanguesDerivados.index') }}">Sangues e Derivados</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_tipo_partos')
                    <li><a href="{{ route('instituicao.tipoPartos.index') }}">Tipo de partos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_motivos_partos')
                    <li><a href="{{ route('instituicao.motivosPartos.index') }}">Motivos de Partos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_motivos_mortes_rn')
                    <li><a href="{{ route('instituicao.motivosMortesRN.index') }}">Motivos de Mortes Recem Nascidos</a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_tipos_anestesia')
                    <li><a href="{{ route('instituicao.tiposAnestesia.index') }}">Tipos de anestesisa</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_grupos_cirurgias')
                    <li><a href="{{ route('instituicao.gruposCirurgias.index') }}">Grupos de Cirurgias</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_cirurgias')
                    <li><a href="{{ route('instituicao.cirurgias.index') }}">Cirurgias</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_vias_acesso')
                    <li><a href="{{ route('instituicao.viasAcesso.index') }}">Vias de Acesso</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_equipamentos')
                    <li><a href="{{ route('instituicao.equipamentos.index') }}">Equipamentos</a></li>
                @endcan
            </ul>
        </li>
    @endif

   <!--  Relatórios-->
   @if (
    Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorios_demonstrativo_financeiro')
    || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_atendimento')
    || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorios_fluxo_caixa')
    || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_auditoria_agendamento')
    || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_estoque')
    || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_conclusao')
    || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_registro_log')
    )
<li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="ti-layers-alt"></i><span
   class="hide-menu">Relatórios </span></a>
   <ul aria-expanded="false" class="collapse">
       @can('habilidade_instituicao_sessao', 'visualizar_relatorios_demonstrativo_financeiro')
       <li><a href="{{route('instituicao.demonstrativoFinanceiro.index')}}">Demonstrativo Financeiro</a></li>
       @endcan
       @can('habilidade_instituicao_sessao', 'visualizar_relatorio_atendimento')
       <li><a href="{{route('instituicao.relatorioAtendimento.index')}}">Atendimentos</a></li>
       @endcan
       @can('habilidade_instituicao_sessao', 'visualizar_relatorios_fluxo_caixa')
       <li><a href="{{route('instituicao.relatoriosFluxoCaixa.index')}}">Fluxo de caixa</a></li>
       @endcan
       @can('habilidade_instituicao_sessao', 'visualizar_relatorio_auditoria_agendamento')
       <li><a href="{{route('instituicao.relatorioAuditoriaAgendamentos.index')}}">Auditoria Agendamentos</a></li>
       @endcan
       @can('habilidade_instituicao_sessao', 'visualizar_relatorio_estoque')
       <li><a href="{{route('instituicao.relatoriosEstoque.index')}}">Estoque</a></li>
       @endcan
       @can('habilidade_instituicao_sessao', 'visualizar_relatorio_cartao')
       <li><a href="{{route('instituicao.relatoriosCartao.index')}}">Conciliação de cartões</a></li>
       @endcan
       @can('habilidade_instituicao_sessao', 'visualizar_relatorio_conclusao')
       <li><a href="{{route('instituicao.relatorioConclusao.index')}}">Conclusão</a></li>
       @endcan
       @can('habilidade_instituicao_sessao', 'visualizar_relatorios_sancoop')
       <li><a href="{{route('instituicao.relatoriosSancoop.index')}}">Sancoop</a></li>
       @endcan
       @can('habilidade_instituicao_sessao', 'visualizar_relatorio_registro_log')
       <li><a href="{{route('instituicao.relatorioRegistroLog.index')}}">Registro de Log</a></li>
       @endcan
   </ul>
</li>
@endif

    <!--  Relatórios-->
    @if (Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_a_pagar') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_a_receber') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_pagas') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_recebidas') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_fluxo_de_caixa'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-layers-alt"></i><span class="hide-menu">Relatórios financeiros</span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_a_pagar')
                    <li><a href="{{ route('instituicao.relatoriosFinanceiros.aPagar') }}">Contas a pagar</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_pagas')
                    <li><a href="{{ route('instituicao.relatoriosFinanceiros.pagas') }}">Contas pagas</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_a_receber')
                    <li><a href="{{ route('instituicao.relatoriosFinanceiros.aReceber') }}">Contas a receber</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_recebidas')
                    <li><a href="{{ route('instituicao.relatoriosFinanceiros.recebidas') }}">Contas recebidas</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_fluxo_de_caixa')
                    <li><a href="{{ route('instituicao.relatoriosFinanceiros.fluxoCaixa') }}">Fluxo de caixa</a></li>
                @endcan
            </ul>
        </li>
    @endif

    <!--  Relatórios Estatisticos -->
    @if (Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_financeiro_ambulatorial') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_agenda_ambulatorial') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_procedimentos_ambulatorial'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-pie-chart"></i><span class="hide-menu">Relatórios Estatísticos</span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_financeiro_ambulatorial')
                    <li><a href="{{ route('instituicao.relatoriosEstatisticos.showFinanceioAmbulatorial') }}">Financeiro
                            Ambulatorial</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_agenda_ambulatorial')
                    <li><a href="{{ route('instituicao.relatoriosEstatisticos.showAgenda') }}">Agenda Ambulatorial</a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_procedimentos_ambulatorial')
                    <li><a href="{{ route('instituicao.relatoriosEstatisticos.showProcedimentos') }}">Procedimentos
                            Ambulatorial</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_convenios_ambulatorial')
                    <li><a href="{{ route('instituicao.relatoriosEstatisticos.showConvenios') }}">Convênios
                            Ambulatorial</a></li>
                @endcan
            </ul>
        </li>
    @endif
    {{-- @if (Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_demonstrativo_odontologico') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_repasse_odontologico') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_odontologico_grupo') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos_aprovados') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_procedimentos_nao_realizados') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos_concluidos'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="ti-pie-chart"></i><span
        class="hide-menu">Relatórios Odontológicos</span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_cadastro_procedimentos')
                    <li><a href="{{ route('instituicao.cadastro-procedimentos.index') }}">Procedimentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_procedimentos')
                    <li><a href="{{ route('instituicao.procedimentos.index') }}">Vinculações</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_pacotes')
                    <li><a href="{{ route('instituicao.pacotesProcedimentos.index') }}">Pacotes</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_grupos')
                    <li><a href="{{ route('instituicao.gruposProcedimentos.index') }}">Grupos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_modalidades_exame')
                    <li><a href="{{ route('instituicao.modalidades.index') }}">Modalidades</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_setores_exame')
                    <li><a href="{{ route('instituicao.setores.index') }}">Setores</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_exame')
                    <li><a href="{{ route('instituicao.motivoscancelamentoexame.index') }}">Motivos de Cancelamento</a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_medicamentos')
                    <li><a href="{{ route('instituicao.medicamentos.index') }}">Medicamentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_faturamentos')
                    <li><a href="{{ route('instituicao.faturamento.index') }}">Faturamentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_regras_cobranca')
                    <li><a href="{{ route('instituicao.regrasCobranca.index') }}">Regras de Cobrança</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_procedimentos_atendimentos')
                    <li><a href="{{ route('instituicao.procedimentoAtendimentos.index') }}">Procedimentos do
                            Atendimento</a></li>
                @endcan
            </ul>
        </li>
    @endif --}}

    <!--  Relatórios-->
    {{-- @if (Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorios_demonstrativo_financeiro') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_atendimento') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorios_fluxo_caixa') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_auditoria_agendamento') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_estoque'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-layers-alt"></i><span class="hide-menu">Relatórios </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_relatorios_demonstrativo_financeiro')
                    <li><a href="{{ route('instituicao.demonstrativoFinanceiro.index') }}">Demonstrativo Financeiro</a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_atendimento')
                    <li><a href="{{ route('instituicao.relatorioAtendimento.index') }}">Atendimentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorios_fluxo_caixa')
                    <li><a href="{{ route('instituicao.relatoriosFluxoCaixa.index') }}">Fluxo de caixa</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_auditoria_agendamento')
                    <li><a href="{{ route('instituicao.relatorioAuditoriaAgendamentos.index') }}">Auditoria
                            Agendamentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_estoque')
                    <li><a href="{{ route('instituicao.relatoriosEstoque.index') }}">Estoque</a></li>
                @endcan
            </ul>
        </li>
    @endif --}}

    <!--  Relatórios Estatisticos -->
    {{-- @if (Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_financeiro_ambulatorial') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_agenda_ambulatorial') || Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_procedimentos_ambulatorial'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-pie-chart"></i><span class="hide-menu">Relatórios Estatísticos</span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_financeiro_ambulatorial')
                    <li><a href="{{ route('instituicao.relatoriosEstatisticos.showFinanceioAmbulatorial') }}">Financeiro
                            Ambulatorial</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_agenda_ambulatorial')
                    <li><a href="{{ route('instituicao.relatoriosEstatisticos.showAgenda') }}">Agenda Ambulatorial</a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_procedimentos_ambulatorial')
                    <li><a href="{{ route('instituicao.relatoriosEstatisticos.showProcedimentos') }}">Procedimentos
                            Ambulatorial</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_estatistico_convenios_ambulatorial')
                    <li><a href="{{ route('instituicao.relatoriosEstatisticos.showConvenios') }}">Convênios
                            Ambulatorial</a></li>
                @endcan
            </ul>
        </li>
    @endif --}}
    @if (Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_demonstrativo_odontologico') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_repasse_odontologico') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_odontologico_grupo') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos_aprovados') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_procedimentos_nao_realizados') ||
        Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos_concluidos'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-pie-chart"></i><span class="hide-menu">Relatórios Odontológicos</span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_demonstrativo_odontologico')
                    <li><a href="{{ route('instituicao.relatorioDemonstrativoOdontologico.index') }}">Demonstrativo
                            Odontológico</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_repasse_odontologico')
                    <li><a href="{{ route('instituicao.relatorioRepasseOdontologico.index') }}">Repasse Odontológico</a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_odontologico_grupo')
                    <li><a href="{{ route('instituicao.relatorioOdontologicoGrupo.index') }}">Demonstrativo Grupo</a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos')
                    <li><a href="{{ route('instituicao.relatorioOrcamentos.index') }}">Orçamentos</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos_aprovados')
                    <li><a href="{{ route('instituicao.relatorioOrcamentosAprovados.index') }}">Orçamentos aprovados</a>
                    </li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_procedimentos_nao_realizados')
                    <li><a href="{{ route('instituicao.relatorioProcedimentosNRealizados.index') }}">Procedimentos não
                            realizados</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos_concluidos')
                    <li><a href="{{ route('instituicao.relatorioOrcamentosConcluidos.index') }}">Orçamentos
                            concluídos</a></li>
                @endcan
            </ul>
        </li>
    @endif

    @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_usuario') ||
        \Gate::check('habilidade_instituicao_sessao', 'editar_instituicao') ||
        \Gate::check('habilidade_instituicao_sessao', 'editar_horarios_funcionamento') ||
        \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_conclusoes') ||
        \Gate::check('habilidade_instituicao_sessao', 'editar_parcelas'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                    class="ti-home"></i><span class="hide-menu">Instituição </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_usuario')
                    <li><a href="{{ route('instituicao.instituicoes_usuarios.index') }}">Usuários</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'editar_instituicao')
                    <li><a href="{{ route('instituicao.instituicao_loja.edit') }}">Instituição</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_configuracao_fiscal')
                    <li><a href="{{ route('instituicao.configuracaoFiscal.index') }}">Configurações fiscais</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'config_instituicao')
                    <li><a href="{{ route('instituicao.configuracoes') }}">Configurações</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_motivos_conclusoes')
                    <li><a href="{{ route('instituicao.motivoConclusao.index') }}">Motivo de Conclusões</a></li>
                @endcan

            </ul>
        </li>
    @endif








    <!-- FIM NOVO MENU -->

    {{-- @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_prestador') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_procedimentos') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_atendimentos') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_escalas_medicas') ||
    // \Gate::check('habilidade_instituicao_sessao', 'visualizar_setores') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_convenios') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_origem') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_centro_de_custo') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_unidade_internacao') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_acomodacoes') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_altas') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_altas') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_instituicoes_transferencia') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_centros_cirurgicos') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_estoques') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_classes') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_especies') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_unidade') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_pessoas') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_fornecedores') ||
    \Gate::check('habilidade_instituicao_sessao', 'visualizar_modalidades_exame'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account"></i><span
            class="hide-menu">Cadastros </span></a>
            <ul aria-expanded="false" class="collapse"> --}}
    {{-- <li><a href="#">instituicao</a></li> --}}

    {{-- @can('habilidade_instituicao_sessao', 'visualizar_prestador')
                    <li><a href="{{route('instituicao.prestadores.index')}}">Prestadores</a></li>
                @endcan --}}
    {{-- @can('habilidade_instituicao_sessao', 'visualizar_pessoas')
                    <li><a href="{{route('instituicao.pessoas.index')}}">Pessoas</a></li>
                @endcan --}}
    {{-- @can('habilidade_instituicao_sessao', 'visualizar_fornecedores')
                    <li><a href="{{route('instituicao.fornecedores.index')}}">Fornecedores</a></li>
                @endcan --}}
    {{-- @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_procedimentos') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_modalidades_exame'))
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">

                        <span class="">Procedimentos </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        @can('habilidade_instituicao_sessao', 'visualizar_estoques')
                            <li>
                                <a href="{{route('instituicao.procedimentos.index')}}">
                                    Procedimentos
                                </a>
                            </li>
                        @endcan

                        @can('habilidade_instituicao_sessao', 'visualizar_modalidades_exame')
                        <li>
                            <a href="{{route('instituicao.modalidades.index')}}">
                                Modalidades
                            </a>
                        </li>
                        @endcan

                        @can('habilidade_instituicao_sessao', 'visualizar_setores_exame')
                        <li>
                            <a href="{{route('instituicao.setores.index')}}">
                                Setores
                            </a>
                        </li>
                        @endcan

                        @can('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_exame')
                        <li>
                            <a href="{{route('instituicao.motivoscancelamentoexame.index')}}">
                                Cancelamento
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif --}}
    {{-- @can('habilidade_instituicao_sessao', 'visualizar_atendimentos')
                    <li><a href="{{route('instituicao.atendimentos.index')}}">Caráter de Atendimento</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_escalas_medicas')
                    <li><a href="{{route('instituicao.escalas-medicas.index')}}">Escalas Médicas</a></li>
                @endcan --}}
    {{-- @can('habilidade_instituicao_sessao', 'visualizar_setores')
                    <li><a href="{{route('instituicao.setores.index')}}">Setores</a></li>
                @endcan --}}
    {{-- @can('habilidade_instituicao_sessao', 'visualizar_convenios')
                    <li><a href="{{route('instituicao.convenios.index')}}">Convênios</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_origem')
                <li><a href="{{route('instituicao.origem.index')}}">Origem</a></li>
                @endcan --}}


    {{-- @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_estoques') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_classes') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_especies'))
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">

                        <span class="">Estoques </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                            @can('habilidade_instituicao_sessao', 'visualizar_estoques')
                                <li>
                                    <a href="{{route('instituicao.estoques.index')}}">
                                        Estoques
                                    </a>
                                </li>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_classes')
                            <li>
                                <a href="{{route('instituicao.classes.index')}}">
                                    Classes
                                </a>
                            </li>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_especies')
                                <li>
                                    <a href="{{route('instituicao.especies.index')}}">
                                        Especies
                                    </a>
                                </li>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_unidade')
                            <li>
                                <a href="{{route('instituicao.unidades.index')}}">
                                    Unidade
                                </a>
                            </li>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_produtos')
                            <li>
                                <a href="{{route('instituicao.produtos.index')}}">
                                    Produtos
                                </a>
                            </li>
                            @endcan

                    </ul>
                </li>
                @endif --}}


    {{-- @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_tipo_compras') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_comprador'))
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">

                        <span class="">Compras </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                            @can('habilidade_instituicao_sessao', 'visualizar_tipo_compras')
                                <li>
                                    <a href="{{route('instituicao.tipoCompras.index')}}">
                                        Tipo de Compras
                                    </a>
                                </li>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_comprador')
                            <li>
                                <a href="{{route('instituicao.compradores.index')}}">
                                    Comprador
                                </a>
                            </li>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_motivo_cancelamento')
                            <li>
                                <a href="{{route('instituicao.motivoCancelamentos.index')}}">
                                    Motivo de Cancel.
                                </a>
                            </li>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_motivo_pedido')
                            <li>
                                <a href="{{route('instituicao.motivoPedidos.index')}}">
                                    Motivo de Pedido
                                </a>
                            </li>
                            @endcan

                    </ul>
                </li>
                @endif --}}

    {{-- @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_centro_de_custo') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_forma_pagamento') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_tipos_documentos') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_contas') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_cartao_credito') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_plano_contas'))
                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">

                            <span class="">Financeiro </span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            @can('habilidade_instituicao_sessao', 'visualizar_centro_de_custo')
                                <li>
                                    <a href="{{route('instituicao.financeiro.cc.index')}}">
                                        Centro de Custo
                                    </a>
                                </li>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_tipos_documentos')
                                <li>
                                    <a href="{{route('instituicao.formasPagamentos.index')}}">
                                        Formas de Pagamentos
                                    </a>
                                </li>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_forma_pagamento')
                                <li>
                                    <a href="{{route('instituicao.tiposDocumentos.index')}}">
                                       Tipos Documentos
                                    </a>
                                </li>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_contas')
                                <li>
                                    <a href="{{route('instituicao.contas.index')}}">
                                       Contas
                                    </a>
                                </li>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_cartao_credito')
                                <li>
                                    <a href="{{route('instituicao.cartoesCredito.index')}}">
                                       Cartões de Crédito
                                    </a>
                                </li>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_plano_contas')
                            <li>
                                <a href="{{route('instituicao.planosContas.index')}}">
                                   Plano de Contas
                                </a>
                            </li>
                        @endcan
                        </ul>
                    </li>
                @endif --}}
    {{-- @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_totens') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_filas_triagem') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_classificacoes_triagem') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_triagens') || \Gate::check('habilidade_instituicao_sessao', 'editar_triagens'))
                    <li>
                        <a href="#" class="has-arrow waves-effect waves-dark" aria-expanded="false">
                            <span>Triagem</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            @can('habilidade_instituicao_sessao', 'editar_triagens')
                                <li>
                                    <a href="{{route('instituicao.triagens.index')}}">
                                        Triagens
                                    </a>
                                </li>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_totens')
                                <li>
                                    <a href="{{route('instituicao.triagem.totens.index')}}">
                                        Totens
                                    </a>
                                </li>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_filas_triagem')
                                <li>
                                    <a href="{{route('instituicao.triagem.filas.index')}}">
                                        Filas
                                    </a>
                                </li>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_classificacoes_triagem')
                                <li>
                                    <a href="{{route('instituicao.triagem.classificacoes.index')}}">
                                        Classificações
                                    </a>
                                </li>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_processos_triagem')
                                <li>
                                    <a href="{{route('instituicao.triagem.processos.index')}}">
                                        Processos
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif --}}
    {{-- @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_unidade_internacao') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_acomodacoes') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_altas') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_altas') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_instituicoes_transferencia') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_pre_internacao'))
                    <li>
                        <a href="#" class="has-arrow waves-effect waves-dark" aria-expanded="false">
                            <span>Internação</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            @can('habilidade_instituicao_sessao', 'visualizar_unidade_internacao')
                                <li>
                                    <a href="{{route('instituicao.internacao.unidade-internacao.index')}}">
                                        Unidades de Internação
                                    </a>
                                </li>
                            @endcan
                        </ul>
                        <ul aria-expanded="false" class="collapse">
                            @can('habilidade_instituicao_sessao', 'visualizar_acomodacoes')
                                <li>
                                    <a href="{{route('instituicao.internacao.acomodacoes.index')}}">
                                        Acomodações
                                    </a>
                                </li>
                            @endcan
                        </ul>
                        <ul aria-expanded="false" class="collapse">
                            @can('habilidade_instituicao_sessao', 'visualizar_motivos_altas')
                                <li>
                                    <a href="{{route('instituicao.internacao.motivos-altas.index')}}">
                                        Motivos de Altas
                                    </a>
                                </li>
                            @endcan
                        </ul>
                        <ul aria-expanded="false" class="collapse">
                            @can('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_altas')
                                <li>
                                    <a href="{{route('instituicao.internacao.motivos-cancelamento-altas.index')}}">
                                        Motivos de Cancelamento de Altas
                                    </a>
                                </li>
                            @endcan
                        </ul>
                        <ul aria-expanded="false" class="collapse">
                            @can('habilidade_instituicao_sessao', 'visualizar_instituicoes_transferencia')
                                <li>
                                    <a href="{{ route('instituicao.internacao.instituicoes-transferencia.index') }}">
                                        Instituições para transferência
                                    </a>
                                </li>
                            @endcan
                        </ul>
                        <ul aria-expanded="false" class="collapse">
                            @can('habilidade_instituicao_sessao', 'visualizar_pre_internacao')
                                <li>
                                    <a href="{{ route('instituicao.preInternacoes.index') }}">
                                        Pre Internação
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif --}}
    {{-- @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_centros_cirurgicos') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_equipes_cirurgicas') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_tipo_partos') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_partos') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_mortes_rn') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_tipos_anestesia'))
                    <li>
                        <a href="#" class="has-arrow waves-effect waves-dark" aria-expanded="false">
                            <span>Centros Cirúrgicos e Obstétricos</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            @can('habilidade_instituicao_sessao', 'visualizar_centros_cirurgicos')
                                <li>
                                    <a href="{{route('instituicao.centros.cirurgicos.index')}}">
                                        Centros Cirúrgicos
                                    </a>
                                </li>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_equipes_cirurgicas')
                                <li>
                                    <a href="{{route('instituicao.centros.equipes.index')}}">
                                        Equipes Cirúrgicas
                                    </a>
                                </li>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_tipo_partos')
                                <li>
                                    <a href="{{route('instituicao.tipoPartos.index')}}">
                                        Tipo de partos
                                    </a>
                                </li>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_motivos_partos')
                                <li>
                                    <a href="{{route('instituicao.motivosPartos.index')}}">
                                        Motivos de Partos
                                    </a>
                                </li>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_motivos_mortes_rn')
                                <li>
                                    <a href="{{route('instituicao.motivosMortesRN.index')}}">
                                        Motivos de Mortes Recem Nascidos
                                    </a>
                                </li>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_tipos_anestesia')
                            <li>
                                <a href="{{route('instituicao.tiposAnestesia.index')}}">
                                    Tipos de anestesisa
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endif --}}
    {{-- </ul>
         </li>
        @endif --}}

    {{-- @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_paciente'))
          <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span
            class="hide-menu">Relatório </span></a>
            <ul aria-expanded="false" class="collapse">

                @can('habilidade_instituicao_sessao', 'visualizar_paciente')

                <li><a href="{{route('instituicao.pacientes')}}">Pacientes</a></li>
                @endcan
            </ul>
        </li>
        @endif --}}

    {{-- @endif --}}
</ul>
