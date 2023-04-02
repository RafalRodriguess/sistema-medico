@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Agendas",
        'breadcrumb' => [
            'Prestadores' => route('instituicao.prestadores.index'),
            'Editar',
        ],
    ])
    @endcomponent

    <form @submit.prevent="submit" id='form' action="{{ route('instituicao.prestadores.agenda.update', [$prestador]) }}" method="post">
                @method('put')
                @csrf

    @foreach($prestador->especialidadeInstituicao as $especialidadeInstituicao)
        @php
            $semana=[

                    'domingo'=>(object)[
                        'nome' => 'Domingo',
                        'value' => 'domingo'
                    ],
                    'segunda'=>(object)[
                        'nome' => 'Segunda',
                        'value' => 'segunda'
                    ],
                    'terca'=>(object)[
                        'nome' => 'Terça',
                        'value' => 'terca'
                    ],
                    'quarta'=>(object)[
                        'nome' => 'Quarta',
                        'value' => 'quarta'
                    ],
                    'quinta'=>(object)[
                        'nome' => 'Quinta',
                        'value' => 'quinta'
                    ],
                    'sexta'=>(object)[
                        'nome' => 'Sexta',
                        'value' => 'sexta'
                    ],
                    'sabado'=>(object)[
                        'nome' => 'Sábado',
                        'value' => 'sabado'
                    ],

            ];
        @endphp
    <div class="card card-outline-info" id='card-{{$especialidadeInstituicao->especialidade->id}}'>
        <div class="card-header">
            <h4 class="m-b-0 text-white">{{$especialidadeInstituicao->especialidade->descricao}}</h4>
        </div>
        <div class="card-body">
            <button type="button" class="btn btn-info waves-effect waves-light m-r-10" style="margin-bottom: 15px;" data-id="{{$especialidadeInstituicao->especialidade->id}}" onclick="addHorarioSemana(this)"><i class="mdi mdi-clock"></i> Adicionar novo horario semana</button>
            @foreach ($semana as $s)
                <div class="semana_{{$s->value}}_{{$especialidadeInstituicao->especialidade->id}}">
                @php
                    $agenda = $especialidadeInstituicao->agenda()->where('dias_continuos',$s->value)->get();
                @endphp
                @if (count($agenda) <= 1)
                    @php
                        $itemAgenda = null;
                        if(count($agenda) > 0){
                            $itemAgenda = $agenda[0];
                        }
                    @endphp
                    <div class='row @if($itemAgenda || old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) panel @else no-panel @endif'>
                        <div class="form-group col-sm-1 col-xs-12" style="text-align:center">
                            <label for="checkbox[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >{{$s->nome}}</label>
                            <div >
                                    <input type="checkbox" class='form-control checkbox' @if($itemAgenda || old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) checked @endif value="{{$especialidadeInstituicao->especialidade->id}}" id='checkbox[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]' name="checkbox[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >
                            </div>
                        </div>

                        <div class="form-group col-sm-2 hidable  @if($errors->has('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                            <label for="inicio[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >Horário de início</label>
                            <div class="input-group " data-autoclose="true">
                                @php
                                    if(old('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)){
                                        $hora = old('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id);
                                    }elseif($itemAgenda){
                                        $hora = date("H:i", strtotime($itemAgenda->hora_inicio));
                                    }else{
                                        $hora = "";
                                    }
                                @endphp

                                <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif type="time" class="form-control" value="{{$hora}}" id="inicio[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="inicio[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">

                                {{-- <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif  type="time" class="form-control" value="@if(old('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) {{old('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)}} @elseif($itemAgenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$itemAgenda->hora_inicio)->format('H:i')}} @else 13:00:00 @endif" id="inicio[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="inicio[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]"> --}}
                                {{-- <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                </div> --}}
                            </div>
                            @if($errors->has('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                <div class="form-control-feedback">{{ $errors->first('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id) }}</div>
                            @endif
                            {{-- <input class="form-control clockpicker" type="time" value="13:00:00" id="{{$especialidadeInstituicao->especialidade->id}}-{{$s->value}}-inicio"> --}}
                        </div>
                        <div class="form-group @if($errors->has('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif col-sm-2 hidable ">
                            <label for="intervalo[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >Horário do intervalo</label>
                            <div class="input-group" data-autoclose="true">
                                @php
                                    if(old('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)){
                                        $hora = old('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id);
                                    }elseif($itemAgenda){
                                        $hora = date("H:i", strtotime($itemAgenda->hora_intervalo));
                                    }else{
                                        $hora = "";
                                    }
                                @endphp

                                <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif type="time" class="form-control" value="{{$hora}}" id="intervalo[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="intervalo[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">


                                {{-- <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @else readonly @endif  type="text" class="form-control" value="@if(old('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) {{old('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)}} @elseif($itemAgenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$itemAgenda->hora_intervalo)->format('H:i')}} @else 15:45 @endif" id="intervalo[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="intervalo[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]"> --}}
                                <div class="input-group-append">
                                    <span class="input-group-text" data-toggle="tooltip" title="" data-original-title="Colocar o mesmo horário de termino para não haver intervalo">
                                        ?
                                    </span>
                                </div>
                            </div>
                            @if($errors->has('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                <div class="form-control-feedback">{{ $errors->first('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id) }}</div>
                            @endif
                            {{-- <input class="form-control clockpicker" type="time" value="15:45:00" id="{{$especialidadeInstituicao->especialidade->id}}-{{$s->value}}-intervalo"> --}}
                        </div>
                        <div class="form-group col-sm-2 hidable @if($errors->has('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif ">
                            <label for="duracao[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >Tempo do intervalo</label>
                            <div class="input-group " data-autoclose="true">
                                @php
                                    if(old('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)){
                                        $hora = old('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id);
                                    }elseif($itemAgenda){
                                        $hora = date("H:i", strtotime($itemAgenda->duracao_intervalo));
                                    }else{
                                        $hora = "";
                                    }
                                @endphp

                                <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif type="time" class="form-control" value="{{$hora}}" id="duracao[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="duracao[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">

                                {{-- <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @else readonly @endif type="text" class="form-control" value="@if(old('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) {{old('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)}} @elseif($itemAgenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$itemAgenda->duracao_intervalo)->format('H:i')}} @else 00:15 @endif" id="duracao[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="duracao[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                </div> --}}
                            </div>
                            @if($errors->has('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                <div class="form-control-feedback">{{ $errors->first('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id) }}</div>
                            @endif
                            {{-- <input class="form-control clockpicker" type="time" value="00:15:00" id="{{$especialidadeInstituicao->especialidade->id}}-{{$s->value}}-duracao-intervalo"> --}}
                        </div>
                        <div class="form-group col-sm-2 hidable @if($errors->has('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif ">
                            <label for="termino[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >Horário de termino</label>
                            <div class="input-group " data-autoclose="true">
                                @php
                                    if(old('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)){
                                        $hora = old('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id);
                                    }elseif($itemAgenda){
                                        $hora = date("H:i", strtotime($itemAgenda->hora_fim));
                                    }else{
                                        $hora = "";
                                    }
                                @endphp

                                <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif type="time" class="form-control" value="{{$hora}}" id="termino[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="termino[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">

                                {{-- <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @else readonly @endif type="text" class="form-control" value="@if(old('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) {{old('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)}} @elseif($itemAgenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$itemAgenda->hora_fim)->format('H:i')}} @else 18:00 @endif" id="termino[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="termino[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                </div> --}}
                            </div>
                            @if($errors->has('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                <div class="form-control-feedback">{{ $errors->first('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id) }}</div>
                            @endif
                            {{-- <input class="form-control clockpicker" type="time" value="18:00:00" id="{{$especialidadeInstituicao->especialidade->id}}-{{$s->value}}-termino"> --}}
                        </div>
                        <div class="form-group col-sm-2 hidable @if($errors->has('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif ">
                            <label for="atendimento[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >Tempo do Atendimento</label>
                            <div class="input-group " data-autoclose="true">
                                @php
                                    if(old('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)){
                                        $hora = old('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id);
                                    }elseif($itemAgenda){
                                        $hora = date("H:i", strtotime($itemAgenda->duracao_atendimento));
                                    }else{
                                        $hora = "";
                                    }
                                @endphp

                                <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif type="time" class="form-control" value="{{$hora}}" id="atendimento[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="atendimento[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">

                                {{-- <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @else readonly @endif  type="text" class="form-control" value="@if(old('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) {{old('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)}} @elseif($itemAgenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$itemAgenda->duracao_atendimento)->format('H:i')}} @else 00:45 @endif" name="atendimento[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" id="atendimento[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                </div> --}}
                            </div>
                            @if($errors->has('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                <div class="form-control-feedback">{{ $errors->first('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id) }}</div>
                            @endif
                            {{-- <input class="form-control clockpicker" type="time" value="00:45:00" id="{{$especialidadeInstituicao->especialidade->id}}-{{$s->value}}-atendimento"> --}}
                        </div>


                        <div class="form-group col-sm-2 col-xs-2 hidable @if($errors->has('setor_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                            <label for="setor_id[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">Setores</label>
                            <select class="form-control selectfild2" name="setor_id_agenda[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" style="width: 100%" @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif>
                                <option value="">Selecione um setor</option>
                                @foreach ($setores as $setor)
                                    <option value="{{ $setor->id }}"
                                        @if ($itemAgenda)
                                            @if ($setor->id == old("setor_id.".$s->value.'.'.$especialidadeInstituicao->especialidade->id, $itemAgenda->setor_id))
                                                selected
                                            @endif
                                        @else
                                            @if ($setor->id == old("setor_id.".$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                                selected
                                            @endif
                                        @endif
                                    >{{ $setor->descricao }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('setor_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                <small class="form-text text-danger">{{ $errors->first('setor_id') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-sm-2 col-xs-2 hidable @if($errors->has('faixa_etaria.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                            <label for="faixa_etaria[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">Faixa etária</label>
                            <select class="form-control selectfild2" name="faixa_etaria_agenda[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" style="width: 100%" @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif>
                                <option value="">Selecione uma faixa</option>
                                @foreach (App\InstituicoesAgenda::getFaixaEtaria() as $item)
                                    <option value="{{$item}}"  @if ($itemAgenda)
                                    @if ($item == old("faixa_etaria.".$s->value.'.'.$especialidadeInstituicao->especialidade->id, $itemAgenda->faixa_etaria))
                                            selected
                                        @endif
                                    @else
                                        @if ($item == old("faixa_etaria.".$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                            selected
                                        @endif
                                    @endif>{{App\InstituicoesAgenda::getFaixaEtariaTexto($item)}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('faixa_etaria.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                <small class="form-text text-danger">{{ $errors->first('faixa_etaria') }}</small>
                            @endif
                        </div>

                        {{-- <div class="form-group col-sm-2 col-xs-2 hidable @if($errors->has('convenio_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                            <label for="convenio_id[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">Convenio*</label>
                            <select class="form-control selectfild2" name="convenio_id[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}][]" style="width: 100%" @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled  @else readonly @endif multiple>
                                @foreach ($convenios as $item)
                                    <option value="{{$item->id}}"
                                        @if ($itemAgenda)
                                            @foreach ($itemAgenda->convenios as $value)
                                                @if ($item->id == $value->id)
                                                    selected
                                                @endif
                                            @endforeach
                                        @endif>
                                        {{$item->nome}}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('convenio_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                <small class="form-text text-danger">{{ $errors->first('convenio_id') }}</small>
                            @endif
                        </div> --}}
                        <div class="form-group col-sm-3 select2Dia" style="padding-top: 30px;">
                            <button type="button" class="btn btn-info waves-effect waves-light m-r-10" style="margin-bottom: 15px;" data-id="modal_id_0_{{$s->value}}_{{$especialidadeInstituicao->especialidade->id}}" onclick="modalAddConvenioContinuo(this)"><i class="mdi mdi-clock"></i> Convênios</button>
                        </div>

                        <div class="modal inmodal" id="modal_id_0_{{$s->value}}_{{$especialidadeInstituicao->especialidade->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">

                                    <div class="modal-body" style="text-align: center;">
                                        <h2>Convênios</h2>
                                        <p style="display: block;">Convênios:</p>

                                        <div class="form-group hidable @if($errors->has('convenio_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                                            <label for="convenio_id[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">Convenio</label>
                                            <select class="form-control selectfild2 selectAll" name="convenio_id[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}][]" style="width: 100%" @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif multiple >
                                                <option value="0">Todos</option>

                                                @foreach ($convenios as $item)
                                                <option value="{{$item->id}}"
                                                    @if ($itemAgenda)
                                                        @foreach ($itemAgenda->convenios as $value)
                                                            @if ($item->id == $value->id)
                                                                selected
                                                            @endif
                                                        @endforeach
                                                    @endif>
                                                    {{$item->nome}}
                                                </option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('convenio_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                                <small class="form-text text-danger">{{ $errors->first('convenio_id') }}</small>
                                            @endif
                                        </div>

                                        <div class='mt-4' >
                                            <button data-dismiss="modal" id="AddConvenio" class="btn btn-success confirm" tabindex="1" style="display: inline-block;">Selecionar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-sm-3 select2Dia" style="padding-top: 30px;">
                            <button type="button" class="btn btn-info waves-effect waves-light m-r-10" style="margin-bottom: 15px;" data-id="modal_id_0_obs_{{$s->value}}_{{$especialidadeInstituicao->especialidade->id}}" onclick="modalAddConvenioContinuo(this)"><i class="mdi mdi-clock"></i> Obs</button>
                        </div>

                        <div class="modal inmodal modalObs" id="modal_id_0_obs_{{$s->value}}_{{$especialidadeInstituicao->especialidade->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">

                                    <div class="modal-body" style="text-align: center;">
                                        <h2>Obs</h2>

                                        <div class="form-group hidable @if($errors->has('obs.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                                            <label for="obs[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">Obs</label>
                                            <textarea class="form-control obs" name="obs[0][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" style="width: 100%" @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif>{{(!empty($itemAgenda->obs)) ? $itemAgenda->obs : ''}}</textarea>
                                            @if($errors->has('obs.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                                <small class="form-text text-danger">{{ $errors->first('obs') }}</small>
                                            @endif
                                        </div>

                                        <div class='mt-4' >
                                            <button data-dismiss="modal" id="AddObs" class="btn btn-success confirm" tabindex="1" style="display: inline-block;">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- FOREACH --}}
                    @foreach ($agenda as $key => $itemAgenda)
                        <div class='row @if($itemAgenda || old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) panel @else no-panel @endif'>
                            <div class="form-group col-sm-1 col-xs-12" style="text-align:center">
                                <label for="checkbox[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >{{$s->nome}}</label>
                                <div >
                                        <input type="checkbox" class='form-control checkbox' @if($itemAgenda || old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) checked @endif value="{{$especialidadeInstituicao->especialidade->id}}" id='checkbox[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]' name="checkbox[{{$key}}][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >
                                </div>
                            </div>

                            <div class="form-group col-sm-2 hidable  @if($errors->has('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                                <label for="inicio[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >Horário de início</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif  type="text" class="form-control" value="@if(old('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) {{old('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)}} @elseif($itemAgenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$itemAgenda->hora_inicio)->format('H:i')}} @else 13:00 @endif" id="inicio[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="inicio[{{$key}}][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                                @if($errors->has('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                    <div class="form-control-feedback">{{ $errors->first('inicio.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id) }}</div>
                                @endif
                                {{-- <input class="form-control clockpicker" type="time" value="13:00:00" id="{{$especialidadeInstituicao->especialidade->id}}-{{$s->value}}-inicio"> --}}
                            </div>
                            <div class="form-group @if($errors->has('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif col-sm-2 hidable ">
                                <label for="intervalo[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >Horário do intervalo</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif  type="text" class="form-control" value="@if(old('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) {{old('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)}} @elseif($itemAgenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$itemAgenda->hora_intervalo)->format('H:i')}} @else 15:45 @endif" id="intervalo[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="intervalo[{{$key}}][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                                @if($errors->has('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                    <div class="form-control-feedback">{{ $errors->first('intervalo.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id) }}</div>
                                @endif
                                {{-- <input class="form-control clockpicker" type="time" value="15:45:00" id="{{$especialidadeInstituicao->especialidade->id}}-{{$s->value}}-intervalo"> --}}
                            </div>
                            <div class="form-group col-sm-2 hidable @if($errors->has('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif ">
                                <label for="duracao[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >duracao do intervalo</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif type="text" class="form-control" value="@if(old('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) {{old('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)}} @elseif($itemAgenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$itemAgenda->duracao_intervalo)->format('H:i')}} @else 00:15 @endif" id="duracao[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="duracao[{{$key}}][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                                @if($errors->has('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                    <div class="form-control-feedback">{{ $errors->first('duracao.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id) }}</div>
                                @endif
                                {{-- <input class="form-control clockpicker" type="time" value="00:15:00" id="{{$especialidadeInstituicao->especialidade->id}}-{{$s->value}}-duracao-intervalo"> --}}
                            </div>
                            <div class="form-group col-sm-2 hidable @if($errors->has('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif ">
                                <label for="termino[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >Horário de termino</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif type="text" class="form-control" value="@if(old('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) {{old('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)}} @elseif($itemAgenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$itemAgenda->hora_fim)->format('H:i')}} @else 18:00 @endif" id="termino[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" name="termino[{{$key}}][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                                @if($errors->has('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                    <div class="form-control-feedback">{{ $errors->first('termino.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id) }}</div>
                                @endif
                                {{-- <input class="form-control clockpicker" type="time" value="18:00:00" id="{{$especialidadeInstituicao->especialidade->id}}-{{$s->value}}-termino"> --}}
                            </div>
                            <div class="form-group col-sm-3 hidable @if($errors->has('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif ">
                                <label for="atendimento[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" >Duração do Atendimento</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif  type="text" class="form-control" value="@if(old('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) {{old('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)}} @elseif($itemAgenda) {{\Carbon\Carbon::createFromFormat('H:i:s',$itemAgenda->duracao_atendimento)->format('H:i')}} @else 00:45 @endif" name="atendimento[{{$key}}][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" id="atendimento[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                                @if($errors->has('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                    <div class="form-control-feedback">{{ $errors->first('atendimento.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id) }}</div>
                                @endif
                                {{-- <input class="form-control clockpicker" type="time" value="00:45:00" id="{{$especialidadeInstituicao->especialidade->id}}-{{$s->value}}-atendimento"> --}}
                            </div>

                            <div class="form-group col-sm-1 col-xs-12" style="text-align:center"></div>

                            <div class="form-group col-sm-2 col-xs-2 hidable @if($errors->has('setor_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                                <label for="setor_id[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">Setores</label>
                                <select class="form-control selectfild2" name="setor_id_agenda[{{$key}}][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" style="width: 100%" @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif>
                                    <option value="">Selecione um setor</option>
                                    @foreach ($setores as $setor)
                                        <option value="{{ $setor->id }}"
                                            @if ($itemAgenda)
                                                @if ($setor->id == old("setor_id.".$s->value.'.'.$especialidadeInstituicao->especialidade->id, $itemAgenda->setor_id))
                                                    selected
                                                @endif
                                            @else
                                                @if ($setor->id == old("setor_id.".$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                                    selected
                                                @endif
                                            @endif
                                        >{{ $setor->descricao }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('setor_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                    <small class="form-text text-danger">{{ $errors->first('setor_id') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-sm-2 col-xs-2 hidable @if($errors->has('faixa_etaria.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                                <label for="faixa_etaria[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">Faixa etária</label>
                                <select class="form-control selectfild2" name="faixa_etaria_agenda[{{$key}}][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" style="width: 100%" @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled  @endif>
                                    <option value="">Selecione uma faixa</option>
                                    @foreach (App\InstituicoesAgenda::getFaixaEtaria() as $item)
                                        <option value="{{$item}}"  @if ($itemAgenda)
                                        @if ($item == old("faixa_etaria.".$s->value.'.'.$especialidadeInstituicao->especialidade->id, $itemAgenda->faixa_etaria))
                                                selected
                                            @endif
                                        @else
                                            @if ($item == old("faixa_etaria.".$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                                selected
                                            @endif
                                        @endif>{{App\InstituicoesAgenda::getFaixaEtariaTexto($item)}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('faixa_etaria.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                    <small class="form-text text-danger">{{ $errors->first('faixa_etaria') }}</small>
                                @endif
                            </div>

                            <div class="form-group col-sm-4 select2Dia" style="padding-top: 30px;">
                                <button type="button" class="btn btn-info waves-effect waves-light m-r-10" style="margin-bottom: 15px;" data-id="modal_id_{{$key}}_{{$s->value}}_{{$especialidadeInstituicao->especialidade->id}}" onclick="modalAddConvenioContinuo(this)"><i class="mdi mdi-clock"></i> Convênios</button>
                            </div>
                            <div class="modal inmodal" id="modal_id_{{$key}}_{{$s->value}}_{{$especialidadeInstituicao->especialidade->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">

                                        <div class="modal-body" style="text-align: center;">
                                            <h2>Convênios</h2>
                                            <p style="display: block;">Convênios:</p>

                                            <div class="form-group hidable @if($errors->has('convenio_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                                                <label for="convenio_id[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">Convenio</label>
                                                <select class="form-control selectfild2" name="convenio_id[{{$key}}][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}][]" style="width: 100%" @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif multiple>

                                                    @foreach ($convenios as $item)
                                                    <option value="{{$item->id}}"
                                                        @if ($itemAgenda)
                                                            @foreach ($itemAgenda->convenios as $value)
                                                                @if ($item->id == $value->id)
                                                                    selected
                                                                @endif
                                                            @endforeach
                                                        @endif>
                                                        {{$item->nome}}
                                                    </option>
                                                @endforeach
                                                </select>
                                                @if($errors->has('convenio_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                                    <small class="form-text text-danger">{{ $errors->first('convenio_id') }}</small>
                                                @endif
                                            </div>
                                            <div class='mt-4' >
                                                <button data-dismiss="modal" id="AddConvenio" class="btn btn-success confirm" tabindex="1" style="display: inline-block;">Selecionar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-3 select2Dia" style="padding-top: 30px;">
                                <button type="button" class="btn btn-info waves-effect waves-light m-r-10" style="margin-bottom: 15px;" data-id="modal_id_0_obs_{{$key}}_{{$s->value}}_{{$especialidadeInstituicao->especialidade->id}}" onclick="modalAddConvenioContinuo(this)"><i class="mdi mdi-clock"></i> Obs</button>
                            </div>

                            <div class="modal inmodal modalObs" id="modal_id_0_obs_{{$key}}_{{$s->value}}_{{$especialidadeInstituicao->especialidade->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">

                                        <div class="modal-body" style="text-align: center;">
                                            <h2>Obs</h2>

                                            <div class="form-group hidable @if($errors->has('obs.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                                                <label for="obs[{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]">Obs</label>
                                                <textarea class="form-control obs" name="obs[{{$key}}][{{$s->value}}][{{$especialidadeInstituicao->especialidade->id}}]" style="width: 100%" @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) disabled @endif>{{(!empty($itemAgenda->obs)) ? $itemAgenda->obs : ''}}</textarea>
                                                @if($errors->has('obs.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                                    <small class="form-text text-danger">{{ $errors->first('obs') }}</small>
                                                @endif
                                            </div>

                                            <div class='mt-4' >
                                                <button data-dismiss="modal" id="AddObs" class="btn btn-success confirm" tabindex="1" style="display: inline-block;">Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                </div>
            @endforeach



            <div class="unicos">
                <div class="form-group" >
                Adicionar data especifica:
                    <span alt="default" class="add fas fa-plus-circle">
                        <a class="mytooltip" href="javascript:void(0)">
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar data especifica. Obs: Datas especificas irão sobrepor a agenda semanal"></i>
                        </a>
                    </span>
                    <p><small style="color: red">**Confira se não existe agendamento antes de editar uma data especifica</small></p>
                </div>


                <div class="panel">
                    <div class="row ">
                        <div class=" col-md-3">
                            <div especialidade="{{$especialidadeInstituicao->especialidade->id}}" class="datepicker_vue"></div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">

                                <input type="text" class="form-control"  v-model='search' placeholder="Pesquisar data">
                            </div>
                            <div  class="scrollabe">
                            <div style="text-align: center;" v-for="date in filtered{{$especialidadeInstituicao->especialidade->id}}" :key='date.date' >
                                    <button @click="selectDate(date)" style='min-width: 140px;margin:5px;' type="button" :class="date.selected?'btn-success':'btn-primary'" class="btn waves-effect waves-light m-r-10"><i class="mdi" :class="date.selected?'mdi-minus':'mdi-plus'"></i> @{{date.date}}</button>
                                    <button @click="removeDateEspecialista(date, date.index)" style='margin:5px;' type="button" class="btn waves-effect waves-light m-r-10 btn-danger"><i class="mdi mdi-calendar-remove"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-7" v-if="filtered{{$especialidadeInstituicao->especialidade->id}}.find(o => o.selected==true && o.especialidade=='{{$especialidadeInstituicao->especialidade->id}}')">
                            <div class="form-group col-sm-4">
                                <label >Horário de início</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input type="text"  class="form-control" v-model ="filtered{{$especialidadeInstituicao->especialidade->id}}.find(o => o.selected==true ).hora_inicio">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label >Horário do intervalo</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input type="text"  class="form-control" v-model ="{{'especialidade_'.$especialidadeInstituicao->especialidade->id}}.find(o => o.selected==true ).hora_intervalo">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label >Duração do intervalo</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input type="text"  class="form-control" v-model ="{{'especialidade_'.$especialidadeInstituicao->especialidade->id}}.find(o => o.selected==true ).duracao_intervalo">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label >Horário de termino</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input type="text" class="form-control" v-model ="{{'especialidade_'.$especialidadeInstituicao->especialidade->id}}.find(o => o.selected==true ).hora_fim">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label >Duração do Atendimento</label>
                                <div class="input-group clockpicker" data-autoclose="true">
                                    <input type="text" class="form-control" v-model ="{{'especialidade_'.$especialidadeInstituicao->especialidade->id}}.find(o => o.selected==true ).duracao_atendimento">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-4 select2Dia">
                                <label >Setores</label>
                                <select class="form-control selectfild2-unicos" style="width: 100%" v-model ="{{'especialidade_'.$especialidadeInstituicao->especialidade->id}}.find(o => o.selected==true ).setor_id">
                                    @foreach ($setores as $setor)
                                        <option value="{{ $setor->id }}"
                                            {{-- @if ($setor->id == old("setor_id"))
                                                selected
                                            @endif --}}
                                        >{{ $setor->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-4 select2Dia">
                                <label >Faixa Etária</label>
                                <select class="form-control selectfild2-unicos" style="width: 100%" v-model ="{{'especialidade_'.$especialidadeInstituicao->especialidade->id}}.find(o => o.selected==true ).faixa_etaria">
                                    @foreach (App\InstituicoesAgenda::getFaixaEtaria() as $item)
                                        <option value="{{ $item }}"
                                            {{-- @if ($item == old("faixa_etaria"))
                                                selected
                                            @endif --}}
                                        >{{ App\InstituicoesAgenda::getFaixaEtariaTexto($item) }}</option>
                                    @endforeach
                                </select>
                            </div>


                            {{-- <div class="form-group col-sm-4 select2Dia">
                                <label >Convênio</label>
                                <select class="form-control selectfild2-unicos convenioUnicoVal" style="width: 100%" v-model ="{{'especialidade_'.$especialidadeInstituicao->especialidade->id}}.find(o => o.selected==true ).convenio_id_unico" multiple>
                                    @foreach ($convenios as $item)
                                        <option value="{{ $item->id }}">{{$item->nome}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group col-sm-4 select2Dia" style="padding-top: 30px;">
                                <button type="button" class="btn btn-info waves-effect waves-light m-r-10" style="margin-bottom: 15px;" data-id="{{$especialidadeInstituicao->especialidade->id}}" onclick="addConvenio(this)"><i class="mdi mdi-clock"></i> Convênios</button>
                            </div>
                            <div class="modal inmodal" id="modalAddConvenio" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">

                                        <div class="modal-body" style="text-align: center;">
                                            <h2>Convênios</h2>
                                            <p style="display: block;">Convênios:</p>
                                            <select class="form-control selectfild2-unicos convenioUnicoVal" style="width: 100%" v-model ="{{'especialidade_'.$especialidadeInstituicao->especialidade->id}}.find(o => (o.selected==true) ).convenio_id_unico" onchange="selectAll(this)" multiple>
                                                <option value="0">Todos</option>
                                                @foreach ($convenios as $item)
                                                    <option value="{{ $item->id }}">{{$item->nome}}</option>
                                                @endforeach
                                            </select>
                                            <div class='mt-4' >
                                                <button data-dismiss="modal" id="AddConvenio" class="btn btn-success confirm" tabindex="1" style="display: inline-block;">Selecionar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-4 select2Dia" style="padding-top: 30px;">
                                <button type="button" class="btn btn-info waves-effect waves-light m-r-10" style="margin-bottom: 15px;" data-id="{{$especialidadeInstituicao->especialidade->id}}" onclick="addObs(this)"><i class="mdi mdi-clock"></i> Obs</button>
                            </div>
                            <div class="modal inmodal modalObs" id="modalAddObs" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">

                                        <div class="modal-body" style="text-align: center;">
                                            <h2>Obs</h2>
                                            <p style="display: block;">Obs:</p>
                                            <textarea class="form-control obsUnicoVal obs" style="width: 100%" v-model ="{{'especialidade_'.$especialidadeInstituicao->especialidade->id}}.find(o => o.selected==true ).obs_unico" ></textarea>
                                            <div class='mt-4' >
                                                <button data-dismiss="modal" id="AddObs" class="btn btn-success confirm" tabindex="1" style="display: inline-block;">Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                        {{-- <input  type="text" :alue="dates.find(o => o.selected==true && o.especialidade=='{{$especialidadeInstituicao->especialidade->id}}').date"> --}}
                        {{-- @{{dates.find(o => o.selected==true && o.especialidade=="{{$especialidadeInstituicao->especialidade->id}}")}} --}}

                    </div>
                </div>

            </div>

        </div>
    </div>
    @endforeach

    <div class="card">
        <div class="card-body">

                <div class="form-group text-right">
                        <a href="{{ route('instituicao.prestadores.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                        <input type='hidden' id='continue' name="continue" value="1" disabled >
                        <button type="submit" onclick="atvContinue()" class="btn btn-primary waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar e ir para convênios</button>
                </div>
        </div>
    </div>

    </form>

    <div class="modal inmodal" id="modalAddDiaSemana" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body" style="text-align: center;">
                    <h2>Informe o dia da semana!</h2>
                    <p style="display: block;">Dia da semana:</p>
                    <select class="form-control selectfild2" name="dia_semana_add" id="dia_semana_add" style="width: 100%">
                        @php
                            $semanaModal=[

                                    'domingo'=>(object)[
                                        'nome' => 'Domingo',
                                        'value' => 'domingo'
                                    ],
                                    'segunda'=>(object)[
                                        'nome' => 'Segunda',
                                        'value' => 'segunda'
                                    ],
                                    'terca'=>(object)[
                                        'nome' => 'Terça',
                                        'value' => 'terca'
                                    ],
                                    'quarta'=>(object)[
                                        'nome' => 'Quarta',
                                        'value' => 'quarta'
                                    ],
                                    'quinta'=>(object)[
                                        'nome' => 'Quinta',
                                        'value' => 'quinta'
                                    ],
                                    'sexta'=>(object)[
                                        'nome' => 'Sexta',
                                        'value' => 'sexta'
                                    ],
                                    'sabado'=>(object)[
                                        'nome' => 'Sábado',
                                        'value' => 'sabado'
                                    ],

                            ];
                        @endphp
                        @foreach ($semanaModal as $item)
                            <option value="{{$item->value}}">{{$item->nome}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="especialidade_id_add" id="especialidade_id_add">
                    <div class='mt-4' >
                        <button data-dismiss="modal" class="btn btn-secondary cancel" tabindex="2" style="display: inline-block;">Fechar</button>
                        <button data-dismiss="modal" class="btn btn-success confirm" tabindex="1" style="display: inline-block;">Adicionar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.4/vue.js"></script>
<script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>
<script src="{{ asset('material/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

<script src="{{ asset('material/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>

    <script>
        var setor = 0;
        var totalUnicos = 0;

        $(document).ready(function() {
            if("{{count($setores)}}" > 0){
                setor = `<?php
                    if(count($setores) > 0){
                        echo $setores[0]->id;
                    }
                ?>`
            }
        })

        function atvContinue(){
            $('#continue').prop('disabled', false);
        }

        function addHorarioSemana(e)
        {
            var especialdiadeId = $(e).attr('data-id');
            $("#modalAddDiaSemana").modal('show');
            $("#modalAddDiaSemana").find('#especialidade_id_add').val(especialdiadeId);
        }

        function addConvenio(e)
        {
            var especialdiadeId = $(e).attr('data-id');
            $("#modalAddConvenio").modal('show');
            $("#modalAddConvenio").find('#especialidade_id_add').val(especialdiadeId);
        }

        function addObs(e)
        {
            var especialdiadeId = $(e).attr('data-id');
            $("#modalAddObs").modal('show');
            $("#modalAddObs").find('#especialidade_id_add').val(especialdiadeId);
        }

        function modalAddConvenioContinuo(e)
        {
            var modalId = $(e).attr('data-id');
            $("#"+modalId).modal('show');
        }


        $("#modalAddDiaSemana").on('click', '.confirm', function(){
            var dia_semana_add = $("#dia_semana_add option:selected").val();
            var dia_semana_texto = $("#dia_semana_add option:selected").text();
            var idEspecialidade = $("#especialidade_id_add").val();

            var quantidadePanel = $(".semana_"+dia_semana_add+"_"+idEspecialidade).find(".panel").length
            var quantidadeNoPanel = $(".semana_"+dia_semana_add+"_"+idEspecialidade).find(".no-panel").length

            var quantidadeTotal = quantidadeNoPanel + quantidadePanel;

            var html = `<div class='row panel'>
                        <div class="form-group col-sm-1 col-xs-12" style="text-align:center">
                            <label for="checkbox[${dia_semana_add}][${idEspecialidade}]" >${dia_semana_texto}</label>
                            <div >
                                <input type="checkbox" class='form-control checkbox' checked value="${idEspecialidade}" id='checkbox[${dia_semana_add}][${idEspecialidade}]' name="checkbox[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}]" >
                            </div>
                        </div>

                        <div class="form-group col-sm-2 hidable">
                            <label for="inicio[${dia_semana_add}][${idEspecialidade}]" >Horário de início</label>
                            <div class="input-group clockpicker" data-autoclose="true">
                                <input type="text" class="form-control" value="13:00" id="inicio[${dia_semana_add}][${idEspecialidade}]" readonly="readonly" name="inicio[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}]">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-2  hidable">
                            <label for="intervalo[${dia_semana_add}][${idEspecialidade}]" >Horário do intervalo</label>
                            <div class="input-group clockpicker" data-autoclose="true">
                                <input  type="text" class="form-control" value="15:45" id="intervalo[${dia_semana_add}][${idEspecialidade}]" readonly="readonly" name="intervalo[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}]">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-2 hidable">
                            <label for="duracao[${dia_semana_add}][${idEspecialidade}]" >duracao do intervalo</label>
                            <div class="input-group clockpicker" data-autoclose="true">
                                <input type="text" class="form-control" value="00:15" id="duracao[${dia_semana_add}][${idEspecialidade}]" readonly="readonly" name="duracao[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}]">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-2 hidable">
                            <label for="termino[${dia_semana_add}][${idEspecialidade}]" >Horário de termino</label>
                            <div class="input-group clockpicker" data-autoclose="true">
                                <input type="text" class="form-control" value="18:00" id="termino[${dia_semana_add}][${idEspecialidade}]" readonly="readonly" name="termino[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}]">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-3 hidable">
                            <label for="atendimento[${dia_semana_add}][${idEspecialidade}]" >Duração do Atendimento</label>
                            <div class="input-group clockpicker" data-autoclose="true">
                                <input type="text" class="form-control" value="00:45" readonly="readonly" name="atendimento[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}]" id="atendimento[${dia_semana_add}][${idEspecialidade}]">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-clock"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-sm-1 col-xs-12" style="text-align:center"></div>

                        <div class="form-group col-sm-2 col-xs-2 hidable">
                            <label for="setor_id[${dia_semana_add}][${idEspecialidade}]">Setores</label>
                            <select class="form-control selectfild2" name="setor_id_agenda[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}]" readonly="readonly" style="width: 100%">
                                <option value="">Selecione um setor</option>
                                @foreach ($setores as $setor)
                                    <option value="{{ $setor->id }}">{{ $setor->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-2 col-xs-2 hidable">
                            <label for="faixa_etaria[${dia_semana_add}][${idEspecialidade}]">Faixa etária</label>
                            <select class="form-control selectfild2" name="faixa_etaria_agenda[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}]" readonly="readonly" style="width: 100%">
                                <option value="">Selecione uma faixa</option>
                                @foreach (App\InstituicoesAgenda::getFaixaEtaria() as $item)
                                    <option value="{{$item}}">{{App\InstituicoesAgenda::getFaixaEtariaTexto($item)}}</option>
                                @endforeach
                            </select>
                        </div>




                        <div class="form-group col-sm-4 select2Dia" style="padding-top: 30px;">
                            <button type="button" class="btn btn-info waves-effect waves-light m-r-10" style="margin-bottom: 15px;" data-id="modal_id_${quantidadeTotal}_${dia_semana_add}_${idEspecialidade}" onclick="modalAddConvenioContinuo(this)"><i class="mdi mdi-clock"></i> Convênios</button>
                        </div>
                            <div class="modal inmodal" id="modal_id_${quantidadeTotal}_${dia_semana_add}_${idEspecialidade}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">

                                        <div class="modal-body" style="text-align: center;">
                                            <h2>Convênios</h2>
                                            <p style="display: block;">Convênios:</p>

                                            <div class="form-group hidable @if($errors->has('convenio_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id)) has-danger @endif">
                                                <label for="convenio_id[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}][]">Convenio</label>
                                                <select class="form-control selectfild2" name="convenio_id[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}][]" style="width: 100%" @if(!$itemAgenda && !old('checkbox.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))   @else readonly @endif multiple onchange="selectAll(this)">
                                                    <option value="0">Todos</option>
                                                    @foreach ($convenios as $item)
                                                    <option value="{{$item->id}}"
                                                        @if ($itemAgenda)
                                                            @foreach ($itemAgenda->convenios as $value)
                                                                @if ($item->id == $value->id)
                                                                    selected
                                                                @endif
                                                            @endforeach
                                                        @endif>
                                                        {{$item->nome}}
                                                    </option>
                                                @endforeach
                                                </select>
                                                @if($errors->has('convenio_id.'.$s->value.'.'.$especialidadeInstituicao->especialidade->id))
                                                    <small class="form-text text-danger">{{ $errors->first('convenio_id') }}</small>
                                                @endif
                                            </div>
                                            <div class='mt-4' >
                                                <button data-dismiss="modal" id="AddConvenio" class="btn btn-success confirm" tabindex="1" style="display: inline-block;">Selecionar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-3 select2Dia" style="padding-top: 30px;">
                                <button type="button" class="btn btn-info waves-effect waves-light m-r-10" style="margin-bottom: 15px;" data-id="modal_id_0_obs_${quantidadeTotal}_${dia_semana_add}_${idEspecialidade}" onclick="modalAddConvenioContinuo(this)"><i class="mdi mdi-clock"></i> Obs</button>
                            </div>

                            <div class="modal inmodal modalObs" id="modal_id_0_obs_${quantidadeTotal}_${dia_semana_add}_${idEspecialidade}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">

                                        <div class="modal-body" style="text-align: center;">
                                            <h2>Obs</h2>

                                            <div class="form-group hidable">
                                                <label for="obs[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}]">Obs</label>
                                                <textarea class="form-control obs" name="obs[${quantidadeTotal}][${dia_semana_add}][${idEspecialidade}]" style="width: 100%"></textarea>
                                            </div>

                                            <div class='mt-4' >
                                                <button data-dismiss="modal" id="AddObs" class="btn btn-success confirm" tabindex="1" style="display: inline-block;">Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>`;

            $(".semana_"+dia_semana_add+"_"+idEspecialidade).append(html)

            $(".selectfild2").select2()

            $('.clockpicker').clockpicker().find('input').change(function(){
                $(this).attr('value', this.value);
            });

            $('.checkbox').iCheck({
                checkboxClass: 'icheckbox_square',
                radioClass: 'iradio_square',
                increaseArea: '90%'
            }).on('ifChecked', function (event) {
                $(this).closest('.row').removeClass('no-panel').addClass('panel')
                // $(this).closest('.row').find('.hidable').removeClass('hidden')
                $(this).closest('.row').find('.hidable').find('input[disabled="disabled"]').each(function () {
                    $(this).attr("disabled", false);
                    $(this).attr("readonly", false);
                })
                $(this).closest('.row').find('.hidable').find('select').each(function () {
                    $(this).prop("disabled", false);
                    $(this).prop("readonly", false);
                })

                $(this).closest('.row').find('.hidable').find('.obs', function () {

                    $(this).prop("disabled", false);
                    $(this).prop("readonly", false);
                })
                $(this).closest('.row').find('.hidable').removeClass('hidden')
                event.currentTarget.setAttribute("checked", "checked");
            }).on('ifUnchecked', function (event) {
                $(this).closest('.panel').removeClass('panel').addClass('no-panel')
                $(this).closest('.row').find('.hidable').find('input[readonly="readonly"]').each(function () {
                    $(this).attr("disabled", "disabled");
                    $(this).attr("readonly", false);
                })
                $(this).closest('.row').find('.hidable').find('select').each(function () {
                    $(this).prop("disabled", true);
                    $(this).prop("readonly", false);
                })
                $(this).closest('.row').find('.hidable').find('.obs').each(function () {
                    $(this).prop("disabled", true);
                    $(this).prop("readonly", false);
                })
                // $(this).closest('.row').find('.hidable').addClass('hidden')
                event.currentTarget.removeAttribute("checked");
            }).on('ifChanged', function (event) {
            });

        })


        var vue = new Vue({
            el: '#form',
            data: {
                @foreach($prestador->especialidadeInstituicao()->pluck('especialidade_id') as $especialidade)
                    @php
                        $InstituicoesPrestadores = $prestador->especialidadeInstituicao()->where('especialidade_id',$especialidade)->first();
                        $agenda = $InstituicoesPrestadores->agenda()->whereNotNull('dias_unicos')->first();
                    @endphp
                    {{'especialidade_'.$especialidade}} : {!!$agenda?($agenda->dias_unicos): '[]'!!},
                @endforeach
                search: ''
            },
            mounted(){


                var self = this;
                $(".datepicker_vue").datepicker({
                    closeText: 'Fechar',
                    prevText: '<Anterior',
                    nextText: 'Próximo>',
                    currentText: 'Hoje',
                    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
                    'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
                    'Jul','Ago','Set','Out','Nov','Dez'],
                    dayNames: ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
                    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
                    dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
                    weekHeader: 'Sm',
                    dateFormat: 'dd/mm/yy',
                    firstDay: 0,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: '',
                    minDate: 0,
                    onSelect: function (dateText, inst) {
                        // if(!(moment(dateText,"DD-MM-YYYY").isBefore(moment(), "day")))
                        // {
                            self.addDate(dateText,this.getAttribute('especialidade'));
                            // self.addOrRemoveDate(dateText,this.getAttribute('especialidade'));
                        // }
                    },
                    beforeShowDay: function (date) {
                        var year = date.getFullYear();
                        var month = self.padNumber(date.getMonth() + 1);
                        var day = self.padNumber(date.getDate());
                        var dateString = day + "/" + month + "/" + year;
                        var gotDate = self.findDate(dateString, this.getAttribute('especialidade'));
                        if (gotDate >= 0) {
                            if(self[['especialidade_'+this.getAttribute('especialidade')]][gotDate].selected==true){
                                return [true, "ui-state-highlight selected"];
                            }
                            return [true, "ui-state-highlight"];
                        }
                        return [true, ""];
                    }
                })

            },
            computed:{
                @foreach($prestador->especialidadeInstituicao()->pluck('especialidade_id') as $especialidade)
                    filtered{{$especialidade}}(){

                        var self = this;
                        return this[['{{"especialidade_".$especialidade}}']].filter(function(item){
                            return item.date.substring(0, 5).includes(self.search)
                        }).filter(function(item){
                            return !(moment(item.date,"DD-MM-YYYY").isBefore(moment(), "day"))
                        })
                        .sort(function (a, b) {
                            if (moment(a.date,"DD-MM-YYYY").isAfter(moment(b.date,"DD-MM-YYYY"), "day")) {
                                return 1;
                            }
                            return -1;
                        })
                    },
                @endforeach
            },
            methods: {

                selectDate(date){
                    // setTimeout(() => {
                    //     $(".panel").find(".selectfild2-unicos").select2()
                    // }, 500);
                    selecionado = this[['especialidade_'+date.especialidade]].find(o => { return o.selected == true });
                    if(selecionado){
                        selecionado.selected = false
                        $('.datepicker_vue[especialidade="'+date.especialidade+'"]').datepicker("refresh");
                        $(".panel").find(".selectfild2-unicos").select2()
                    }
                    if(selecionado!=date){

                        date.selected=true
                        $('.datepicker_vue[especialidade="'+date.especialidade+'"]').datepicker("setDate", date.date);
                        setTimeout(() => {
                            $('.clockpicker').clockpicker()
                            .find('input').change(function(e){
                                this.dispatchEvent(new Event('input', { target: e.target }));
                            })
                            $(".panel").find(".selectfild2-unicos").select2().on('select2:select', function(e){
                                this.dispatchEvent(new Event('change', { target: e.target }));
                            });
                            $(".panel").on('change', ".convenioUnicoVal", function(e){
                                e.stopPropagation()
                                e.stopImmediatePropagation()
                                e.preventDefault();
                                this.dispatchEvent(new Event('change', { target: e.target }));
                            })
                        }, 0);
                    }
                },

                addOrRemoveDate(date, especialidade) {
                    var gotDate = this.findDate(date, especialidade);
                    if (gotDate >= 0)
                        this.removeDate(gotDate, especialidade);
                    else
                        this.addDate(date, especialidade);
                },
                findDate(date, especialidade, index = null){
                    if(index){
                        return this[['especialidade_'+especialidade]].findIndex( o => {return (o.date == date && o.index == index)})
                    }else{
                        return this[['especialidade_'+especialidade]].findIndex( o => {return o.date == date})
                    }
                },
                addDate(date, especialidade) {
                    
                    // if(this.findDate(date, especialidade) < 0){
                        totalUnicos ++;
                        var data_usa = date.split('/');
                        data_usa_nova = data_usa[2]+'-'+data_usa[1]+'-'+data_usa[0];
                        date_object = {
                            'date': date,
                            'data': data_usa_nova,
                            'especialidade': especialidade,
                            'selected': false,
                            'hora_inicio' : '13:00',
                            'hora_fim' : '18:00',
                            'hora_intervalo' : '15:45',
                            'duracao_intervalo' : '00:15',
                            'duracao_atendimento' : '00:45',
                            'setor_id' : setor,
                            'faixa_etaria' : 'todas',
                            'index': totalUnicos
                            }
                            console.log(date_object)
                        this[['especialidade_'+especialidade]].push(date_object)
                        this.$forceUpdate()
                        this.selectDate(date_object)
                    // }
                },

                removeDate(index, especialidade) {
                    this[['especialidade_'+especialidade]].splice(index, 1);
                    this.$forceUpdate()
                },
                removeDateEspecialista(date, index) {
                    var gotDate = this.findDate(date.date, date.especialidade, index);
                    this[['especialidade_'+date.especialidade]].splice(gotDate, 1);
                    this.$forceUpdate()
                },
                padNumber(number) {
                    var ret = new String(number);
                    if (ret.length == 1)
                        ret = "0" + ret;
                    return ret;
                },
                submit(){
                    var form = document.getElementById('form');
                    var formData = new FormData(form);

                    @foreach($prestador->especialidadeInstituicao()->pluck('especialidade_id') as $especialidade)
                        if(this[["{{'especialidade_'.$especialidade}}"]].find(o => { return o.selected == true })){
                            this[["{{'especialidade_'.$especialidade}}"]].find(o => { return o.selected == true }).selected = false;
                        }

                        formData.append("unicos[{{$especialidade}}]",JSON.stringify(this[["{{'especialidade_'.$especialidade}}"]]))
                    @endforeach
                    $('.datepicker_vue').datepicker("refresh");


                    $.ajax("{{ route('instituicao.prestadores.agenda.update', [$prestador]) }}", {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $.toast({
                                heading: response.title,
                                text: response.text,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: response.icon,
                                hideAfter: 3000,
                                stack: 10
                            });

                            if(response.continue){
                                window.location.href = response.route;
                            }


                        },
                        error: function (response) {

                            if(response.responseJSON.errors){
                                Object.keys(response.responseJSON.errors).forEach(function(key) {
                                    $.toast({
                                        heading: 'Erro',
                                        text: response.responseJSON.errors[key][0],
                                        position: 'top-right',
                                        loaderBg: '#ff6849',
                                        icon: 'error',
                                        hideAfter: 9000,
                                        stack: 10
                                    });

                                });
                            }

                        }
                    })
                    // var formData = new FormData($('#form')[0]);

                }

            }
        })

        $( document ).ready(function() {

            $('.clockpicker').clockpicker().find('input').change(function(){
                $(this).attr('value', this.value);
            });

            $('.checkbox').iCheck({
                checkboxClass: 'icheckbox_square',
                radioClass: 'iradio_square',
                increaseArea: '90%'
            }).on('ifChecked', function (event) {
                $(this).closest('.row').removeClass('no-panel').addClass('panel')
                // $(this).closest('.row').find('.hidable').removeClass('hidden')
                $(this).closest('.row').find('.hidable').find('input[disabled="disabled"]').each(function () {
                    $(this).attr("disabled", false);
                    $(this).attr("readonly", false);
                })
                $(this).closest('.row').find('.hidable').find('select').each(function () {
                    $(this).prop("disabled", false);
                    $(this).prop("readonly", false);
                })
                $(this).closest('.row').find('.hidable').find('.obs').each(function () {
                    $(this).prop("disabled", false);
                    $(this).prop("readonly", false);
                })
                $(this).closest('.row').find('.hidable').removeClass('hidden')
                event.currentTarget.setAttribute("checked", "checked");
            }).on('ifUnchecked', function (event) {
                $(this).closest('.panel').removeClass('panel').addClass('no-panel')
                $(this).closest('.row').find('.hidable').find('input[type="time"]').each(function () {
                    $(this).attr("disabled", "disabled");
                    $(this).attr("readonly", false);
                })
                $(this).closest('.row').find('.hidable').find('select').each(function () {
                    $(this).prop("disabled", true);
                    $(this).prop("readonly", false);
                })
                $(this).closest('.row').find('.hidable').find('textarea').each(function () {
                    $(this).prop("disabled", true);
                    $(this).prop("readonly", false);
                })
                // $(this).closest('.row').find('.hidable').addClass('hidden')
                event.currentTarget.removeAttribute("checked");
            }).on('ifChanged', function (event) {
            });

            $('.selectAll').on('change', function() {
                // Select(this.value, this);

                if($(this).val()[0] == 0){
                    $(this).find('option[value!="0"]').each(function(){
                        $(this).prop('selected', true);

                    });
                    // $(this)find('option[value*="0"]').prop('selected', false);
                    $(this).find('option[value="0"]').prop('selected', false);
                }

            })


        })

        function selectAll(el){
            if($(el).val()[0] == 0){
                $(el).find('option[value!="0"]').each(function(index, element){
                    $(element).prop('selected', true);
                });
                // $(el)find('option[value*="0"]').prop('selected', false);
                $(el).find('option[value="0"]').prop('selected', false);
                $(el).change();
            }
        }


    </script>

@endpush

@push('estilos')
<style>
        .form-control[readonly] {
            opacity: 1;
        }

        .form-control[readonly] {
            background-color: #fff;
        }

        .hidden{
            display:none;
        }

        .no-panel{
            background-color: #dbdbdc;
            padding: 10px 10px 0px 10px;
            margin-bottom: 10px;
                border: black 1px solid;
        }

		.panel{
			background-color: #d3e1fb;
            padding: 10px 10px 0px 10px;
            margin-bottom: 10px;
                border: black 1px solid;
		}

        td.ui-state-highlight.selected > a{
            background: inherit;
            border: inherit;
            color: #ffffff;
        }
        .ui-state-active, .ui-widget-content .ui-state-active{
            background: #1eacbe;
        }
        .ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
            border: 1px solid #1eacbe;
            background: #1eacbe;
        }

        .ui-datepicker-today > .ui-state-highlight{
            border: 1px solid #c5c5c5;
            background: #f6f6f6;
        }

        .ui-state-active,
        .ui-widget-content .ui-state-active,
        .ui-widget-header .ui-state-active,
        a.ui-button:active,
        .ui-button:active,
        .ui-button.ui-state-active:hover {
            border: 1px solid #cccccc;
            background: #f6f6f6;
            color: #454545;
        }
        </style>
        <style>


    .scrollabe {
        overflow-y: scroll;
        max-height: 235px;
        margin-bottom: 10px;
    }


    .scrollabe::-webkit-scrollbar {
        width: 7px;
    }

    .scrollabe::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .scrollabe::-webkit-scrollbar-thumb {
        background: #1E88E5;
        border-radius: 10px;
    }

    .scrollabe::-webkit-scrollbar-thumb:hover {
        background: #0F4473;
    }
</style>
@endpush

