

@if (empty($oldestoqueEntradaProdutos))

    @if ($entradaEstoque->estoqueEntradaProdutos)
        @for ($i = 0; $i < count($entradaEstoque->estoqueEntradaProdutos); $i++)
            <div class="col-md-12 item-produto">
                <div class="row">
                    <div class="col-md-12">
                        <a href="javascrit:void(0)" class="small remove-produto">(remover)</a>
                    </div>
                     <input type="hidden" name="estoqueEntradaProdutos[{{$i}}][id]"  value="{{ old('id', $entradaEstoque->estoqueEntradaProdutos ? $entradaEstoque->estoqueEntradaProdutos[$i]->id : '') }}" />
                    <div class="form-group dados_parcela col-md-8 @if($errors->has("estoqueEntradaProdutos.{$i}.id_produto")) has-danger @endif">
                        <label class="form-control-label">Produtos <span class="text-danger">*</span></label>
                        <select name="estoqueEntradaProdutos[{{$i}}][id_produto]" class="form-control selectfild2 @if($errors->has("estoqueEntradaProdutos.{$i}.id_produto")) form-control-danger @endif" style="width: 100%">
                           <option value="">Selecione um equipamento</option>
                            @foreach ($produtos as $item)
                                <option value="{{$item->id}}" @if (old("produtos.{$i}.produtos", $entradaEstoque->estoqueEntradaProdutos[$i]->id_produto) == $item->id)
                                    selected="selected"
                                @endif>{{$item->descricao}}</option>
                            @endforeach
                    </select>
                        </select>
                        @if($errors->has("estoqueEntradaProdutos.{$i}.id_produto"))
                            <div class="form-control-feedback">{{ $errors->first("estoqueEntradaProdutos.{$i}.id_produto") }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-4 @if($errors->has("estoqueEntradaProdutos.{$i}.quantidade")) has-danger @endif">
                        <label class="form-control-label">Quantidade</label>
                        <input type="text" class="form-control @if($errors->has("estoqueEntradaProdutos.{$i}.quantidade")) form-control-danger @endif" name="estoqueEntradaProdutos[{{$i}}][quantidade]" id="estoqueEntradaProdutos[{{$i}}][quantidade]" value="{{old("oldestoqueEntradaProdutos.{$i}.quantidade", $entradaEstoque->estoqueEntradaProdutos[$i]->quantidade)}}" >
                        @if($errors->has("estoqueEntradaProdutos.{$i}.quantidade"))
                            <div class="form-control-feedback">{{ $errors->first("estoqueEntradaProdutos.{$i}.quantidade") }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-4 @if($errors->has("estoqueEntradaProdutos.{$i}.lote")) has-danger @endif">
                        <label class="form-control-label">Lote</label>
                        <input type="text" class="form-control @if($errors->has("estoqueEntradaProdutos.{$i}.lote")) form-control-danger @endif" name="estoqueEntradaProdutos[{{$i}}][lote]" id="estoqueEntradaProdutos[{{$i}}][lote]" value="{{old("oldestoqueEntradaProdutos.{$i}.lote", $entradaEstoque->estoqueEntradaProdutos[$i]->lote)}}" >
                        @if($errors->has("estoqueEntradaProdutos.{$i}.lote"))
                            <div class="form-control-feedback">{{ $errors->first("estoqueEntradaProdutos.{$i}.lote") }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endfor
    @else


        <div class="col-md-12 item-produto">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-produto">(remover)</a>
                </div>
                <div class="form-group dados_parcela col-md-8 @if($errors->has("estoqueEntradaProdutos.{$i}.id_produto")) has-danger @endif">
                        <label class="form-control-label">Produtos <span class="text-danger">*</span></label>
                        <select name="estoqueEntradaProdutos[{{$i}}][id_produto]" class="form-control selectfild2 @if($errors->has("estoqueEntradaProdutos.{$i}.id_produto")) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione um produto</option>
                            @foreach ($produtos as $item)
                                <option value="{{$item->id}}" @if ($oldestoqueEntradaProdutos[$i]->id_produto == $item->id)
                                    selected="selected"
                                @endif>{{$item->id}}</option>
                            @endforeach
                        </select>
                        @if($errors->has("estoqueEntradaProdutos.{$i}.id_produto"))
                            <div class="form-control-feedback">{{ $errors->first("estoqueEntradaProdutos.{$i}.id_produto") }}</div>
                        @endif
                    </div>

                <div class="form-group col-md-4 @if($errors->has("estoqueEntradaProdutos.{$i}.quantidade")) has-danger @endif">
                    <label class="form-control-label">Quantidade</label>
                    <input type="number" class="form-control @if($errors->has("estoqueEntradaProdutos.{$i}.quantidade")) form-control-danger @endif" name="estoqueEntradaProdutos[0][quantidade]" id="estoqueEntradaProdutos[0][quantidade]" value="{{old("oldestoqueEntradaProdutos.{$i}.quantidade")}}" >
                    @if($errors->has("estoqueEntradaProdutos.{$i}.quantidade"))
                        <div class="form-control-feedback">{{ $errors->first("estoqueEntradaProdutos.{$i}.quantidade") }}</div>
                    @endif
                </div>

                <div class="form-group col-md-4 @if($errors->has("estoqueEntradaProdutos.{$i}.lote")) has-danger @endif">
                    <label class="form-control-label">Lote</label>
                    <input type="number" class="form-control @if($errors->has("estoqueEntradaProdutos.{$i}.lote")) form-control-danger @endif" name="estoqueEntradaProdutos[0][lote]" id="estoqueEntradaProdutos[0][lote]" value="{{old("oldestoqueEntradaProdutos.{$i}.lote")}}" >
                    @if($errors->has("estoqueEntradaProdutos.{$i}.lote"))
                        <div class="form-control-feedback">{{ $errors->first("estoqueEntradaProdutos.{$i}.lote") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@else


    @for($i = 0, $max = count($oldestoqueEntradaProdutos); $i < $max; $i++)
        <div class="col-md-12 item-produto">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-produto">(remover)</a>
                </div>
                      <div class="form-group col-md-8">
                    <label class="form-control-label">Produtos <span class="text-danger">*</span></label>
                    <select name="estoqueEntradaProdutos[#][id_produto]" class="form-control selectfild2" style="width: 100%">
                        <option value="">Selecione um produto</option>
                        @foreach ($produtos as $item)
                            <option value="{{$item->id}}" @if ($oldestoqueEntradaProdutos[$i]->id_produto == $item->id)
                                    selected="selected"
                                @endif>{{$item->id}}</option>
                        @endforeach
                    </select>
                </div>

                 <div class="form-group col-md-4 @if($errors->has("estoqueEntradaProdutos.{$i}.quantidade")) has-danger @endif">
                    <label class="form-control-label">Quantidade</label>
                    <input type="number" class="form-control @if($errors->has("estoqueEntradaProdutos.{$i}.quantidade")) form-control-danger @endif" name="estoqueEntradaProdutos[0][quantidade]" id="estoqueEntradaProdutos[0][quantidade]" value="{{old("estoqueEntradaProdutos.{$i}.quantidade")}}" >
                    @if($errors->has("estoqueEntradaProdutos.{$i}.quantidade"))
                        <div class="form-control-feedback">{{ $errors->first("estoqueEntradaProdutos.{$i}.quantidade") }}</div>
                    @endif
                </div>

                 <div class="form-group col-md-4 @if($errors->has("estoqueEntradaProdutos.{$i}.lote")) has-danger @endif">
                    <label class="form-control-label">Lote</label>
                    <input type="number" class="form-control @if($errors->has("estoqueEntradaProdutos.{$i}.lote")) form-control-danger @endif" name="estoqueEntradaProdutos[0][lote]" id="estoqueEntradaProdutos[0][lote]" value="{{old("estoqueEntradaProdutos.{$i}.lote")}}" >
                    @if($errors->has("estoqueEntradaProdutos.{$i}.lote"))
                        <div class="form-control-feedback">{{ $errors->first("estoqueEntradaProdutos.{$i}.lote") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endfor
@endif


@push('scripts')

    <script>
        function quantidadeEquipamentos(){
            quantidade = $('.item-produto').length
        }

       $('.estoqueEntradaProdutos').on('click', '.add-produto', function(){
            addEquipamento();
        });


        function addEquipamento(){
            quantidadeEquipamentos();

            $($('#item-produto').html()).insertBefore(".add-class-produto");

            $(".selectfild2").select2();

            $("[name^='estoqueEntradaProdutos[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade));
            })

            $("[name^='produtos[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade));
            })
        }

        $('.estoqueEntradaProdutos').on('click', '.item-produto .remove-produto', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.item-produto').remove();
            if ($('.estoqueEntradaProdutos').find('.item-produto').length == 0) {
                quantidadeEquipamentos();
            }

        });


    </script>

    <script type="text/template" id="item-produto">
        <div class="col-md-12 item-produto">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-produto">(remover)</a>
                </div>
                      <div class="form-group col-md-8">
                    <label class="form-control-label">Produtos <span class="text-danger">*</span></label>
                    <select name="estoqueEntradaProdutos[#][id_produto]" class="form-control selectfild2" style="width: 100%">
                        <option value="">Selecione um produto</option>
                        @foreach ($produtos as $item)
                            <option value="{{$item->id}}">{{$item->id}}</option>
                        @endforeach
                    </select>
                </div>

                 <div class="form-group col-md-4 @if($errors->has("estoqueEntradaProdutos.0.quantidade")) has-danger @endif">
                    <label class="form-control-label">Quantidade</label>
                    <input type="number" class="form-control @if($errors->has("estoqueEntradaProdutos.0.quantidade")) form-control-danger @endif" name="estoqueEntradaProdutos[0][quantidade]" id="estoqueEntradaProdutos[0][quantidade]" value="{{old("oldestoqueEntradaProdutos.0.quantidade")}}" >
                    @if($errors->has("estoqueEntradaProdutos.0.quantidade"))
                        <div class="form-control-feedback">{{ $errors->first("estoqueEntradaProdutos.0.quantidade") }}</div>
                    @endif
                </div>

                 <div class="form-group col-md-4 @if($errors->has("estoqueEntradaProdutos.0.lote")) has-danger @endif">
                    <label class="form-control-label">Lote</label>
                    <input type="number" class="form-control @if($errors->has("estoqueEntradaProdutos.0.lote")) form-control-danger @endif" name="estoqueEntradaProdutos[0][lote]" id="estoqueEntradaProdutos[0][lote]" value="{{old("oldestoqueEntradaProdutos.0.lote")}}" >
                    @if($errors->has("estoqueEntradaProdutos.0.lote"))
                        <div class="form-control-feedback">{{ $errors->first("estoqueEntradaProdutos.0.lote") }}</div>
                    @endif
                </div>
            </div>
        </div>
    </script>
@endpush

