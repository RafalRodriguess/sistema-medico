@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Atividades médicas',
        'breadcrumb' => ['Prestadores', 'Atividades médicas' => route('instituicao.atividadesMedicas.index'), 'Editar'],
    ])
    @endcomponent

    <div class="card">
        <form action="{{ route('instituicao.atividadesMedicas.update', [$atividades_medica]) }}" method="POST">
            <div class="card-body">
                @method('put')
                @include('instituicao.atividades_medicas.form')

            </div>

            <div class="card-footer">
                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.atividadesMedicas.index') }}"
                        class="btn btn-secondary waves-effect waves-light m-r-10 btn-left">
                        <i class="mdi mdi-arrow-left-bold"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10 btn-save">
                        <i class="mdi mdi-check"></i> Salvar
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
@endpush
