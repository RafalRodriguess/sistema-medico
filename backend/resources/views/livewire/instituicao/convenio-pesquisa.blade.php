@push('estilos')
<style>
    .select2-selection {
        height: 50px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: 50px!important;
    }

    .select2-selection__choice {
        font-size: 14px;
        margin: 2px !important;
        color: black;
    }
</style>
@endpush

<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                        <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">


                </div>
            </div>

            @can('habilidade_instituicao_sessao', 'cadastrar_convenio')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.convenio.create') }}">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>


<table class="tablesaw table-bordered table-hover table" style="overflow-wrap: anywhere">
    <thead>
        <tr>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="1">Nome</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Razão Social</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Responsável</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Inicio Contrato</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="4">Ativo</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($convenios as $convenio)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $convenio->id }}</a></td>

                <td>{{ $convenio->nome }}</td>
                <td>{{ $convenio->razao_social }}</td>
                <td>{{ $convenio->responsavel }}</td>
                <td>{{ \Carbon\Carbon::parse($convenio->dt_inicio_contrato)->format('d/m/Y')}}</td>
                <td>{{ ($convenio->ativo) ? 'Ativo' : 'Inativo'}} </td>
                <td>

                    @can('habilidade_instituicao_sessao', 'visualizar_convenio_planos')
                        <a href="{{ route('instituicao.convenios.planos.index', [$convenio]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Cadastrar Plano">
                                        <i class="ti-settings"></i>
                                </button>
                        </a>
                    @endcan

                    @can('habilidade_instituicao_sessao', 'editar_convenio')
                        <a href="{{ route('instituicao.convenio.edit', [$convenio]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                        </a>
                    @endcan

                    @can('habilidade_instituicao_sessao', 'excluir_convenio')
                            <form action="{{ route('instituicao.convenio.destroy', [$convenio]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                        <i class="ti-trash"></i>
                                </button>
                            </form>
                    @endcan
                    
                    @can('habilidade_instituicao_sessao', 'vincular_convenio_agendas')
                        <button type="button" class="btn btn-xs btn-secondary vincular_convenio" aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-original-title="Vincular" data-text="{{$convenio->nome}}" data-id="{{$convenio->id}}">
                                <i class="ti-layers-alt"></i>
                        </button>
                    @endcan

                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        {{-- <tr>
            <td colspan="5">
                {{ $comerciais->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $convenios->links() }}
</div>
</div>

<div class="modal inmodal no_print" id="modalVincularConvenio" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-body" style="text-align: center;">
                <form action="" id="vincular_convenio_prestador">
                    @csrf
                    <h2>Selecione os prestadores que deseja vincular o convênio (<span class="convenio_texto"></span>)</h2>
                    <input type="hidden" name="convenio_id" id="convenio_id">
                    <div class="row">
                        <div class="col-md-12">
                            <select id="prestadores" style="width: 100%" class="form-control select2" name="prestadores[]" multiple required>
                                @foreach ($prestadores as $prestador)
                                    <optgroup label="{{ $prestador->descricao }}">
                                        @foreach ($prestador->prestadoresInstituicao as $prestadoresInstituicao)
                                            <option value="{{ $prestadoresInstituicao->id }}" selected>{{ $prestadoresInstituicao->prestador->nome }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12" style="text-align: left">
                            <span style="cursor: pointer" onclick="limpa_filtros('prestadores')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                            <span style="cursor: pointer" onclick="seleciona_filtros('prestadores')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                        </div>
                        <div class="col-md-12" style="text-align: left">
                            <small style="color: red">Obs: o convênio estara disponivel para todos os dias da agenda</small>
                        </div>
                    
                        <div class='col-md-12' style="margin-top: 15px">
                            <div class="form-groupn text-right">
                                <button type="button" class="btn btn-success waves-effect waves-light salvar_convenio" data-dismiss="modal">Salvar</button>  
                                <button type="button" class="btn btn-secondary waves-effect waves-light" data-dismiss="modal">Cancelar</button>  
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(".vincular_convenio").on('click', function(){
            $("#modalVincularConvenio").modal('show')
            $(".convenio_texto").html($(".vincular_convenio").attr('data-text'))
            $("#convenio_id").val($(".vincular_convenio").attr('data-id'))
        })

        function limpa_filtros(elemento){
            $("#"+elemento).find("option").attr("selected", false);
            $("#"+elemento).val('').trigger('change');
        }

        function seleciona_filtros(elemento){
            $("#"+elemento).val([]);
            var dados = [];
            $("#"+elemento).find("option").each(function(index, elem){
                $(elem).attr("selected", true);
                dados.push($(elem).val())
            })
            $("#"+elemento).val(dados)
            $("#"+elemento).trigger('change');
        }

        $(".salvar_convenio").on('click', function(e){
            e.preventDefault()
            e.stopPropagation()

            var formData = new FormData($("#vincular_convenio_prestador")[0]);

            $.ajax("{{ route('instituicao.convenio.convenioPrestador') }}", {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function (response) {   
                        $.toast({
                            heading: 'Sucesso',
                            text: "Convênio vinculado com sucesso!",
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: "success",
                            hideAfter: 3000,
                            stack: 10
                        });
                        $("#modalVincularConvenio").modal('hide')
                        $(".convenio_texto").html('')
                        $("#convenio_id").val('')
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
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
        })
    </script>
@endpush
