@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Perfis Usuário da institução',
        'breadcrumb' => [
            'Perfis de Usuário da institução' => route('perfis-usuarios-instituicoes.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('perfis-usuarios-instituicoes.store') }}" method="post">
                @csrf

                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                    <label class="form-control-label">Nome *</span></label>
                    <input type="text" name="nome" value="{{ old('nome') }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                    @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                    @endif
                </div>


                <div class="form-group text-right">
                         <a href="{{ route('perfis-usuarios-instituicoes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
