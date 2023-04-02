@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => 'Cadastrar prestador solicitante',
    'breadcrumb' => [
        'Solicitantes' => route('instituicao.solicitantes.index'),
        'Novo',
    ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.solicitantes.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md-8 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label">Nome: *</label>
                        <input type="text" name="nome" value="{{ old('nome') }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                        @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-groupn text-right">
                    <hr>
                    <a href="{{ route('instituicao.solicitantes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
    @endsection
