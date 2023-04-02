@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Atividades médicas',
        'breadcrumb' => ['Prestadores', 'Atividades médicas'],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col">
                    <h3>Atividades médicas</h3>
                </div>
                {{-- @can('habilidade_instituicao_sessao', 'cadastrar_atividades_medicas') --}}
                <div class="col text-right">
                    <a href="{{ route('instituicao.atividadesMedicas.create') }}"
                        class="btn btn-info waves-effect waves-light">
                        Novo
                    </a>
                </div>
                {{-- @endcan --}}
            </div>
            <div class="scrolling-pagination">
                <table class="tablesaw table-bordered table-hover table">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Ordem de apresentação</th>
                            <th>Tipo de função</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($atividades as $atividade)
                            <tr>
                                <td>{{ $atividade->descricao }}</td>
                                <td>{{ $atividade->ordem_apresentacao }}</td>
                                <td>{{ $atividade->tipo_funcao }}</td>
                                <td>
                                    @can('habilidade_instituicao_sessao', 'editar_atividades_medicas')
                                    <a href="{{ route('instituicao.atividadesMedicas.edit', [$atividade]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                            aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                            data-original-title="Editar">
                                            <i class="ti-pencil-alt"></i>
                                        </button>
                                    </a>
                                    @endcan

                                    @can('habilidade_instituicao_sessao', 'excluir_atividades_medicas')
                                    <form action="{{ route('instituicao.atividadesMedicas.destroy', [$atividade]) }}"
                                        method="post" class="d-inline form-excluir-registro">
                                        @method('delete')
                                        @csrf
                                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"
                                            aria-haspopup="true" aria-expanded="false" data-toggle="tooltip"
                                            data-placement="top" data-original-title="Excluir">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $atividades->links() }}
        </div>
    </div>
@endsection

@push('scripts')
@endpush
