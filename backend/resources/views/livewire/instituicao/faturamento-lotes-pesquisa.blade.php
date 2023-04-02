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
             @can('habilidade_instituicao_sessao', 'visualizar_lotes')
                 <div class="col-md-2">
                     <div class="form-group" style="margin-bottom: 0px !important;">
                         <a href="{{ route('instituicao.faturamento.lotes.create') }}">
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
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Tipo</th>
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Situação</th>
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                 </tr>
             </thead>
             <tbody>
                  @foreach($faturamentoLotes as $faturamentoLote)
                     <tr
                     @php 

                     if($faturamentoLote->status == 1):
                      echo 'style="background:#a5e1e8"'; 
                     elseif($faturamentoLote->status == 2):
                      echo 'style="background:#96e49a"'; 
                     endif;

                     //vamos ver se possui guia e varrer para ver se tem pendencia
                     if(!empty($faturamentoLote->guias)):

                        $alert_guia_removida = null;

                        foreach($faturamentoLote->guias as $guia_lot):

                            if($guia_lot->status == 4):
                                $alert_guia_removida = 1;
                            endif;

                        endforeach;

                     else:

                      $alert_guia_removida = null;

                     endif;
                     //fim validação pendencia

                     @endphp
                     >
                         <td class="title"><a href="javascript:void(0)">{{ $faturamentoLote->id }}</a></td>
                         
                         <td>
                            {{ $faturamentoLote->descricao }}
                            {{-- VERIFICACAO DE PENDENCIA --}}
                            @php
                                if(!empty($alert_guia_removida)) echo '&nbsp;&nbsp;<button type="button" class="btn waves-effect waves-light btn-rounded btn-warning">Guia(s) pendente(s)</button>';
                            @endphp
                        </td>
                         <td>{{ ($faturamentoLote->tipo == 1)  ? "Manual" : "Sancoop" }}</td>
                         <td>
                            @php 

                            if($faturamentoLote->status == 1):
                              echo 'Transmitido/Aguardando Guias Físicas'; 
                            elseif($faturamentoLote->status == 2):
                              echo 'Entregue (guias recebidas e auditadas)'; 
                            else:
                              echo 'Em aberto';
                            endif;

                            @endphp
                         </td>
                         <td>
                             
                             @can('habilidade_instituicao_sessao', 'editar_lotes')
                                 <a href="{{ route('instituicao.faturamento.lotes.edit', [$faturamentoLote]) }}">
                                         <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                         data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                 <i class="ti-pencil-alt"></i>
                                         </button>
                                 </a>
                             @endcan


                             @can('habilidade_instituicao_sessao', 'editar_lotes')
                                 <a href="{{ route('instituicao.faturamento.lotesGuiasSancoop', [$faturamentoLote]) }}">
                                         <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                         data-toggle="tooltip" data-placement="top" data-original-title="Guias">
                                                 <i class="ti-layers-alt"></i>
                                         </button>
                                 </a>
                             @endcan

                             {{-- *****STAND BY KENNEDY, ESSE VAI SER PARA O FATURAMENTO NORMAL PARA O SANCOOP AUTOMATICO IREMOS USAR DE CIMA
                                
                                @can('habilidade_instituicao_sessao', 'editar_lotes')
                                 <a href="{{ route('instituicao.faturamento.lotesGuias', [$faturamentoLote]) }}">
                                         <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                         data-toggle="tooltip" data-placement="top" data-original-title="Guias">
                                                 <i class="ti-layers-alt"></i>
                                         </button>
                                 </a>
                             @endcan --}}
                             
                             @can('habilidade_instituicao_sessao', 'excluir_lotes')
                                 <form action="{{ route('instituicao.faturamento.lotes.destroy', [$faturamentoLote]) }}" method="post" class="d-inline form-excluir-registro">
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
         {{ $faturamentoLotes->links() }}
     </div>
 </div>
