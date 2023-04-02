@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Forma de pagamento',
        'breadcrumb' => [
            'Formas de Pagamento' => route('instituicao.formasPagamentos.index'),
            'Nova',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.formasPagamentos.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-8 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Descrição<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @if($errors->has('descricao')) form-control-danger @endif" name="descricao"
                            value="{{ old('descricao') }}">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('sigla')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Sigla<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @if($errors->has('sigla')) form-control-danger @endif" name="sigla"
                            value="{{ old('sigla') }}">
                        @if($errors->has('sigla'))
                            <small class="form-control-feedback">{{ $errors->first('sigla') }}</small>
                        @endif
                    </div>
                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.formasPagamentos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
