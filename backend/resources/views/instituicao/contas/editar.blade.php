@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar contas #{$conta->id} {$conta->descricao}",
        'breadcrumb' => [
            'Contas' => route('instituicao.contas.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.contas.update', [$conta]) }}" method="post">

                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $conta->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo</label>
                        <select class="form-control @if($errors->has('tipo')) form-control-danger @endif" name="tipo" id="tipo" required>
                            @foreach ($opcoes_tipo as $id => $opcao)
                                <option value="{{ $id }}" @if(old('tipo', $conta->tipo) == $id) selected="selected" @endif>{{ $opcao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo'))
                            <small class="form-control-feedback">{{ $errors->first('tipo') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('situacao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Situação</label>
                        <select class="form-control @if($errors->has('situacao')) form-control-danger @endif" name="situacao" id="situacao" required>
                            <option value="1" {{ (old('situacao', $conta->situacao) == 1) ? 'selected' : '' }} >Ativo</option>
                            <option value="0" {{ (old('situacao', $conta->situacao) == 0) ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @if($errors->has('tipo'))
                            <small class="form-control-feedback">{{ $errors->first('tipo') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('saldo_inicial')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Saldo Inicial</label>
                        <input type="text" alt='decimal' name="saldo_inicial" value="{{ old('saldo_inicial', $conta->saldo_inicial) }}"
                        class="form-control @if($errors->has('saldo_inicial')) form-control-danger @endif">
                        @if($errors->has('saldo_inicial'))
                            <small class="form-control-feedback">{{ $errors->first('saldo_inicial') }}</small>
                        @endif
                    </div>
                </div>

                <div class="banco">
                    <div class="row">
                        <div class=" col-md-4 form-group @if($errors->has('banco')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Banco</label>
                            <input type="text" name="banco" id='banco' value="{{ old('banco', $conta->banco) }}"
                            class="form-control banco-dados @if($errors->has('banco')) form-control-danger @endif">
                            @if($errors->has('descricao'))
                                <small class="form-control-feedback">{{ $errors->first('banco') }}</small>
                            @endif
                        </div>

                        <div class=" col-md-4 form-group @if($errors->has('agencia')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Agencia</label>
                            <input type="text" name="agencia" id="agencia" value="{{ old('agencia', $conta->agencia) }}"
                            class="form-control banco-dados @if($errors->has('agencia')) form-control-danger @endif">
                            @if($errors->has('agencia'))
                                <small class="form-control-feedback">{{ $errors->first('agencia') }}</small>
                            @endif
                        </div>

                        <div class=" col-md-4 form-group @if($errors->has('conta')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Conta</label>
                            <input type="text" name="conta" id="conta" value="{{ old('conta', $conta->conta) }}"
                            class="form-control banco-dados @if($errors->has('conta')) form-control-danger @endif">
                            @if($errors->has('conta'))
                                <small class="form-control-feedback">{{ $errors->first('conta') }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.contas.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            tipo()
        })

        $('#tipo').on('change', function(){
            tipo()
        })

        function tipo(){
            let tipo = $('#tipo option:selected').val()

            if(tipo != 1){
               $('.banco').css('display','block')
            }else{
                $('.banco').css('display','none')
                $('.banco-dados').val('');
            }
        }
    </script>
@endpush
