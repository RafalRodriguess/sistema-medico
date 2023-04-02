@foreach ($odontologicos as $item)
    <tr @if ($item->finalizado == 1)
        style="background: #02ff024d"
    @endif>
        <td style="text-center"><span class="label {{$item->status}}">{{$item->status}}</span></td>
        <td>{{$item->created_at->format('d/m/Y')}}</td>
        <td>
            <div class="button-group">
                <button type="button" class="btn btn-xs btn-secondary visualizar-orcamento" aria-haspopup="true" aria-expanded="false"
                    data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Visualizar orçamento">
                    <i class="fas fa-file-alt"></i>
                </button>
                
                @can('habilidade_instituicao_sessao', 'concluir_procedimento_odontologico')
                    @if ($item->finalizado == 0)
                        <button type="button" class="btn btn-xs btn-secondary concluir-procedimento-odontologico" aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Concluir procedimentos" @if ($item->status != 'aprovado')
                                disabled
                            @endif>
                            <i class="fas fa-check"></i>
                        </button>
                    @endif
                @endcan
            
                @can('habilidade_instituicao_sessao', 'cancelar_aprovado_orcamento_odontologico')
                    <button type="button" class="btn btn-xs btn-secondary cancelar-aprovacao-orcamento-odontologico" aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Cancelar aprovação do orcamento" @if ($item->status != 'aprovado')
                            disabled
                        @endif>
                        <i class="fas fa-ban"></i>
                    </button>
                @endcan
            
                @if ($item->status == 'aprovado')
                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" onclick="contratoOdontologico({{$item->id}})" data-original-title="Imprimir contrato">
                        <i class="fas fa-file-word"></i>
                    </button>
                @endif
            
                
                @can('habilidade_instituicao_sessao', 'editar_orcamento_odontologico')
                    <button type="button" class="btn btn-xs btn-secondary editar-orcamento-odontologico" aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Editar orçamento" @if ($item->status == 'aprovado')
                            disabled
                        @endif>
                        <i class="far fa-edit"></i>
                    </button>
                @endcan
            
                @can('habilidade_instituicao_sessao', 'excluir_orcamento_odontologico')
                    <button type="button" class="btn btn-xs btn-secondary excluir-orcamento-odontologico" aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-original-title="Excluir" @if ($item->status == 'aprovado')
                            disabled
                        @endif>
                        <i class="fas fa-trash-alt"></i>
                    </button>
                @endcan
            </div>
        </td>
    </tr>
@endforeach