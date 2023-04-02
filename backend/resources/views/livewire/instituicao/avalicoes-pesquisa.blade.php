<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row  col-md-12">
            <div class="col-md-6">
                <div class="form-group" wire:ignore>
                    <select name="paciente_id_avaliacao" id="paciente_id_avaliacao" class="form-control" style="width: 100%"></select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" wire:ignore>
                    <select name="medico_id_avaliacao" id="medico_id_avaliacao" class="form-control selectfild2" wire:model="medico_id_avaliacao"  style="width: 100%">
                        <option value="0">Todos Medicos</option>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" wire:ignore>
                    <select name="especialidade_id_avaliacao" id="especialidade_id_avaliacao" class="form-control selectfild2" wire:model="especialidade_id_avaliacao" style="width: 100%">
                        <option value="0">Todas especialidades</option>
                        @foreach ($especialidades as $item)
                            <option value="{{$item->id}}">{{$item->descricao}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" wire:ignore>
                    <select name="atendidos" id="atendidos" class="form-control selectfild2" wire:model="atendidos" style="width: 100%">
                        <option value="0">Somente não atendidos</option>
                        <option value="1">Somente atendidos</option>
                        <option value="2">Todas avaliações</option>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <hr>

    <div class="table-responsive">
        <table class="tablesaw table-bordered table-hover table" >
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Paciente</th>
                    <th scope="col">Profissional</th>
                    <th scope="col">Especialidade</th>
                    <th scope="col">Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach($avaliacoes as $item)
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>                        
                        <td>{{ $item->paciente->nome }}</td>
                        <td>@if($item->medico_id) {{ $item->prestador->nome }} @endif</td>
                        <td>@if($item->especialidade_id) {{ $item->especialidade }} @endif</td>
                        <td>
                            <button type="button" class="btn btn-xs btn-secondary vizualisarAvaliacao" aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-original-title="Visualizar" onClick="vizualisarAvaliacao($(this))" data-descricao="{!! $item->avaliacao !!}" data-atendido="{{$item->atendido}}" data-id="{{$item->id}}">
                                <i class="mdi mdi-eye"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="float: right">
        {{ $avaliacoes->links() }}
    </div>
</div>

<div class="modal right fade bs-example-modal-lg" id="modalDescricaoAvaliacao" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Avaliação</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="modal-body">
                <p id='avaliacaoText'></p>

                <button type="button" class="btn btn-secondary" id="atenderAvaliacao" aria-haspopup="true" aria-expanded="false"
                data-toggle="tooltip" data-placement="top" data-original-title="Atender" onclick="atenderAvaliacao($(this).data('id'))">
                    Atender
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal.right .modal-dialog {
		position: fixed;
		margin: auto;
		width: 320px;
		height: 100%;
	}

	.modal.right .modal-content {
		height: 100%;
		overflow-y: auto;
	}
	
	.modal.right .modal-body {
		padding: 15px 15px 80px;
	}

	.modal.right.fade .modal-dialog {
		right: 0;
	}
</style>

@push('scripts')
    <script>
        $(document).ready(function(){
            $("#paciente_id_avaliacao").select2({
                placeholder: "Pesquise por nome do paciente",
                allowClear: true,
                // minimumInputLength: 3,

                language: {
                    searching: function () {
                        return 'Buscando paciente (aguarde antes de selecionar)…';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                },

                ajax: {
                    url:"{{route('instituicao.contasPagar.getPacientes')}}",
                    dataType: 'json',
                    type: 'get',
                    delay: 100,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },

                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: `${item.nome} ${(item.cpf) ? '- ('+item.cpf+')': ''}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
            });


            $('#paciente_id_avaliacao').on('change', function(){
                window.livewire.emit('pacienteIdAvaliacao', $(this).val());
            })

            function resetPage(){
                @this.call('render');
            }
        });        
    </script>
@endpush