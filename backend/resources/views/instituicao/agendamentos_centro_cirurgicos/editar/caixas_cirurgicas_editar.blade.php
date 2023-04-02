@if (count($agendamento->caixasCirurgicas) > 0)
    @foreach ($agendamento->caixasCirurgicas as $key => $item)
        <div class="row item">
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove_caixa_cirurgica">(remover)</a>
            </div>
            <div class="form-group col-md-8">
                <label for="centro_cirurgico_editar" class="control-label">Caixas cirúrgicas *:</label>
                <select class="form-control select2ecc" name="caixas_cirurgicas[{{$key}}][caixa_cirurgica]" id="caixas_cirurgicas_caixa_cirurgico_{{$key}}" style="width: 100%">
                    @foreach ($caixas_cirurgicos as $caixa_cirurgica)
                        <option value="{{$caixa_cirurgica->id}}" @if ($caixa_cirurgica->id == $item->id)
                            selected
                        @endif>{{$caixa_cirurgica->descricao}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="centro_cirurgico_editar" class="control-label">Quantidade *:</label>
                <input type="number" class="form-control item_ecc" id="caixas_cirurgicas_quantidade_{{$key}}" name="caixas_cirurgicas[{{$key}}][quantidade]" alt="numeric" value="{{$item->pivot->quantidade}}">
            </div>
        </div>
    @endforeach
@endif

<div class="form-group col-md-12 add-class-caixa-cirurgica" >
    <span alt="default" class="add_caixa_cirurgica fas fa-plus-circle" style="cursor: pointer;">
        <a class="mytooltip" href="javascript:void(0)">
            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar caixas cirúrgicas"></i>
        </a>
    </span>
</div>

<script>

    $('.caixas_cirurgicas').on('click', '.item .remove_caixa_cirurgica', function(e){
        e.preventDefault()

        $(e.currentTarget).parents('.item').remove();
    });

    $('.caixas_cirurgicas').on('click', '.add_caixa_cirurgica', function(){
        // $('.formula').append($($('#item-formula').html()).insertBefore(".add-class"));
        $($('#item-caixa-cirurgica').html()).insertBefore(".add-class-caixa-cirurgica");

        $('.item_ecc').setMask();
        $('.item_ecc').removeClass('item_ecc');
        // $(".select2eccs").select2();

        $("[name^='caixas_cirurgicas[#]']").each(function(index, element) {
            const name = $(element).attr('name');
            $(element).attr('name', name.replace('#',quantidade_caixa_cirurgica));
            const ids = $(element).attr('id');
            $(element).attr('id', ids.replace('#',quantidade_caixa_cirurgica));
        })

        $("#caixas_cirurgicas_caixa_cirurgico_"+quantidade_caixa_cirurgica).select2()
        
        quantidade_caixa_cirurgica++;  
    });
</script>

<script type="text/template" id="item-caixa-cirurgica">
    <div class="row item">
        <div class="col-md-12">
            <a href="javascrit:void(0)" class="small remove_caixa_cirurgica">(remover)</a>
        </div>
        <div class="form-group col-md-8">
            <label for="centro_cirurgico_editar" class="control-label">Caixa cirúrgica *:</label>
            <select class="form-control select2ecc" name="caixas_cirurgicas[#][caixa_cirurgica]" id="caixas_cirurgicas_caixa_cirurgico_#" style="width: 100%">
                <option value="">Selecione uma caixa cirúrgica</option>
                @foreach ($caixas_cirurgicos as $caixa_cirurgica)
                    <option value="{{$caixa_cirurgica->id}}">{{$caixa_cirurgica->descricao}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Quantidade *:</label>
            <input type="number" class="form-control item_ecc" id="caixas_cirurgicas_quantidade_#" name="caixas_cirurgicas[#][quantidade]" alt="numeric" value="0">
        </div>
    </div>
</script>