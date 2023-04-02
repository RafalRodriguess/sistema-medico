@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar estoqueEntrada #{$estoqueEntrada->id} {$estoqueEntrada->nome_fantasia}",
        'breadcrumb' => [
            'Estoque Entrada' => route('estoque_entrada.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('estoque_entrada.update', [$estoqueEntrada]) }}" method="POST" >
                @method('post')
                @csrf
               <div class="form-group @error('id_tipo_documento') has-danger @enderror">
                    <label class="form-control-label">Tipo de Documento *</label>
                    <select name="id_tipo_documento"
                        class="form-control @error('id_tipo_documento') form-control-danger @enderror">
                            <option value="">Selecione o tido de documento</option>
                        @foreach ($tiposDocumentos as $key =>$tiposDocumento)
                            <option value="{{ $tiposDocumento->id }}"
                            {{ ( $tiposDocumento->id == old('id_tipo_documento', $estoqueEntrada->id_tipo_documento)) ? "selected='selected'" : "" }}>
                                {{old('id_tipo_documento', $estoqueEntrada->id_tipo_documento)}} -  {{ $tiposDocumento->descricao }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_tipo_documento')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @error('id_estoque') has-danger @enderror">
                    <label class="form-control-label">Estoques *</label>
                    <select name="id_estoque"
                        class="form-control @error('id_estoque') form-control-danger @enderror">
                            <option value="">Selecione o estoque</option>
                        @foreach ($estoques as $key =>$estoque)
                            <option value="{{ $estoque->id }}"
                            {{ ( $estoqueEntrada->id_estoque == old('id', $estoque->id)) ? "selected='selected'" : "" }}>
                                {{old('id_estoque', $estoque->id)}} -  {{ $estoque->descricao }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_estoque')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                 <div class="form-group @if($errors->has('consignado')) has-danger @endif">
                    <label class="form-control-label">Consignado *</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="consignado" value="1" id="consignado1" checked>
                        <label class="form-check-label" for="consignado1">
                           Sim
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="consignado" value="0" id="consignado2" >
                        <label class="form-check-label" for="consignado2">
                            Não
                        </label>
                    </div>
                </div>
                <div class="form-group num_parcelas">
                    <label class="form-control-label">Contabiliza</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="contabiliza" value="1" id="contabiliza1" checked>
                        <label class="form-check-label" for="contabiliza1">
                           Sim
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="contabiliza" value="0" id="contabiliza2" >
                        <label class="form-check-label" for="contabiliza2">
                            Não
                        </label>
                    </div>
            </div>
                <div class="form-group @if($errors->has('numero_documento')) has-danger @endif">
                    <label class="form-control-label">Numero Documento *</label>
                    <input type="text" name="numero_documento" value="{{ old('numero_documento', $estoqueEntrada->numero_documento) }}"
                        class="form-control  @if($errors->has('numero_documento')) form-control-danger @endif">
                    @if($errors->has('numero_documento'))
                        <div class="form-control-feedback">{{ $errors->first('numero_documento') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('serie')) has-danger @endif">
                    <label class="form-control-label">Série *</label>
                    <input type="text" name="serie" value="{{ old('serie', $estoqueEntrada->serie) }}"
                        class="form-control  @if($errors->has('serie')) form-control-danger @endif">
                    @if($errors->has('serie'))
                        <div class="form-control-feedback">{{ $errors->first('serie') }}</div>
                    @endif
                </div>

                <div class="form-group @error('id_fornecedor') has-danger @enderror">
                    <label class="form-control-label">Fornecedor *</label>
                    <select name="id_fornecedor"
                        class="form-control @error('id_fornecedor') form-control-danger @enderror">
                            <option value="">Selecione fornecedor</option>
                        @foreach ($pessoas as $key =>$pessoa)
                            <option value="{{ $pessoa->id }}"
                            {{ ( $pessoa->id == old('id', $estoqueEntrada->id_fornecedor)) ? "selected='selected'" : "" }}>
                                {{old('id_fornecedor', $pessoa->id)}} -  {{ $pessoa->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_fornecedor')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @if($errors->has('data_emissao')) has-danger @endif">
                    <label class="form-control-label">Data Emissão *</label>
                    <input type="date" name="data_emissao" value="{{ old('data_emissao', $estoqueEntrada->data_emissao) }}"
                        class="form-control  @if($errors->has('data_emissao')) form-control-danger @endif">
                    @if($errors->has('data_emissao'))
                        <div class="form-control-feedback">{{ $errors->first('data_emissao') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('data_hora_entrada')) has-danger @endif">
                    <label class="form-control-label"> Hora Emissão *</label>
                    <input type="time" name="data_hora_entrada" value="{{ old('data_hora_entrada', $estoqueEntrada->data_hora_entrada) }}"
                        class="form-control  @if($errors->has('data_hora_entrada')) form-control-danger @endif">
                    @if($errors->has('data_hora_entrada'))
                        <div class="form-control-feedback">{{ $errors->first('data_hora_entrada') }}</div>
                    @endif
                </div>

                <div class="form-group text-right">
                        <a href="{{ route('estoque_entrada.index') }}">
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
        $( document ).ready(function() {

        });
    </script>
@endpush
