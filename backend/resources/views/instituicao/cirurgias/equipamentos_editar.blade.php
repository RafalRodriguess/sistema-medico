@php($oldEquipamento = old('equipamentos') ?: [])
@if (empty($oldEquipamento))
    {{-- {{dd($cirurgia->toArray())}} --}}
    @if ($cirurgia->cirurgiasEquipamentos)
        @for ($i = 0; $i < count($cirurgia->cirurgiasEquipamentos); $i++)
            <div class="col-md-12 item-equipamento">
                <div class="row">
                    <div class="col-md-12">
                        <a href="javascrit:void(0)" class="small remove-equipamento">(remover)</a>
                    </div>

                    <div class="form-group dados_parcela col-md-8 @if($errors->has("equipamentos.{$i}.equipamento_id")) has-danger @endif">
                        <label class="form-control-label">Equipamento:</span></label>
                        <select name="equipamentos[{{$i}}][equipamento_id]" class="form-control selectfild2 @if($errors->has("equipamentos.{$i}.equipamento_id")) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione um equipamento</option>
                            @foreach ($equipamentos as $item)
                                <option value="{{$item->id}}" @if (old("equipamentos.{$i}.equipamento_id", $cirurgia->cirurgiasEquipamentos[$i]->id) == $item->id)
                                    selected="selected"
                                @endif>{{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has("equipamentos.{$i}.equipamento_id"))
                            <div class="form-control-feedback">{{ $errors->first("equipamentos.{$i}.equipamento_id") }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 @if($errors->has("equipamentos.{$i}.quantidade")) has-danger @endif">
                        <label class="form-control-label">Quantidade</label>
                        <input type="text" class="form-control @if($errors->has("equipamentos.{$i}.quantidade")) form-control-danger @endif" name="equipamentos[{{$i}}][quantidade]" id="equipamentos[{{$i}}][quantidade]" value="{{old("equipamentos.{$i}.quantidade", $cirurgia->cirurgiasEquipamentos[$i]->pivot->quantidade)}}" >
                        @if($errors->has("equipamentos.{$i}.quantidade"))
                            <div class="form-control-feedback">{{ $errors->first("equipamentos.{$i}.quantidade") }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endfor
    @else
        <div class="col-md-12 item-equipamento">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-equipamento">(remover)</a>
                </div>
                <div class="form-group dados_parcela col-md-8 @if($errors->has("equipamentos.0.equipamento_id")) has-danger @endif">
                    <label class="form-control-label">Equipamento:</label>
                    <select name="equipamentos[0][equipamento_id]" class="form-control selectfild2 @if($errors->has("equipamentos.0.equipamento_id")) form-control-danger @endif" style="width: 100%">
                        <option value="">Selecione um equipamento</option>
                        @foreach ($equipamentos as $item)
                            <option value="{{$item->id}}" @if (old("equipamentos.0.equipamento_id") == $item->id)
                                selected="selected"
                            @endif>{{$item->descricao}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("equipamentos.0.equipamento_id"))
                        <div class="form-control-feedback">{{ $errors->first("equipamentos.0.equipamento_id") }}</div>
                    @endif
                </div>
                <div class="form-group col-md-4 @if($errors->has("equipamentos.0.quantidade")) has-danger @endif">
                    <label class="form-control-label">Quantidade</label>
                    <input type="number" class="form-control @if($errors->has("equipamentos.0.quantidade")) form-control-danger @endif" name="equipamentos[0][quantidade]" id="equipamentos[0][quantidade]" value="{{old("equipamentos.0.quantidade")}}" >
                    @if($errors->has("equipamentos.0.quantidade"))
                        <div class="form-control-feedback">{{ $errors->first("equipamentos.0.quantidade") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@else
    @for($i = 0, $max = count($oldEquipamento); $i < $max; $i++)
        <div class="col-md-12 item-equipamento">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-equipamento">(remover)</a>
                </div>
                <div class="form-group dados_parcela col-md-8 @if($errors->has("equipamentos.{$i}.equipamento_id")) has-danger @endif">
                    <label class="form-control-label">Equipamento:</label>
                    <select name="equipamentos[{{$i}}][equipamento_id]" class="form-control selectfild2 @if($errors->has("equipamentos.{$i}.equipamento_id")) form-control-danger @endif" style="width: 100%">
                        <option value="">Selecione um equipamento</option>
                        @foreach ($equipamentos as $item)
                            <option value="{{$item->id}}" @if (old("equipamentos.{$i}.equipamento_id") == $item->id)
                                selected="selected"
                            @endif>{{$item->descricao}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("equipamentos.{$i}.equipamento_id"))
                        <div class="form-control-feedback">{{ $errors->first("equipamentos.{$i}.equipamento_id") }}</div>
                    @endif
                </div>

                <div class="form-group col-md-4 @if($errors->has("equipamentos.{$i}.quantidade")) has-danger @endif">
                    <label class="form-control-label">Quantidade</label>
                    <input type="number" class="form-control @if($errors->has("equipamentos.{$i}.quantidade")) form-control-danger @endif" name="equipamentos[{{$i}}][quantidade]" id="equipamentos[{{$i}}][quantidade]" value="{{old("equipamentos.{$i}.quantidade")}}" >
                    @if($errors->has("equipamentos.{$i}.quantidade"))
                        <div class="form-control-feedback">{{ $errors->first("equipamentos.{$i}.quantidade") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endfor
@endif


@push('scripts')
    
    <script>
        function quantidadeEquipamentos(){
            quantidade_equipamentos = $('.item-equipamento').length
        }

       $('.equipamentos').on('click', '.add-equipamento', function(){
            addEquipamento();
        });

        function addEquipamento(){
            quantidadeEquipamentos();
            
            $($('#item-equipamento').html()).insertBefore(".add-class-equipamento");

            $(".selectfild2").select2();

            $("[name^='equipamentos[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade_equipamentos));
            })
        }

        $('.equipamentos').on('click', '.item-equipamento .remove-equipamento', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.item-equipamento').remove();
            if ($('.equipamentos').find('.item-equipamento').length == 0) {
                quantidadeEquipamentos();
            }

        });

        
    </script>

    <script type="text/template" id="item-equipamento">
        <div class="col-md-12 item-equipamento">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-equipamento">(remover)</a>
                </div>
                <div class="form-group col-md-8">
                    <label class="form-control-label">Equipamento:</label>
                    <select name="equipamentos[#][equipamento_id]" class="form-control selectfild2" style="width: 100%">
                        <option value="">Selecione um equipamento</option>
                        @foreach ($equipamentos as $item)
                            <option value="{{$item->id}}">{{$item->descricao}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label class="form-control-label">Quantidade</label>
                    <input type="number" class="form-control quantidade_equipamentos" name="equipamentos[#][quantidade]">
                </div>
            </div>
        </div>
    </script>
@endpush