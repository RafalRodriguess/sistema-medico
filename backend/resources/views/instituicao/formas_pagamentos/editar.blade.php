@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Forma de pagamento #{$formaPagamento->id} {$formaPagamento->descricao}",
        'breadcrumb' => [
            'Formas de Pagamento' => route('instituicao.formasPagamentos.index'),
            'Atualização',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.formasPagamentos.update', [$formaPagamento]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md-8 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $formaPagamento->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-4 form-group @if($errors->has('sigla')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Sigla</label>
                        <input type="text" name="sigla" value="{{ old('sigla', $formaPagamento->sigla) }}"
                        class="form-control @if($errors->has('sigla')) form-control-danger @endif">
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
