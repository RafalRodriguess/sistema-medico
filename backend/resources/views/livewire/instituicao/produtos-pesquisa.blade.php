<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular"> 
        <div class="row ">
            <div class="row col-md-12">
                <div class="col-md-3" style="padding-right: 5px;">
                    <div class="form-group" >
                        <input type="text" id="pesquisa" 
                            wire:model.lazy="pesquisa" name="pesquisa"
                            class="form-control" 
                            placeholder="Pesquise por descrição...">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group" wire:ignore> 
                        <select name="generico" class="form-control selectfild2"  wire:model="generico">
                        <option value="2">Todos Genericos </option> 
                        <option value="0">NÃO </option> 
                        <option value="1">SIM </option>  
                        </select> 
                    </div>
                </div> 
                
                <div class="col-md-3">
                    <div class="form-group" wire:ignore> 
                        <select name="mestre" class="form-control selectfild2"  wire:model="mestre">
                        <option value="2">Todos Mestres </option> 
                        <option value="0">NÃO </option> 
                        <option value="1">SIM </option>  
                        </select> 
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group" wire:ignore> 
                        <select name="kit" class="form-control selectfild2"  wire:model="kit">
                        <option value="2">Todos Kits </option> 
                        <option value="0">NÃO </option> 
                        <option value="1">SIM </option>  
                        </select> 
                    </div>
                </div>  

                <div class="col-md-3">
                    <div class="form-group" wire:ignore> 
                        <select name="tipo" class="form-control selectfild2"  wire:model="tipo">
                        <option value="">Todos Kits </option>                             
                        <option value="normal">Normal</option> 
                        <option value="re_processado">Re-processado</option> 
                        <option value="consignado">Consignado</option> 
                        </select> 
                    </div>
                </div>  

                <div class="col-md-3">
                    <div class="form-group" wire:ignore> 
                        <select name="classe" class="form-control selectfild2" wire:model="classe">
                        <option value="0">Todas Classes </option>
                            @foreach ($classes as $classe)
                                <option value="{{ $classe->id }}">
                                    {{ $classe->descricao }}
                                </option>
                            @endforeach
                        </select> 
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group"   wire:ignore> 
                        <select name="especie" class="form-control selectfild2" wire:model="especie">
                        <option value="0">Todas Especies </option>
                            @foreach ($especies as $especie)
                                <option value="{{ $especie->id }}">
                                    {{ $especie->descricao }}
                                </option>
                            @endforeach
                        </select> 
                    </div>
                </div>
                @can('habilidade_instituicao_sessao', 'cadastrar_produtos')
              <div class="col-md-3">
                  <div class="form-group" style="margin-bottom: 0px !important;">
                      <a href="{{ route('instituicao.produtos.create') }}">
                      <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                      </a>
                  </div>
              </div>
            @endcan
            </div>
            {{-- <div class="col-md-9"></div> --}}
            
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
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Especie</th>
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Classe</th>
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                 </tr>
             </thead>
             <tbody>
                 @foreach($produtos as $produto)
                     <tr>
                         <td class="title"><a href="javascript:void(0)">{{ $produto->id }}</a></td>
                         
                         <td>{{ $produto->descricao }}</td>
                         <td>{{ ($produto->tipo=='re_processado')?'Re-Processado': $produto->tipo }}</td>
                         <td>{{ $produto->especie->descricao }}</td>
                         <td>{{ $produto->classe->descricao }}</td>
                         <td>
                             
                             @can('habilidade_instituicao_sessao', 'editar_produtos')
                                 <a href="{{ route('instituicao.produtos.edit', [$produto]) }}">
                                         <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                         data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                 <i class="ti-pencil-alt"></i>
                                         </button>
                                 </a>
                             @endcan
                             
                             @can('habilidade_instituicao_sessao', 'excluir_produtos')
                                 <form action="{{ route('instituicao.produtos.destroy', [$produto]) }}" method="post" class="d-inline form-excluir-registro">
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
         {{ $produtos->links() }}
     </div>
 </div>
 