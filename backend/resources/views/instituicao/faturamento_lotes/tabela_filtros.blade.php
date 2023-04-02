@if(!empty($agendamentos))

<thead>
    <tr >
        <th style="background: #b5b5d2;">
          
            <input  type="checkbox" id="check_agendamentos_all" class="filled-in" onclick="marca_all_agendamentos()" />
            <label for="check_agendamentos_all" style="top: 5px;"><label>
           
        </th>
        <th style="background: #b5b5d2;">Cod.</th>
        <th style="background: #b5b5d2;">Nome</th>
        <th style="background: #b5b5d2;">Atendimento</th>
        <th style="background: #b5b5d2;">Prestador</th>
        {{-- <th>Açoes</th> --}}
    </tr>
</thead>
<tbody>

    @foreach ($agendamentos as $agendamento)
    <tr>
        <td>
            {{-- <input type="checkbox" class="checks_agendamentos" id="agendamento_{{ $agendamento->id }}" name="agendamento_{{ $agendamento->id }}" value="{{ $agendamento->id }}" class="filled-in" /> --}}
            <div class="form-group">
                <input type="checkbox" class="checks_agendamentos" id="agendamento_{{ $agendamento->id }}" name="agendamento_{{ $agendamento->id }}" value="{{ $agendamento->id }}" class="filled-in" />
                <label for="agendamento_{{ $agendamento->id }}"> <label>
            </div>
        </td>
        <td>{{$agendamento->id}}</td>
        <td>{{$agendamento->pessoa->nome}}</td>
        <td>{{ date("d/m/Y", strtotime($agendamento->data))}}</td>
        <td>{{$agendamento->instituicoesAgenda->prestadores->prestador->nome}}</td>
        {{-- <td></td> --}}
    </tr>
    @endforeach
    
                                        
</tbody>
@else

Não Encontrado

@endif
