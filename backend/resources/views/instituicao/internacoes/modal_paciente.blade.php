<div id="modalPaciente" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span>Pesquisar Paciente</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="formPesquisarPaciente">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <form action="javascript:void(0)" id="FormTitular">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                        <input type="text" id="nome" wire:model.lazy="nome" name="nome" class="form-control" placeholder="Pesquise por nome...">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                        <input type="text" id="cpf" alt="cpf" wire:model.lazy="cpf" name="cpf" class="form-control" placeholder="Pesquise cpf">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group" style="margin-bottom: 10px !important; float: right; width: 100%">
                                        <button type="submit" id="pesquisar" class="btn waves-effect waves-light btn-block btn-success" wire:click="pesquisar">Pesquisar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    
                        <hr>
                    
                        <div id='tabela'></div>
                        
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>
