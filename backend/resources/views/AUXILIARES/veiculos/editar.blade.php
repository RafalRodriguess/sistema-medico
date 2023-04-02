
@php

// dd(request()->route());

@endphp




@extends('layouts/material')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar entrada de Veículo #{$veiculo->id} {$veiculo->marca->descricao} {$veiculo->modelo->descricao}",
        'breadcrumb' => [
            'Veículos' => route('veiculos.index'),
            'Editar entrada',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('veiculos.update', [$veiculo]) }}" method="post">
                @method('put')
                @csrf

                <div class="form-body">

                        <h3 class="card-title">Dados do veículo</h3>
                        <hr>

                        <div class="row">


                                <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Tipo entrada
                                            {{-- tooltip --}}
                                            <a class="mytooltip" href="javascript:void(0)">
                                            <i class="mdi mdi-information-outline"></i>
                                            <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                                Estoque: veículo adquirido pela empresa<br /> Consignado: veículo de cliente disponibilizado para venda
                                            </span></span></span>
                                            </a>
                                            {{-- end tooltip --}}
                                            </label>
                                            <select name="tipo" class="form-control" data-placeholder="Selecione" tabindex="1">
                                                <option @if(old('tipo', $veiculo->tipo) == 'estoque') selected="selected" @endif value="estoque">Estoque</option>
                                                <option @if(old('tipo', $veiculo->tipo) == 'consignado') selected="selected" @endif value="consignado">Consignado</option>
                                            </select>
                                        </div>
                                </div>

                                <div class="col-md-4">
                                        <div class="form-group @error('modelos_id') has-danger @enderror">
                                            <label class="control-label">Modelo <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('modelos_id') form-control-danger @enderror" name="modelos_id" style="width: 100%">
                                                   <option value="">Selecione o modelo</option>
                                                    {{-- <optgroup label="Fiat">
                                                        <option value="AK">Uno</option>
                                                        <option value="HI">Bravo</option>
                                                    </optgroup> --}}
                                                    @foreach ($modelos as $modelo)
                                                        <option value="{{ $modelo->id }}"
                                                            @if(old('modelos_id', $veiculo->modelos_id) == $modelo->id) selected="selected" @endif>
                                                            {{ $modelo->descricao }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('modelos_id')
                                                        <div class="form-control-feedback">{{ $message }}</div>
                                                   @enderror

                                        </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="form-group @error('cores_id') has-danger @enderror">
                                            <label class="control-label">Cor <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('cores_id') form-control-danger @enderror" name="cores_id"  style="width: 100%">
                                                    <option value="">Selecione a cor do veículo</option>
                                                    @foreach ($cores as $cor)
                                                        <option value="{{ $cor->id }}"
                                                            @if(old('cores_id', $veiculo->cores_id) == $cor->id) selected="selected" @endif>
                                                            {{ $cor->descricao }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('cores_id')
                                                   <div class="form-control-feedback">{{ $message }}</div>
                                               @enderror

                                        </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="form-group @error('categorias_id') has-danger @enderror">
                                            <label class="control-label">Categoria <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('categorias_id') form-control-danger @enderror" name="categorias_id"  style="width: 100%">
                                                    <option value="">Selecione a categoria do veículo</option>
                                                    @foreach ($categorias as $categoria)
                                                        <option value="{{ $categoria->id }}"
                                                            @if(old('categorias_id', $veiculo->categorias_id) == $categoria->id) selected="selected" @endif>
                                                            {{ $categoria->descricao }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('categorias_id')
                                                   <div class="form-control-feedback">{{ $message }}</div>
                                               @enderror

                                        </div>
                                </div>

                        </div>

                        <div class="row">

                                <div class="col-md-3">
                                        <div class="form-group @error('cambios_id') has-danger @enderror">
                                            <label class="control-label">Câmbio <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('cambios_id') form-control-danger @enderror" name="cambios_id"  style="width: 100%">
                                                    <option value="">Selecione o câmbio do veículo</option>
                                                    @foreach ($cambios as $cambio)
                                                        <option value="{{ $cambio->id }}"
                                                            @if(old('cambios_id', $veiculo->cambios_id) == $cambio->id) selected="selected" @endif>
                                                            {{ $cambio->descricao }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('cambios_id')
                                                   <div class="form-control-feedback">{{ $message }}</div>
                                               @enderror

                                        </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="form-group @error('tracoes_id') has-danger @enderror">
                                            <label class="control-label">Tração <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('tracoes_id') form-control-danger @enderror" name="tracoes_id"  style="width: 100%">
                                                    <option value="">Selecione a tração do veículo</option>
                                                    @foreach ($tracoes as $tracao)
                                                        <option value="{{ $tracao->id }}"
                                                            @if(old('tracoes_id', $veiculo->tracoes_id) == $tracao->id) selected="selected" @endif>
                                                            {{ $tracao->descricao }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('tracoes_id')
                                                   <div class="form-control-feedback">{{ $message }}</div>
                                               @enderror

                                        </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Placa
                                            </label>
                                            <input type="text" name="placa" value="{{ old('placa', $veiculo->placa) }}" class="form-control">
                                        </div>
                                </div>

                        </div>

                        <div class="row">

                                <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Opcionais
                                            {{-- tooltip --}}
                                            <a class="mytooltip" href="javascript:void(0)">
                                            <i class="mdi mdi-information-outline"></i>
                                            <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                                Caso veículo possua opcionais como: "kit multimídia", "rodas personalizadas", etc...
                                            </span></span></span>
                                            </a>
                                            {{-- end tooltip --}}
                                            </label>
                                            <input type="text" name="opcionais" value="{{ old('opcionais', $veiculo->opcionais) }}" class="form-control">
                                        </div>
                                </div>

                        </div>

                        <h3 class="card-title">Dados para venda</h3>
                        <hr>

                        <div class="row">

                                <div class="col-md-2">
                                        <div class="form-group @if($errors->has('km')) has-danger @endif">
                                            <label class="control-label">Km atual <span class="text-danger">*</span></label>
                                            <input type="text" name="km" alt="integer" value="{{ old('km', $veiculo->km) }}" class="form-control @if($errors->has('km')) form-control-danger @endif">
                                            @if($errors->has('km'))
                                                <div class="form-control-feedback">{{ $errors->first('km') }}</div>
                                            @endif
                                        </div>
                                </div>

                                <div class="col-md-2">
                                        <div class="form-group @if($errors->has('data_entrada')) has-danger @endif">
                                            <label class="control-label">Data entrada <span class="text-danger">*</span></label>
                                            <input type="text" name="data_entrada" alt="date" value="{{ old('data_entrada', $veiculo->data_entrada) }}" class="form-control @if($errors->has('data_entrada')) form-control-danger @endif">
                                            @if($errors->has('data_entrada'))
                                                <div class="form-control-feedback">{{ $errors->first('data_entrada') }}</div>
                                            @endif
                                        </div>
                                </div>


                                <div class="col-md-2">
                                        <div class="form-group @if($errors->has('valor_compra')) has-danger @endif">
                                            <label class="control-label">Valor da compra <span class="text-danger">*</span>
                                                {{-- tooltip --}}
                                                <a class="mytooltip" href="javascript:void(0)">
                                                <i class="mdi mdi-information-outline"></i>
                                                <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                                    Caso seja veículo de estoque
                                                </span></span></span>
                                                </a>
                                                {{-- end tooltip --}}
                                            </label>
                                            <input type="text" name="valor_compra" alt="money" value="{{ old('valor_compra', $veiculo->valor_compra) }}" class="form-control @if($errors->has('valor_compra')) form-control-danger @endif">
                                            @if($errors->has('valor_compra'))
                                                <div class="form-control-feedback">{{ $errors->first('valor_compra') }}</div>
                                            @endif
                                        </div>
                                </div>

                                <div class="col-md-2">
                                        <div class="form-group @if($errors->has('valor_fipe')) has-danger @endif">
                                            <label class="control-label">Valor tabela fipe <span class="text-danger">*</span></label>
                                            <input type="text" name="valor_fipe" alt="money" value="{{ old('valor_fipe', $veiculo->valor_fipe) }}" class="form-control @if($errors->has('valor_fipe')) form-control-danger @endif">
                                            @if($errors->has('valor_fipe'))
                                                <div class="form-control-feedback">{{ $errors->first('valor_fipe') }}</div>
                                            @endif
                                        </div>
                                </div>

                                <div class="col-md-4">
                                        <div class="form-group @if($errors->has('valor_sugerido')) has-danger @endif">
                                            <label class="control-label">Valor sugerido para venda <span class="text-danger">*</span></label>
                                            <input type="text" name="valor_sugerido" alt="money" value="{{ old('valor_sugerido', $veiculo->valor_sugerido) }}" class="form-control @if($errors->has('valor_sugerido')) form-control-danger @endif">
                                            @if($errors->has('valor_sugerido'))
                                                <div class="form-control-feedback">{{ $errors->first('valor_sugerido') }}</div>
                                            @endif
                                        </div>
                                </div>

                        </div>

                        <h3 class="card-title">Dados cliente/fornecedor
                            {{-- tooltip --}}
                            <a class="mytooltip" href="javascript:void(0)">
                            <i class="mdi mdi-information-outline"></i>
                            <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                Selecionar proprietário do veículo disponibilizado
                            </span></span></span>
                            </a>
                            {{-- end tooltip --}}
                        </h3>
                        <hr>

                        <div class="row">

                                <div class="col-md-6">
                                        <div class="form-group @if($errors->has('clientes_id')) has-danger @endif">
                                            <label class="control-label">Cliente/fornecedor <span class="text-danger">*</span></label>
                                                   <select class="select2 @if($errors->has('clientes_id')) form-control-danger @endif" name="clientes_id"  style="width: 100%">
                                                    <option value="">Selecione a cliente/fornecedor</option>
                                                    @foreach ($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}"
                                                            @if(old('clientes_id', $veiculo->clientes_id) == $cliente->id) selected="selected" @endif>
                                                            {{ $cliente->nome }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('clientes_id')
                                                   <div class="form-control-feedback">{{ $errors->first('clientes_id') }}</div>
                                               @enderror

                                        </div>
                                </div>



                        </div>



                        <h3 class="card-title">Adicionais</h3>
                        <hr>

                        <div class="row">

                                <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Observações:</label>
                                            <textarea name="obs" cols="30" class="form-control">{{ old('obs', $veiculo->obs) }}</textarea>
                                        </div>
                                </div>

                        </div>

                </div>


                <div class="form-group text-right">
                        <a href="{{ route('veiculos.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
