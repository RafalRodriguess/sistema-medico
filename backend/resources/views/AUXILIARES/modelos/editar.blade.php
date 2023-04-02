@php

//dd(request()->route());

@endphp




@extends('layouts/material')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar modelo #{$modelo->id} {$modelo->descricao}",
        'breadcrumb' => [
            'Modelos' => route('modelos.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('modelos.update', [$modelo]) }}" method="post">
                @method('put')
                @csrf

                <div class="form-group @if($errors->has('marcas_id')) has-danger @endif">
                    <label class="form-control-label">Marca</label>
                    <select name="marcas_id"
                        class="form-control @if($errors->has('marcas_id')) form-control-danger @endif">
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->id }}"
                                @if(old('marcas_id', $modelo->marcas_id) == $marca->id) selected="selected" @endif>
                                {{ $marca->descricao }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('marcas_id'))
                        <div class="form-control-feedback">{{ $errors->first('marcas_id') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('descricao')) has-danger @endif">
                    <label class="form-control-label">Descrição</span></label>
                    <input type="text" name="descricao" value="{{ old('descricao', $modelo->descricao) }}"
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
