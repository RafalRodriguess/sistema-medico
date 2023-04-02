@if (count($agendamento->equipamentos) > 0)
    @foreach ($agendamento->equipamentos as $key => $item)
        <div class="row item">
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove_equipamento">(remover)</a>
            </div>
            <div class="form-group col-md-8">
                <label for="centro_cirurgico_editar" class="control-label">Equipamento *:</label>
                <select class="form-control select2ecc" name="equipamentos[{{$key}}][equipamento]" id="equipamentos_equipamento_{{$key}}" style="width: 100%">
                    @foreach ($equipamentos as $equipamento)
                        <option value="{{$equipamento->id}}" @if ($equipamento->id == $item->id)
                            selected
                        @endif>{{$equipamento->descricao}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="centro_cirurgico_editar" class="control-label">Quantidade *:</label>
                <input type="number" class="form-control item_ecc" id="equipamentos_quantidade_{{$key}}" name="equipamentos[{{$key}}][quantidade]" alt="numeric" value="{{$item->pivot->quantidade}}">
            </div>
        </div>
    @endforeach
@else
    @if (count($agendamento->cirurgia->cirurgiasEquipamentos) > 0)
        @foreach ($agendamento->cirurgia->cirurgiasEquipamentos as $key => $item)
            <div class="row item">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove_equipamento">(remover)</a>
                </div>
                <div class="form-group col-md-8">
                    <label for="centro_cirurgico_editar" class="control-label">Equipamento *:</label>
                    <select class="form-control select2ecc" name="equipamentos[{{$key}}][equipamento]" id="equipamentos_{{$key}}" style="width: 100%">
                        @foreach ($equipamentos as $equipamento)
                            <option value="{{$equipamento->id}}" @if ($equipamento->id == $item->id)
                                selected
                            @endif>{{$equipamento->descricao}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="centro_cirurgico_editar" class="control-label">Quantidade *:</label>
                    <input type="number" class="form-control item_ecc" id="equipamentos_{{$key}}" name="equipamentos[{{$key}}][quantidade]" alt="numeric" value="{{$item->pivot->quantidade}}">
                </div>
            </div>
        @endforeach
    @endif
@endif

<div class="form-group col-md-12 add-class-equipamento" >
    <span alt="default" class="add_equipamento fas fa-plus-circle" style="cursor: pointer;">
        <a class="mytooltip" href="javascript:void(0)">
            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar equipamento"></i>
        </a>
    </span>
</div>

<script>

    

    $('.equipamentos').on('click', '.item .remove_equipamento', function(e){
        e.preventDefault()

        $(e.currentTarget).parents('.item').remove();
    });

    $('.equipamentos').on('click', '.add_equipamento', function(){
        // $('.formula').append($($('#item-formula').html()).insertBefore(".add-class"));
        $($('#item-equipamento').html()).insertBefore(".add-class-equipamento");

        $('.item_ecc').setMask();
        $('.item_ecc').removeClass('item_ecc');
        // $(".select2eccs").select2();

        $("[name^='equipamentos[#]']").each(function(index, element) {
            const name = $(element).attr('name');
            $(element).attr('name', name.replace('#',quantidade_equipamento));
            const ids = $(element).attr('id');
            $(element).attr('id', ids.replace('#',quantidade_equipamento));
        })

        $("#equipamentos_equipamento_"+quantidade_equipamento).select2()
        
        quantidade_equipamento++;  
    });
</script>

<script type="text/template" id="item-equipamento">
    <div class="row item">
        <div class="col-md-12">
            <a href="javascrit:void(0)" class="small remove_equipamento">(remover)</a>
        </div>
        <div class="form-group col-md-8">
            <label for="centro_cirurgico_editar" class="control-label">Equipamento *:</label>
            <select class="form-control select2ecc" name="equipamentos[#][equipamento]" id="equipamentos_equipamento_#" style="width: 100%">
                <option value="">Selecione um equipamento</option>
                @foreach ($equipamentos as $equipamento)
                    <option value="{{$equipamento->id}}">{{$equipamento->descricao}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="centro_cirurgico_editar" class="control-label">Quantidade *:</label>
            <input type="number" class="form-control item_ecc" id="equipamentos_quantidade_#" name="equipamentos[#][quantidade]" alt="numeric" value="0">
        </div>
    </div>
</script>