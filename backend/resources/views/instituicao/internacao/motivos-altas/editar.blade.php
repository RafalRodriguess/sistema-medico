

@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Editar Motivo de Alta',
        'breadcrumb' => [
            'Motivos de Altas' => route('instituicao.internacao.motivos-altas.index'),
            'Atualizar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.internacao.motivos-altas.update', [$motivo_alta]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div wire:ignore class="col-md-7 form-group @if($errors->has('descricao_motivo_alta')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição do Motivo de Alta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="descricao_motivo_alta"
                            value="{{ old('descricao_motivo_alta', $motivo_alta->descricao_motivo_alta) }}">
                        @if($errors->has('descricao_motivo_alta'))
                            <small class="form-control-feedback">{{ $errors->first('descricao_motivo_alta') }}</small>
                        @endif
                    </div>
                    <div class="col-md-5 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="tipo">
                            <option selected disabled>Selecione</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo }}" @if ($motivo_alta->tipo==$tipo)
                                    selected
                                @endif @if (old('tipo')==$tipo)
                                    selected
                                @endif>{{ App\MotivoAlta::getTipoTexto($tipo) }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo'))
                            <small class="form-control-feedback">{{ $errors->first('tipo') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 form-group @if($errors->has('codigo_alta_sus')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Código de Alta do SUS <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="codigo_alta_sus"
                            value="{{ old('codigo_alta_sus', $motivo_alta->codigo_alta_sus) }}">
                        @if($errors->has('codigo_alta_sus'))
                            <small class="form-control-feedback">{{ $errors->first('codigo_alta_sus') }}</small>
                        @endif
                    </div>
                    <div id="motivo-transferencia-campo" class="col-md-7 form-group @if($errors->has('motivo_transferencia_id')) has-danger @endif">
                    </div>
                </div>


                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.internacao.motivos-altas.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/template" id="motivo-transferencia-item">
        <div class="p-0 m-0 col-12" id="motivo-transferencia-item-colocado">
            <label class="form-control-label p-0 m-0">Motivo de Transferência <span class="text-danger">*</span></label>
            <select class="form-control p-0 m-0" name="motivo_transferencia_id">
                <option selected disabled>Selecione</option>
                @foreach ($motivos_transferencia as $motivo_transferencia)
                    <option value="{{ $motivo_transferencia }}"
                        @if ($motivo_alta->motivo_transferencia_id==$motivo_transferencia)
                            selected
                        @endif
                        @if (old('motivo_transferencia_id')==$motivo_transferencia)
                            selected
                        @endif>{{ App\MotivoAlta::getMotivoTransferenciaTexto($motivo_transferencia) }}</option>
                @endforeach
            </select>
            @if($errors->has('motivo_transferencia_id'))
                <small class="form-control-feedback">{{ $errors->first('motivo_transferencia_id') }}</small>
            @endif
        </div>
    </script>
    <script text="text/javascript">
        $(document).ready(function(){

            function tipo(){

                function inserirMotivoTransferenciaCampo(){
                    let motivo_transferencia_item = $($('#motivo-transferencia-item').html())[0];
                    let motivo_transferencia_campo = document.querySelector('#motivo-transferencia-campo');
                    motivo_transferencia_campo.appendChild(motivo_transferencia_item);
                }

                function removerMotivoTransferenciaCampo(){
                    let motivo_transferencia_item = document.querySelector('#motivo-transferencia-item-colocado');
                    let motivo_transferencia_campo = document.querySelector('#motivo-transferencia-campo');
                    if(motivo_transferencia_item) motivo_transferencia_campo.removeChild(motivo_transferencia_item);
                }

                let tipo = $('select[name="tipo"]');
                if(tipo.val() == 4){
                    inserirMotivoTransferenciaCampo();
                } else {
                    removerMotivoTransferenciaCampo();
                }
            }

            $('select[name="tipo"]').change(function(){
                tipo();
            });

            tipo();

        });
    </script>
@endpush
