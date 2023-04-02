<ul id="sidebarnav">

    {{-- @foreach($menus as $menu)
        <li>
            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="{{ $menu['icone'] }}"></i>
                <span class="hide-menu">{{ $menu['titulo'] }}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                @foreach($menu['submenus'] as $titulo => $rota)
                    <li class="{{ request()->route()->getName() === $rota ? 'active' : '' }}">
                        <a href="{{ route($rota) }}">{{ $titulo }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
        @endforeach --}}

        {{-- <li class="nav-small-cap">MENUS</li> --}}
        {{-- @if (\session()->get('instituicao')) --}}

        <li class="nav-small-cap">ADMINISTRAÇÃO</li>

        @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_usuario') || \Gate::check('habilidade_instituicao_sessao', 'editar_instituicao') || \Gate::check('habilidade_instituicao_sessao', 'editar_horarios_funcionamento') || \Gate::check('habilidade_instituicao_sessao', 'editar_parcelas'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account-card-details"></i><span
            class="hide-menu">Instituição </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_usuario')
                <li><a href="{{ route('instituicao.instituicoes_usuarios.index') }}">Usuários</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'editar_instituicao')
                <li><a href="{{ route('instituicao.instituicao_loja.edit') }}">Instituição</a></li>
                @endcan

            </ul>
        </li>
        @endif


        <!--  NOVO MENU -->
        <li class="nav-devider"></li>
        <li class="nav-small-cap">CADASTROS</li>


        <!--  PESSOAS -->
        @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_pessoas'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-users"></i><span
            class="hide-menu">Pessoas </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_pessoas')
                <li><a href="{{route('instituicao.pessoas.index')}}">Pessoas</a></li>
                @endcan
            </ul>
        </li>
        @endif

        <!--  PRESTADORES -->
        @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_prestador'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-user-md"></i><span
            class="hide-menu">Prestadores </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_prestador')
                <li><a href="{{route('instituicao.prestadores.index')}}">Prestadores</a></li>
                @endcan
            </ul>
        </li>
        @endif

        <!--  FORNECEDORES -->
        @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_fornecedores'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account-settings-variant"></i><span
            class="hide-menu">Fornecedores </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_fornecedores')
                <li><a href="{{route('instituicao.fornecedores.index')}}">Fornecedores</a></li>
                @endcan
            </ul>
        </li>
        @endif

         <!--  PROCEDIMENTOS -->
         @if (
              \Gate::check('habilidade_instituicao_sessao', 'visualizar_procedimentos')
            || Gate::check('habilidade_instituicao_sessao', 'visualizar_modalidades_exame')
            || Gate::check('habilidade_instituicao_sessao', 'visualizar_setores_exame')
              )
         <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-pulse"></i><span
             class="hide-menu">Procedimentos </span></a>
             <ul aria-expanded="false" class="collapse">
                 @can('habilidade_instituicao_sessao', 'visualizar_procedimentos')
                 <li><a href="{{route('instituicao.procedimentos.index')}}">Procedimentos</a></li>
                 @endcan
                 @can('habilidade_instituicao_sessao', 'visualizar_modalidades_exame')
                 <li><a href="{{route('instituicao.modalidades.index')}}">Modalidades</a></li>
                 @endcan
                 @can('habilidade_instituicao_sessao', 'visualizar_setores_exame')
                 <li><a href="{{route('instituicao.setores.index')}}">Setores</a></li>
                 @endcan
                 @can('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_exame')
                 <li><a href="{{route('instituicao.motivoscancelamentoexame.index')}}">Motivos de Cancelamento</a></li>
                 @endcan
             </ul>
         </li>
         @endif

         <!--  TIPO DE ATENDIMENTO -->
         @if (
              \Gate::check('habilidade_instituicao_sessao', 'visualizar_procedimentos')
            || Gate::check('habilidade_instituicao_sessao', 'visualizar_modalidades_exame')
            || Gate::check('habilidade_instituicao_sessao', 'visualizar_setores_exame')
              )
         <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-pulse"></i><span
             class="hide-menu">Tipo de Atendimento </span></a>
             <ul aria-expanded="false" class="collapse">
                 @can('habilidade_instituicao_sessao', 'visualizar_procedimentos')
                 <li><a href="{{route('instituicao.procedimentos.index')}}">Caráter de Atendimento</a></li>
                 @endcan
                 @can('habilidade_instituicao_sessao', 'visualizar_modalidades_exame')
                 <li><a href="{{route('instituicao.modalidades.index')}}">Modalidades</a></li>
                 @endcan
                 @can('habilidade_instituicao_sessao', 'visualizar_setores_exame')
                 <li><a href="{{route('instituicao.setores.index')}}">Setores</a></li>
                 @endcan
                 @can('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_exame')
                 <li><a href="{{route('instituicao.motivoscancelamentoexame.index')}}">Motivos de Cancelamento</a></li>
                 @endcan
             </ul>
         </li>
         @endif

        

        


        <!-- FIM NOVO MENU -->

        @if (
            \Gate::check('habilidade_instituicao_sessao', 'visualizar_prestador')||
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

            \Gate::check('habilidade_instituicao_sessao', 'visualizar_centros_cirurgicos') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_estoques') ||
            \Gate::check('habilidade_instituicao_sessao', 'visualizar_classes') ||
            \Gate::check('habilidade_instituicao_sessao', 'visualizar_especies')||
            \Gate::check('habilidade_instituicao_sessao', 'visualizar_unidade') ||
            \Gate::check('habilidade_instituicao_sessao', 'visualizar_pessoas') ||
            \Gate::check('habilidade_instituicao_sessao', 'visualizar_fornecedores') ||
            \Gate::check('habilidade_instituicao_sessao', 'visualizar_modalidades_exame')

        )
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account"></i><span
            class="hide-menu">Cadastros </span></a>
            <ul aria-expanded="false" class="collapse">
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
                @can('habilidade_instituicao_sessao', 'visualizar_atendimentos')
                    <li><a href="{{route('instituicao.atendimentos.index')}}">Caráter de Atendimento</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_escalas_medicas')
                    <li><a href="{{route('instituicao.escalas-medicas.index')}}">Escalas Médicas</a></li>
                @endcan
                {{-- @can('habilidade_instituicao_sessao', 'visualizar_setores')
                    <li><a href="{{route('instituicao.setores.index')}}">Setores</a></li>
                @endcan --}}
                @can('habilidade_instituicao_sessao', 'visualizar_convenios')
                    <li><a href="{{route('instituicao.convenios.index')}}">Convênios</a></li>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_origem')
                <li><a href="{{route('instituicao.origem.index')}}">Origem</a></li>
                @endcan


                @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_estoques') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_classes') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_especies'))
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                        {{-- <i class="mdi mdi-account"></i> --}}
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
                @endif


                @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_tipo_compras') || \Gate::check('habilidade_instituicao_sessao', 'visualizar_comprador') )
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                        {{-- <i class="mdi mdi-account"></i> --}}
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
                @endif

                @if(
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_centro_de_custo') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_forma_pagamento') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_tipos_documentos') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_contas')
                    || \Gate::check('habilidade_instituicao_sessao', 'visualizar_cartao_credito')
                    || \Gate::check('habilidade_instituicao_sessao', 'visualizar_plano_contas')
                    || \Gate::check('habilidade_instituicao_sessao', 'visualizar_movimentacoes')

                )
                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                            {{-- <i class="mdi mdi-account"></i> --}}
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
                            @can('habilidade_instituicao_sessao', 'visualizar_movimentacoes')
                                <li>
                                    <a href="{{route('instituicao.movimentacoes.index')}}">
                                    Movimentação
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
                @endif
                @if(
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_totens') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_filas_triagem') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_classificacoes_triagem') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_triagens') ||
                    \Gate::check('habilidade_instituicao_sessao', 'editar_triagens')
                )
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
                @endif
                @if(
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_unidade_internacao') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_acomodacoes') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_altas') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_altas') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_instituicoes_transferencia')
                )
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
                    </li>
                @endif
                @if(
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_centros_cirurgicos') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_equipes_cirurgicas') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_tipo_partos') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_partos') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_motivos_mortes_rn') ||
                    \Gate::check('habilidade_instituicao_sessao', 'visualizar_tipos_anestesia')
                )
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
                @endif
            </ul>
        </li>
        @endif
        @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_paciente') )
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span
            class="hide-menu">Relatório </span></a>
            <ul aria-expanded="false" class="collapse">
                {{-- <li><a href="#">instituicao</a></li> --}}
                @can('habilidade_instituicao_sessao', 'visualizar_paciente')
                {{-- OBS CATEGORIAS, FAZER SUBCATEGORIAS --}}
                <li><a href="{{route('instituicao.pacientes')}}">Pacientes</a></li>
                @endcan
            </ul>
        </li>
        @endif
        @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_agendamentos') )
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-calendar-clock"></i><span
            class="hide-menu">Agendamentos </span></a>
            <ul aria-expanded="false" class="collapse">
                @can('habilidade_instituicao_sessao', 'visualizar_agendamentos')
                <li><a href="{{route('instituicao.agendamentos.index')}}">Agendamentos</a></li>
                @endcan
            </ul>
        </li>
        @endif
        {{-- @endif --}}
    </ul>
