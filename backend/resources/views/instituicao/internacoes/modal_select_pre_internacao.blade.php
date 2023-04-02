<div id="mostraPreInternacao" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Pre internações</h3>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            
            
           <form id="formPesquisarPaciente">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group col-md-12 pagar_menor bg-danger text-white">
                            <span class=''>Existe uma ou mais pre internações para este paciente, deseja utiliza estes dados? caso sim, gentileza selecionar a pre internação na tabela abaixo, caso não basta fechar a tela e continuar o cadastro da internação.</span>
                        </div>

                        <hr>
                        <table class="tablesaw table-bordered table-hover table" style="overflow-wrap: anywhere">
                            <thead>
                                <tr>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                                    <th scope="col" >Previsão</th>
                                    <th scope="col" >Médico</th>
                                    <th scope="col" >Especialidade</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($preInternacoes as $item)
                                    <tr>
                                        <td class="title"><a href="javascript:void(0)">{{ $item['id'] }}</a></td>
                                        <td>{{ date("d/m/Y", strtotime($item['previsao'])) }}</td>
                                        <td>{{ $item['medico']['nome']}}</td>
                                        <td>{{ $item['especialidade']['descricao']}}</td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-secondary selectCarteirinha" value="{{ json_encode($item) }}" onClick="selectPreInternacao(this.value)"><i class="mdi mdi-check"></i></button>
                                        </td>
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
