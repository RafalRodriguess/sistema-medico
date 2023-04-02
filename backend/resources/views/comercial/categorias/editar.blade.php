@extends('comercial.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Categoria #{$categoria->id} {$categoria->nome}",
        'breadcrumb' => [
            'Categoria' => route('comercial.categorias.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('comercial.categorias.update', [$categoria]) }}" method="post">
                @method('put')
                @csrf

                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                    <label class="form-control-label">Nome *</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $categoria->nome) }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                    @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                    @endif
                </div>

                <div class="form-group text-right">
                        <a href="{{ route('comercial.categorias.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
