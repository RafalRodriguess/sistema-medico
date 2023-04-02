@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Estoque Inventario Produtos',
        'breadcrumb' => [
            'Estoque Inventario Produtos' => route('instituicao.estoque_inventario.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.estoque_inventario_produtos.store', [$estoqueInventario]) }}" method="POST" >
                @csrf
            <div class="col-sm-6">
                <div class="form-group @error('produto_id') has-danger @enderror">
                    <label class="form-control-label">Produto *</label>
                    <select name="produto_id"
                        class="form-control @error('produto_id') form-control-danger @enderror">
                            <option value="">Selecione Produto</option>
                        @foreach ($produtos as $key =>$produto)
                            <option value="{{ $produto->id }}">
                                {{ $produto->descricao }}
                            </option>
                        @endforeach
                    </select>
                    @error('produto_id')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6"  style="float: right;margin-top: -8%;">
                <div class="form-group @if($errors->has('quantidade')) has-danger @endif">
                    <label class="form-control-label">Quantidade *</label>
                    <input type="number" name="quantidade" value="{{ old('quantidade') }}"
                        class="form-control  @if($errors->has('quantidade')) form-control-danger @endif">
                    @if($errors->has('quantidade'))
                        <div class="form-control-feedback">{{ $errors->first('quantidade') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group @if($errors->has('lote')) has-danger @endif">
                    <label class="form-control-label">Lote *</label>
                    <input type="number" name="lote" value="{{ old('lote') }}"
                        class="form-control  @if($errors->has('lote')) form-control-danger @endif">
                    @if($errors->has('lote'))
                        <div class="form-control-feedback">{{ $errors->first('lote') }}</div>
                    @endif
                </div>
            </div>


                <div class="form-group text-right">
                    <a href="{{ route('instituicao.estoque_inventario_produtos.index', [$estoqueInventario]) }}">
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



    </script>
@endpush
