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
             @can('habilidade_instituicao_sessao', 'cadastrar_forma_pagamento')
                 <div class="col-md-2">
                     <div class="form-group" style="margin-bottom: 0px !important;">
                         <a href="{{ route('instituicao.formasPagamentos.create') }}">
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
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Sigla</th>
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                 </tr>
             </thead>
             <tbody>
                 @foreach($formasPagamentos as $formaPagamento)
                     <tr>
                         <td class="title"><a href="javascript:void(0)">{{ $formaPagamento->id }}</a></td>
                         
                         <td>{{ $formaPagamento->descricao }}</td>
                         <td>{{ $formaPagamento->sigla }}</td>
                         <td>
                             
                             @can('habilidade_instituicao_sessao', 'editar_forma_pagamento')
                                 <a href="{{ route('instituicao.formasPagamentos.edit', [$formaPagamento]) }}">
                                         <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                         data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                 <i class="ti-pencil-alt"></i>
                                         </button>
                                 </a>
                             @endcan
                             
                             @can('habilidade_instituicao_sessao', 'excluir_forma_pagamento')
                                 <form action="{{ route('instituicao.formasPagamentos.destroy', [$formaPagamento]) }}" method="post" class="d-inline form-excluir-registro">
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
         {{ $formasPagamentos->links() }}
     </div>
 </div>
