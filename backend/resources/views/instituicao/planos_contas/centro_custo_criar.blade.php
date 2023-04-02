@php($oldCc = old('cc') ?: [])
@if (empty($oldCc))
    <div class="col-md-12 item-cc">
        <div class="row">
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove-cc">(remover)</a>
            </div>

            <div class="form-group col-md-8 @if($errors->has("cc.0.centro_custos_id")) has-danger @endif">
                <label class="form-control-label">Centro de custo:</span></label>
                <select name="cc[0][centro_custos_id]" class="form-control selectfild2 @if($errors->has("cc.0.centro_custos_id")) form-control-danger @endif" style="width: 100%">
                    <option value="">Selecione um centro de custo</option>
                    @foreach ($centroCusto as $item)
                        <option value="{{$item->id}}" @if (old("cc.0.centro_custos_id") == $item->id)
                            selected="selected"
                        @endif>{{$item->descricao}}</option>
                    @endforeach
                </select>
                @if($errors->has("cc.0.centro_custos_id"))
                    <div class="form-control-feedback">{{ $errors->first("cc.0.centro_custos_id") }}</div>
                @endif
            </div>

            <div class="form-group col-md-4 @if($errors->has("cc.0.percentual")) has-danger @endif">
                <label class="form-control-label">Percentual *</span></label>
                <input type="text" alt="decimal" class="form-control percentual_cc @if($errors->has("cc.0.percentual")) form-control-danger @endif" name="cc[0][percentual]" id="cc[0][percentual]" value="{{old("cc.0.percentual")}}" onchange="totalCC(this)">
                @if($errors->has("cc.0.percentual"))
                    <div class="form-control-feedback">{{ $errors->first("cc.0.percentual") }}</div>
                @endif
            </div>
        </div>
    </div>

@else

    @for($i = 0, $max = count($oldCc); $i < $max; $i++)
        <div class="col-md-12 item-cc">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-cc">(remover)</a>
                </div>
                <div class="form-group dados_parcela col-md-8 @if($errors->has("cc.{$i}.centro_custos_id")) has-danger @endif">
                    <label class="form-control-label">Centro de custo:</span></label>
                    <select name="cc[{{$i}}][centro_custos_id]" class="form-control selectfild2 @if($errors->has("cc.{$i}.centro_custos_id")) form-control-danger @endif" style="width: 100%">
                        <option value="">Selecione um centro de custo</option>
                        @foreach ($centroCusto as $item)
                            <option value="{{$item->id}}" @if (old("cc.{$i}.centro_custos_id") == $item->id)
                                selected="selected"
                            @endif>{{$item->nome}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("cc.{$i}.centro_custos_id"))
                        <div class="form-control-feedback">{{ $errors->first("cc.{$i}.centro_custos_id") }}</div>
                    @endif
                </div>

                <div class="form-group col-md-4 @if($errors->has("cc.{$i}.percentual")) has-danger @endif">
                    <label class="form-control-label">Percentual *</span></label>
                    <input type="text" alt="decimal" class="form-control percentual_cc @if($errors->has("cc.{$i}.percentual")) form-control-danger @endif" name="cc[{{$i}}][percentual]" id="cc[{{$i}}][percentual]" value="{{old("cc.{$i}.percentual")}}" onchange="totalCC(this)">
                    @if($errors->has("cc.{$i}.percentual"))
                        <div class="form-control-feedback">{{ $errors->first("cc.{$i}.percentual") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endfor
@endif


@push('scripts')

    <script>
        // function quantidadeCC(){
        //     quantidade_cc = $('.item-cc').length;
        // }

        function totalCC(e){

            var Percentual_total = 0

            $(".percentual_cc").each(function(index, element) {
                var percentual = retornaFormatoValor($(element).val())
                Percentual_total += parseFloat(percentual);
            })
            // Impedindo erros de matemática por causa de arredondamento de bit
            if(Math.abs(Percentual_total - 100) > 0.001 ){
                $(".salvar_form").prop('disabled', true);
                $.toast({
                    heading: 'Error',
                    text: 'Rateio de centro de custo deve ser no total 100%',
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

        $('.rateio').on('click', '.add-cc', function(){
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

        $('.rateio').on('click', '.item-cc .remove-cc', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.item-cc').remove();
            if ($('.rateio').find('.item-cc').length == 0) {
                addCC();
            }

            totalCC()
        });
    </script>

    <script type="text/template" id="item-cc">
        <div class="col-md-12 item-cc">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-cc">(remover)</a>
                </div>
                <div class="form-group col-md-8">
                    <label class="form-control-label">Centro de custo:</span></label>
                    <select name="cc[#][centro_custos_id]" class="form-control selectfild2" style="width: 100%">
                        <option value="">Selecione um centro de custo</option>
                        @foreach ($centroCusto as $item)
                            <option value="{{$item->id}}">{{$item->descricao}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label class="form-control-label">Percentual *</span></label>
                    <input type="text" alt="decimal" class="form-control percentual_cc mask_item" name="cc[#][percentual]"  onchange="totalCC(this)">
                </div>
            </div>
        </div>
    </script>
@endpush
