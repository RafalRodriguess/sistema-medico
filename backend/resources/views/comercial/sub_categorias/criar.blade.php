@extends('comercial.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Sub Categoria',
        'breadcrumb' => [
            'Sub Categorias' => route('comercial.sub_categorias.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('comercial.sub_categorias.store') }}" method="post">
                @csrf

                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                    <label class="form-control-label">Nome *</span></label>
                    <input type="text" name="nome" value="{{ old('nome') }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                    @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                    @endif
                </div>

                <div class="form-group @error('categoria_id') has-danger @enderror">
                    <label class="form-control-label">Categoria *</label>
                    <select name="categoria_id"
                        class="form-control selectfild2 @error('categoria_id') form-control-danger @enderror">
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                @if(old('categoria_id') == $categoria->id) selected="selected" @endif>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="button-group">
                    <button type="button" class="btn waves-effect waves-light btn-primary">Primary</button>
                    <button type="button" class="btn waves-effect waves-light btn-secondary">Secondary</button>
                    <button type="button" class="btn waves-effect waves-light btn-success">Success</button>
                    <button type="button" class="btn waves-effect waves-light btn-info">Info</button>
                    <button type="button" class="btn waves-effect waves-light btn-warning">Warning</button>
                    <button type="button" class="btn waves-effect waves-light btn-danger">Danger</button>
                </div> --}}

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
