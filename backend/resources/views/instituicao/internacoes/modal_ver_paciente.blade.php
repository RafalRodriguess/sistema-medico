<div id="modalVerPaciente" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span>Visualizar Paciente</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form>
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <form action="javascript:void(0)">
                            <div class="row">
                                <div class="col-md-7 form-group">
                                    <label class="form-control-label p-0 m-0">Nome</label>
                                    <input type="text" name="paciente_nome" class="form-control" value="{{$paciente->nome}}" readonly/>
                                </div>
                                <div class="col-md form-group">
                                    <label class="form-control-label p-0 m-0">CPF</label>
                                    <input type="text" name="paciente_nome" class="form-control" value="{{$paciente->cpf}}" readonly/>
                                </div>
                                <div class="col-md form-group">
                                    <label class="form-control-label p-0 m-0">Data nascimento</label>
                                    <input type="date" name="data_nascimento" class="form-control" value="" readonly/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md form-group">
                                    <label class="form-control-label p-0 m-0">Telefone 1</label>
                                    <input type="text" name="telefone1" class="form-control" value="{{$paciente->telefone1}}" readonly/>
                                </div>

                                <div class="col-md form-group">
                                    <label class="form-control-label p-0 m-0">Telefone 2</label>
                                    <input type="text" name="telefone2" class="form-control" value="{{$paciente->telefone2}}" readonly/>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="form-control-label p-0 m-0">E-mail</label>
                                    <input type="text" name="email" class="form-control" value="{{$paciente->email}}" readonly/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="form-control-label p-0 m-0">Endereço</label>
                                    <input type="text" name="endereco" class="form-control" value="{{$paciente->rua}}" readonly/>
                                </div>

                                <div class="col-md-2 form-group">
                                    <label class="form-control-label p-0 m-0">Número</label>
                                    <input type="text" name="numero" class="form-control" value="{{$paciente->numero}}" readonly/>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label class="form-control-label p-0 m-0">Complemento</label>
                                    <input type="text" name="complemento" class="form-control" value="{{$paciente->complemento}}" readonly/>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label class="form-control-label p-0 m-0">Bairro</label>
                                    <input type="text" name="bairro" class="form-control" value="{{$paciente->bairro}}" readonly/>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label class="form-control-label p-0 m-0">Cidade</label>
                                    <input type="text" name="cidade" class="form-control" value="{{$paciente->cidade}}" readonly/>
                                </div>

                                <div class="col-md-1 form-group">
                                    <label class="form-control-label p-0 m-0">uf</label>
                                    <input type="text" name="estado" class="form-control" value="{{$paciente->estado}}" readonly/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8 form-group">
                                    <label class="form-control-label p-0 m-0">Referencia</label>
                                    <input type="text" name="referencia_nome" class="form-control" value="{{$paciente->referencia_nome}}" readonly/>
                                </div>
                                <div class="col-md form-group">
                                    <label class="form-control-label p-0 m-0">Telefone referencia</label>
                                    <input type="text" name="referencia_telefone" class="form-control" value="{{$paciente->referencia_telefone}}" readonly/>
                                </div>
                            </div>
                        </form>                        
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>
