@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Tipo de chamada: {$tipo->descricao}",
        'breadcrumb' => [
            'Tipos de chamada dos totens' => route('instituicao.totens.tipos-chamada.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.totens.tipos-chamada.update', $tipo) }}" method="post">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $tipo->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('ganchos_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Senhas</label>
                        <select type="text" name="ganchos_id"
                        class="form-control @if($errors->has('ganchos_id')) form-control-danger @endif">
                            @foreach ($ganchos as $gancho)
                                <option value="{{ $gancho->id }}" @if(old('ganchos_id', $tipo->ganchos_id) == $gancho->id) selected="selected" @endif>{{ $gancho->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('ganchos_id'))
                            <small class="form-control-feedback">{{ $errors->first('ganchos_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.totens.tipos-chamada.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
