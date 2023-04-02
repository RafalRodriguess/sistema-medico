@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => 'Cadastrar configuração fiscal',
    'breadcrumb' => [
        'Configuração fiscal' => route('instituicao.configuracaoFiscal.index'),
        'Novo',
    ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.configuracaoFiscal.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md-3 form-group @if($errors->has('cod_servico_municipal')) has-danger @endif">
                        <label class="form-control-label">Cod Serviço Municipal: *</span></label>
                        <input type="text" name="cod_servico_municipal" value="{{ old('cod_servico_municipal') }}"
                        class="form-control @if($errors->has('cod_servico_municipal')) form-control-danger @endif">
                        @if($errors->has('cod_servico_municipal'))
                        <div class="form-control-feedback">{{ $errors->first('cod_servico_municipal') }}</div>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição: *</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @error('regime') has-danger @enderror">
                        <label class="form-control-label">Regime de tributação *</label>
                        <select required id='regime' name="regime"
                            class="form-control @error('regime') form-control-danger @enderror">
                            <option value="">Selecione</option>
                            @foreach ($regimes as $item)
                                <option value="{{$item}}" @if(old('regime') == $item) selected @endif>
                                    {{App\ConfiguracaoFiscal::regime_texto($item)}}
                                </option>
                            @endforeach
                        </select>
                        @error('regime')
                            <div class="form-control-feedback">{{ $errors->first('regime')  }}</div>
                        @enderror
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('cnae')) has-danger @endif">
                        <label class="form-control-label">CNAE: *</span></label>
                        <input type="text" name="cnae" value="{{ old('cnae') }}"
                        class="form-control @if($errors->has('cnae')) form-control-danger @endif">
                        @if($errors->has('cnae'))
                        <div class="form-control-feedback">{{ $errors->first('cnae') }}</div>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('item_lista_servicos')) has-danger @endif">
                        <label class="form-control-label">Item lista de serviço: *</label>
                        <select name='item_lista_servicos' class="select2 form-control @if($errors->has('item_lista_servicos')) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach(App\ConfiguracaoFiscal::getListaServicos() as $itens)
                                <optgroup label="{{$itens['codigo']}} - {{$itens['grupo']}}" >
                                    @foreach($itens['servicos'] as $item)
                                        <option value="{{$item['codigo']}}" @if(old('item_lista_servicos')) checked @endif>{{$item['codigo']}} {{$item['descricao']}}</option>
                                    @endforeach
                            @endforeach
                        </select> 
                        @if($errors->has('item_lista_servicos'))
                            <div class="form-control-feedback">{{ $errors->first('item_lista_servicos') }}</div>
                        @endif
                    </div>
                
                    <div class=" col-md-3 form-group @if($errors->has('aliquota_iss')) has-danger @endif">
                        <label class="form-control-label">Aliquota ISS:</span></label>
                        <input type="text" alt="decimal" name="aliquota_iss" value="{{ old('aliquota_iss') }}"
                        class="form-control @if($errors->has('aliquota_iss')) form-control-danger @endif">
                        @if($errors->has('aliquota_iss'))
                        <div class="form-control-feedback">{{ $errors->first('aliquota_iss') }}</div>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @error('iss_retido_fonte') has-danger @enderror">
                        <label class="form-control-label">ISS retido na fonte *</label>
                        <select required id='iss_retido_fonte' name="iss_retido_fonte"
                            class="form-control @error('iss_retido_fonte') form-control-danger @enderror">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                        @error('iss_retido_fonte')
                            <div class="form-control-feedback">{{ $errors->first('iss_retido_fonte')  }}</div>
                        @enderror
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('p_pis')) has-danger @endif">
                        <label class="form-control-label">Percentual PIS:</span></label>
                        <input type="text" alt="decimal" name="p_pis" value="{{ old('p_pis') }}"
                        class="form-control @if($errors->has('p_pis')) form-control-danger @endif">
                        @if($errors->has('p_pis'))
                        <div class="form-control-feedback">{{ $errors->first('p_pis') }}</div>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('p_cofins')) has-danger @endif">
                        <label class="form-control-label">Percentual COFINS:</span></label>
                        <input type="text" alt="decimal" name="p_cofins" value="{{ old('p_cofins') }}"
                        class="form-control @if($errors->has('p_cofins')) form-control-danger @endif">
                        @if($errors->has('p_cofins'))
                        <div class="form-control-feedback">{{ $errors->first('p_cofins') }}</div>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('p_inss')) has-danger @endif">
                        <label class="form-control-label">Percentual INSS:</span></label>
                        <input type="text" alt="decimal" name="p_inss" value="{{ old('p_inss') }}"
                        class="form-control @if($errors->has('p_inss')) form-control-danger @endif">
                        @if($errors->has('p_inss'))
                        <div class="form-control-feedback">{{ $errors->first('p_inss') }}</div>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('p_ir')) has-danger @endif">
                        <label class="form-control-label">Percentual IR:</span></label>
                        <input type="text" alt="decimal" name="p_ir" value="{{ old('p_ir') }}"
                        class="form-control @if($errors->has('p_ir')) form-control-danger @endif">
                        @if($errors->has('p_ir'))
                        <div class="form-control-feedback">{{ $errors->first('p_ir') }}</div>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class=" col-md-3 form-group @if($errors->has('usuario')) has-danger @endif">
                        <label class="form-control-label">Usuario do provedor Municipal:</label>
                        <input type="text" name="usuario" value="{{ old('usuario') }}"
                        class="form-control @if($errors->has('usuario')) form-control-danger @endif">
                        @if($errors->has('usuario'))
                        <div class="form-control-feedback">{{ $errors->first('usuario') }}</div>
                        @endif
                    </div>
                    
                    <div class=" col-md-3 form-group @if($errors->has('senha')) has-danger @endif">
                        <label class="form-control-label">Senha do provedor Municipal:</label>
                        <input type="password" name="senha" value="{{ old('senha') }}"
                        class="form-control @if($errors->has('senha')) form-control-danger @endif">
                        @if($errors->has('senha'))
                        <div class="form-control-feedback">{{ $errors->first('senha') }}</div>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('ambiente')) has-danger @endif">
                        <label class="form-control-label">Ambiente: </label>
                        <select name="ambiente" class="form-control @if($errors->has('ambiente')) form-control-danger @endif">
                            <option value="">Selecione</option>
                            <option value="Homologacao" @if(old('ambiente') == "Homologacao") selected @endif>Homologacão</option>
                            <option value="Producao" @if(old('ambiente') == "Producao") selected @endif>Producão</option>
                        </select>
                        @if($errors->has('ambiente'))
                            <div class="form-control-feedback">{{ $errors->first('ambiente') }}</div>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @error('regime_especial_tributacao') has-danger @enderror">
                        <label class="form-control-label">Regime Especial Tributação*</label>
                        <select id='regime_especial_tributacao' name="regime_especial_tributacao" class="form-control @error('regime_especial_tributacao') form-control-danger @enderror">
                            <option value="0" @if(old('regime_especial_tributacao') == 0) selected @endif>Nenhum</option>
                            <option value="7" @if(old('regime_especial_tributacao') == 7) selected @endif>Nenhum - Com Envio de alíquota</option>
                            <option value="2" @if(old('regime_especial_tributacao') == 2) selected @endif>Estimativa</option>
                            <option value="1" @if(old('regime_especial_tributacao') == 1) selected @endif>Microempresa Municipal</option>
                            <option value="3" @if(old('regime_especial_tributacao') == 3) selected @endif>Sociedade de Profissionais</option>
                            <option value="4" @if(old('regime_especial_tributacao') == 4) selected @endif>Cooperativa</option>
                            <option value="5" @if(old('regime_especial_tributacao') == 5) selected @endif>MEI - Simples Nacional</option>
                            <option value="8" @if(old('regime_especial_tributacao') == 8) selected @endif>MEI - Simples Nacional - Com Envio De Alíquota</option>
                            <option value="6" @if(old('regime_especial_tributacao') == 6) selected @endif>ME EPP - Simples Nacional</option>
                            <option value="9" @if(old('regime_especial_tributacao') == 9) selected @endif>ME EPP - Simples Nacional - Com Envio De Alíquota</option>
                        </select>
                        @error('regime_especial_tributacao')
                            <div class="form-control-feedback">{{ $errors->first('regime_especial_tributacao')  }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-sm-12">
                        <div class=" col-md-12 form-group @if($errors->has('certificado')) has-danger @endif">
                            <label class="form-control-label">
                                Certificado:
                            </label>
                            <input id="arquivo_upload" class="dropifyUpload" type="file" name="certificado_upload" accept=".pfx,.p12">
                            </div>
                            @if($errors->has('certificado'))
                                <div class="form-control-feedback">{{ $errors->first('certificado') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('senha_certificado')) has-danger @endif">
                        <label class="form-control-label">Senha do certificado: </label>
                        <input type="password" name="senha_certificado" value="{{ old('senha_certificado') }}"
                        class="form-control @if($errors->has('senha_certificado')) form-control-danger @endif">
                        @if($errors->has('senha_certificado'))
                        <div class="form-control-feedback">{{ $errors->first('senha_certificado') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-groupn text-right">
                    <hr>
                    <a href="{{ route('instituicao.configuracaoFiscal.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
    @endsection
    
    @push('scripts')
        <script>
            $(document).ready(function() {
                $(".dropifyUpload").dropify();
            });
        </script>
    @endpush