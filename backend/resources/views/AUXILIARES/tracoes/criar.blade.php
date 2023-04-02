@extends('layouts/material')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Tração',
        'breadcrumb' => [
            'Trações' => route('tracoes.index'),
            'Nova',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('tracoes.store') }}" method="post">
                @csrf

                <div class="form-group @if($errors->has('descricao')) has-danger @endif">
                    <label class="form-control-label">Descrição</span></label>
                    <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                    @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                    @endif
                </div>


                <div class="form-group text-right">
                         <a href="{{ route('tracoes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
