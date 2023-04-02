@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title',
        [
            'titulo' => 'Cadastrar produto na entrada de estoque',
            'breadcrumb' => [
                'Produtos da entrada de estoque' => route('instituicao.estoque_entrada_produtos.index', [$entradaEstoque]),
                'Cadastrar produto na entrada de estoque',
            ],
        ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.estoque_entrada_produtos.store', [$entradaEstoque]) }}" method="POST">
                <div class="row">
                    @csrf
                    <div class="col-sm-6">
                        <div class="form-group @error('id_produto') has-danger @enderror">
                            <label class="form-control-label">Produto <span class="text-danger">*</span></label>
                            <select name="id_produto"
                                class="form-control @error('id_produto') form-control-danger @enderror">
                                <option value="">Selecione Produto</option>
                                @foreach ($produtos as $key => $produto)
                                    <option value="{{ $produto->id }}">
                                        {{ $produto->descricao }} - {{$produto->unidade->descricao}}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_produto')
                                <div class="form-control-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group @if ($errors->has('lote')) has-danger @endif">
                            <label class="form-control-label">Lote <span class="text-danger">*</span></label>
                            <input type="text" name="lote" value="{{ old('lote') }}"
                                class="form-control  @if ($errors->has('lote')) form-control-danger @endif">
                            @if ($errors->has('lote'))
                                <div class="form-control-feedback">{{ $errors->first('lote') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group @if ($errors->has('quantidade')) has-danger @endif">
                            <label class="form-control-label">Quantidade <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="quantidade" value="{{ old('quantidade', 0) }}"
                                class="form-control quantidade  @if ($errors->has('quantidade')) form-control-danger @endif">
                            @if ($errors->has('quantidade'))
                                <div class="form-control-feedback">{{ $errors->first('quantidade') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group col-sm-4 col-md-3 @if ($errors->has('valor')) has-danger @endif">
                        <label class="form-control-label">Valor de compra:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text"
                                class="form-control produtos-valor @if ($errors->has('valor_custo')) form-control-danger @endif"
                                name="valor_custo" id="valor_custo" value="{{ (float) old('valor_custo') * 100 }}">
                        </div>
                        @if ($errors->has('valor_custo'))
                            <div class="form-control-feedback">{{ $errors->first('valor_custo') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-sm-4 col-md-3 @if ($errors->has('valor')) has-danger @endif">
                        <label class="form-control-label">Valor de venda:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text"
                                class="form-control produtos-valor @if ($errors->has('valor')) form-control-danger @endif"
                                name="valor" id="valor" value="{{ (float) old('valor') * 100 }}">
                        </div>
                        @if ($errors->has('valor'))
                            <div class="form-control-feedback">{{ $errors->first('valor') }}</div>
                        @endif
                    </div>


                    <div class="form-group text-right col-12">
                        <a href="{{ route('instituicao.estoque_entrada_produtos.index', [$entradaEstoque]) }}">
                            <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                                    class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i
                                class="mdi mdi-check"></i> Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(() => {
            $('.produtos-valor').setMask({
                mask: '99,999999999',
                type: 'reverse',
                defaultValue: '000'
            }).on('keyup', (e) => {
                const val = parseFloat(e.target.value.replace(',', ''));
                if (val < 0) {
                    e.target.value = '0,00';
                } else if(val < 10) {
                    e.target.value = '0,0' + val;
                } else if (val < 100) {
                    e.target.value = '0,' + val;
                }
            });
            $('#id_produto').select2();
        });
    </script>
@endpush
