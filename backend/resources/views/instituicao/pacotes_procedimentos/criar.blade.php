@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Pacote',
        'breadcrumb' => [
            'Grupos' => route('instituicao.pacotesProcedimentos.index'),
            'Novo',
        ],
        ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.pacotesProcedimentos.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descricão: *</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.pacotesProcedimentos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
