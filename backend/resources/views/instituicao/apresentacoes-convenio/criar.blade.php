@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar apresentação de convênio',
        'breadcrumb' => [
            'Apresentações de convênios' => route('instituicao.convenios.apresentacoes.index'),
            'Nova',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.convenios.apresentacoes.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-md-6 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label">Nome<span class="text-danger">*</span></span></label>
                        <input type="text" name="nome" value="{{ old('nome') }}"
                            class="form-control @if($errors->has('nome')) form-control-danger @endif">
                        @if($errors->has('nome'))
                            <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right">
                         <a href="{{ route('instituicao.convenios.apresentacoes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
