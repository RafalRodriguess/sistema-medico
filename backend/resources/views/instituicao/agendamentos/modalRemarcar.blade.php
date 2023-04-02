

@if($agenda)
    @php
        $agendamentos_controle2 = clone $agendamentos;
    @endphp
    @for ($x = 0; $x < count($agenda); $x++)
        @for ( $i=\Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agenda[$x]['hora_inicio']); $i < \Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agenda[$x]['hora_intervalo']); $i->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agenda[$x]['duracao_atendimento'])) )
            @php $agendamentos_count = 0; @endphp
            @foreach ( $agendamentos_controle2 as $key => $agendamento)
                @if ( \Carbon\Carbon::parse($agendamento['data']) < $i || (\Carbon\Carbon::parse($agendamento['data']) >= $i &&  \Carbon\Carbon::parse($agendamento['data']) < \Carbon\Carbon::parse($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agenda[$x]['duracao_atendimento']))) )
                    @php unset($agendamentos_controle2[$key]);$agendamentos_count++; @endphp
                @endif
            @endforeach
            @if($agendamentos_count>0)
                <button  class="btn btn-secondary" disabled tabindex="1" style="display: inline-block;">{{$i->format('H:i')}}</button>
            @else
                <button data-dismiss="modal" class="btn btn-success remarcar_horario" data-id="{{$agenda[$x]['id']}}" data-horario="{{$i}}"  tabindex="1" style="display: inline-block;">{{$i->format('H:i')}}</button>

            @endif
        @endfor
        {{-- <button  class="btn btn-secondary" disabled tabindex="1" style="display: inline-block;">{{\Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agenda[$x]['hora_intervalo'])->format('H:i')}}</button> --}}
      
        @for ( $i=\Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agenda[$x]['hora_intervalo'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agenda[$x]['duracao_intervalo'])); $i < \Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agenda[$x]['hora_fim']); $i->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agenda[$x]['duracao_atendimento'])) )
            @php $agendamentos_count = 0; @endphp
            @foreach ( $agendamentos_controle2 as $key => $agendamento)
                @if ((\Carbon\Carbon::parse($agendamento['data']) > \Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agenda[$x]['hora_fim']) && \Carbon\Carbon::parse($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agenda[$x]['duracao_atendimento'])) > \Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agenda[$x]['hora_fim'])) || (\Carbon\Carbon::parse($agendamento['data']) >= $i &&  \Carbon\Carbon::parse($agendamento['data']) < \Carbon\Carbon::parse($i)->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agenda[$x]['duracao_atendimento']))) )
                    @php unset($agendamentos_controle2[$key]);$agendamentos_count++; @endphp
                @endif
            @endforeach
            @if($agendamentos_count>0)
                <button  class="btn btn-secondary" disabled tabindex="1" style="display: inline-block;">{{$i->format('H:i')}}</button>
            @else
                <button data-dismiss="modal" class="btn btn-success remarcar_horario" data-id="{{$agenda[$x]['id']}}" data-horario="{{$i}}"  tabindex="1" style="display: inline-block;">{{$i->format('H:i')}}</button>

            @endif
        @endfor
    @endfor
@else
<span style='font-size: 100px;' class="mdi mdi-calendar-remove"></span>
<p class="lead">Não existem horários disponíveis para atendimento para este dia!</p>
@endif
