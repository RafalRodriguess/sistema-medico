<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" 
                        class="form-control" placeholder="Pesquise por nome...">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>
                    <select name="especialidade" id="especialidade" class="form-control" 
                        wire:model="especialidade">
                        <option value="0">Todos</option>
                        @foreach ($especialidades as $especialidade)
                            <option value="{{ $especialidade->id }}">{{ $especialidade->descricao }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @can('habilidade_instituicao_sessao', 'cadastrar_prestador')                            
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.prestadores.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>

    <div class="table-responsive">
        
        <table class="tablesaw table-bordered table-hover table">
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">CPF/CNPJ</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Atuação</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Especialidade</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prestadores as $prestador)
                    <?php $instituicao_prestador = $prestador->instituicaoPrestador($instituicao_id); ?>
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $prestador->id }}</a></td>
                        <td>{{ !empty($prestador->especialidadeInstituicao[0]->nome) ? $prestador->especialidadeInstituicao[0]->nome : $prestador->nome }}</td>
                        <td>{{ (!$prestador->cnpj) ? $prestador->cpf : $prestador->cnpj }}</td>
                        <td>{{ App\InstituicoesPrestadores::getTipoTexto($instituicao_prestador[0]->tipo) }}</td>
                            @php $espec=[];
                                foreach($prestador->especialidade as $e){
                                array_push($espec, $e->nome);
                                }
                            @endphp
                        <td>{{ implode(", ",$espec) }}</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'editar_prestador')
                                <a href="{{ route('instituicao.prestadores.edit', [$prestador]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                                </a>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'excluir_prestador')
                                @if($prestador->instituicoes_count == 0)
                                    <form action="{{ route('instituicao.prestadores.destroy', [$prestador]) }}" method="post" class="d-inline form-excluir-registro">
                                        @method('delete')
                                        @csrf
                                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                                <i class="ti-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @endcan
                            
                            @can('habilidade_instituicao_sessao', 'visualizar_documento_prestador')
                                <a href="{{ route('instituicao.prestadores.documentos.index', [$prestador]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Documentos">
                                        <i class="ti-folder"></i>
                                    </button>
                                </a>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'editar_agenda_prestador')
                                @if(in_array($prestador->especialidadeInstituicao[0]->tipo, [2, 3, 6, 7, 8, 9, 10, 15]))
                                    <a href="{{ route('instituicao.prestadores.getAgenda', [$prestador->id]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Agenda">
                                                <i class="ti-calendar"></i>
                                        </button>
                                    </a>
                                
                                    <a href="{{ route('instituicao.prestadores.agendaAusente.index', [$prestador]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Agenda ausente">
                                                <i class="mdi mdi-calendar-remove"></i>
                                        </button>
                                    </a>
                                @endif
                            @endcan

                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                {{-- <tr>
                    <td colspan="5">
                        {{ $prestador->links() }}
                    </td>
                </tr>  --}}
            </tfoot>
        </table>
    </div>

    <div style="float: right">
        {{ $prestadores->links() }}
    </div>
</div>

