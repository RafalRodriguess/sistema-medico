@php($oldSala = old('salas') ?: [])
@if (empty($oldSala))
    {{-- {{dd($cirurgia->toArray())}} --}}
    @if ($cirurgia->cirurgiasSalas)
        @for ($i = 0; $i < count($cirurgia->cirurgiasSalas); $i++)
            <div class="col-md-12 item-sala">
                <div class="row">
                    <div class="col-md-12">
                        <a href="javascrit:void(0)" class="small remove-sala">(remover)</a>
                    </div>

                    <div class="form-group col-md-12 @if($errors->has("salas.{$i}.sala_id")) has-danger @endif">
                        <label class="form-control-label">Sala:</span></label>
                        <select name="salas[{{$i}}][sala_id]" class="form-control selectfild2 @if($errors->has("salas.{$i}.sala_id")) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione uma sala</option>
                            @foreach ($salas as $item)
                                <option value="{{$item->id}}" @if (old("salas.{$i}.sala_id", $cirurgia->cirurgiasSalas[$i]->id) == $item->id)
                                    selected="selected"
                                @endif>{{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has("salas.{$i}.sala_id"))
                            <div class="form-control-feedback">{{ $errors->first("salas.{$i}.sala_id") }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endfor
    @else
        <div class="col-md-12 item-sala">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-sala">(remover)</a>
                </div>
                <div class="form-group col-md-12 @if($errors->has("salas.0.sala_id")) has-danger @endif">
                    <label class="form-control-label">Sala:</label>
                    <select name="salas[0][sala_id]" class="form-control selectfild2 @if($errors->has("salas.0.sala_id")) form-control-danger @endif" style="width: 100%">
                        <option value="">Selecione uma sala</option>
                        @foreach ($salas as $item)
                            <option value="{{$item->id}}" @if (old("salas.0.sala_id") == $item->id)
                                selected="selected"
                            @endif>{{$item->descricao}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("salas.0.sala_id"))
                        <div class="form-control-feedback">{{ $errors->first("salas.0.sala_id") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@else
    @for($i = 0, $max = count($oldSala); $i < $max; $i++)
        <div class="col-md-12 item-sala">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-sala">(remover)</a>
                </div>
                <div class="form-group col-md-12 @if($errors->has("salas.{$i}.sala_id")) has-danger @endif">
                    <label class="form-control-label">Sala:</label>
                    <select name="salas[{{$i}}][sala_id]" class="form-control selectfild2 @if($errors->has("salas.{$i}.sala_id")) form-control-danger @endif" style="width: 100%">
                        <option value="">Selecione uma sala</option>
                        @foreach ($salas as $item)
                            <option value="{{$item->id}}" @if (old("salas.{$i}.sala_id") == $item->id)
                                selected="selected"
                            @endif>{{$item->descricao}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("salas.{$i}.sala_id"))
                        <div class="form-control-feedback">{{ $errors->first("salas.{$i}.sala_id") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endfor
@endif


@push('scripts')
    
    <script>
        function quantidadeSalas(){
            quantidade_salas = $('.item-sala').length
        }

       $('.salas').on('click', '.add-sala', function(){
            addSala();
        });

        function addSala(){
            quantidadeSalas();
            
            $($('#item-sala').html()).insertBefore(".add-class-sala");

            $(".selectfild2").select2();

            $("[name^='salas[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade_salas));
            })
        }

        $('.salas').on('click', '.item-sala .remove-sala', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.item-sala').remove();
            if ($('.salas').find('.item-sala').length == 0) {
                quantidadeSalas();
            }
        });
    </script>

    <script type="text/template" id="item-sala">
        <div class="col-md-12 item-sala">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-sala">(remover)</a>
                </div>
                <div class="form-group col-md-12">
                    <label class="form-control-label">Sala:</label>
                    <select name="salas[#][sala_id]" class="form-control selectfild2" style="width: 100%">
                        <option value="">Selecione uma sala</option>
                        @foreach ($salas as $item)
                            <option value="{{$item->id}}">{{$item->descricao}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </script>
@endpush