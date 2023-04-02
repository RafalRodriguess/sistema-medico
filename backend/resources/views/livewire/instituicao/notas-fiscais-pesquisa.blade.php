<div class="card-body">         
    <form action="javascript:void(0)">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" 
                        wire:model.lazy="pesquisa" name="pesquisa"
                        class="form-control" 
                        placeholder="Pesquise por nome..."
                    >
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_nota_fiscal')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.notasFiscais.create') }}">
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
                    <th>ID</th>
                    <th>Número nota</th>
                    <th>Pessoa</th>
                    <th>Status</th>
                    <th>Valor total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notas as $item)
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                        <td class="title">{{ $item->numero_nota }}</td>
                        <td>{{ $item->paciente->nome}} ({{ $item->paciente->cpf }})</td>
                        <td>
                            <span id="status_{{$item->id}}">{{ $item->status }}</span>
                            @if(!empty($item->motivo_status))                            
                                <a class="mytooltip" href="javascript:void(0)">
                                    <i class="fa fa-question-circle help" id="motivo_{{$item->id}}" data-toggle="tooltip" data-placement="right" title="" data-original-title="Motivo: {{$item->motivo_status}}"></i>
                                </a>
                            @endif
                        </td>
                        <td>R$ {{number_format($item->valor_total, 2, ",", ".")}}</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'editar_nota_fiscal')
                                <a href="{{ route('instituicao.notasFiscais.edit', [$item]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Visualizar">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                </a>

                                <button type="button" class="btn btn-xs btn-secondary btn-reload" data-id="{{$item->id}}" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Atualizar status">
                                    <i class="mdi mdi-rotate-right"></i>
                                </button>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'cancelar_nota_fiscal')
                                @if($item->status != "Cancelada")
                                    <button type="button" class="btn btn-xs btn-secondary btn-cancel" data-id="{{$item->id}}" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Cancelar nota">
                                        <i class="mdi mdi-block-helper"></i>
                                    </button>
                                @endif
                            @endcan

                            <button type="button" class="btn btn-xs btn-secondary btn-pdf" data-id="{{$item->id}}" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="baixar pdf da nota">
                                <i class="mdi mdi-file-pdf"></i>
                            </button>
                            
                            @can('habilidade_instituicao_sessao', 'excluir_nota_fiscal')
                                <form action="{{ route('instituicao.notasFiscais.destroy', [$item]) }}" method="post" class="d-inline form-excluir-registro">
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
        {{ $notas->links() }}
    </div>
</div>

@push('scripts')
    <script>
        $(".btn-reload").on("click", function(){
            id = $(this).data("id");
            
            $.ajax("{{route('instituicao.notasFiscais.getStatus', ['nota' => 'nota_id'])}}".replace('nota_id', id), {
                method: "POST",
                data: {"_token": "{{csrf_token()}}",},
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader');
                },
                success: function (result) {
                    console.log(result);
                    $("#status_"+id).text(result.status);
                    $("#motivo_"+id).data("original-title", result.motivoStatus);
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader');
                }
            });
        })

        $(".btn-cancel").on("click", function(){
            id = $(this).data("id");

            Swal.fire({
                title: "Atenção!",
                text: 'Tem certeza que deseja cancelar esta nota fiscal? esta ação é irreversível!',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){        
                    $.ajax("{{route('instituicao.notasFiscais.cancelarNota', ['nota' => 'nota_id'])}}".replace('nota_id', id), {
                        method: "POST",
                        data: {"_token": "{{csrf_token()}}",},
                        beforeSend: () => {
                            $('.loading').css('display', 'block');
                            $('.loading').find('.class-loading').addClass('loader');
                        },
                        success: function (result) {
                            console.log(result);
                            if(result.icon == "error"){
                                Object.keys(result.errors).forEach(function(key) {
                                    $.toast({
                                        heading: 'Erro',
                                        text: result.errors[key].mensagem,
                                        position: 'top-right',
                                        loaderBg: '#ff6849',
                                        icon: 'error',
                                        hideAfter: 9000,
                                        stack: 10
                                    });
                                });
                            }else{
                                $("#status_"+id).text('Cancelada');
                                $(this).prop('disabled', true);
                                $.toast({
                                    heading: 'Sucesso',
                                    text: "Nota cancelada com sucesso",
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'success',
                                    hideAfter: 5000,
                                    stack: 10
                                });
                            }
                        },
                        complete: () => {
                            $('.loading').css('display', 'none');
                            $('.loading').find('.class-loading').removeClass('loader');
                        }
                    });
                }
            });
        })

        $(".btn-pdf").on("click", function(){
            id = $(this).data("id");

            window.open("{{route('instituicao.notasFiscais.getPDF', ['nota' => 'nota_id'])}}".replace('nota_id', id), 'Nota Fiscal', 'width=1024, height=860');
            
            // $.ajax("{{route('instituicao.notasFiscais.getPDF', ['nota' => 'nota_id'])}}".replace('nota_id', id), {
            //     method: "GET",
            //     data: {"_token": "{{csrf_token()}}",},
            //     beforeSend: () => {
            //         $('.loading').css('display', 'block');
            //         $('.loading').find('.class-loading').addClass('loader');
            //     },
            //     success: function (result) {
            //         console.log(result);                   
            //         // var url = "{{route('instituicao.relatoriosFluxoCaixa.showMovimentacao', ['data_inicio'=>'-inicio','data_fim'=>'-fim','contas'=>'_contas','valor'=>'_valor'])}}".replace('-inicio', inicio).replace('-fim', fim).replace('_contas', contas).replace('_valor', valor);
            //         // console.log(url);

            //         // window.open(result, 'Movimentacao', 'width=1024, height=860');
                    
                    
            //     },
            //     complete: () => {
            //         $('.loading').css('display', 'none');
            //         $('.loading').find('.class-loading').removeClass('loader');
            //     }
            // });
        })


        

    </script>
@endpush
