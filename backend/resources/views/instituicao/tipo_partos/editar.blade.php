@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar tipo de parto #{$tipo_parto->id} {$tipo_parto->descricao}",
        'breadcrumb' => [
            'Tipo de partos' => route('instituicao.tipoPartos.index'),
            'Atualização',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.tipoPartos.update', [$tipo_parto]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md-8 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $tipo_parto->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-4 form-group @if($errors->has('motivo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Motivo</label>
                        <select class="form-control @if($errors->has('motivo')) form-control-danger @endif" name="motivo" id="motivo" required>
                            <option value="0" {{ (old('motivo', $tipo_parto->motivo) == 0) ? 'selected' : '' }}>Não</option>
                            <option value="1" {{ (old('motivo', $tipo_parto->motivo) == 1) ? 'selected' : '' }}>Sim</option>
                        </select>
                        @if($errors->has('motivo'))
                            <small class="form-control-feedback">{{ $errors->first('motivo') }}</small>
                        @endif
                    </div>
                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.tipoPartos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
