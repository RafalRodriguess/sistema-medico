@extends('layouts/material')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Modelo',
        'breadcrumb' => [
            'Modelos' => route('modelos.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('modelos.store') }}" method="post">
                @csrf

                <div class="form-group @error('marcas_id') has-danger @enderror">
                    <label class="form-control-label">Marca</label>
                    <select name="marcas_id"
                        class="form-control @error('marcas_id') form-control-danger @enderror">
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->id }}"
                                @if(old('marcas_id') == $marca->id) selected="selected" @endif>
                                {{ $marca->descricao }}
                            </option>
                        @endforeach
                    </select>
                    @error('marcas_id')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @if($errors->has('descricao')) has-danger @endif">
                    <label class="form-control-label">Descrição</span></label>
                    <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                    @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                    @endif
                </div>


                <div class="form-group text-right">
                         <a href="{{ route('modelos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
