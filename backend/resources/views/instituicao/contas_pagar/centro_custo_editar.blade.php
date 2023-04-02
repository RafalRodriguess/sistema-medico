@php($oldCc = old('cc') ?: [])
@if (empty($oldCc))
    @if ($contaPagar->centroCusto->count())
        @for ($i = 0; $i < count($contaPagar->centroCusto); $i++)
            <div class="col-md-12 item-cc">
                <div class="row">
                    <div class="col-md-12">
                        <a href="javascrit:void(0)" class="small remove-cc">(remover)</a>
                    </div>
                    <div class="form-group dados_parcela col-md-8 @if($errors->has("cc.{$i}.centro_custo_id")) has-danger @endif">
                        <label class="form-control-label">Centro de custo:</span></label>
                        <select name="cc[{{$i}}][centro_custo_id]" class="form-control selectfild2 @if($errors->has("cc.{$i}.centro_custo_id")) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione um centro de custo</option>
                            @foreach ($centroCustos as $item)
                                <option value="{{$item->id}}" @if (old("cc.{$i}.centro_custo_id", $contaPagar->centroCusto[$i]->id) == $item->id)
                                    selected="selected"
                                @endif>{{$item->codigo}} - {{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has("cc.{$i}.centro_custo_id"))
                            <div class="form-control-feedback">{{ $errors->first("cc.{$i}.centro_custo_id") }}</div>
                        @endif
                    </div>
                    
                    <div class="form-group col-md-4 @if($errors->has("cc.{$i}.valor")) has-danger @endif">
                        <label class="form-control-label">Valor *</span></label>
                        <input type="text" alt="decimal" class="form-control valor_cc @if($errors->has("cc.{$i}.valor")) form-control-danger @endif" name="cc[{{$i}}][valor]" id="cc[{{$i}}][valor]" value="{{old("cc.{$i}.valor", $contaPagar->centroCusto[$i]->pivot->valor)}}" onchange="totalCC(this)">
                        @if($errors->has("cc.{$i}.valor"))
                            <div class="form-control-feedback">{{ $errors->first("cc.{$i}.valor") }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endfor
    @else 
        
        <div class="col-md-12 item-cc">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-cc">(remover)</a>
                </div>
                <div class="form-group dados_parcela col-md-8 @if($errors->has("cc.0.centro_custo_id")) has-danger @endif">
                    <label class="form-control-label">Centro de custo:</span></label>
                    <select name="cc[0][centro_custo_id]" class="form-control selectfild2 @if($errors->has("cc.0.centro_custo_id")) form-control-danger @endif" style="width: 100%">
                        <option value="">Selecione um centro de custo</option>
                        @foreach ($centroCustos as $item)
                            <option value="{{$item->id}}" @if (old("cc.0.centro_custo_id") == $item->id)
                                selected="selected"
                            @endif>{{$item->codigo}} - {{$item->descricao}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("cc.0.centro_custo_id"))
                        <div class="form-control-feedback">{{ $errors->first("cc.0.centro_custo_id") }}</div>
                    @endif
                </div>
                <div class="form-group col-md-4 @if($errors->has("cc.0.valor")) has-danger @endif">
                    <label class="form-control-label">Valor *</span></label>
                    <input type="text" alt="decimal" class="form-control valor_cc @if($errors->has("cc.0.valor")) form-control-danger @endif" name="cc[0][valor]" id="cc[0][valor]" value="{{old("cc.0.valor")}}" onchange="totalCC(this)">
                    @if($errors->has("cc.0.valor"))
                        <div class="form-control-feedback">{{ $errors->first("cc.0.valor") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endif

@else

    @for($i = 0, $max = count($oldCc); $i < $max; $i++)
        <div class="col-md-12 item-cc">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-cc">(remover)</a>
                </div>
                <div class="form-group dados_parcela col-md-8 @if($errors->has("cc.{$i}.centro_custo_id")) has-danger @endif">
                    <label class="form-control-label">Centro de custo:</span></label>
                    <select name="cc[{{$i}}][centro_custo_id]" class="form-control selectfild2 @if($errors->has("cc.{$i}.centro_custo_id")) form-control-danger @endif" style="width: 100%">
                        <option value="">Selecione um centro de custo</option>
                        @foreach ($centroCustos as $item)
                            <option value="{{$item->id}}" @if (old("cc.{$i}.centro_custo_id") == $item->id)
                                selected="selected"
                            @endif>{{$item->codigo}} - {{$item->descricao}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("cc.{$i}.centro_custo_id"))
                        <div class="form-control-feedback">{{ $errors->first("cc.{$i}.centro_custo_id") }}</div>
                    @endif
                </div>
                <div class="form-group col-md-4 @if($errors->has("cc.{$i}.valor")) has-danger @endif">
                    <label class="form-control-label">Valor *</span></label>
                    <input type="text" alt="decimal" class="form-control valor_cc @if($errors->has("cc.{$i}.valor")) form-control-danger @endif" name="cc[{{$i}}][valor]" id="cc[{{$i}}][valor]" value="{{old("cc.{$i}.valor")}}" onchange="totalCC(this)">
                    @if($errors->has("cc.{$i}.valor"))
                        <div class="form-control-feedback">{{ $errors->first("cc.{$i}.valor") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endfor
@endif


@push('scripts')
    
    <script>
        function quantidadeCC(){
            quantidade_cc = $('.item-cc').length;
            totalCC();
        }

        function totalCC(e){            
            
            var centro_custo_total = 0;

            var valor_total = retornaFormatoValor($("#total").val())
            valor_total = parseFloat(valor_total).toFixed(2)

            $(".valor_cc").each(function(index, element) {
                var valor_cc = retornaFormatoValor($(element).val())
                centro_custo_total = parseFloat(valor_cc) + parseFloat(centro_custo_total);
            })
            
            $("#centro_custo_total").val(centro_custo_total.toFixed(2))
            if(centro_custo_total != valor_total && centro_custo_total > 0){
                $(".salvar_form").prop('disabled', true);
                $.toast({
                    heading: 'Error',
                    text: 'Valor total centro de custo encontra diferente do valor total',
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: 'error',
                    hideAfter: 3000,
                    stack: 10
                });
            }else{
                $(".salvar_form").prop('disabled', false);
            }
        }
        
        $('.centro_custo').on('click', '.add-cc', function(){
            addCC();
        });

        function addCC(){
            quantidade_cc++;
            
            $($('#item-cc').html()).insertBefore(".add-class");

            $('.mask_item').setMask();
            $('.mask_item').removeClass('mask_item');
            $(".selectfild2").select2();

            $("[name^='cc[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade_cc));
            })
        }

        $('.centro_custo').on('click', '.item-cc .remove-cc', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.item-cc').remove();
            if ($('.centro_custo').find('.item-cc').length == 0) {
                addCC();
            }

            totalCC()
        });
    </script>

    <script type="text/template" id="item-cc">
        <div class="col-md-6 item-cc">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-cc">(remover)</a>
                </div>
                <div class="form-group dados_parcela col-md-6">
                    <label class="form-control-label">Centro de custo:</span></label>
                    <select name="cc[#][centro_custo_id]" class="form-control selectfild2" style="width: 100%">
                        <option value="">Selecione um centro de custo</option>
                        @foreach ($centroCustos as $item)
                            <option value="{{$item->id}}">{{$item->codigo}} - {{$item->descricao}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-control-label">Valor *</span></label>
                    <input type="text" alt="decimal" class="form-control valor_cc mask_item" name="cc[#][valor]"  onchange="totalCC(this)">
                </div>
            </div>
        </div>
    </script>
@endpush
