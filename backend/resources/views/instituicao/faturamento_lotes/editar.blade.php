@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Lote / Protocolo #{$dado->id} {$dado->descricao}",
        'breadcrumb' => [
            'Faturamento / lotes' => route('instituicao.faturamento.lotes.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.faturamento.lotes.update', [$dado]) }}" method="post">

                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md-9 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $dado->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo</label>
                        <select class="form-control @if($errors->has('tipo')) form-control-danger @endif" name="tipo" id="tipo" required>
                            <option value="1" @if(old('tipo', $dado->tipo) == 1) selected="selected" @endif>Manual</option>
                            <option value="2" @if(old('tipo', $dado->tipo) == 2) selected="selected" @endif>Sancoop</option>
                        </select>
                        @if($errors->has('tipo'))
                            <small class="form-control-feedback">{{ $errors->first('tipo') }}</small>
                        @endif
                    </div>


                </div>



                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.faturamento.lotes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
