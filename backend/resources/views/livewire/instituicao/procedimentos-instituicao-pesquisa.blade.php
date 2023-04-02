<div class="card-body">

	<form action="javascript:void(0)" id="FormTitular">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group" style="margin-bottom: 0px !important;">

					<input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por grupo ou procedimento">


				</div>
			</div>
			@can('habilidade_instituicao_sessao', 'cadastrar_procedimentos')


			<div class="col-md-3">
				<div class="form-group" style="margin-bottom: 0px !important;">
					<a href="{{ route('instituicao.procedimentos.create') }}">
						<button type="button" class="btn waves-effect waves-light btn-block btn-info">Adicionar Procedimento</button>
					</a>
				</div>
            </div>
            @endcan
            @can('habilidade_instituicao_sessao', 'editar_agenda_grupo')
            <div class="col-md-3">
				<div class="form-group" style="margin-bottom: 0px !important;">
				    <button data-toggle="modal" data-target="#modalGrupos" type="button" class="btn waves-effect waves-light btn-block btn-info">Agenda dos grupos</button>
				</div>
            </div>

			@endcan
		</div>
	</form>

	<hr>

	<table class="tablesaw table-bordered table-hover table">
		<thead>
			<tr>
				<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
				<th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Grupo</th>
				<th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Tipo</th>
				<th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Procedimento</th>
				<th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="4">Atendimento</th>
				<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
			</tr>
		</thead>
		<tbody>
			@foreach($procedimentos as $value)
			<tr id="itemTable_{{ $value->id }}">
				<td class="title"><a href="javascript:void(0)">{{ $value->id }}</a></td>

				<td>{{ $value->grupoProcedimento->nome }}</td>
				<td>{{ $value->procedimento->tipo }}</td>
                <td>{{ $value->procedimento->descricao }}</td>
                <td>
                    @if ( $value->procedimento->tipo=='exame' && $value->tipo=='avulso')
                        Check-in
                    @elseif($value->procedimento->tipo=='exame' && $value->tipo=='unico')
                        Hora marcada
                    @elseif($value->procedimento->tipo=='exame' && $value->tipo=='ambos')
                        Check-in, Hora marcada
                    @endif
                </td>

				<td>

					<a href="{{ route('instituicao.procedimentos.edit', [$value->id]) }}">
						<button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
						data-toggle="tooltip" data-placement="top" data-original-title="Editar">
						<i class="ti-pencil"></i>
					</button>
				</a>
				<!-- @can('habilidade_instituicao_sessao', 'editar_procedimento') -->
					<!-- @endcan -->

					@if ($value->procedimento->tipo == 'exame')
						@can('habilidade_instituicao_sessao', 'editar_agenda_procedimento')

						<a href="{{ route('instituicao.procedimentos.getAgenda', [$value->id]) }}">
								<button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
								data-toggle="tooltip" data-placement="top" data-original-title="Agenda">
										<i class="ti-calendar"></i>
								</button>
						</a>
						@endcan
					@endif

					@can('habilidade_instituicao_sessao', 'retirar_procedimento')

					<button type="button" class="btn btn-xs btn-secondary retirar-procedimento" aria-haspopup="true" data-id="{{$value->id}}" aria-expanded="false"
						data-toggle="tooltip" data-placement="top" data-original-title="Retirar Procedimento">
						<i class="ti-close"></i>
					</button>

					@endcan


				</td>
			</tr>
			@endforeach
		</tbody>
		<tfoot>
	{{-- <tr>
		<td colspan="5">
			{{ $procedimento->links() }}
		</td>
	</tr>  --}}
</tfoot>
</table>
<div style="float: right">
	{{ $procedimentos->links() }}
</div>
</div>


<div class="modal inmodal" id="modalGrupos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agenda dos Grupos</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>


            </div>

            <div class="modal-body" style="text-align: center;">
                <div  class="scrollabe">
                    <div style="text-align: center;display: flex;flex-direction: column;">
                        @foreach ( $grupos as  $grupo)
                            <a href="{{ route('instituicao.grupos.getAgenda', [$grupo->id]) }}">
                                <button style='margin:5px;' type="button" class="btn btn-success" class="btn waves-effect waves-light m-r-10"><i class="ti-calendar"></i> {{$grupo->nome}}</button>
                            </a>
                        @endforeach
                    </div>
                </div>
        </div>
    </div>
</div>

@push('scripts');
<script>

	/*FUNCAO EXCLUINDO REGISTROS*/

	$('.retirar-procedimento').on('click', function(e) {
		e.preventDefault();
		var id = $(this).data('id')
		Swal.fire({
			title: "Confirmar retirada?",
			text: "Ao confirmar você estará retirando o procedimento!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Sim, confirmar!",
			cancelButtonText: "Não, cancelar!",
		}).then(function (result) {
			if (result.value) {
				$.ajax({
					url: '{{route("instituicao.retiraprocedimento")}}',
					method: 'POST',
					dataType: 'json',
					data: {
						id : id,
						'_token': '{{csrf_token()}}'},
						success: function (data) {
							$('#itemTable_'+data).remove()
						}
					})
			}
		});
	});


</script>
@endpush
@push('estilos')
<style>

    .scrollabe {
        overflow-y: scroll;
        margin-bottom: 10px;
        max-height: 500px;
    }
</style>
@endpush
