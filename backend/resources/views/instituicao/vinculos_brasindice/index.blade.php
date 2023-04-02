@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Vincular Brasíndice',
        'breadcrumb' => ['Vincular Brasíndice'],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body">
            <h3>Medicamentos Brasíndice</h3>
            <hr>
            <form action="{{ route('instituicao.vinculoBrasindice.index') }}" method="GET">
                <div class="row">
                    <div class="form-group col-6 col-md-2">
                        <label class="form-control-label" for="tipo_id">
                            Tipo de importação
                        </label>
                        <select name="tipo_id" id="tipo_id" class="form-control">
                            <option value="">Selecione...</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo->id }}"
                                    {{ isset($filters['tipo_id']) && $filters['tipo_id'] == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->descricao }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 col-md-1">
                        <label class="form-control-label" for="edicao">
                            Edição
                        </label>
                        <input type="text" name="edicao" id="edicao" class="form-control"
                            value="{{ $filters['edicao'] ?? '' }}">
                    </div>

                    <div class="form-group col-6 col-md-1">
                        <label class="form-control-label" for="tiss">
                            TISS
                        </label>
                        <input type="text" name="tiss" id="tiss" class="form-control"
                            value="{{ $filters['tiss'] ?? '' }}">
                    </div>

                    <div class="form-group col-6 col-md-1">
                        <label class="form-control-label" for="ean">
                            EAN
                        </label>
                        <input type="text" name="ean" id="ean" class="form-control"
                            value="{{ $filters['ean'] ?? '' }}">
                    </div>

                    <div class="form-group col-6 col-md-1">
                        <label class="form-control-label" for="tuss">
                            TUSS
                        </label>
                        <input type="text" name="tuss" id="tuss" class="form-control"
                            value="{{ $filters['tuss'] ?? '' }}">
                    </div>

                    <div class="form-group col-6 col-md-3">
                        <label class="form-control-label" for="laboratorio">
                            Laboratório
                        </label>
                        <input type="text" name="laboratorio" id="laboratorio" class="form-control"
                            value="{{ $filters['laboratorio'] ?? '' }}">
                    </div>

                    <div class="form-group col-6 col-md-3">
                        <label class="form-control-label" for="medicamento">
                            Medicamento
                        </label>
                        <input type="text" name="medicamento" id="medicamento" class="form-control"
                            value="{{ $filters['medicamento'] ?? '' }}">
                    </div>

                </div>

                <div class="form-group text-right">
                    @can('habilidade_instituicao_sessao', 'importar_vincular_brasindice')
                        <a href="{{ route('instituicao.vinculoBrasindice.cadastrar') }}"
                            class="btn btn-info waves-effect waves-light">Importar</a>
                    @endcan

                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10" value="Search">
                        Buscar <i class="mdi mdi-magnify"></i>
                    </button>
                </div>
            </form>
            
            @if (count($vinculos))
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Edição</th>
                                <th>TISS</th>
                                <th>EAN</th>
                                <th>TUSS</th>
                                <th>Tipo</th>
                                <th>Cod. Lab.</th>
                                <th>Laboratório</th>
                                <th>Cod. Med.</th>
                                <th>Medicamento</th>
                                <th>Cod. Apr.</th>
                                <th>Apresentação</th>
                                <th>Tipo de preço</th>
                                <th>Preço do medicamento</th>
                                <th>Qt. fracionamento</th>
                                <th>Preço fracionado</th>
                                <th>IPI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vinculos as $vinculo)
                                <tr>
                                    <td>{{ $vinculo->edicao }}</td>
                                    <td>{{ $vinculo->tiss }}</td>
                                    <td>{{ $vinculo->ean }}</td>
                                    <td>{{ $vinculo->tuss }}</td>
                                    <td>{{ $vinculo->tipo->descricao }}</td>
                                    <td>{{ $vinculo->laboratorio_cod }}</td>
                                    <td>{{ $vinculo->laboratorio }}</td>
                                    <td>{{ $vinculo->medicamento_cod }}</td>
                                    <td>{{ $vinculo->medicamento }}</td>
                                    <td>{{ $vinculo->apresentacao_cod }}</td>
                                    <td>{{ $vinculo->apresentacao }}</td>
                                    <td>{{ $vinculo->tipo_preco }}</td>
                                    <td>{{ number_format($vinculo->preco_medicamento, 2, ',', '.') }}</td>
                                    <td>{{ $vinculo->qtd_fracionamento }}</td>
                                    <td>{{ number_format($vinculo->valor_fracionado, 2, ',', '.') }}</td>
                                    <td>{{ number_format($vinculo->ipi, 2, ',', '.') }}</td>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <strong>Nenhum médicamento importado.</strong>
            @endif
        </div>
        <div class="card-footer">
            {{ $vinculos->links() }}
        </div>
    </div>
@endsection

@push('scripts')
@endpush
