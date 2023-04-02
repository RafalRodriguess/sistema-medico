<table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
    <thead>
        <tr>
            <th >Paciente</th>
            <th >Data Finalizado</th>
            <th >Avaliador</th>
            <th >Negociador</th>            
            <th >Procedimentos Realizados</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orcamentos as $item)
           <tr>
               <td>{{$item->paciente->nome}}</td>
               <td>{{$item->data_finalizado->format('d/m/Y')}}</td>
               <td>{{($item->avaliador) ? $item->avaliador->nome : '-'}}</td>
               <td>{{($item->negociador) ? $item->negociador->nome : '-'}}</td>
               <td>
                    @foreach ($item->itens as $key => $value)
                        @if ($key == 0)
                            {{$value->procedimentosItens->descricao}}
                        @else
                            <br> {{$value->procedimentosItens->descricao}}
                        @endif
                    @endforeach
               </td>
           </tr>
        @endforeach
    </tbody>
</table>