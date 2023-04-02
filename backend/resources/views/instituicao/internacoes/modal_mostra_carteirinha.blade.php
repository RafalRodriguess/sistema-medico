<div id="mostraCarteirinha" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Carteirinhas de convenios</h3>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="formPesquisarPaciente">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <table class="tablesaw table-bordered table-hover table" style="overflow-wrap: anywhere">
                            <thead>
                                <tr>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                                    <th scope="col" >Convenio</th>
                                    <th scope="col" >Plano</th>
                                    <th scope="col" >Carteirinha</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carteirinhas as $item)
                                    <tr>
                                        <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                                        <td>{{ $item->convenio[0]->nome}}</td>
                                        <td>{{ $item->plano[0]->nome }}</td>
                                        <td>{{ $item->carteirinha }}</td>
                                        <td><button type="button" class="btn btn-xs btn-secondary selectCarteirinha" value="{{ $item->id }}" onClick="selectCarteirinha(this.value)")><i class="mdi mdi-check"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>
