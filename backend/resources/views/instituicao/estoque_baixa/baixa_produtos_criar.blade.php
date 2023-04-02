@php($oldEstoqueEntrada = old('estoqueEntradaProduto') ?: [])
@if (empty($oldEstoqueEntrada))


    <div class="col-md-12 item-produto">
        <div class="row">

            <div class="form-group col-md-6 @if ($errors->has('produtos.0.id')) has-danger @endif">
                <label class="form-control-label">Produto <span class="text-danger">*</span></label>

                <select name="produtos[0][id]"
                    class="form-control selectfild2 @if ($errors->has('produtos.0.id')) form-control-danger @endif"
                    style="width: 100%">
                    <option value="">Selecione um produto</option>

                    @foreach ($produtos as $item)
                        <option value="{{ $item->id }}" @if (old('produtos.0.id') == $item->id) selected="selected" @endif>
                            {{ $item->descricao }}</option>
                    @endforeach
                </select>
                @if ($errors->has('produtos.0.id'))
                    <div class="form-control-feedback">{{ $errors->first('produtos.0.id') }}</div>
                @endif
            </div>

            <div class="form-group col-md-3 col-sm-6 @if ($errors->has('produtos.0.quantidade')) has-danger @endif">
                <label class="form-control-label">Quantidade:</label>
                <input type="number" min="0"
                    class="form-control @if ($errors->has('produtos.0.quantidade')) form-control-danger @endif"
                    name="produtos[0][quantidade]" id="produtos[0][quantidade]"
                    value="{{ old('produtos.0.quantidade', 0) }}">
                @if ($errors->has('produtos.0.quantidade'))
                    <div class="form-control-feedback">{{ $errors->first('produtos.0.quantidade') }}</div>
                @endif
            </div>

            <div class="form-group col-md-3 col-sm-6 @if ($errors->has('produtos.0.lote')) has-danger @endif">
                <label class="form-control-label">Lote:</label>
                <input class="form-control @if ($errors->has('produtos.0.lote')) form-control-danger @endif"
                    name="produtos[0][lote]" id="produtos[#][lote]" value="{{ old('produtos.0.lote') }}">
                @if ($errors->has('produtos.0.lote'))
                    <div class="form-control-feedback">{{ $errors->first('produtos.0.lote') }}</div>
                @endif
            </div>
        </div>
    </div>
@endif


@push('scripts')
    <script type="text/template" id="item-produto">
        <div class="col-md-12 item-produto">
            <div class="row">
                <div class="col-md-12">
                    <hr/>
                    <a href="javascrit:void(0)" class="small remove-produto">(remover)</a>
                </div>

                <div class="form-group col-md-6 @if($errors->has("produtos.0.id")) has-danger @endif">
                    <label class="form-control-label">Produto:</label>

                    <select name="produtos[#][id]" class="form-control selectfild2 @if($errors->has("produtos.0.id")) form-control-danger @endif" style="width: 100%">
                        <option value="">Selecione um produto</option>

                        @foreach ($produtos as $item)

                            <option value="{{$item->id}}" @if (old("produtos.0.id") == $item->id)
                                selected="selected"
                            @endif>{{$item->descricao}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("produtos.0.id"))
                        <div class="form-control-feedback">{{ $errors->first("produtos.0.id") }}</div>
                    @endif
                </div>

                <div class="form-group col-md-3 col-sm-6 @if($errors->has("produtos.0.quantidade")) has-danger @endif">
                    <label class="form-control-label">Quantidade:</label>
                    <input type="number" min="0" class="form-control @if($errors->has("produtos.0.quantidade")) form-control-danger @endif" name="produtos[#][quantidade]" id="produtos[#][quantidade]" value="0">
                    @if($errors->has("produtos.0.quantidade"))
                        <div class="form-control-feedback">{{ $errors->first("produtos.0.quantidade") }}</div>
                    @endif
                </div>

                <div class="form-group col-md-3 col-sm-6 @if($errors->has("produtos.0.lote")) has-danger @endif">
                    <label class="form-control-label">Lote:</label>
                    <input class="form-control @if($errors->has("produtos.0.lote")) form-control-danger @endif" name="produtos[#][lote]" id="produtos[#][lote]" value="{{old("produtos.0.lote")}}">
                    @if($errors->has("produtos.0.lote"))
                        <div class="form-control-feedback">{{ $errors->first("produtos.0.lote") }}</div>
                    @endif
                </div>
            </div>
        </div>
    </script>

    <script>
        var quantidade_equipamento = 1;

        $('.produtos').on('click', '.add-produto', function() {
            addEquipamento();
        });

        function addEquipamento() {

            a = quantidade_equipamento++;

            $($('#item-produto').html()).insertBefore(".add-class-produto");

            $(".selectfild2").select2();

            $("[name^='produtos[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#', a));
            })
        }

        $('.produtos').on('click', '.item-produto .remove-produto', function(e) {
            e.preventDefault()

            $(e.currentTarget).parents('.item-produto').remove();
            if ($('.produtos').find('.item-produto').length == 0) {
                addEquipamento();
            }
        });
    </script>
@endpush
