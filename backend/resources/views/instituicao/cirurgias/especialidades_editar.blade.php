@php($oldEspecialidae = old('especialidades') ?: [])
@if (empty($oldEspecialidae))
    {{-- {;{dd($cirurgia->cirurgiasEspecialidades->toArray())}} --}}
    @if ($cirurgia->cirurgiasEspecialidades)
        @for ($i = 0; $i < count($cirurgia->cirurgiasEspecialidades); $i++)
            <div class="col-md-12 item-especialidade">
                <div class="row">
                    <div class="col-md-12">
                        <a href="javascrit:void(0)" class="small remove-especialidade">(remover)</a>
                    </div>

                    <div class="form-group col-md-12 @if($errors->has("especialidades.{$i}.especialidade_id")) has-danger @endif">
                        <label class="form-control-label">Especialidade:</label>
                        <select name="especialidades[{{$i}}][especialidade_id]" class="form-control selectfild2 @if($errors->has("especialidades.{$i}.especialidade_id")) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione uma especialidade</option>
                            @foreach ($especialidades as $item)
                                <option value="{{$item->id}}" @if (old("especialidades.{$i}.especialidade_id", $cirurgia->cirurgiasEspecialidades[$i]->id) == $item->id)
                                    selected="selected"
                                @endif>{{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has("especialidades.{$i}.especialidade_id"))
                            <div class="form-control-feedback">{{ $errors->first("especialidades.{$i}.especialidade_id") }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endfor
    @else
        <div class="col-md-12 item-especialidade">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-especialidade">(remover)</a>
                </div>
                <div class="form-group col-md-12 @if($errors->has("especialidades.0.especialidade_id")) has-danger @endif">
                    <label class="form-control-label">Especialidade:</label>
                    <select name="especialidades[0][especialidade_id]" class="form-control selectfild2 @if($errors->has("especialidades.0.especialidade_id")) form-control-danger @endif" style="width: 100%">
                        <option value="">Selecione uma especialidade</option>
                        @foreach ($especialidades as $item)
                            <option value="{{$item->id}}" @if (old("especialidades.0.especialidade_id") == $item->id)
                                selected="selected"
                            @endif>{{$item->descricao}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("especialidades.0.especialidade_id"))
                        <div class="form-control-feedback">{{ $errors->first("especialidades.0.especialidade_id") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@else
    @for($i = 0, $max = count($oldEspecialidae); $i < $max; $i++)
        <div class="col-md-12 item-especialidade">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-especialidade">(remover)</a>
                </div>
                <div class="form-group col-md-12 @if($errors->has("especialidades.{$i}.especialidade_id")) has-danger @endif">
                    <label class="form-control-label">Especialidade:</label>
                    <select name="especialidades[{{$i}}][especialidade_id]" class="form-control selectfild2 @if($errors->has("especialidades.{$i}.especialidade_id")) form-control-danger @endif" style="width: 100%">
                        <option value="">Selecione uma especialidade</option>
                        @foreach ($especialidades as $item)
                            <option value="{{$item->id}}" @if (old("especialidades.{$i}.especialidade_id") == $item->id)
                                selected="selected"
                            @endif>{{$item->descricao}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("especialidades.{$i}.especialidade_id"))
                        <div class="form-control-feedback">{{ $errors->first("especialidades.{$i}.especialidade_id") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endfor
@endif


@push('scripts')
    
    <script>
        function quantidadeEspecialidades(){
            quantidade_especialidades = $('.item-especialidade').length
        }

       $('.especialidades').on('click', '.add-especialidade', function(){
            addEspecialidade();
        });

        function addEspecialidade(){
            quantidadeEspecialidades();
            
            $($('#item-especialidade').html()).insertBefore(".add-class-especialidade");

            $(".selectfild2").select2();

            $("[name^='especialidades[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade_especialidades));
            })
        }

        $('.especialidades').on('click', '.item-especialidade .remove-especialidade', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.item-especialidade').remove();
            if ($('.especialidades').find('.item-especialidade').length == 0) {
                quantidadeEspecialidades();
            }

        });

        
    </script>

    <script type="text/template" id="item-especialidade">
        <div class="col-md-12 item-especialidade">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-especialidade">(remover)</a>
                </div>
                <div class="form-group col-md-12">
                    <label class="form-control-label">Especialidade:</label>
                    <select name="equipamentos[#][especialidade_id]" class="form-control selectfild2" style="width: 100%">
                        <option value="">Selecione uma especialidade</option>
                        @foreach ($equipamentos as $item)
                            <option value="{{$item->id}}">{{$item->descricao}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </script>
@endpush