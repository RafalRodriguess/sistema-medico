@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar estoqueEntradasProdutos #{$estoqueEntradasProdutos->id}",
        'breadcrumb' => [
            'Estoque Entrada' => route('estoque_entrada.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('estoque_entrada_produtos.update', [$estoqueEntradasProdutos]) }}" method="POST" >
                @method('post')
                @csrf

                <div class="form-group @if($errors->has('id_entrada')) has-danger @endif">

                    <input type="hidden" name="id_entrada" value="{{ old('id_entrada', $estoqueEntradasProdutos->id_entrada) }}"
                        class="form-control @if($errors->has('id_entrada')) form-control-danger @endif">
                    @if($errors->has('id_entrada'))
                        <div class="form-control-feedback">{{ $errors->first('id_entrada') }}</div>
                    @endif
                </div>

             <div class="form-group @error('id_produto') has-danger @enderror">
                    <label class="form-control-label">Produto *</label>
                    <select name="id_produto"
                        class="form-control @error('id_produto') form-control-danger @enderror">
                            <option value="">Selecione Produto</option>
                        @foreach ($produtos as $key =>$produto)
                            <option value="{{ $produto->id }}"
                            {{( $estoqueEntradasProdutos->id_produto == old('id', $produto->id)) ? "selected='selected'" : "" }}>
                                {{ $produto->id}} -  {{ $produto->descricao }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_produto')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>


                   <div class="form-group @if($errors->has('quantidade')) has-danger @endif">
                    <label class="form-control-label">Quantidade *</span></label>
                    <input type="text" name="quantidade" value="{{ old('quantidade', $estoqueEntradasProdutos->quantidade) }}"
                        class="form-control @if($errors->has('quantidade')) form-control-danger @endif">
                    @if($errors->has('quantidade'))
                        <div class="form-control-feedback">{{ $errors->first('quantidade') }}</div>
                    @endif
                </div>

                   <div class="form-group @if($errors->has('lote')) has-danger @endif">
                    <label class="form-control-label">Lote *</span></label>
                    <input type="text" name="lote" value="{{ old('lote', $estoqueEntradasProdutos->lote) }}"
                        class="form-control @if($errors->has('lote')) form-control-danger @endif">
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

    <div class="modal inmodal" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>

                <h5 class="modal-title">Defina a logo</h5>

            </div>
            <div class="modal-body">
                <div >
                <img style="max-width: 100%;" id="imageModal" src="">
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" style='margin:0;' class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="crop">Definir</button>

            </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $( document ).ready(function() {

        });
    </script>
@endpush
