@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Etiqueta #{$compromisso->id} {$compromisso->descricao}",
        'breadcrumb' => [
            'Etiquetas' => route('instituicao.compromissos.index'),
            'Atualização',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.compromissos.update', $compromisso) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md-8 form-group @if ($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $compromisso->descricao) }}"
                            class="form-control @if ($errors->has('descricao')) form-control-danger @endif">
                        @if ($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class=" col-md-8 form-group @if ($errors->has('cor')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Cor</label>
                        <div class="col-md-2 col-4 p-0">
                            <input type="color" name="cor" value="{{ old('cor', $compromisso->cor) }}"
                                class="form-control @if ($errors->has('cor')) form-control-danger @endif">
                        </div>
                        @if ($errors->has('cor'))
                            <small class="form-control-feedback">{{ $errors->first('cor') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.compromissos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                                class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i>
                        Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
