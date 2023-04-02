<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
         <div class="row">
             <div class="col-md-10">
                 <div class="form-group" style="margin-bottom: 0px !important;">
                     <input type="text" id="pesquisa" 
                         wire:model.lazy="pesquisa" name="pesquisa"
                         class="form-control" 
                         placeholder="Pesquise por descrição...">
                 </div>
             </div>
             @can('habilidade_instituicao_sessao', 'cadastrar_regras_cobranca')
                 <div class="col-md-2">
                     <div class="form-group" style="margin-bottom: 0px !important;">
                         <a href="{{ route('instituicao.regrasCobranca.create') }}">
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
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Descrição</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Cir mesma via %</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Cir via diferente %</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Base de calc. via de acesso</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                 </tr>
             </thead>
             <tbody>
                 @foreach($regras as $item)
                     <tr>
                         <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                         
                         <td>{{ $item->descricao }}</td>
                         <td>{{ $item->cir_mesma_via }}</td>
                         <td>{{ $item->cir_via_diferente }}</td>
                         <td>{{ $item->base_via_acesso }}</td>
                         <td>
                             
                             @can('habilidade_instituicao_sessao', 'editar_regras_cobranca')
                                 <a href="{{ route('instituicao.regrasCobranca.edit', [$item]) }}">
                                         <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                         data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                 <i class="ti-pencil-alt"></i>
                                         </button>
                                 </a>
                             @endcan
                             @can('habilidade_instituicao_sessao', 'editar_regras_cobranca_itens')
                                 <a href="{{ route('instituicao.regrasCobrancaItens.index', [$item]) }}">
                                         <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                         data-toggle="tooltip" data-placement="top" data-original-title="itens">
                                                 <i class="ti-layers-alt"></i>
                                         </button>
                                 </a>
                             @endcan
                             
                             @can('habilidade_instituicao_sessao', 'excluir_regras_cobranca')
                                 <form action="{{ route('instituicao.regrasCobranca.destroy', [$item]) }}" method="post" class="d-inline form-excluir-registro">
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
         {{ $regras->links() }}
     </div>
 </div>
 