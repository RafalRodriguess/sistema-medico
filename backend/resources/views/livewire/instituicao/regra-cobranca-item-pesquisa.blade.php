<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
         <div class="row">
            <div class="col-md-5">
                <div class="form-group" wire:ignore> 
                    <select name="grupo" class="form-control selectfild2"  wire:model="grupo">
                    <option value="0">Todos</option>  
                    @foreach ($grupos as $item)
                        <option value="{{$item->id}}">{{$item->nome}}</option>
                    @endforeach
                    </select> 
                </div>
            </div> 
            <div class="col-md-5">
                <div class="form-group" wire:ignore> 
                    <select name="faturamento" class="form-control selectfild2"  wire:model="faturamento">
                    <option value="0">Todos</option>  
                    @foreach ($faturamentos as $item)
                        <option value="{{$item->id}}">{{$item->descricao}}</option>
                    @endforeach
                    </select> 
                </div>
            </div> 
             @can('habilidade_instituicao_sessao', 'cadastrar_regras_cobranca_itens')
                 <div class="col-md-2">
                     <div class="form-group" style="margin-bottom: 0px !important;">
                         <a href="{{ route('instituicao.regrasCobrancaItens.create', [$regra]) }}">
                         <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                         </a>
                     </div>
                 </div>
             @endcan
         </div>
     </form>
 
     <hr>
 
    <div class="table-responsive">
         <table class="tablesaw table-bordered table-hover table" >
             <thead>
                 <tr>
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Grupo Procedimento</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Faturamento</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Pago</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Base</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                 </tr>
             </thead>
             <tbody>
                 @foreach($itens as $item)
                     <tr>
                         <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                         
                         <td>{{ $item->grupoProcedimento->nome }}</td>
                         <td>{{ $item->faturamento->descricao }}</td>
                         <td>{{ $item->pago }}</td>
                         <td>{{ App\RegraCobrancaItem::baseTexto($item->base) }}</td>
                         <td>
                             
                             @can('habilidade_instituicao_sessao', 'editar_regras_cobranca_itens')
                                 <a href="{{ route('instituicao.regrasCobrancaItens.edit', [$regra, $item]) }}">
                                         <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                         data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                 <i class="ti-pencil-alt"></i>
                                         </button>
                                 </a>
                             @endcan
                             
                             @can('habilidade_instituicao_sessao', 'excluir_regras_cobranca_itens')
                                 <form action="{{ route('instituicao.regrasCobrancaItens.destroy', [$regra, $item]) }}" method="post" class="d-inline form-excluir-registro">
                                     @method('delete')
                                     @csrf
                                     <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                     data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                             <i class="ti-trash"></i>
                                     </button>
                                 </form>
                             @endcan
                         
                         </td>
                     </tr>
                 @endforeach
             </tbody>
         </table>
     </div>
     <div style="float: right">
         {{ $itens->links() }}
     </div>
 </div>
 