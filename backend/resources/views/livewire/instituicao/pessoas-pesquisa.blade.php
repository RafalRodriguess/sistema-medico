<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_pessoas')
                <div class="col-md-2">
                    <div class="form-group btn-responsive" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.pessoas.create') }}">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>

    {{-- <table class="tablesaw table-bordered table-hover table" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch> --}}
        {{-- <table class="tablesaw table-bordered table-hover table" style="overflow-wrap: anywhere"> --}}
        <table class="tablesaw table-bordered table-hover table" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap >
        <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" data-tablesaw-sortable-default-col data-tablesaw-priority="3">ID</th>
                <th scope="col" data-tablesaw-sortable-col  >Nome</th>
                <th scope="col">Personalidade</th>
                <th scope="col">CPF/CNPJ</th>
                <th scope="col">Tipo</th>
                <!-- INTEGRAÇÃO ASAPLAN COLUNAS DO PLANO -->
                @if ($instituicao->integracao_asaplan == 1)
                <th scope="col">Associado</th>
                <th scope="col">Situação</th>
                @endif
                <!-- FIM INTEGRAÇÃO ASAPLAN -->
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pessoas as $pessoa)

                <!-- INTEGRAÇÃO ASAPLAN CORES DOS STATUS DO PLANO -->
                @php
                if($instituicao->integracao_asaplan == 1):

                     if($pessoa->asaplan_situacao_plano == 1):

                      $setar_background_associado = "style='background:#b2f2b3'";

                     elseif ($pessoa->asaplan_situacao_plano == 2):

                      $setar_background_associado = "style='background:#cdd2f7'";

                     elseif ($pessoa->asaplan_situacao_plano == 3):

                      $setar_background_associado = "style='background:#f1c1c6'";

                     else:
                     $setar_background_associado = "";
                     endif;


                else:
                 $setar_background_associado = "";
                endif;
                @endphp
                <!-- FIM INTEGRAÇÃO ASAPLAN -->

                <tr @php echo $setar_background_associado @endphp>
                    <td class="title"><a href="javascript:void(0)">{{ $pessoa->id }}</a></td>
                    <td>
                        @if ($pessoa->nome) {{ $pessoa->nome}} @else {{ $pessoa->nome_fantasia}} @endif
                    </td>
                    <td> {{ App\Pessoa::getPersonalidadeTexto($pessoa->personalidade) }} </td>
                    <td>
                        @if ($pessoa->cpf) {{ $pessoa->cpf }} @else {{ $pessoa->cnpj }} @endif
                    </td>
                    <td>{{ App\Pessoa::getTipoTexto($pessoa->tipo) }}</td>
                    <!-- INTEGRAÇÃO ASAPLAN COLUNAS DO PLANO -->
                    @if ($instituicao->integracao_asaplan == 1)
                    <td>
                        @if ($pessoa->asaplan_tipo == 1) {{ 'Titular' }} @elseif($pessoa->asaplan_tipo == 2) {{ 'Beneficiário' }} @else {{ 'Não Associado' }} @endif
                    </td>
                    <td>
                        @if ($pessoa->asaplan_situacao_plano == 1) {{ 'Ativo' }} @elseif($pessoa->asaplan_situacao_plano == 2) {{ 'Suspenso' }} @elseif($pessoa->asaplan_situacao_plano == 3) {{ 'Cancelado' }} @else {{ 'Não Associado' }} @endif
                    </td>
                    @endif
                    <!-- FIM INTEGRAÇÃO ASAPLAN -->
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_pessoas')
                            <a href="{{ route('instituicao.pessoas.edit', [$pessoa]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_pessoas')
                            <form action="{{ route('instituicao.pessoas.destroy', [$pessoa]) }}" method="post" class="d-inline form-excluir-registro-pessoa">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro-pessoa"  aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                        <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'visualizar_documentos_pessoas')
                            <a href="{{ route('instituicao.pessoas.documentos.index', [$pessoa]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Documentos">
                                    <i class="ti-folder"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'visualizar_carteirinha')
                            <a href="{{ route('instituicao.carteirinhas.index', [$pessoa]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Carteirinha convenio">
                                    <i class="mdi mdi-account-card-details"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'vincula_convenios_pessoas')
                            <a href="{{ route('instituicao.pessoas.documentos.index', [$pessoa]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Documentos">
                                    <i class="mdi mdi-human-greeting"></i>
                                </button>
                            </a>
                        @endcan
                        @if ($pessoa->tipo == 2 && $medico == true)
                            @can('habilidade_instituicao_sessao', 'abrir_prontuario')
                                <a href="{{ route('instituicao.pessoas.abrirProntuario', [$pessoa]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Atender avulso">
                                        <i class="ti-write"></i>
                                    </button>
                                </a>
                            @endcan
                        @endif
                        {{-- @if($pessoa->tipo == 2 && $medico == true) --}}
                            @can('habilidade_instituicao_sessao', 'visualizar_historico')
                                <a href="{{ route('instituicao.pessoas.abrirProntuarioResumo', [$pessoa]) }}" target="_blank" >
                                    <button type="button" class="btn btn-xs btn-secondary" data-toggle="tooltip" data-placement="top" data-original-title="Histórico do paciente">
                                        <span class="ti-heart-broken"></span>
                                    </button>
                                </a>
                            @endcan
                        {{-- @endif --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            {{-- <tr>
                <td colspan="5">
                    {{ $instituicoes->links() }}
                </td>
            </tr>  --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $pessoas->links() }}
    </div>
</div>
