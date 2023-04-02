<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>

                    <select name="pesquisa_id" class="form-control selectfild2" wire:model="pesquisa" style="width: 100%">
                    <option value="">Todos Motivos</option>
                        @foreach ($motivos as $motivo)
                            <option value="{{ $motivo->id }}">
                                {{ $motivo->descricao }}
                            </option>
                        @endforeach
                    </select>


                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>

                    <select name="usuarioAtendeu_id" id="usuarioAtendeu_id" class="form-control selectfild2" wire:model="usuarioAtendeu" style="width: 100%">
                        <option value="">Todos usuários</option>
                        @foreach ($usuarios as $item)
                            <option value="{{$item->id}}">{{$item->nome}}</option>
                        @endforeach
                    </select>


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_atendimento_paciente')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info nova_atendimento">Novo Atendimento</button>
                        
                    </div>
                </div>
            @endcan
        </div>
    </form>

          <hr>


<table class="tablesaw table-bordered table-hover table" >
    <thead>
        <tr>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Data atendimento</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Usuario atendeu</th>            
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Motivo</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Descrição</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Ação</th>
        </tr>
    </thead>
    <tbody>
        @foreach($atendimentoPaciente as $atendimento)
            <tr id="linha_{{$atendimento->id}}">
                <td>{{$atendimento->id}}</td>
                <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $atendimento->created_at)->format('d/m/Y H:i')}}</td>   
                <td>{{$atendimento->usuario->nome}}</td>                
                <td>
                    {{($atendimento->motivoView) ? $atendimento->motivoView->descricao : ''}}
                </td>
                <td>{{$atendimento->descricao}}</td>
                <td>
                    @can('habilidade_instituicao_sessao', 'editar_atendimento_paciente')
                        {{-- <a href="{{ route('instituicao.caixasCirurgicos.edit', [$item]) }}"> --}}
                                <button type="button" class="btn btn-xs btn-secondary editarAtendimento button_tooltip" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Editar" data-id="{{$atendimento->id}}">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                        {{-- </a> --}}
                    @endcan
                    
                    @can('habilidade_instituicao_sessao', 'excluir_atendimento_paciente')
                        {{-- <form action="{{ route('instituicao.caixasCirurgicos.destroy', [$pessoaPesquisa, $item]) }}" id="form_atendimento_delete" method="post" class="d-inline form-excluir-registro">
                            @method('delete')
                            @csrf --}}
                            <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro excluirAtendimento button_tooltip"  aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-original-title="Excluir" data-id="{{$atendimento->id}}">
                                    <i class="ti-trash"></i>
                            </button>
                        {{-- </form> --}}
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
    </tfoot>
</table>
<div style="float: right">
    {{ $atendimentoPaciente->links() }}
</div>
</div>

<script>
    $(".editarAtendimento").on('click', function(e){
        e.stopPropagation();
        var id = $(this).attr('data-id');
        $.ajax({
            url: "{{route('instituicao.atendimentos_paciente.edit', ['pessoa' => 'pessoa_id', 'atendimento_paciente' => 'atendimento_paciente_id'])}}".replace('pessoa_id', pessoa_id).replace('atendimento_paciente_id', id),
            type: 'GET',
            beforeSend: () => {
                $(".novo_atendimento").html('')
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },

            success: function(result) {
                
                $(".novo_atendimento").html(result)
                $(".novo_atendimento").css('display', 'block')
                $(".lista_atendimento").css('display', 'none')
                $('.loading').css('display', 'none');
                
            },

            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader')
            }

        });
    })
    
    $(".excluirAtendimento").on('click', function(e){
        e.stopPropagation();
        var id = $(this).attr('data-id');
        Swal.fire({
            title: "Confirmar!",
            text: 'Deseja excluir o atendimento ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#393ed9",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Sim",
        }).then(function(result) {
            if(result.isConfirmed){
                $.ajax({
                    url: "{{route('instituicao.atendimentos_paciente.excluir', ['pessoa' => 'pessoa_id', 'atendimento_paciente' => 'atendimento_paciente_id'])}}".replace('pessoa_id', pessoa_id).replace('atendimento_paciente_id', id),
                    type: 'POST',
                    data: {"_token": "{{csrf_token()}}"},
                    beforeSend: () => {
                        $(".atendimento-paciente").html('')
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },

                    success: function(result) {
                        
                        atendimentoAtualizar(result)
                        $('.loading').css('display', 'none');
                        $.toast({
                            heading: 'Sucesso',
                            text: 'Atendimento excluido com sucesso!',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000,
                            stack: 10
                        })
                        
                    },

                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader')
                    }

                });
            }
        })
        
    })
</script>
