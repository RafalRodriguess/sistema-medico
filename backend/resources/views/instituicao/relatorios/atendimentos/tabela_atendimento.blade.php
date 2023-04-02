    <div class="cabecalho" style="display: none;">
        <h4>Relatório de atendimenoto</h4>
    </div>    
    
    <table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
        <thead>
            <tr>
                <th >Cod Agenda</th>
                <th >Profissional/agenda</th>
                <th >Paciente</th>
                <th >Data</th>
                <th >Status</th>          
                <th >Motivo status</th>
                <th >Nota satisfação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agendamentos as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->instituicoesAgenda->prestadores->prestador->nome}}</td>
                    <td>{{$item->pessoa->nome}}</td>
                    <td>{{date('d/m/Y H:i', strtotime($item->data))}}</td>
                    <td>{{App\Agendamentos::status_para_texto($item->status)}}</td>
                    <td>{{$item->status_motivo}}</td>
                    <td>{{$item->resposta_pesquisa_satisfacao_whatsapp ? $item->resposta_pesquisa_satisfacao_whatsapp : '-'}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>