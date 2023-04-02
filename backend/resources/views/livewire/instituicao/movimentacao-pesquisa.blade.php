<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>

                        <select name="conta_origem" class="form-control selectfild2" wire:model="conta_origem">
                        <option value="0">Contas origem</option>
                            @foreach ($contas as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nome }}
                                </option>
                            @endforeach
                        </select>


                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>

                        <select name="conta_destino" class="form-control selectfild2" wire:model="conta_destino">
                        <option value="0">Contas destino</option>
                            @foreach ($contas as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nome }}
                                </option>
                            @endforeach
                        </select>


                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                         
                        <input type="date" id="data_inicio" wire:model.lazy="data_inicio" name="data_inicio" class="form-control" placeholder="Data vencimento inicio">
                    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                         
                        <input type="date" id="data_fim" wire:model.lazy="data_fim" name="data_fim" class="form-control" placeholder="Data vencimento final">
                        
                    </div>
                </div>
                <div class='col-md-8'></div>
                @can('habilidade_instituicao_sessao', 'duplicar_movimentacoes')
                    <div class="col-md-2">
                        <div class="form-group" style="margin-bottom: 10px !important; float: right;">
                            <button type="button" class="btn waves-info waves-light btn-block btn-warning duplicar-contas">Duplicar contas</button>
                        </div>
                    </div>
                @endcan
                @can('habilidade_instituicao_sessao', 'cadastrar_movimentacoes')
                    <div class="col-md-2">
                        <div class="form-group" style="margin-bottom: 0px !important;">
                            <a href="{{ route('instituicao.movimentacoes.create') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Tipo movimentação</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Data</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Conta origem</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Conta destino</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Valor</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Obs</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Usuario</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movimentacoes as $key => $item)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                    <td>{{ App\Movimentacao::natureza_para_texto($item->tipo_movimentacao) }}</td>
                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d',$item->data)->format('d/m/Y')}}</td>
                    <td>{{ $item->contaOrigem->descricao}}</td>
                    <td>{{ $item->contaDestino->descricao}}</td>
                    <td>{{ number_format($item->valor, 2, ',', '.')}}</td>
                    <td>{{ $item->obs }}</td>
                    <td>{{ $item->usuarioInstituicao->nome }}</td>
                    <td>
                        {{-- @can('habilidade_instituicao_sessao', 'editar_movimentacoes')
                            
                        
                            <a href="{{ route('instituicao.movimentacoes.edit', [$item]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                            </a>
                        @endcan  --}}
                    
                        @can('habilidade_instituicao_sessao', 'excluir_movimentacoes')
                            <form action="{{ route('instituicao.movimentacoes.destroy', [$item]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                        <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'duplicar_movimentacoes')
                            <input type="checkbox" name="checkbox[{{$key}}][id]" data-id="{{$item->id}}" id="id_{{$item->id}}" value="{{$item->id}}" style="position: relative; left: 0px; opacity: 1;" class="checkbox-inputs" data-toggle="tooltip" data-placement="top" data-original-title="Duplicar">
                        @endcan
                    
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            {{-- <tr>
                <td colspan="5">
                    {{ $movimentacoes->links() }}
                </td>
            </tr>  --}}
        </tfoot>
    </table>
</div>
<div style="float: right">
    {{ $movimentacoes->links() }}
</div>
</div>

@push('scripts')
    <script>
        $(".duplicar-contas").on('click', function(){
            var ids = [];
            $(".checkbox-inputs").each(function(index, e){
                if($(e).is(':checked')){
                    const id = $(e).attr('data-id');
                    ids.push(id)
                }
            })

            if(ids.length > 0){

                Swal.fire({
                    title: "Atenção!",
                    text: 'Deseja duplicar a(s) parcela(s) ?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Não, cancelar!",
                    confirmButtonText: "Sim, confirmar!",
                }).then(function(result) {
                    if(result.value){
                        $.ajax({
                            type: "POST",
                            data: {
                                ids: ids,
                                "_token": "{{ csrf_token() }}"
                            },
                            url: "{{ route('instituicao.movimentacoes.duplicar') }}",
                            beforeSend: () => {
                            },
                            success: (result) => {
                                $.toast({
                                    heading: result.header,
                                    text: result.text,
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: result.icon,
                                    hideAfter: 9000,
                                    stack: 10
                                });    
                                
                                if(result.icon == 'success'){
                                    @this.call('render')
                                }
                            },
                            complete: () => {
                            },
                            error: function (response) {
                                if(response.responseJSON.errors){
                                    Object.keys(response.responseJSON.errors).forEach(function(key) {
                                        $.toast({
                                            heading: 'Erro',
                                            text: response.responseJSON.errors[key][0],
                                            position: 'top-right',
                                            loaderBg: '#ff6849',
                                            icon: 'error',
                                            hideAfter: 9000,
                                            stack: 10
                                        });

                                    });
                                }
                            }
                        })
                    }
                })
            }else{
                swal({
                    title: "Error!",
                    text: 'Selecione alguma parcela',
                    type: "error",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                })
            }
        })
    </script>
@endpush