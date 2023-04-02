@extends('instituicao.layout')
@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Criar motivo para baixa de estoque",
        'breadcrumb' => [
            'Motivos para baixa de estoque' => route('instituicao.motivos-baixa.index'),
            'Criar motivo para baixa de estoque',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.motivos-baixa.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md-8 form-group @if ($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descricao *</label>
                        <input rows="3" type="text" name="descricao"
                            class="form-control @if ($errors->has('descricao')) form-control-danger @endif" value="{{ old('descricao') }}"/>
                        @if ($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                </div>


                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.motivos-baixa.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i>
                        Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
