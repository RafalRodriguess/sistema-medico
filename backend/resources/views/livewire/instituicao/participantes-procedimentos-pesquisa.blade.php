<div class="card-body">

	<form action="javascript:void(0)" id="FormTitular">
		<div class="row">
			<div class="col-md-9">
				<div class="form-group" style="margin-bottom: 0px !important;">

					<input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">


				</div>
			</div>
			@can('habilidade_instituicao_sessao', 'vincular_procedimentos')


			<div class="col-md-3">
				<div class="form-group" style="margin-bottom: 0px !important;">
					<a href="{{ route('instituicao.vincular.procedimentos', [$id_instituicao_prestador]) }}">
						<button type="button" class="btn waves-effect waves-light btn-block btn-info">Adicionar Procedimento</button>
					</a>
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
				<th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Procedimento</th>

				<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
			</tr>
		</thead>
		<tbody>
			@foreach($procedimentos as $value)
			<tr id="itemTable_{{ $value->id }}">
				<td class="title"><a href="javascript:void(0)">{{ $value->id }}</a></td>
				<td>{{ $value->descricao }}</td>



				<td>
					@can('habilidade_instituicao_sessao', 'vincular_procedimentos')
					<a href="{{ route('instituicao.vincular.procedimentos.editar', [$value, $id_instituicao_prestador]) }}">
						<button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Convênios">
							<i class="fas fa-first-aid"></i> Convênios
						</button>
					</a>
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
			</tr> --}}
		</tfoot>
	</table>
	<div style="float: right">
		{{ $procedimentos->links() }}
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
		}).then(function(result) {
			if (result.value) {
				$.ajax({
					url: '{{route("instituicao.retiraprocedimento")}}',
					method: 'POST',
					dataType: 'json',
					data: {
						id: id,
						'_token': '{{csrf_token()}}'
					},
					success: function(data) {
						$('#itemTable_' + data).remove()
					}
				})
			}
		});
	});
</script>
@endpush