
@extends('comercial.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar produto #{$produto->id} {$produto->nome}",
        'breadcrumb' => [
            'Produtos' => route('comercial.produtos.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('comercial.produtos.estoque', [$produto]) }}" method="post">
                @method('put')
                @csrf

                <div class="form-group @if($errors->has('quantidade')) has-danger @endif">
                    <label class="form-control-label">Quantidade</span></label>
                    <input type="number" name="quantidade" id="quantidade" value="{{ old('quantidade', $produto->quantidade) }}"
                        class="form-control @if($errors->has('quantidade')) form-control-danger @endif">
                    @if($errors->has('quantidade'))
                        <div class="form-control-feedback">{{ $errors->first('quantidade') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <input type="checkbox" id="estoque_ilimitado" class="filled-in" name="estoque_ilimitado" @if ($produto->estoque_ilimitado)
                        checked
                    @endif/>
                    <label for="estoque_ilimitado">Estoque ilimitado</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="permitir_comprar_muitos" class="filled-in" name="permitir_comprar_muitos" @if ($produto->permitir_comprar_muitos)
                        checked
                    @endif/>
                    <label for="permitir_comprar_muitos">Permite ao usuário adicionar mais de um produto ao carrinho</label>
                </div>

                <div class="form-group text-right">
                        <a href="{{ route('comercial.produtos.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts');
    <script>
        $(document).ready(function(){
            if ($('#estoque_ilimitado').is(':checked') == true) {
                $('#quantidade').attr('readonly', true);
            }else{
                $('#quantidade').attr('readonly', false);
            }
        })

        $('#estoque_ilimitado').change(function(){
            if ($('#estoque_ilimitado').is(':checked') == true) {
                $('#quantidade').attr('readonly', true);
            }else{
                $('#quantidade').attr('readonly', false);
            }
        })
    </script>
@endpush
