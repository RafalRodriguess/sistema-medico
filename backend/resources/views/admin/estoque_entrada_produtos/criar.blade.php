@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Estoque Entrada Produtos',
        'breadcrumb' => [
            'Estoque Entrada Produtos' => route('estoque_entrada.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('estoque_entrada_produtos.store') }}" method="POST" >
                @csrf

                 <div class="form-group @if($errors->has('id_entrada')) has-danger @endif">


                    @if (is_string($estoqueEntradaProduto))
                        <input type="hidden" name="id_entrada" value="{{ old('id_entrada', $estoqueEntradaProduto) }}"
                            class="form-control @if($errors->has('id_entrada')) form-control-danger @endif">
                        @if($errors->has('id_entrada'))
                            <div class="form-control-feedback">{{ $errors->first('id_entrada') }}</div>
                        @endif

                    @else
                        <input type="hidden" name="id_entrada" value="{{ old('id_entrada', $estoqueEntradaProduto[0]->id_entrada) }}"
                            class="form-control @if($errors->has('id_entrada')) form-control-danger @endif">
                        @if($errors->has('id_entrada'))
                            <div class="form-control-feedback">{{ $errors->first('id_entrada') }}</div>
                        @endif
                    @endif
                </div>


                <div class="form-group @error('id_produto') has-danger @enderror">
                    <label class="form-control-label">Produto *</label>
                    <select name="id_produto"
                        class="form-control @error('id_produto') form-control-danger @enderror">
                            <option value="">Selecione Produto</option>
                        @foreach ($produtos as $key =>$produto)
                            <option value="{{ $produto->id }}">
                                {{ $produto->id}} -  {{ $produto->descricao }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_produto')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-group @if($errors->has('quantidade')) has-danger @endif">
                    <label class="form-control-label">Quantidade *</label>
                    <input type="text" name="quantidade" value="{{ old('quantidade') }}"
                        class="form-control  @if($errors->has('quantidade')) form-control-danger @endif">
                    @if($errors->has('quantidade'))
                        <div class="form-control-feedback">{{ $errors->first('quantidade') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('lote')) has-danger @endif">
                    <label class="form-control-label">Lote *</label>
                    <input type="text" name="lote" value="{{ old('lote') }}"
                        class="form-control  @if($errors->has('lote')) form-control-danger @endif">
                    @if($errors->has('lote'))
                        <div class="form-control-feedback">{{ $errors->first('lote') }}</div>
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



    </script>
@endpush
