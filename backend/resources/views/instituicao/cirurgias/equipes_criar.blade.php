@php($oldEquipe = old('equipes') ?: [])
@if (empty($oldEquipe))
    <div class="col-md-12 item-equipe">
        <div class="row">
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove-equipe">(remover)</a>
            </div>

            <div class="form-group col-md-12 @if($errors->has("equipes.0.equipe_id")) has-danger @endif">
                <label class="form-control-label">Equipe</label>
                <select name="equipes[0][equipe_id]" class="form-control selectfild2 @if($errors->has("equipes.0.equipe_id")) form-control-danger @endif" style="width: 100%">
                    <option value="">Selecione uma equipe</option>
                    @foreach ($equipes as $item)
                        <option value="{{$item->id}}" @if (old("equipes.0.equipe_id") == $item->id)
                            selected="selected"
                        @endif>{{$item->descricao}}</option>
                    @endforeach
                </select>
                @if($errors->has("equipes.0.equipe_id"))
                    <div class="form-control-feedback">{{ $errors->first("equipes.0.equipe_id") }}</div>
                @endif
            </div>
        </div>
    </div>

@else

    @for($i = 0, $max = count($oldEquipe); $i < $max; $i++)
        <div class="col-md-12 item-equipe">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-equipe">(remover)</a>
                </div>
                <div class="form-group col-md-12 @if($errors->has("equipes.{$i}.equipe_id")) has-danger @endif">
                    <label class="form-control-label">Equipe</label>
                    <select name="equipes[{{$i}}][equipe_id]" class="form-control selectfild2 @if($errors->has("equipes.{$i}.equipe_id")) form-control-danger @endif" style="width: 100%">
                        <option value="">Selecione uma equipe</option>
                        @foreach ($equipes as $item)
                            <option value="{{$item->id}}" @if (old("equipes.{$i}.equipe_id") == $item->id)
                                selected="selected"
                            @endif>{{$item->nome}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("equipes.{$i}.equipe_id"))
                        <div class="form-control-feedback">{{ $errors->first("equipes.{$i}.equipe_id") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endfor
@endif


@push('scripts')
    
    <script>
        $('.equipes').on('click', '.add-equipe', function(){
            addEquipe();
        });

        function addEquipe(){
            quantidade_equipe++;
            
            console.log("aqui")

            $($('#item-equipe').html()).insertBefore(".add-class-equipe");

            $(".selectfild2").select2();

            $("[name^='equipes[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade_equipe));
            })
        }

        $('.equipes').on('click', '.item-equipe .remove-equipe', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.item-equipe').remove()
            if($('.equipes').find('.item-equipe').length == 0) {
                addEquipe();
            }
        });
    </script>

    <script type="text/template" id="item-equipe">
        <div class="col-md-12 item-equipe">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-equipe">(remover)</a>
                </div>
                <div class="form-group col-md-12">
                    <label class="form-control-label">Equipe</label>
                    <select name="equipes[#][equipe_id]" class="form-control selectfild2" style="width: 100%">
                        <option value="">Selecione uma equipe</option>
                        @foreach ($equipes as $item)
                            <option value="{{$item->id}}">{{$item->descricao}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </script>
@endpush