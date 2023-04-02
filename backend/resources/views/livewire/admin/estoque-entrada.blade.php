<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">
                    <div class="form-group" style="margin-bottom: 0px !important;">

                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por documento">


                    </div>
                  </div>
                    @can('habilidade_admin', 'visualizar_usuario_comercial')
                        <div class="col-md-2">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <a href="{{ route('estoque_entrada.criar') }}">
                                <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                                </a>
                            </div>
                        </div>
                    @endcan
                                             </div>
          </form>

          <hr>


<table class="tablesaw table-bordered table-hover table" >
    <thead>
        <tr>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Estoque</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">ID Tipo Documento</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Consignado</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Contabiliza
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Numero do Documento
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Série
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                ID Fornecedor
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Data Emissão
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Data Hora Entrada
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($estoqueEntradas as $estoqueEntradas)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $estoqueEntradas->id }}</a></td>
                <td>{{ $estoqueEntradas->id_estoque }} {{ $estoqueEntradas->descricao_estoques}}</td>
                <td>{{ $estoqueEntradas->id_tipo_documento}} - {{$estoqueEntradas->descricao_tipo_documento}}</td>

                @if ($estoqueEntradas->consignado)
                <td>Sim</td>
                @else
                <td>Não</td>
                @endif

                @if ($estoqueEntradas->contabiliza)
                <td>Sim</td>
                @else
                <td>Não</td>
                @endif

                <td>{{ $estoqueEntradas->numero_documento }}</td>
                <td>{{ $estoqueEntradas->serie }}</td>
                <td>{{ $estoqueEntradas->id_fornecedor }} - {{ $estoqueEntradas->descricao_fornecedor }}</td>
                <td>{{ $estoqueEntradas->data_emissao }}</td>
                <td>{{ $estoqueEntradas->data_hora_entrada }}</td>
                <td>
                    @can('habilidade_admin', 'visualizar_usuario_comercial')
                        <a href="{{ route('estoque_entrada.editar', [$estoqueEntradas]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                        </a>
                    @endcan
                          @can('habilidade_admin', 'visualizar_usuario_comercial')
                    <form action="{{ route('estoque_entrada.destroy', [$estoqueEntradas]) }}" method="get" class="d-inline form-excluir-registro">
                        @method('delete')
                        @csrf
                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                <i class="ti-trash"></i>
                        </button>
                    </form>
                @endcan

                @can('habilidade_admin', 'visualizar_usuario_comercial')
                <a href="{{ route('estoque_entrada_produtos.index', [$estoqueEntradas]) }}">
                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                    data-toggle="tooltip" data-placement="top" data-original-title="Produtos">
                            <i class="ti-user"></i>
                    </button>
                </a>
                @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        {{-- <tr>

        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
<!--  -->
</div>
</div>
