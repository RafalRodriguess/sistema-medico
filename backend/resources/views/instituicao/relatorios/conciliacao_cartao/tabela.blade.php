    <table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
        <thead>
            <tr>
                <th>Profissional</th>
                <th>Paciente</th>
                <th>Cod Agenda / Orçamento</th>
                <th>Data vencimento</th>
                <th>Data pagamento</th>
                {{-- <th>Procedimento</th>
                <th>Convenio</th> --}}
                <th>Valor parcela</th>
                <th>Nº Parcela</th>
                <th>Liquido s/ taxa</th>
                <th>Código Aut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cartoes as $item)
                <tr>
                    <td>
                        @if(!empty($item->agendamentos))
                            {{$item->agendamentos->instituicoesAgenda->prestadores->prestador->nome}}
                        @elseif(!empty($item->odontologico->prestador))
                            {{$item->odontologico->prestador->nome}}
                        @endif
                    </td>
                    <td>{{!empty($item->paciente) ? $item->paciente->nome : ""}}</td>
                    <td>
                        @if(!empty($item->agendamento_id))
                            Agendamento: {{$item->agendamento_id}}
                        @elseif(!empty($item->odontologico_id))
                            Orçamento: {{$item->odontologico_id}}
                        @endif
                    </td>
                    <td>{{date('d/m/Y', strtotime($item->data_vencimento))}}</td>
                    <td>{{!empty($item->data_pago) ? date('d/m/Y', strtotime($item->data_pago)): ""}}</td>
                    <td>R$ {{number_format($item->valor_parcela, 2, ',','.')}}</td>
                    <td>{{$item->num_parcela}} / {{$item->num_parcelas}}</td>
                    <td>{{number_format($item->valor_parcela - $item->num_parcela, 2, ",", ".")}}</td>
                    <td>{{$item->cod_aut}}</td>                        
                </tr>
            @endforeach
        </tbody>
        
        <tfoot>
            <tr></tr>            
        </tfoot>
    </table>