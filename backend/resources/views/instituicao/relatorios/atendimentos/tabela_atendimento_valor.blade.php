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
                <th >Convenios</th>
                <th >Procedimentos</th>
                <th >Valor pago</th>
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
                    <td>
                        @php
                            $convenios = [];                    
                            foreach ($item->agendamentoProcedimento as $procedimento){
                                $convenios[] = $procedimento->procedimentoInstituicaoConvenioTrashed->convenios->nome;
                            }
                        @endphp
                        {{implode("; ", array_unique($convenios))}}
                    </td>
                    <td>
                        @php
                            $procedimentos = [];                    
                            foreach ($item->agendamentoProcedimento as $procedimento){
                                $procedimentos[] = $procedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->descricao;
                            }
                        @endphp
                        {{implode("; ", array_unique($procedimentos))}}    
                    </td>
                    <td>R$ {{number_format($item->contaReceber->sum('valor_pago'), 2, ",", ".")}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>