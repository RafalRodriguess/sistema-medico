@extends('comercial.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Sub Categoria #{$sub_categoria->id} {$sub_categoria->nome}",
        'breadcrumb' => [
            'Sub Categoria' => route('comercial.sub_categorias.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('comercial.sub_categorias.update', [$sub_categoria]) }}" method="post">
                @method('put')
                @csrf

                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                    <label class="form-control-label">Nome *</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $sub_categoria->nome) }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                    @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('categoria_id')) has-danger @endif">
                    <label class="form-control-label">Categoria *</label>
                    <select name="categoria_id"
                        class="form-control selectfild2 @if($errors->has('categoria_id')) form-control-danger @endif">
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                @if(old('categoria_id', $sub_categoria->categoria_id) == $categoria->id) selected="selected" @endif>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('categoria_id'))
                        <div class="form-control-feedback">{{ $errors->first('categoria_id') }}</div>
                    @endif
                </div>

                <div class="form-group text-right">
                        <a href="{{ route('comercial.sub_categorias.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
