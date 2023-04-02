<div class="modal inmodal fade bs-example-modal-lg" id="modalAdicionarCampo" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Adicionar campo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="col-lg-10 col-md-10">
                                        
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs customtab" role="tablist">

                        <li class="nav-item"> <a class="nav-link tab-tipo-campo active show" data-toggle="tab" href="#tipo-campo" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-network"></i> Tipo do campo</span></a> </li>

                        <li class="nav-item" > <a class="nav-link tab-dados-campos dados-campos disabled" data-toggle="tab" href="#dados-campos" role="tab" ><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-box-outline"></i> Dados do campo</span></a> </li>
                        
                    </ul>

                    <form id="formAddCampo" method="post" enctype="multipart/form-data">
                    <div class="tab-content">
                            <div class="tab-pane p-20 active show" id="tipo-campo" role="tabpanel">
                                <div class="tipo-campo">
                                    <div class=" col-md-6 form-group @if($errors->has('tipo_item')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Tipo</label>
                                        <select class="form-control select2 @if($errors->has('tipo_item')) form-control-danger @endif" name="tipo_item" id="tipo_item" style="width: 100%">
                                            <option value="">Selecione um tipo</option>
                                                <option value="texto_curto">Texto curto</option>
                                                <option value="texto_longo">Texto longo</option>
                                                <option value="select">Select</option>
                                                <option value="radio">Radio</option>
                                                <option value="checkbox">Checkbox</option>
                                                <option value="cid">CID</option>
                                                <option value="imc">IMC</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane p-20" id="dados-campos" role="tabpanel">
                                <div class="dados-campos">
                                    teste
                                </div>
                            </div>
                        </div>
                    </form>

                        
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect text-left salvar_add_campo">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>