





@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Editar Motivo de Cancelamento de Alta',
        'breadcrumb' => [
            'Motivos de Cancelamento de Altas' => route('instituicao.internacao.motivos-cancelamento-altas.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.internacao.motivos-cancelamento-altas.update', [$motivo_cancelamento_alta]) }}" method="post">
                @method('put')
                @csrf

                <div class="row mb-3">
                    <div wire:ignore class="col-md-6 form-group @if($errors->has('descricao_motivo_cancelamento_alta')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="descricao_motivo_cancelamento_alta"
                            value="{{ old('descricao_motivo_cancelamento_alta', $motivo_cancelamento_alta->descricao_motivo_cancelamento_alta) }}">
                        @if($errors->has('descricao_motivo_cancelamento_alta'))
                            <small class="form-control-feedback">{{ $errors->first('descricao_motivo_cancelamento_alta') }}</small>
                        @endif
                    </div>
                    <div class="col-md-3 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="tipo">
                            <option selected disabled>Selecione</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo }}" @if ($motivo_cancelamento_alta->tipo==$tipo)
                                    selected
                                @endif @if (old('tipo')==$tipo)
                                    selected
                                @endif>{{ App\MotivoCancelamentoAlta::getTipoTexto($tipo) }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo'))
                            <small class="form-control-feedback">{{ $errors->first('tipo') }}</small>
                        @endif
                    </div>
                    <div class="col-md-3 p-0 m-0 pt-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="ativo" value="1"
                                @if ($motivo_cancelamento_alta->ativo==1) checked @endif
                                @if(old('ativo')=="1") checked @endif id="ativoCheck">
                            <label class="form-check-label" for="ativoCheck">Ativo</label>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.internacao.motivos-cancelamento-altas.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
