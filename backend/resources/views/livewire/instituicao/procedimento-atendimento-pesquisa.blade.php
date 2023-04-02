<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
         <div class="row">
            <div class="col-md-5">
                <div class="form-group" wire:ignore> 
                    <select name="convenio" class="form-control selectfild2 convenios"  wire:model="convenio">
                    <option value="0">Todos</option>  
                    @foreach ($convenios as $item)
                        <option value="{{$item->id}}">{{$item->nome}}</option>
                    @endforeach
                    </select> 
                </div>
            </div> 
            <div class="col-md-5">
                <div class="form-group" wire:ignore> 
                    <select name="plano" class="form-control selectfild2 planos"  wire:model="plano">
                    <option value="0">Todos</option>  
                    </select> 
                </div>
            </div> 
             @can('habilidade_instituicao_sessao', 'cadastrar_procedimentos_atendimentos')
                 <div class="col-md-2">
                     <div class="form-group" style="margin-bottom: 0px !important;">
                         <a href="{{ route('instituicao.procedimentoAtendimentos.create') }}">
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
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Convênio</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Plano</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Tipo</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Origem</th>   
                     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                 </tr>
             </thead>
             <tbody>
                 @foreach($procedimentos as $item)
                     <tr>
                         <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                         
                         <td>{{ $item->convenio->nome }}</td>
                         <td>
                            @if ($item->tipo == 'urgencia')
                                Urgencia/Emergencia
                            @else
                                @if ($item->tipo == 'ambulatorio')
                                    Ambulatório
                                @else
                                    Internação
                                @endif
                            @endif
                         </td>
                         <td>{{ ($item->plano) ? $item->plano->nome : '' }}</td>
                         <td>{{ ($item->origem) ? $item->origem->descricao : '' }}</td>
                         <td>
                             
                             @can('habilidade_instituicao_sessao', 'editar_procedimentos_atendimentos')
                                 <a href="{{ route('instituicao.procedimentoAtendimentos.edit', [$item]) }}">
                                         <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                         data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                 <i class="ti-pencil-alt"></i>
                                         </button>
                                 </a>
                             @endcan
                             
                             @can('habilidade_instituicao_sessao', 'excluir_procedimentos_atendimentos')
                                 <form action="{{ route('instituicao.procedimentoAtendimentos.destroy', [$item]) }}" method="post" class="d-inline form-excluir-registro">
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
         {{ $procedimentos->links() }}
     </div>
 </div>
 
 @push('scripts')
    <script>
        $(".convenios").on('change', function(){
            var convenio_id = $(".convenios option:selected").val();
            var options = $('.planos');
            options.val(0).change()
            if(convenio_id != 0){
                $.ajax({
                    type: "POST",
                    data: {'_token': '{{csrf_token()}}'},
                    url: "{{route('instituicao.procedimentoAtendimentos.getPlanos', ['convenio' => 'convenio_id'])}}".replace('convenio_id', convenio_id),
                    datatype: "json",
                    success: function(result) {
                        
                        if(result != null){
                            planos = result;
                            
                            options.find('option').filter(':not([value=0])').remove();
                            $.each(planos, function (key, value) {
                                        // $('<option').val(value.id).text(value.Nome).appendTo(options);
                                        options.append('<option value='+value.id+'>'+value.nome+'</option>')
                                //options += '<option value="' + key + '">' + value + '</option>';
                            });
                        }else{
                            
                            options.find('option').filter(':not([value=0])').remove();
                        }
                    }

                });
            }else{
                
                options.find('option').filter(':not([value=0])').remove();
            }
        })
    </script>
 @endpush