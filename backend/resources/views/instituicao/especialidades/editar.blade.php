@php

//dd(request()->route());

@endphp




@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar especialidade #{$especialidade->id} {$especialidade->descricao}",
        'breadcrumb' => [
            'Especialidades' => route('instituicao.especialidades.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.especialidades.update', [$especialidade]) }}" method="post">
                @method('put')
                @csrf

                <div class="form-group @if($errors->has('descricao')) has-danger @endif">
                    <label class="form-control-label">Descrição *</span></label>
                    <input type="text" name="descricao" value="{{ old('descricao', $especialidade->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                    @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                    @endif
                </div>

                {{-- <div class="form-group @if($errors->has('especializacoes')) has-danger @endif">
                    <label class="form-control-label p-0 m-0">Especializações <span class="text-danger">*</span></label>
                    <select id="especializacoes-select" class="form-control campo multiplas-especializacoes select2" name="especializacoes[][especializacoes_id]" multiple style="width: 100%">
                        @foreach ($especializacoes as $especializacao)
                            <option value="{{ $especializacao->id }}" > {{$especializacao->descricao }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('especializacoes'))
                        <small class="form-text text-danger">{{ $errors->first('especializacoes') }}</small>
                    @endif
                </div> --}}

                <div class="form-group text-right">
                        <a href="{{ route('instituicao.especialidades.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
