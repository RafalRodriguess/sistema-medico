<?php

use Illuminate\Support\Facades\Route;


Route::post('notificacao-pagar-me','UserController@postbackPagarme')->name('api.notificacao-pagarme');

Route::post('notas_fiscais/get_status', 'NotasFiscais@getStatusWebHook')->name('instituicao.notasFiscais.getStatusWebHook');
Route::post('apibb/boletos', 'WebhookBB@statusApiWh')->name('instituicao.webhookBB.statusApiWh');

Route::post('acesso_externo/getPrestadores', 'AcessoExternoController@getPrestadoresExterno');
Route::post('acesso_externo/getPrestador', 'AcessoExternoController@getPrestadorExterno');
Route::post('acesso_externo/getEspecialidades', 'AcessoExternoController@getEspecialidadesExterno');
Route::post('acesso_externo/getEspecialidadeUnica', 'AcessoExternoController@getEspecialidadeUnica');
Route::post('acesso_externo/getConveniosProcedimentoPrestadorExterno', 'AcessoExternoController@getConveniosProcedimentoPrestadorExterno');
Route::post('acesso_externo/salvarAgenda', 'AcessoExternoController@salvarAgenda');

// Autenticação
Route::post('login', 'AuthController@authenticate')->name('api.login');
Route::post('register', 'AuthController@register')->name('api.register');
Route::post('self_revoke', 'AuthController@selfRevoke')->name('api.self_revoke');
Route::post('send_token_recover_password', 'AuthController@send_token_recover_password');
Route::post('verify_token_recover_password', 'AuthController@verify_token_recover_password');
Route::post('password_reset', 'AuthController@password_reset');

//GET CONVENIOS
Route::post('getConvenios','ConveniosController@getConvenios')->name('getConvenios');

