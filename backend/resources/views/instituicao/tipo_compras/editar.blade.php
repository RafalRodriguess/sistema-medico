@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Tipo Compras #{$tipoCompra->id} {$tipoCompra->descricao}",
        'breadcrumb' => [
            'Tipo Compras' => route('instituicao.tipoCompras.index'),
            'Atualização',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.tipoCompras.update', [$tipoCompra]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao', $tipoCompra->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="checkbox" id="urgente" @if(old('urgente', $tipoCompra->urgente)==true) checked @endif  value="1" name="urgente" class="filled-in" />
                            <label for="urgente">Urgente<label>
                        </div>
                    </div>

                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.tipoCompras.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