Route::group([
    'middleware' => [
        'auth:sanctum'
    ]
], function () {
    // Rotas protegidas

    Route::match(['get', 'post'], 'me', 'ProfileController@index')->name('api.me');

    //COMERCIAL
    Route::get('comerciais', 'ComercialController@index')->name('api.comerciais');

    ///PRODUTOS
    Route::get('comerciais/{comercial}/produtos', 'ComercialProdutoController@index')->name('api.comercialProdutos');

    Route::get('produto/{produto}', 'ComercialProdutoController@descricao')->name('api.produtoDescricao');

    ///MEDICAMENTOS
    Route::get('medicamentos', 'MedicamentoController@index')->name('api.medicamentos');

    Route::get('medicamentos/{medicamento}/produtos', 'MedicamentoProdutoController@index')->name('api.medicamentoProdutos');

    ///////////////
    Route::post('instituicoes/{instituicao}/vincular', 'InstituicoesController@vincular')
        ->name('api.instuicao.vincular');

    Route::post('prontuarios/lista', 'ProntuariosController@index')
        ->name('api.prontuarios');
    
    Route::post('getInstituicoes', 'ProntuariosController@getInstituicoes')
        ->name('api.getInstituicoes');

    Route::post('prontuarios/visualizar', 'ProntuariosController@visualizar')
        ->name('api.prontuario');

    Route::post('documentos/lista', 'DocumentosController@index')
        ->name('api.documentos.lista');
    
    Route::post('getGrupos', 'DocumentosController@getGrupos')
        ->name('api.getGrupos');

    Route::post('documentos/visualizar', 'DocumentosController@visualizar')
        ->name('api.documento');

    Route::post('documentos/visualizar-resultado', 'DocumentosController@visualizarResultado')
        ->name('api.documento-resultado');

    Route::post('documentos/visualizar-resultado-html', 'DocumentosController@visualizarResultadoHtml')
        ->name('api.documento-resultado-html');

    Route::post('sincronizacao/instituicoes', 'SincronizacoesController@instituicoes')
        ->name('api.sincronizar-instituicoes');

    Route::post('sincronizacao/verificar-instituicoes-usuario', 'SincronizacoesController@instituicoesVerificarUsuario')
        ->name('api.sincronizar-instituicoes-verificar-usuario');

    Route::post('sincronizacao/instituicoes-detalhes', 'SincronizacoesController@instituicoesDetalhes')
        ->name('api.sincronizar-instituicoes-detalhes');

    Route::post('sincronizacao/instituicoes-validar', 'SincronizacoesController@instituicoesValidar')
        ->name('api.sincronizar-instituicoes-validar');

    Route::post('sincronizacao/instituicoes-paciente', 'SincronizacoesController@instituicoesPaciente')
        ->name('api.sincronizar-instituicoes-paciente');

    Route::post('sincronizacao/instituicoes-paciente-manual', 'SincronizacoesController@instituicoesPacienteManual')
        ->name('api.sincronizar-instituicoes-paciente-manual');

    Route::post('perfil/atualizar-dados', 'ProfileController@atualizar')->name('api.perfil-atualizar');

    //USUARIO
    Route::post('enderecoUsuario', 'UserController@enderecos')->name('api.enderecoUsuario');
    Route::get('enderecoUsuario/{endereco}', 'UserController@getEndereco')->name('api.getEnderecoUsuario');

    Route::get('listaEndereco', 'UserController@listaEndereco')->name('api.listaEndereco');
    Route::post('salvarEnderecoUsuario', 'UserController@salvarEndereco')->name('api.salvarEnderecoUsuario');
    Route::post('editandoEnderecoUsuario', 'UserController@editandoEndereco')->name('api.editandoEnderecoUsuario');

    Route::post('excluirEndereco/{endereco}', 'UserController@excluirEndereco')->name('api.excluirEndereco');

    Route::get('pedidosUsuario', 'UserController@pedidosUsuario')->name('api.pedidosUsuario');
    Route::get('pedidoDetalhesUsuario/{pedido}', 'UserController@pedidoDetalhesUsuario')->name('api.pedidoDetalhesUsuario');

    Route::post('pedidoMensagensUsuario/{pedido}', 'UserController@pedidoMensagensUsuario')->name('api.pedidoMensagensUsuario');
    Route::post('enviarMensagemPedido/{pedido}', 'UserController@enviarMensagemPedido')->name('api.enviarMensagemPedido');

    Route::get('listaCartao', 'UserController@listaCartao')->name('api.listaCartao');
    Route::post('excluirCartao/{cartao}', 'UserController@excluirCartao')->name('api.excluirCartao');

    Route::get('statusEntregue/{pedidoId}', 'UserController@statusEntregue')->name('api.statusEntregue');

    //Carrinho
    Route::post('finalizarCarrinho', 'UserController@finalizarCarrinho')->name('api.finalizarCarrinho');

    //TOKEN FIREBASE
    Route::post('RegisterTokenFcm', 'UserController@RegisterTokenFcm')->name('api.RegisterTokenFcm');

    //INSTITUICAO
    Route::get('exames', 'InstituicoesController@index')->name('api.exames');
    Route::get('instituicaoExame', 'InstituicoesController@instituicaoExame')->name('api.instituicaoExame');
    Route::get('instituicao/{instituicao}', 'InstituicoesController@getInstituicao')->name('api.getInstituicao');

    //ESPECIALIDADES
    Route::get('especialidades/{instituicao}', 'EspecialidadesController@getEspecialidades')->name('api.getEspecialidades');
    Route::get('especialidade/{especialidade}', 'EspecialidadesController@especialidade')->name('api.especialidade');

    //PRESTADORES E FINALIZAR CONSULTA
    Route::post('prestadores', 'PrestadoresController@getPrestadores')->name('api.getPrestadores');
    Route::post('prestador', 'PrestadoresController@prestador')->name('api.prestador');
    Route::post('getConveniosProcedimentoPrestador', 'PrestadoresController@getConveniosProcedimentoPrestador')->name('api.getConveniosProcedimentoPrestador');
    Route::post('finalizarConsulta', 'PrestadoresController@finalizarConsulta')->name('api.finalizarConsulta');

    //AGENDAMENTOS USUARIOS CONSULTAS
    Route::get('agendamentosConsultasUsuario','AgendamentosUsuarioController@consultasUsuario')->name('api.consultasUsuario');
    Route::get('agendamentoConsultaDetalhesUsuario/{agendamento}','AgendamentosUsuarioController@agendamentoConsultaDetalhesUsuario')->name('api.agendamentoConsultaDetalhesUsuario');

    //AGENDA GRUPOS PROCEDIMENTOS E FINALIZAR EXAME
    Route::post('grupos','AgendaGruposProcedimentosController@grupos')->name('api.grupos');
    Route::post('grupoProcedimentos','AgendaGruposProcedimentosController@grupoProcedimentos')->name('api.grupoProcedimentos');
    Route::post('agendaProcedimento','AgendaGruposProcedimentosController@agendaProcedimento')->name('api.agendaProcedimento');
    Route::post('getDadosExameFinalizar','AgendaGruposProcedimentosController@getDadosExameFinalizar')->name('api.getDadosExameFinalizar');
    Route::post('finalizarExame','AgendaGruposProcedimentosController@finalizarExame')->name('api.finalizarExame');
    Route::post('finalizarBateriaExame','AgendaGruposProcedimentosController@finalizarBateriaExame')->name('api.finalizarBateriaExame');

    //AGENDAMENTOS USUARIO EXAMES
    Route::get('agendamentosExamesUsuario', 'AgendamentosUsuarioController@agendamentosExamesUsuario')->name('api.agendamentosExamesUsuario');
    Route::get('agendamentoExameDetalhesUsuario/{agendamento}','AgendamentosUsuarioController@agendamentoExameDetalhesUsuario')->name('api.agendamentoExameDetalhesUsuario');


});
