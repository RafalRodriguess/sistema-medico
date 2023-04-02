<?php

use Illuminate\Support\Facades\Route;

Route::get('login', 'Auth\LoginController@showLoginForm')->name('instituicao.login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('instituicao.logout');

Route::post('send_token_recover_password', 'Auth\ForgotPasswordController@send_token_recover_password')->name('instituicao.send_token_recover_password');
Route::get('recover_password/{token}', 'Auth\ResetPasswordController@showResetForm')->name('instituicao.recover_password');
Route::post('/password_reset', 'Auth\ResetPasswordController@password_reset')->name('instituicao.password_reset');
Route::get('notas_fiscais/prefeitura/{instituicao}', 'NotasFiscais@perfilPrefeitura')->name('instituicao.notasFiscais.perfilPrefeitura');

// Escolher estabelecimento / rotas de perfil
Route::group([
    'middleware' => [
        'auth:instituicao',
    ]
], function () {
    Route::get('/eu/instituicoes', 'EuController@instituicoes')->name('instituicao.eu.instituicoes');
    Route::get('/eu/instituicoes/{instituicao}/accessar', 'EuController@accessarInstituicoes')->name('instituicao.eu.escolher_instituicao');
});

// Estabelecimento foi escolhido
Route::group([
    'middleware' => [
        'auth:instituicao',
        'instituicao'
    ],
], function () {
    Route::get('/', 'PainelInicial@index')->name('instituicao.home');
    Route::get('/dashboard', 'PainelInicial@dashboard')->name('instituicao.dashboard');

    //USUARIO INSTITUIÇÃO
    Route::resource('instituicao_usuarios', 'Usuarios_instituicao')->names('instituicao.instituicoes_usuarios')->parameters([
        'instituicoes' => 'instituicao',
    ]);
    Route::get('instituicao_usuarios/{instituicao_usuario}/habilidades','Usuarios_instituicao@editHabilidade')->name('instituicao.instituicoes_usuarios.habilidade');
    Route::put('instituicao_usuarios/{instituicao_usuario}/habilidades','Usuarios_instituicao@updateHabilidade');
    Route::post('instituicao_usuarios/status','Usuarios_instituicao@status')->name('instituicao.instituicoes_usuarios.status');
    Route::get('instituicao_usuarios/vincular-contas/{usuario}','Usuarios_instituicao@vincularContas')->name('instituicao.instituicoes_usuarios.vincularContas');
    Route::post('instituicao_usuarios/salvar-vinculo-contas/{usuario}','Usuarios_instituicao@salvarVinculoContas')->name('instituicao.instituicoes_usuarios.salvarVinculoContas');
    Route::get('instituicao_usuarios/configuracoes','Usuarios_instituicao@configuracoes')->name('instituicao.instituicoes_usuarios.configuracoes');

    Route::get('verficiaCpfExistenteInstituicao','Usuarios_instituicao@verificaCpfExistenteInstituicao')->name('instituicao.verificaCpfExistenteInstituicao');
    Route::post('instituicao_usuarios/visualizar_prestadores/{usuario}','Usuarios_instituicao@visualizarPrestadores')->name('instituicao.instituicoes_usuarios.visualizarPrestadores');
    Route::post('instituicao_usuarios/salvar_prestadores/{usuario}','Usuarios_instituicao@salvarPrestadores')->name('instituicao.instituicoes_usuarios.salvarPrestadores');
    Route::post('instituicao_usuarios/visualizar_setores/{usuario}','Usuarios_instituicao@visualizarSetores')->name('instituicao.instituicoes_usuarios.visualizarSetores');
    Route::post('instituicao_usuarios/salvar_setores/{usuario}','Usuarios_instituicao@salvarSetores')->name('instituicao.instituicoes_usuarios.salvarSetores');

    //INSTITUIÇÃO
    Route::get('editar_instituicao', 'Instituicao_loja@edit')->name('instituicao.instituicao_loja.edit');
    Route::put('editar_instituicao', 'Instituicao_loja@update')->name('instituicao.instituicao_loja.update');

    Route::get('configuracoes', 'Instituicao_loja@configuracoes')->name('instituicao.configuracoes');
    Route::post('configuracoes/salvar-config/{instituicao}','Instituicao_loja@salvarConfig')->name('instituicao.configuracoes.salvarConfig');

    Route::get('editar_parcelas', 'Instituicao_loja@edit_parcelas')->name('instituicao.parcelas.edit');
    Route::put('editar_parcelas', 'Instituicao_loja@update_parcelas')->name('instituicao.parcelas.update');

    //ESPECIALIDADES
    Route::resource('especialidades', 'Especialidades')->names('instituicao.especialidades');
    Route::post('getespecialidades', 'Especialidades@getespecialidades')->name('instituicao.getespecialidades');

    //PRESTADORES
    Route::resource('prestadores', 'Prestadores')->names('instituicao.prestadores')->parameters([
        'prestadores' => 'prestador',
    ]);
    Route::post('getprestador', 'Prestadores@getPrestador')->name('instituicao.getprestador');    //
    Route::post('buscaprestador', 'Prestadores@buscaPrestadorInstituicao')->name('instituicao.ajax.buscaprestador');
    Route::post('buscar-especializacoes', 'Prestadores@getEspecializacoes')->name('instituicao.ajax.buscarespecializacoes');
    Route::post('buscar-especialidades', 'Prestadores@getEspecialidades')->name('instituicao.ajax.buscarespecialidades');

    //DOCUMENTOS DE PRESTADORES
    Route::resource('prestadores.documentos', 'PrestadoresDocumentos')->names('instituicao.prestadores.documentos')->parameters([
        'prestadores' => 'prestador',
        'documentos' => 'documento',
    ]);
    // DOWNLOAD DE DOCUMENTO DO PRESTADOR
    Route::get('prestadores/download/{file_path_name}', 'PrestadoresDocumentos@download')->name('instituicao.prestadoresDocumentosDownload')->where('file_path_name', '.*');

    // Atividades medicas
    Route::resource('atividades-medicas', 'AtividadesMedicas')->names('instituicao.atividadesMedicas');

    //PRESTADORES PROCEDIMENTOS
    Route::get('prestadores/{instituicao_usuario}/procedimentos','Prestadores@procedimentos')->name('instituicao.prestadores.procedimentos');
    Route::get('vincular/{instituicao_usuario}/procedimentos','Prestadores@criar_vinculacao')->name('instituicao.vincular.procedimentos');
    Route::post('vincular/procedimentos/salvar','Prestadores@salvar_vinculacao')->name('instituicao.vincular.salvar');
    Route::get('vincular/{procedimento}/{prestador}/editar', 'Prestadores@edita_vinculacao')->name('instituicao.vincular.procedimentos.editar');
    Route::post('vincular/procedimentos/editar', 'Prestadores@salvar_editar_vinculacao')->name('instituicao.salvar.procedimentos.editar');
    Route::post('getprocedimentos/prestadores', 'Prestadores@getprocedimentos')->name('instituicao.vinculacao.getprocedimentos');
    Route::post('getconvenios/prestadores', 'Prestadores@getConvenios')->name('instituicao.vinculacao.getconvenios');

    //INSTITUICAO AGENDA
    Route::get('prestador/{prestador}/agenda', 'Instituicoes_agenda@editAgendaPrestador')->name('instituicao.prestadores.getAgenda');
    Route::put('prestador/{prestador}/agenda', 'Instituicoes_agenda@updateAgendaPrestador')->name('instituicao.prestadores.agenda.update');
    Route::get('procedimentos/{InstituicaoProcedimentos}/agenda', 'Instituicoes_agenda@editAgendaProcedimento')->name('instituicao.procedimentos.getAgenda');
    Route::put('procedimento/{InstituicaoProcedimentos}/agenda', 'Instituicoes_agenda@updateAgendaProcedimento')->name('instituicao.procedimentos.agenda.update');
    Route::get('grupos/{grupo}/agenda', 'Instituicoes_agenda@editAgendaGrupo')->name('instituicao.grupos.getAgenda');
    Route::put('grupos/{grupo}/agenda', 'Instituicoes_agenda@updateAgendaGrupo')->name('instituicao.grupos.agenda.update');

    //Agenda ausente profissionais
    Route::resource('prestador/{prestador}/agenda-ausente', 'AgendasAusente')->names('instituicao.prestadores.agendaAusente');


    //PROCEDIMENTOS
    Route::resource('procedimentos', 'Procedimentos_instituicao')->names('instituicao.procedimentos');
    Route::put('instituicaoprocedimentos/{instituicaoprocedimentos}', 'Procedimentos_instituicao@update')->name('instituicao.instituicaoprocedimentos.update');
    Route::post('retiraprocedimento', 'Procedimentos_instituicao@retiraProcedimento')->name('instituicao.retiraprocedimento');
    Route::post('getprocedimentos', 'Procedimentos_instituicao@getprocedimentos')->name('instituicao.getprocedimentos');
    Route::post('getprocedimento', 'Procedimentos_instituicao@getprocedimento')->name('instituicao.getprocedimento');
    Route::post('getprocedimentosByGrupo', 'Procedimentos_instituicao@getprocedimentosByGrupo')->name('instituicao.getprocedimentosbygrupo');
    Route::post('getGrupoByProcedimento', 'Procedimentos_instituicao@getGrupoByProcedimento')->name('instituicao.getgrupobyprocedimento');
    Route::get('getProcedimentoVinculoConvenio/{convenio}', 'Procedimentos_instituicao@getProcedimentoVinculoConvenio')->name('instituicao.getProcedimentoVinculoConvenio');

    //Cadastro PROCEDIMENTOS
    Route::resource('cadastro-procedimentos', 'Procedimentos')->names('instituicao.cadastro-procedimentos');

    //Busca ajax de procedimentos convenio
    Route::post('procedimentos/buscar-procedimentos-convenio', 'Procedimentos@buscarProcedimentosConvenio')->name('instituicao.buscar-procedimentos-convenio');
    //Busca ajax de procedimentos instituicao
    Route::post('procedimentos/buscar-procedimentos-instituicao', 'Procedimentos@buscarProcedimentosInstituicao')->name('instituicao.buscar-procedimentos-instituicao');

    //MODALIDADES DE EXAME
    Route::resource('modalidades', 'ModalidadesExame')->names('instituicao.modalidades');

    //SETORES EXAME
    Route::resource('setores', 'SetoresExame')->names('instituicao.setores')->parameters([
        'setores' => 'setor'
    ]);
    Route::put('setores/switch/{setor}', 'SetoresExame@switch')->name('instituicao.setores.switch');

    //MOTIVOS DE CANCELAMENTO DE EXAME
    Route::resource('cancelamento-exame', 'MotivosCancelamentoExame')->names('instituicao.motivoscancelamentoexame')->parameters([
        'cancelamento-exame' => 'motivo'
    ]);
    Route::put('cancelamento-exame/switch/{motivo}', [\App\Http\Controllers\Instituicao\MotivosCancelamentoExame::class, 'switch'])->name('instituicao.motivoscancelamentoexame.switch');

    //ATENDIMENTOS
    Route::resource('atendimentos', 'Atendimentos')->names('instituicao.atendimentos');
    Route::post('atendimentos/buscar', 'Atendimentos@getAtendimentos')->name('instituicao.ajax.buscar-atendimentos');


    //ESCALAS MÉDICAS
    Route::resource('escalas-medicas', 'EscalasMedicas')
        ->names('instituicao.escalas-medicas')
        ->parameters(['escalas-medicas' => 'escala_medica']);

    Route::post('escalaMedica/{escalaMedica}/duplicar', 'EscalasMedicas@duplicarEscalasMedicas')->name('instituicao.escalasmedicas.duplicar');


    Route::post('getprestadores', 'Prestadores@getPrestadoresByEspecialidade')->name('instituicao.getPrestadoresByEspecialidade');

    /*
        Rota desativada -----------------------------------------------------
    */
    // //SETORES
    // Route::resource('setores', 'Setores')->names('instituicao.setores');

    //PESSOAS
    Route::resource('pessoas', 'Pessoas')->names('instituicao.pessoas')->parameters(['pessoas' => 'pessoa']);
    Route::post('getPessoa', 'Pessoas@getPessoa')->name('instituicao.pessoas.getPessoa');
    Route::get('pessoas/{pessoa}/prontuario-avulso', 'Pessoas@abrirProntuario')->name('instituicao.pessoas.abrirProntuario');
    Route::get('pessoas/{pessoa}/prontuario-visualizar', 'Pessoas@abrirProntuarioResumo')->name('instituicao.pessoas.abrirProntuarioResumo');
    //DOCUMENTOS DAS PESSOAS
    Route::resource('pessoas.documentos', 'PessoasDocumentos')
        ->names('instituicao.pessoas.documentos')->parameters(['pessoas' => 'pessoa', 'documentos' => 'documento']);
    //DOWNLOAD DE DOCUMENTO DA PESSOA
    Route::get('pessoas/download/{file_path_name}', 'PessoasDocumentos@download')->name('instituicao.pessoasDocumentosDownload')->where('file_path_name', '.*');
    //ATUALIZAÇÃO PESSOAS ASAPLAN
    Route::get('/sincronizar-pacientes-asaplan', 'Pessoas@sincronizarPessoasAsaplan')->name('sincronizar.pacientesAsaplan');


    //FORNECEDORES
    Route::resource('fornecedores', 'Fornecedores')->names('instituicao.fornecedores')->parameters(['fornecedores' => 'fornecedor']);
    Route::post('getFornecedor', 'Fornecedores@getFornecedor')->name('instituicao.fornecedores.getFornecedor');
    //DOCUMENTOS DOS FORNECEDORES
    Route::resource('fornecedores.documentos', 'FornecedoresDocumentos')
        ->names('instituicao.fornecedores.documentos')->parameters(['fornecedores' => 'fornecedor', 'documentos' => 'documento']);
    //DOWNLOAD DE DOCUMENTO DO FORNECEDOR
    Route::get('fornecedores/download/{file_path_name}', 'FornecedoresDocumentos@download')->name('instituicao.fornecedoresDocumentosDownload')->where('file_path_name', '.*');

    //FINANCEIRO
        //CENTRO DE CUSTO OBS: cc = centro de custo
        Route::resource('centros-custos', 'CentrosCustos')
            ->names('instituicao.financeiro.cc')
            ->parameters(['centros-custos' => 'centro_custo']);

    Route::post('centros-custos/buscar', 'CentrosCustos@getCentrosDeCusto')->name('instituicao.ajax.buscar-centros-custo');

    //INTERNAÇÃO
    Route::prefix('internacao')->group(function() {
        // UNIDADE DE INTERNAÇÃO
        Route::resource('unidades', 'UnidadesInternacoes')
            ->names('instituicao.internacao.unidade-internacao')
            ->parameters(['unidades' => 'unidade_internacao']);
        // ACOMODAÇÕES
        Route::resource('acomodacoes', 'Acomodacoes')
            ->names('instituicao.internacao.acomodacoes')
            ->parameters(['acomodacoes' => 'acomodacao']);
        // MOTIVOS DE ALTAS
        Route::resource('motivos-altas', 'MotivosAltas')
            ->names('instituicao.internacao.motivos-altas')
            ->parameters(['motivos-altas' => 'motivo_alta']);
        // MOTIVOS DE CANCELAMENTO DE ALTAS
        Route::resource('motivos-cancelamento-altas', 'MotivosCancelamentoAltas')
            ->names('instituicao.internacao.motivos-cancelamento-altas')
            ->parameters(['motivos-cancelamento-altas' => 'motivo_cancelamento_alta']);
        // LEITOS DAS UNIDADES
        Route::resource('unidades.leitos', 'UnidadesLeitos')
            ->names('instituicao.internacao.leitos')
            ->parameters(['unidades' => 'unidade_internacao', 'leitos' => 'leito']);

        //INSTITUIÇÕES DE TRANSFERÊNCIA
        Route::resource('instituicoes-transferencia', 'InstituicoesTransferencia')
            ->names('instituicao.internacao.instituicoes-transferencia')
            ->parameters(['instituicoes-transferencia' => 'instituicao_transferencia']);

    });

    // CENTROS CIRÚRGICOS E OBSTÉTRICOS
    Route::prefix('centros')->group(function() {
        // CENTROS CIRÚRGICOS
        Route::resource('cirurgicos', 'CentrosCirurgicos')
            ->names('instituicao.centros.cirurgicos')
            ->parameters(['cirurgicos' => 'centro_cirurgico']);
        // SALAS CIRÚRGICAS DOS CENTROS CIRÚRGICOS
        Route::resource('cirurgicos.salas', 'SalasCirurgicas')
            ->names('instituicao.centros.cirurgicos.salas')
            ->parameters(['cirurgicos' => 'centro_cirurgico', 'salas' => 'sala_cirurgica']);
        // EQUIPES CIRÚRGICAS
        Route::resource('equipes', 'EquipesCirurgicas')
            ->names('instituicao.centros.equipes')
            ->parameters(['equipes' => 'equipe_cirurgica']);
    });


    //ORIGEM
    Route::resource('origens', 'Origens')->names('instituicao.origem')->parameters([
        'origens' => 'origem'
    ]);
    Route::post('origens/buscar-origem', 'Origens@buscarOrigem')->name('instituicao.ajax.buscar-origem');

    //CONVENIOS
    Route::resource('convenios', 'Convenios_procedimentos')->names('instituicao.convenios');
    Route::post('retiraprocedimentoconvenio', 'Convenios_procedimentos@retiraProcedimentoconvenio')->name('instituicao.convenios.retiraprocedimentoconvenio');
    Route::get('conveniosgetProcedimentoPesquisaConvenio', 'Convenios_procedimentos@getProcedimentoPesquisaConvenio')->name('instituicao.convenios.getProcedimentoPesquisaConvenio');

    //PACIENTES
    Route::get('pacientes', 'Pacientes_instituicao@pacientes')->name('instituicao.pacientes');
    Route::get('pacientes/{paciente}/visualizarPaciente', 'Pacientes_instituicao@visualizarPaciente')->name('instituicao.visualizarPaciente');
    // Search paciente
    Route::post('atendimentos-urgencia/buscar-paciente', 'ContasPagar@getPacientes')->name('instituicao.ajax.buscar-paciente');
    Route::post('atendimentos-urgencia/chamar', 'AtendimentosUrgencia@chamarPaciente')->name('instituicao.ajax.chamar-paciente');

    //HORARIO FUNCIONAMENTO
    Route::get('horarios_funcionamento', 'HorariosFuncionamentoInstituicao@index')->name('instituicao.horarios_funcionamento.index');
    Route::put('horarios_funcionamento', 'HorariosFuncionamentoInstituicao@update')->name('instituicao.horarios_funcionamento.update');

    //Agendamentos
    Route::get('agendamentos', 'Agendamentos@index')->name('instituicao.agendamentos.index');
    Route::post('agendamentos/cancelarAgendamento', 'Agendamentos@cancelar_agendamento')->name('instituicao.agendamentos.cancelar_agendamento');
    Route::post('agendamentos/confirmarAgendamento', 'Agendamentos@confirmar_agendamento')->name('instituicao.agendamentos.confirmar_agendamento');
    Route::post('agendamentos/finalizarAgendamento', 'Agendamentos@finalizar_agendamento')->name('instituicao.agendamentos.finalizar_agendamento');
    Route::post('agendamentos/cancelarHorario', 'Agendamentos@cancelar_horario')->name('instituicao.agendamentos.cancelar_horario');
    Route::post('agendamentos/reativarHorario/{agendamento}', 'Agendamentos@reativarHorario')->name('instituicao.agendamentos.reativarHorario');
    Route::post('agendamentos/alterarHorario', 'Agendamentos@alterar_horario')->name('instituicao.agendamentos.alterar_horario');
    Route::post('agendamentos/modalRemarcar', 'Agendamentos@modalRemarcar')->name('instituicao.agendamentos.modalRemarcar');
    Route::post('agendamentos/modalDescricao', 'Agendamentos@modalDescricao')->name('instituicao.agendamentos.modalDescricao');
    Route::post('agendamentos/modalInserirAgenda', 'Agendamentos@modalInserirAgenda')->name('instituicao.agendamentos.modalInserirAgenda');
    Route::put('agendamentos/estornarParcialmente', 'Agendamentos@estornarParcialmente')->name('instituicao.agendamentos.estornarParcialmente');
    Route::get('agendamentos/getPacientes', 'Agendamentos@getPacientes')->name('instituicao.agendamentos.getPacientes');
    Route::get('agendamentos/getSolicitantes', 'Agendamentos@getSolicitantes')->name('instituicao.agendamentos.getSolicitantes');
    Route::get('agendamentos/getPaciente/{pessoa}', 'Agendamentos@getPaciente')->name('instituicao.agendamentos.getPaciente');
    Route::get('agendamentos/getProcedimentos/{convenio}/{prestador}', 'Agendamentos@getProcedimentos')->name('instituicao.agendamentos.getProcedimentos');
    Route::post('agendamentos/salvarProcedimentoPaciente', 'Agendamentos@salvarProcedimentoPaciente')->name('instituicao.agendamentos.salvarProcedimentoPaciente');
    Route::post('agendamentos/editarAgendamento/{agendamento}', 'Agendamentos@editarAgendamento')->name('instituicao.agendamentos.editarAgendamento');
    Route::get('agendamentos/getAuditoria/{agendamento}', 'Agendamentos@getAuditoria')->name('instituicao.agendamentos.getAuditoria');
    Route::post('agendamentos/setToobar', 'Agendamentos@setToobar')->name('instituicao.agendamentos.setToobar');
    Route::get('agendamentos/getCarteirinhas', 'Agendamentos@getCarteirinhas')->name('instituicao.agendamentos.getCarteirinhas');
    Route::post('agendamentos/editarCarteirinha/{agendamento}', 'Agendamentos@editarCarteirinha')->name('instituicao.agendamentos.editarCarteirinha');

    Route::post('agendamentos/iniciar_atendimento/', 'Agendamentos@iniciar_atendimento')->name('instituicao.agendamentos.iniciar_atendimento');
    Route::post('agendamentos/reativar_agendamento/', 'Agendamentos@reativar_agendamento')->name('instituicao.agendamentos.reativar_agendamento');
    Route::post('agendamentos/remover_agendamento/', 'Agendamentos@remover_agendamento')->name('instituicao.agendamentos.remover_agendamento');
    Route::post('agendamentos/finalizarAtendimento', 'Agendamentos@finalizar_atendimento')->name('instituicao.agendamentos.finalizar_atendimento');
    Route::post('agendamentos/ausente_agendamento', 'Agendamentos@ausente_agendamento')->name('instituicao.agendamentos.ausente_agendamento');
    Route::post('agendamentos/salvarPagamento/{agendamento}', 'Agendamentos@salvarPagamento')->name('instituicao.agendamentos.salvarPagamento');
    Route::post('agendamentos/salvarPagamentoStatus/{agendamento}', 'Agendamentos@salvarPagamentoStatus')->name('instituicao.agendamentos.salvarPagamentoStatus');
    Route::post('agendamentos/{agendamento}/cancelarParcelaPagamento/{parcela}', 'Agendamentos@cancelarParcelaPagamento')->name('instituicao.agendamentos.cancelarParcelaPagamento');
    Route::get('agendamentos/getConvenio/{pessoa}', 'Agendamentos@getConvenio')->name('instituicao.agendamentos.getConvenio');
    Route::post('agendamentos/getDiasPrestador', 'Agendamentos@getDiasPrestador')->name('instituicao.agendamentos.getDiasPrestador');
    Route::get('agendamentos/getAgendamentos/{pessoa}', 'Agendamentos@getAgendamentos')->name('instituicao.agendamentos.getAgendamentos');
    Route::post('agendamentos/imprimeAgendamento/{agendamento}', 'Agendamentos@imprimeAgendamento')->name('instituicao.agendamentos.imprimeAgendamento');
    Route::get('agendamentos/getInfoAsaplan/{pessoa}', 'Agendamentos@getInfoAsaplan')->name('instituicao.agendamentos.getInfoAsaplan');

    Route::post('agendamentos/salvaObs/{agendamento}', 'Agendamentos@salvaObs')->name('instituicao.agendamentos.salvaObs');

    Route::post('agendamentos/retornaPendente/{agendamento}', 'Agendamentos@retornaPendente')->name('instituicao.agendamentos.retornaPendente');
    Route::post('agendamentos/desistencia/{agendamento}', 'Agendamentos@setDesistencia')->name('instituicao.agendamentos.setDesistencia');



    Route::post('agendamentos/salvaObsConsultorio', 'Agendamentos@salvaObsConsultorio')->name('instituicao.agendamentos.salvaObsConsultorio');
    // Route::get('agendamentos/prontuarioRegistro', 'Agendamentos@prontuarioRegistro')->name('instituicao.agendamentos.prontuarioRegistro');
    Route::get('agendamentos/getProfissionaisDia/', 'Agendamentos@getProfissionaisDia')->name('instituicao.agendamentos.getProfissionaisDia');

    Route::get('agendamentos/getSelectProcedimentos/{convenio}/{prestador}', 'Agendamentos@getSelectProcedimentos')->name('instituicao.agendamentos.getSelectProcedimentos');

    Route::get('agendamentos/getProcedimentoPesquisa', 'Agendamentos@getProcedimentoPesquisa')->name('instituicao.agendamentos.getProcedimentoPesquisa');

    Route::post('agendamentos/getDiasAtendimentoPrestador', 'Agendamentos@getDiasAtendimentoPrestador')->name('instituicao.agendamentos.getDiasAtendimentoPrestador');

    Route::get('agendamentos/getRegistroPesquisa', 'Agendamentos@getRegistroPesquisa')->name('instituicao.agendamentos.getRegistroPesquisa');


    Route::get('agendamentos/guias/{agendamento}', 'Agendamentos@getGias')->name('instituicao.agendamentos.getGias');
    Route::post('agendamentos/upload-guia/{agendamento}', 'Agendamentos@uploadGuia')->name('instituicao.agendamentos.uploadGuia');
    Route::post('agendamentos/verifica-proximo-horario', 'Agendamentos@verificaProximoHorarioAgenda')->name('instituicao.agendamentos.verificaProximoHorarioAgenda');
    Route::post('agendamentos/get-agenda-semanal', 'Agendamentos@getAgendaSemanal')->name('instituicao.agendamentos.getAgendaSemanal');
    Route::get('agendamentos/get-dados-semanal', 'Agendamentos@getDadosSemanal')->name('instituicao.agendamentos.getDadosSemanal');
    Route::get('agendamentos/get-dados-semanal-dia', 'Agendamentos@getDadosSemanalDia')->name('instituicao.agendamentos.getDadosSemanalDia');

    Route::get('agendamentos/gera-boleto/{agendamento}', 'Agendamentos@geraBoelto')->name('instituicao.agendamentos.geraBoelto');

    //Prontuario
    Route::get('agendamentos/{agendamento}/prontuario/', 'Prontuarios@prontuario')->name('instituicao.agendamentos.prontuario');
    Route::get('agendamentos/prontuario/{paciente}/pacienteForm', 'Prontuarios@pacienteForm')->name('agendamento.prontuario.pacienteForm');
    Route::post('agendamentos/prontuario/{paciente}/pacienteUpdate', 'Prontuarios@pacienteUpdate')->name('agendamento.prontuario.pacienteUpdate');
    Route::get('agendamentos/{agendamento}/prontuario/{paciente}/prontuario-paciente', 'Prontuarios@prontuarioPaciente')->name('agendamento.prontuario.prontuarioPaciente');
    Route::post('agendamentos/{agendamento}/prontuario/{paciente}/prontuario-salvar', 'Prontuarios@prontuarioSalvar')->name('agendamento.prontuario.prontuarioSalvar');
    Route::post('agendamentos/{agendamento}/prontuario/{paciente}/prontuario-salvar-padrao', 'Prontuarios@prontuarioSalvarPadrao')->name('agendamento.prontuario.prontuarioSalvarPadrao');
    Route::get('agendamentos/{agendamento}/prontuario/{paciente}/prontuario-get-historico', 'Prontuarios@prontuarioPacienteHistorico')->name('agendamento.prontuario.prontuarioPacienteHistorico');
    Route::get('agendamentos/{agendamento}/prontuario/{paciente}/paciente-get-prontuario/{prontuario}', 'Prontuarios@pacienteGetProntuario')->name('agendamento.prontuario.pacienteGetProntuario');
    Route::post('agendamentos/{agendamento}/prontuario/{paciente}/paciente-excluir-prontuario/{prontuario}', 'Prontuarios@pacienteExcluirProntuario')->name('agendamento.prontuario.pacienteExcluirProntuario');
    Route::post('agendamentos/{agendamento}/prontuario/{paciente}/compartilhar-prontuario/{prontuario}', 'Prontuarios@compartilharProntuario')->name('agendamento.prontuario.compartilharProntuario');
    Route::get('agendamento-imprimir-prontuario/{prontuario}', 'Prontuarios@imprimirProntuario')->name('agendamento.prontuario.imprimirProntuario');
    Route::get('agendamento-modelo-prontuario/{modelo}', 'Prontuarios@getModelo')->name('agendamento.prontuario.getModelo');


    //Receituario
    Route::get('agendamentos/{agendamento}/receituario/{paciente}/receituario-paciente', 'Receituarios@receituarioPaciente')->name('agendamento.receituario.receituarioPaciente');
    Route::get('agendamentos/{agendamento}/receituario/{paciente}/receituario-get-historico', 'Receituarios@receituarioPacienteHistorico')->name('agendamento.receituario.receituarioPacienteHistorico');
    Route::post('agendamentos/{agendamento}/receituario/{paciente}/receituario-salvar-livre', 'Receituarios@receituarioSalvarLivre')->name('agendamento.receituario.receituarioSalvarLivre');
    Route::post('agendamentos/{agendamento}/receituario/{paciente}/receituario-salvar', 'Receituarios@receituarioSalvar')->name('agendamento.receituario.receituarioSalvar');
    Route::post('agendamentos/receituario/cadastrar-medicamento', 'Receituarios@cadastrarMedicamento')->name('agendamento.receituario.cadastrarMedicamento');
    Route::post('agendamentos/{agendamento}/receituario/{paciente}/paciente-excluir-receituario/{receituario}', 'Receituarios@pacienteExcluirReceituario')->name('agendamento.receituario.pacienteExcluirReceituario');
    Route::get('agendamentos/{agendamento}/receituario/{paciente}/paciente-get-receituario/{receituario}', 'Receituarios@pacienteGetReceituario')->name('agendamento.receituario.pacienteGetReceituario');
    Route::get('agendamentos/receituario/get-composicao-medicamento/{medicamento}', 'Receituarios@getComposicaoMedicamento')->name('agendamento.receituario.getComposicaoMedicamento');
    Route::post('agendamentos/{agendamento}/receituario/{paciente}/compartilhar-receituario/{receituario}', 'Receituarios@compartilharReceituario')->name('agendamento.receituario.compartilharReceituario');
    Route::get('agendamento-imprimir-receituario/{receituario}', 'Receituarios@imprimirReceituario')->name('agendamento.receituario.imprimirReceituario');
    Route::get('agendamento-modelo-receituario/{modelo}', 'Receituarios@modeloReceituario')->name('agendamento.receituario.modeloReceituario');

    //Receituario Memed
    Route::get('agendamentos/{agendamento}/receituario/{paciente}/receituario-memed', 'ReceituariosMemed@receituarioPaciente')->name('agendamento.receituario_memed.receituarioPaciente');
    Route::post('agendamentos/{agendamento}/receituario/{paciente}/salva-receituario-memed', 'ReceituariosMemed@salvaReceituario')->name('agendamento.receituario_memed.salvaReceituario');
    Route::post('agendamentos/{agendamento}/receituario/{paciente}/busca-receituario-memed/{receituario}', 'ReceituariosMemed@getReceituario')->name('agendamento.receituario_memed.getReceituario');

    //Atestado
    Route::get('agendamentos/{agendamento}/atestado/{paciente}/atestado-paciente', 'AtestadosPaciente@atestadoPaciente')->name('agendamento.atestado.atestadoPaciente');
    Route::post('agendamentos/{agendamento}/atestado/{paciente}/atestado-salvar', 'AtestadosPaciente@atestadoSalvar')->name('agendamento.atestado.atestadoSalvar');
    Route::get('agendamentos/{agendamento}/atestado/{paciente}/atestado-get-historico', 'AtestadosPaciente@atestadoPacienteHistorico')->name('agendamento.atestado.atestadoPacienteHistorico');
    Route::get('agendamentos/{agendamento}/atestado/{paciente}/paciente-get-atestado/{atestado}', 'AtestadosPaciente@pacienteGetAtestado')->name('agendamento.atestado.pacienteGetAtestado');
    Route::post('agendamentos/{agendamento}/atestado/{paciente}/paciente-excluir-atestado/{atestado}', 'AtestadosPaciente@pacienteExcluirAtestado')->name('agendamento.atestado.pacienteExcluirAtestado');
    Route::post('agendamentos/{agendamento}/atestado/{paciente}/compartilhar-atestado/{atestado}', 'AtestadosPaciente@compartilharAtestado')->name('agendamento.atestado.compartilharAtestado');
    Route::get('agendamento-imprimir-atestado/{atestado}', 'AtestadosPaciente@imprimirAtestado')->name('agendamento.atestado.imprimirAtestado');
    Route::get('agendamento-modelo-atestado/{modelo}', 'AtestadosPaciente@modeloAtestado')->name('agendamento.atestado.modeloAtestado');

    //Relatorio
    Route::get('agendamentos/{agendamento}/relatorio/{paciente}/relatorio-paciente', 'RelatoriosPaciente@relatorioPaciente')->name('agendamento.relatorio.relatorioPaciente');
    Route::post('agendamentos/{agendamento}/relatorio/{paciente}/relatorio-salvar', 'RelatoriosPaciente@relatorioSalvar')->name('agendamento.relatorio.relatorioSalvar');
    Route::get('agendamentos/{agendamento}/relatorio/{paciente}/relatorio-get-historico', 'RelatoriosPaciente@relatorioPacienteHistorico')->name('agendamento.relatorio.relatorioPacienteHistorico');
    Route::get('agendamentos/{agendamento}/relatorio/{paciente}/paciente-get-relatorio/{relatorio}', 'RelatoriosPaciente@pacienteGetRelatorio')->name('agendamento.relatorio.pacienteGetRelatorio');
    Route::post('agendamentos/{agendamento}/relatorio/{paciente}/paciente-excluir-relatorio/{relatorio}', 'RelatoriosPaciente@pacienteExcluirRelatorio')->name('agendamento.relatorio.pacienteExcluirRelatorio');
    Route::post('agendamentos/{agendamento}/relatorio/{paciente}/compartilhar-relatorio/{relatorio}', 'RelatoriosPaciente@compartilharRelatorio')->name('agendamento.relatorio.compartilharRelatorio');
    Route::get('agendamento-imprimir-relatorio/{relatorio}', 'RelatoriosPaciente@imprimirRelatorio')->name('agendamento.relatorio.imprimirRelatorio');
    Route::get('agendamento-modelo-relatorio/{modelo}', 'RelatoriosPaciente@modeloRelatorio')->name('agendamento.relatorio.modeloRelatorio');

    //Exame
    Route::get('agendamentos/{agendamento}/exame/{paciente}/exame-paciente', 'ExamesPaciente@examePaciente')->name('agendamento.exame.examePaciente');
    Route::post('agendamentos/{agendamento}/exame/{paciente}/exame-salvar', 'ExamesPaciente@exameSalvar')->name('agendamento.exame.exameSalvar');
    Route::get('agendamentos/{agendamento}/exame/{paciente}/exame-get-historico', 'ExamesPaciente@examePacienteHistorico')->name('agendamento.exame.examePacienteHistorico');
    Route::get('agendamentos/{agendamento}/exame/{paciente}/paciente-get-exame/{exame}', 'ExamesPaciente@pacienteGetExame')->name('agendamento.exame.pacienteGetExame');
    Route::post('agendamentos/{agendamento}/exame/{paciente}/paciente-excluir-exame/{exame}', 'ExamesPaciente@pacienteExcluirExame')->name('agendamento.exame.pacienteExcluirExame');
    Route::post('agendamentos/{agendamento}/exame/{paciente}/compartilhar-exame/{exame}', 'ExamesPaciente@compartilharExame')->name('agendamento.exame.compartilharExame');
    Route::get('agendamento-imprimir-exame/{exame}', 'ExamesPaciente@imprimirExame')->name('agendamento.exame.imprimirExame');
    Route::get('agendamento-modelo-exame/{modelo}', 'ExamesPaciente@modeloExame')->name('agendamento.exame.modeloExame');
    Route::get('agendamento-modelo-exame-get-procedimentos', 'ExamesPaciente@getSelectProcedimentos')->name('agendamento.exame.getSelectProcedimentos');
    Route::get('agendamento-modelo-exame-get-procedimentos-descricao', 'ExamesPaciente@getSelectProcedimentosDescricao')->name('agendamento.exame.getSelectProcedimentosDescricao');

    //Resumo paciente
    Route::get('agendamentos/resumo/{paciente}/paciente', 'ResumosPaciente@pacienteResumo')->name('agendamento.resumo.pacienteResumo');
    Route::post('agendamentos/{agendamento}/resumo/{paciente}/paciente/{prontuario}/prontuario', 'ResumosPaciente@prontuario')->name('agendamento.resumo.paciente.prontuario');
    Route::post('agendamentos/{agendamento}/resumo/{paciente}/paciente/{receituario}/receituario', 'ResumosPaciente@receituario')->name('agendamento.resumo.paciente.receituario');
    Route::post('agendamentos/{agendamento}/resumo/{paciente}/paciente/{atestado}/atestado', 'ResumosPaciente@atestado')->name('agendamento.resumo.paciente.atestado');
    Route::post('agendamentos/{agendamento}/resumo/{paciente}/paciente/{relatorio}/relatorio', 'ResumosPaciente@relatorio')->name('agendamento.resumo.paciente.relatorio');
    Route::post('agendamentos/{agendamento}/resumo/{paciente}/paciente/{exame}/exame', 'ResumosPaciente@exame')->name('agendamento.resumo.paciente.exame');
    Route::post('agendamentos/{agendamento}/resumo/{paciente}/paciente/{refracao}/refracao', 'ResumosPaciente@refracao')->name('agendamento.resumo.paciente.refracao');
    Route::post('agendamentos/{agendamento}/resumo/{paciente}/paciente/{encaminhamento}/encaminhamento', 'ResumosPaciente@encaminhamento')->name('agendamento.resumo.paciente.encaminhamento');
    Route::post('agendamentos/{agendamento}/resumo/{paciente}/paciente/{laudo}/laudo', 'ResumosPaciente@laudo')->name('agendamento.resumo.paciente.laudo');
    Route::post('agendamentos/{agendamento}/resumo/{paciente}/paciente/{conclusao}/conclusao', 'ResumosPaciente@conclusao')->name('agendamento.resumo.paciente.conclusao');

    //Solicitação de estoque consultorio
    Route::get('internacoes/{agendamento}/estoque/{paciente}', 'SolicitacoesEstoque@solicitacaoConsultorio')->name('agendamento.internacoes.estoque');
    Route::post('internacoes/{agendamento}/estoque/{paciente}/salvar', 'SolicitacoesEstoque@solicitacaoConsultorioSalvar')->name('agendamento.internacoes.estoque.salvar');
    Route::get('internacoes/{agendamento}/estoque/{paciente}/historico', 'SolicitacoesEstoque@solicitacaoConsultorioHistorico')->name('agendamento.internacoes.estoque.getHistorico');
    Route::get('internacoes/estoque/get-solicitacao/{solicitacao}', 'SolicitacoesEstoque@getSolicitacao')->name('agendamento.internacoes.estoque.getSolicitacao');
    Route::post('internacoes/estoque/delete-solicitacao/{solicitacao}', 'SolicitacoesEstoque@deleteSolicitacao')->name('agendamento.internacoes.estoque.deleteSolicitacao');

    //Arquivos paciente
    Route::get('agendamentos/arquivo/{paciente}/paciente', 'PacienteArquivos@index')->name('agendamento.arquivo.index');
    Route::post('agendamentos/arquivo/{paciente}/modal', 'PacienteArquivos@getModalUpload')->name('agendamento.arquivo.getModalUpload');
    Route::post('agendamentos/arquivo/{paciente}/novo', 'PacienteArquivos@novoArquvo')->name('agendamento.arquivo.novoArquvo');
    Route::post('agendamentos/arquivo/{paciente}/get-arquivos/{pasta}', 'PacienteArquivos@getArquivosPasta')->name('agendamento.arquivo.getArquivosPasta');
    Route::post('agendamentos/arquivo/{paciente}/excluir-pasta/{pasta}', 'PacienteArquivos@excluirPasta')->name('agendamento.arquivo.excluirPasta');
    Route::get('agendamentos/arquivo/{paciente}/baixar-arquivo/{arquivo}', 'PacienteArquivos@baixarArquivo')->name('agendamento.arquivo.baixarArquivo');
    Route::post('agendamentos/arquivo/{paciente}/excluir-arquivo/{arquivo}', 'PacienteArquivos@excluirArquivo')->name('agendamento.arquivo.excluirArquivo');
    Route::post('agendamentos/arquivo/{paciente}/visualizar-arquivo/{arquivo}', 'PacienteArquivos@visualizarArquivo')->name('agendamento.arquivo.visualizarArquivo');    
    
    // Buscar agendamento por paciente
    Route::post('agendamentos/buscaragendamento', 'Agendamentos@buscarAgendamentos')->name('instituicao.ajax.buscaagendamentos');

    //Tipo de partos
    Route::resource('tipo-partos', 'TipoPartos')->names('instituicao.tipoPartos');

    //Tipo de Anestesias
    Route::resource('tipos-anestesia', 'TiposAnestesias')->names('instituicao.tiposAnestesia')->parameters([
        "tipos-anestesia" => "tipo_anestesia"
    ]);

    //Estoques
    Route::resource('estoques', 'Estoques')->names('instituicao.estoques');

    //Motivos de Partos
    Route::resource('motivo-partos', 'MotivosPartos')->names('instituicao.motivosPartos');

    //Motivos de mortes de recem nascido
    Route::resource('motivos-mortes-rn', 'MotivosMortesRN')->names('instituicao.motivosMortesRN')->parameters([
        "motivos-mortes-rn" => "motivos_mortes_rn"
    ]);

     //Classes
     Route::resource('classes', 'Classes')->names('instituicao.classes')->parameters([
        "classes" => "classe"
    ]);

     //Especies
     Route::resource('especies', 'Especies')->names('instituicao.especies')->parameters([
        "especies" => "especie"
    ]);

    //Unidade
    Route::resource('unidades', 'Unidades')->names('instituicao.unidades');

    //Produtos
    Route::resource('produtos', 'Produtos')->names('instituicao.produtos');
    // Buscar Produtos AJAX
    Route::post('get-produtos', 'Produtos@getProdutos')->name('instituicao.ajax.buscar-produtos');

    Route::get('get-fornecedores', 'Pessoas@getFornecedores')->name('instituicao.ajax.buscar-fornecedores');

    //Formas de Pagamento
    Route::resource('formas-pagamentos', 'FormasPagamentos')->names('instituicao.formasPagamentos')->parameters([
        'formas-pagamentos' => 'forma_pagamento'
    ]);

    //tipos de documentos
    Route::resource('tipos-documentos', 'TiposDocumentos')->names('instituicao.tiposDocumentos')->parameters([
        'tipos-documentos' => 'tipo_documento'
    ]);

    //Contas
    Route::resource('contas', 'Contas')->names('instituicao.contas');

    //Cartoes Credito
    Route::resource('cartoes-credito', 'CartoesCredito')->names('instituicao.cartoesCredito')->parameters([
        'cartoes-credito' => 'cartao_credito'
    ]);

    //Plano de contas
    Route::resource('planos-contas', 'PlanosContas')->names('instituicao.planosContas')->parameters([
        'planos-contas' => 'plano_conta'
    ]);

    Route::post('planos-contas-pai/{planoConta}', 'PlanosContas@getCodigoPai')->name('instituicao.planosContas.getCodigoPai');

    //Tipo de Compras
    Route::resource('tipo-compras', 'TipoCompras')->names('instituicao.tipoCompras');

    //Solicitação de Compras
    Route::resource('solicitacao-compras', 'SolicitacaoCompras')->names('instituicao.solicitacaoCompras')->parameters([
        'solicitacao-compras' => 'solicitacao_compras'
    ]);
   
    
    //Comprador
    Route::resource('compradores', 'Compradores')->names('instituicao.compradores')->parameters([
        'compradores' => 'comprador'
    ]);

    //Motivo de Cancelamento
    Route::resource('motivo-cancelamentos', 'MotivoCancelamentos')->names('instituicao.motivoCancelamentos');

    //motivo de Pedido
    Route::resource('motivo-pedidos', 'MotivoPedidos')->names('instituicao.motivoPedidos');

    // TRIAGEM
    Route::prefix('triagem')->group(function() {
        // Triagens
        Route::resource('triagens', 'Triagens')->names('instituicao.triagens')->parameters([
            'triagens' => 'triagem'
        ]);
        // Retirar senhas
        Route::get('{totem}/retirar', 'Triagens@retirar')->name('instituicao.triagem.senhas.retirar');
        Route::post('{totem}/retirar', 'Triagens@retirarSenha')->name('instituicao.triagem.senhas.retirar-senha');
        // Totens
        Route::resource('totens', 'Totens')->names('instituicao.triagem.totens')->parameters([
            'totens' => 'totem'
        ]);
        // Filas totem
        Route::resource('filas', 'FilasTriagem')->names('instituicao.triagem.filas')->parameters([
            'filas' => 'fila'
        ]);
        // Filas totem
        Route::resource('classificacoes', 'ClassificacoesTriagem')->names('instituicao.triagem.classificacoes')->parameters([
            'classificacoes' => 'classificacao'
        ]);
        // Processos
        Route::resource('processos', 'ProcessosTriagem')->names('instituicao.triagem.processos')->parameters([
            'processos' => 'processo'
        ]);
    });

    // Grupos Cirurgias
    Route::resource('grupos-cirurgias', 'GruposCirurgias')->names('instituicao.gruposCirurgias')->parameters([
        'grupos-cirurgias' => 'grupoCirurgia'
    ]);

    //Vias Acesso
    Route::resource('vias-acesso', 'ViasAcesso')->names('instituicao.viasAcesso')->parameters([
        'vias-acesso' => 'viaAcesso'
    ]);

    //Cirurgias
    Route::resource('cirurgias', 'Cirurgias')->names('instituicao.cirurgias');

    //Equipamentos
    Route::resource('equipamentos', 'Equipamentos')->names('instituicao.equipamentos');

    //Pre Internação
    Route::resource('pre-internacoes', 'PreInternacoes')->names('instituicao.preInternacoes')->parameters([
        'pre-internacoes' => 'pre_internacao'
    ]);

    Route::post('pre-internacoes-paciente/{paciente_id}', 'PreInternacoes@getPaciente')->name('instituicao.PreInternacoes.getPaciente');
    Route::post('pre-internacoes-especialidades', 'PreInternacoes@getEspecialidades')->name('instituicao.PreInternacoes.getEspecialidades');
    Route::post('pre-internacoes-leitos/{unidade_id}', 'PreInternacoes@getLeitos')->name('instituicao.PreInternacoes.getLeitos');
    Route::post('pre-internacoes/add_paciente_modal', 'PreInternacoes@addPacienteModal')->name('instituicao.PreInternacoes.addPacienteModal');
    Route::post('pre-internacoes/salvar_paciente_modal', 'PreInternacoes@salvarPaciente')->name('instituicao.PreInternacoes.salvarPaciente');
    Route::get('pre_internacoes/getPacientes', 'PreInternacoes@getPacientes')->name('instituicao.PreInternacoes.getPacientes');
    Route::get('pre_internacoes/getProcedimentos/{convenio}', 'PreInternacoes@getProcedimentos')->name('instituicao.PreInternacoes.getProcedimentos');
    Route::get('pre_internacoes/getCids', 'PreInternacoes@getCids')->name('instituicao.PreInternacoes.getCids');

    //Contas a pagar
    Route::resource('contas-pagar', 'ContasPagar')->names('instituicao.contasPagar')->parameters([
        'contas-pagar' => 'conta_pagar'
    ]);

    Route::post('contas-pagar/get-tipo', 'ContasPagar@getTipo')->name('instituicao.contasPagar.getTipo');
    Route::post('contas-pagar/get-parcela/{conta}', 'ContasPagar@pagarParcela')->name('instituicao.contasPagar.pagarParcela');
    Route::post('contas-pagar/pagar-parcela/{conta}', 'ContasPagar@contaPagar')->name('instituicao.contasPagar.contaPagar');

    Route::post('contas-pagar/get-cartao', 'ContasPagar@getCartao')->name('instituicao.contasPagar.getCartao');

    Route::get('contas-pagar/print-recibo/{conta}', 'ContasPagar@printRecibo')->name('instituicao.contasPagar.printRecibo');

    // Rota destinada a ser utilizada com ajax para busca de pacientes
    Route::get('contaspagar/getPacientes', 'ContasPagar@getPacientes')->name('instituicao.contasPagar.getPacientes');

    Route::get('contaspagar/getFornecedores', 'ContasPagar@getFornecedores')->name('instituicao.contasPagar.getFornecedores');
    Route::get('contaspagar/getPrestadores', 'ContasPagar@getPrestadores')->name('instituicao.contasPagar.getPrestadores');
    Route::get('contas-pagar/estornar-parcela/{contaPagar}', 'ContasPagar@estornarParcela')->name('instituicao.contasPagar.estornarParcela');

    //Contas a receber
    Route::resource('contas-receber', 'ContasReceber')->names('instituicao.contasReceber')->parameters([
        'contas-receber' => 'contaReceber'
    ]);

    Route::post('contas-receber/get-tipo', 'ContasReceber@getTipo')->name('instituicao.contasReceber.getTipo');
    Route::post('contas-receber/get-parcela/{contaReceber}', 'ContasReceber@visualizarParcelas')->name('instituicao.contasReceber.visualizarParcelas');
    Route::post('contas-receber/receber-parcela/{contaReceber}', 'ContasReceber@receberParcela')->name('instituicao.contasReceber.receberParcela');
    Route::post('contas-receber/processar-parcela/{contaReceber}', 'ContasReceber@processarContaReceber')->name('instituicao.contasReceber.processarContaReceber');

    Route::get('contas-receber/print-recibo/{conta}', 'ContasReceber@printRecibo')->name('instituicao.contasReceber.printRecibo');

    Route::get('contas-receber/estornar-parcela/{contaReceber}', 'ContasReceber@estornarParcela')->name('instituicao.contasReceber.estornarParcela');

    Route::get('contas_receber/getConvenios', 'ContasReceber@getConvenios')->name('instituicao.contasReceber.getConvenios');

    Route::get('contas-receber/gera_boleto/{conta_rec}', 'ContasReceber@geraBoleto')->name('instituicao.contasReceber.geraBoleto');

    // Solicitações de estoque
    Route::resource('solicitacoes-estoque', 'SolicitacoesEstoque')->names('instituicao.solicitacoes-estoque')->parameters([
        'solicitacoes-estoque' => 'solicitacao'
    ]);
    Route::prefix('solicitacoes-estoque')->group(function() {
        Route::get('{solicitacao}/atender', 'AtendimentoSolicitacoesEstoque@edit')->name('instituicao.solicitacoes-estoque.atender.edit');
        Route::match(['PUT', 'PATCH'], '{solicitacao}/atender', 'AtendimentoSolicitacoesEstoque@update')->name('instituicao.solicitacoes-estoque.atender.update');
        Route::get('{solicitacao}/imprimir', 'AtendimentoSolicitacoesEstoque@imprimir')->name('instituicao.solicitacoes-estoque.atender.imprimir');
    });

    Route::resource('motivos-divergencia', 'MotivosDivergencia')->names('instituicao.motivos-divergencia')->parameters([
        'motivos-divergencia' => 'motivo'
    ]);

    // Saídas de estoque
    Route::resource('saidas-estoque', 'SaidasEstoque')->names('instituicao.saidas-estoque')->parameters([
        'saidas-estoque' => 'saida'
    ]);
    Route::get('saidas-estoque/{saida}/imprimir', 'SaidasEstoque@imprimir')->name('instituicao.saidas-estoque.imprimir');

    //internação
    Route::resource('internacoes', 'Internacoes')->names('instituicao.internacoes')->parameters([
        'internacoes' => 'internacao'
    ]);
    Route::post('internacoes/pesquisa-paciente', 'Internacoes@pesquisaPaciente')->name('instituicao.internacoes.pesquisaPaciente');
    Route::post('internacoes/ver-paciente', 'Internacoes@verPaciente')->name('instituicao.internacoes.verPaciente');
    Route::post('internacoes/getPaciente', 'Internacoes@getPaciente')->name('instituicao.internacoes.getPaciente');
    Route::post('internacoes/getCarteirinha', 'Internacoes@getCarteirinha')->name('instituicao.internacoes.getCarteirinha');
    Route::post('internacoes/getPreInternacoes', 'Internacoes@getPreInternacoes')->name('instituicao.internacoes.getPreInternacoes');
    Route::post('internacoes/getEspecialidades', 'Internacoes@getEspecialidades')->name('instituicao.internacoes.getEspecialidades');
    Route::post('internacoes/getLeitos', 'Internacoes@getLeitos')->name('instituicao.internacoes.getLeitos');
    Route::post('internacoes/verAlta', 'Internacoes@verAlta')->name('instituicao.internacoes.verAlta');
    Route::post('internacoes/realizarAlta', 'Internacoes@realizarAlta')->name('instituicao.internacoes.realizarAlta');
    Route::post('internacoes/cancelarAlta', 'Internacoes@cancelarAlta')->name('instituicao.internacoes.cancelarAlta');
    Route::post('internacoes/verLeito', 'Internacoes@verLeito')->name('instituicao.internacoes.verLeito');
    Route::post('internacoes/trocaLeito', 'Internacoes@trocaLeito')->name('instituicao.internacoes.trocaLeito');
    Route::post('internacoes/verMedico', 'Internacoes@verMedico')->name('instituicao.internacoes.verMedico');
    Route::post('internacoes/trocaMedico', 'Internacoes@trocaMedico')->name('instituicao.internacoes.trocaMedico');
    Route::post('internacoes/verInstituicao', 'Internacoes@verInstituicao')->name('instituicao.internacoes.verInstituicao');
    Route::post('internacoes/transferirInstituicao/{internacao}', 'Internacoes@transferirInstituicao')->name('instituicao.internacoes.transferirInstituicao');
    Route::get('internacoes/{internacao}/prontuario', 'Internacoes@abrirProntuario')->name('instituicao.internacoes.abrirProntuario');
    Route::get('internacoes/{internacao}/prontuario-visualizar', 'Internacoes@abrirProntuarioResumo')->name('instituicao.internacoes.abrirProntuarioResumo');
    Route::post('internacoes/atender-avalicao/{avaliacao}', 'Internacoes@atenderAvaliacao')->name('instituicao.internacoes.atenderAvaliacao');

    //carteirinhas de convenios das pessoas
    Route::resource('pessoas.carteirinhas', 'Carteirinhas')->names('instituicao.carteirinhas');
    Route::post('pessoas.carteirinhas/getPlanos', 'Carteirinhas@getPlanos')->name('instituicao.carteirinhas.getPlanos');



    //Estoque Entrada
    Route::get('estoqueEntrada/criar', 'EstoqueEntradaInstitucional@create')->name('instituicao.estoque_entrada.criar');
    Route::get('estoqueEntrada/', 'EstoqueEntradaInstitucional@index')->name('instituicao.estoque_entrada.index');
    Route::post('estoqueEntrada/store', 'EstoqueEntradaInstitucional@store')->name('instituicao.estoque_entrada.store');
    Route::get('estoqueEntrada/{entradaEstoque}/editar', 'EstoqueEntradaInstitucional@edit')->name('instituicao.estoque_entrada.editar');
    Route::post('estoqueEntrada/{entradaEstoque}/destroy', 'EstoqueEntradaInstitucional@destroy')->name('instituicao.estoque_entrada.destroy');
    Route::post('estoqueEntrada/{entradaEstoque}/update', 'EstoqueEntradaInstitucional@update')->name('instituicao.estoque_entrada.update');
    // Busca de produtos em entradas
    Route::post('entradas-de-estoque/buscar-entrada', 'EstoqueEntradaInstitucional@buscarEntradaProduto')->name('instituicao.ajax.getentradaprodutos');
    Route::post('entradas-de-estoque/buscar-lote', 'EstoqueEntradaInstitucional@buscarLoteProduto')->name('instituicao.ajax.getlote');

    //Estoque Inventário
    Route::get('estoqueInventario/criar', 'EstoqueInventario@create')->name('instituicao.estoque_inventario.criar');
    Route::get('estoqueInventario/', 'EstoqueInventario@index')->name('instituicao.estoque_inventario.index');
    Route::post('estoqueInventario/store', 'EstoqueInventario@store')->name('instituicao.estoque_inventario.store');
    Route::get('estoqueInventario/{estoqueInventario}/editar', 'EstoqueInventario@edit')->name('instituicao.estoque_inventario.editar');
    Route::post('estoqueInventario/{estoqueInventario}/destroy', 'EstoqueInventario@destroy')->name('instituicao.estoque_inventario.destroy');
    Route::post('estoqueInventario/{estoqueInventario}/update', 'EstoqueInventario@update')->name('instituicao.estoque_inventario.update');


    //Estoque Entrada Produtos
    Route::get('estoqueEntradaProdutos/create/{entradaEstoque}', 'EstoqueEntradaProdutoInstitucional@create')->name('instituicao.estoque_entrada_produtos.create');
    Route::get('estoqueEntradaProdutos/criar', 'EstoqueEntradaProdutoInstitucional@criar')->name('instituicao.estoque_entrada_produtos.criar');
    Route::post('estoqueEntradaProdutos/store/{entradaEstoque}', 'EstoqueEntradaProdutoInstitucional@store')->name('instituicao.estoque_entrada_produtos.store');
    Route::get('estoqueEntradaProdutos/{entradaEstoque}', 'EstoqueEntradaProdutoInstitucional@index')->name('instituicao.estoque_entrada_produtos.index');
    Route::get('estoqueEntradaProdutos/{entradaEstoque}/editar/{estoqueEntradaProduto}', 'EstoqueEntradaProdutoInstitucional@edit')->name('instituicao.estoque_entrada_produtos.editar');
    Route::post('estoqueEntradaProdutos/{entradaEstoque}/update/{estoqueEntradaProduto}', 'EstoqueEntradaProdutoInstitucional@update')->name('instituicao.estoque_entrada_produtos.update');
    Route::post('estoqueEntradaProdutos/{entradaEstoque}/destroy/{estoqueEntradaProduto}', 'EstoqueEntradaProdutoInstitucional@destroy')->name('instituicao.estoque_entrada_produtos.destroy');



     //Estoque Baixa Produtos
     Route::get('estoqueBaixaProdutos/criar', 'EstoqueBaixaProdutos@create')->name('instituicao.estoque_baixa_produtos.criar');
     Route::get('estoqueBaixaProdutos/', 'EstoqueBaixaProdutos@index')->name('instituicao.estoque_baixa_produtos.index');
     Route::post('estoqueBaixaProdutos/store', 'EstoqueBaixaProdutos@store')->name('instituicao.estoque_baixa_produtos.store');
     Route::get('estoqueBaixaProdutos/{estoqueBaixa}/editar', 'EstoqueBaixaProdutos@edit')->name('instituicao.estoque_baixa_produtos.editar');
     Route::post('estoqueBaixaProdutos/{estoqueBaixa}/destroy', 'EstoqueBaixaProdutos@destroy')->name('instituicao.estoque_baixa_produtos.destroy');
     Route::post('estoqueBaixaProdutos/{estoqueBaixa}/update', 'EstoqueBaixaProdutos@update')->name('instituicao.estoque_baixa_produtos.update');

     //Produtos Baixa
     Route::get('estoqueBaixa/{estoqueBaixa}/criar', 'ProdutosBaixa@create')->name('instituicao.produtos_baixa.criar');
     Route::post('estoqueBaixa/{estoqueBaixa}/store', 'ProdutosBaixa@store')->name('instituicao.produtos_baixa.store');
     Route::get('estoqueBaixa/{estoqueBaixa}/produtosBaixa', 'ProdutosBaixa@index')->name('instituicao.produtos_baixa.index');
     Route::get('estoqueBaixa/{estoqueBaixa}/editar/{produtoBaixa}', 'ProdutosBaixa@edit')->name('instituicao.produtos_baixa.editar');
     Route::post('estoqueBaixa/{estoqueBaixa}/destroy/{produtoBaixa}', 'ProdutosBaixa@destroy')->name('instituicao.produtos_baixa.destroy');
     Route::post('estoqueBaixa/{estoqueBaixa}/update/{produtoBaixa}', 'ProdutosBaixa@update')->name('instituicao.produtos_baixa.update');

    // Atendimentos de Urgência
    Route::get('atendimentos-urgencia', 'AtendimentosUrgencia@index')->name('instituicao.atendimentos-urgencia.index');
    Route::post('atendimentos-urgencia/modal-atendimento', 'AtendimentosUrgencia@modalAtendimento')->name('instituicao.atendimentos-urgencia.modal-atendimento');
    Route::post('atendimentos-urgencia/{senha}/finalizar-atendimento', 'AtendimentosUrgencia@finalizarAtendimento')->name('instituicao.atendimentos-urgencia.finalizar-atendimento');
    Route::get('atendimentos-urgencia/modal-atendimento', 'AtendimentosUrgencia@visualizarAtendimento')->name('instituicao.atendimentos-urgencia.modal-atendimento-visualizar');

    //DEMONSTRATIVO FINANCEIRO
    Route::get('demonstrativo-financeiro', 'DemonstrativosFinanceiro@index')->name('instituicao.demonstrativoFinanceiro.index');
    Route::post('demonstrativo-financeiro-tabela', 'DemonstrativosFinanceiro@tabela')->name('instituicao.demonstrativoFinanceiro.tabela');


    //AGENDAMENTOS CENTRO CIRURGICO
    Route::get('agendamento-centro-cirurgicos', 'AgendamentoCentroCirurgicos@index')->name('instituicao.agendamentoCentroCirurgico.index');
    Route::post('centro-cirurgicos-get-agenda', 'AgendamentoCentroCirurgicos@getAgenda')->name('instituicao.agendamentoCentroCirurgico.getAgenda');
    Route::post('centro-cirurgicos-nova-agenda', 'AgendamentoCentroCirurgicos@novaAgenda')->name('instituicao.agendamentoCentroCirurgico.novaAgenda');
    Route::post('get-cirurgias-sala', 'AgendamentoCentroCirurgicos@cirurgiasSalas')->name('instituicao.agendamentoCentroCirurgico.cirurgiasSalas');
    Route::post('agendamento-centro-cirurgicos-salvar', 'AgendamentoCentroCirurgicos@salvarAgendamento')->name('instituicao.agendamentoCentroCirurgico.salvarAgendamento');
    Route::post('agendamento-centro-cirurgicos-excluir/{agendamento}', 'AgendamentoCentroCirurgicos@excluirAgendamento')->name('instituicao.agendamentoCentroCirurgico.excluirAgendamento');
    Route::post('agendamento-centro-cirurgicos-editar/{agendamento}', 'AgendamentoCentroCirurgicos@editarAgenda')->name('instituicao.agendamentoCentroCirurgico.editarAgenda');
    Route::post('agendamento-centro-cirurgicos-update/{agendamento}', 'AgendamentoCentroCirurgicos@updateAgenda')->name('instituicao.agendamentoCentroCirurgico.updateAgenda');
    Route::post('agendamento-centro-cirurgicos-equipamentos-caixas-cirurgicos/{agendamento}', 'AgendamentoCentroCirurgicos@equipamentosCaixasCirurgicos')->name('instituicao.agendamentoCentroCirurgico.equipamentosCaixasCirurgicos');
    Route::post('agendamento-centro-cirurgicos-outras-cirurgias/{agendamento}', 'AgendamentoCentroCirurgicos@outrasCirurgias')->name('instituicao.agendamentoCentroCirurgico.outrasCirurgias');
    Route::post('agendamento-centro-cirurgicos-sangues-derivados/{agendamento}', 'AgendamentoCentroCirurgicos@sanguesDerivados')->name('instituicao.agendamentoCentroCirurgico.sanguesDerivados');
    Route::post('agendamento-centro-cirurgicos-produtos/{agendamento}', 'AgendamentoCentroCirurgicos@produtos')->name('instituicao.agendamentoCentroCirurgico.produtos');
    Route::get('agendamentocentrocirurgicosgetpacientes', 'AgendamentoCentroCirurgicos@getPacientes')->name('instituicao.agendamentoCentroCirurgico.getPacientes');
    Route::get('agendamentocentrocirurgicosgetpacientesUrgencia', 'AgendamentoCentroCirurgicos@getPacientesUrgencia')->name('instituicao.agendamentoCentroCirurgico.getPacientesUrgencia');
    Route::get('agendamentocentrocirurgicosgetpacientesInternacao', 'AgendamentoCentroCirurgicos@getPacientesInternacao')->name('instituicao.agendamentoCentroCirurgico.getPacientesInternacao');
    Route::get('agendamentocentrocirurgicosgetcids', 'AgendamentoCentroCirurgicos@getCids')->name('instituicao.agendamentoCentroCirurgico.getCids');
    Route::get('agendamentocentrocirurgicosgetProdutos', 'AgendamentoCentroCirurgicos@getProdutos')->name('instituicao.agendamentoCentroCirurgico.getProdutos');
    Route::post('agendamentocentrocirurgicosgetFornecedores/{produto}', 'AgendamentoCentroCirurgicos@getFornecedores')->name('instituicao.agendamentoCentroCirurgico.getFornecedores');
    Route::post('agendamentocentrocirurgicosgetLotesFornecedores/{produto}/{fornecedor}', 'AgendamentoCentroCirurgicos@getLotesFornecedores')->name('instituicao.agendamentoCentroCirurgico.getLotesFornecedores');
    Route::post('agendamento-centro-cirurgicos-dados-complementares/{agendamento}', 'AgendamentoCentroCirurgicos@dadosComplementares')->name('instituicao.agendamentoCentroCirurgico.dadosComplementares');
    Route::get('agendamento-centro-cirurgicos-ficha-cirurgica/{agendamento}', 'AgendamentoCentroCirurgicos@fichaCirurgica')->name('instituicao.agendamentoCentroCirurgico.fichaCirurgica');
    Route::get('agendamento-centro-cirurgicos-folha-sala/{agendamento}', 'AgendamentoCentroCirurgicos@folhaSala')->name('instituicao.agendamentoCentroCirurgico.folhaSala');
    Route::post('agendamento-centro-cirurgicos-mudar-status/{agendamento}', 'AgendamentoCentroCirurgicos@mudarStatusAgendamento')->name('instituicao.agendamentoCentroCirurgico.mudarStatusAgendamento');
    Route::post('agendamento-centro-cirurgicos-gerar-estoque/{agendamento}', 'AgendamentoCentroCirurgicos@gerarEstoque')->name('instituicao.agendamentoCentroCirurgico.gerarEstoque');
    
    
    // APRESENTAÇÕES CONVENIOS
    Route::resource('convenios-apresentacoes', 'ApresentacoesConvenio')->names('instituicao.convenios.apresentacoes')->parameters([
        'apresentacoes' => 'apresentacao',
    ]);
    //INSTITUIÇÃO CONVENIOS
    Route::resource('convenio', 'Convenios')->names('instituicao.convenio')->parameters([
        'convenios' => 'convenio',
    ]);
    Route::post('convenio-prestador', 'Convenios@convenioPrestador')->name('instituicao.convenio.convenioPrestador');
    Route::post('convenios/buscar-convenios', 'Convenios@buscarConvenios')->name('instituicao.buscar-convenios');

    //INSTITUIÇÃO CONVENIOS_PLANOS
    Route::resource('convenios.planos', 'ConveniosPlanos')->names('instituicao.convenios.planos')->parameters([
        'planos' => 'plano',
    ]);
    Route::post('convenios-planos/get-convenios-planos', 'ConveniosPlanos@getConveniosPlanos')->name('instituicao.ajax.get-convenios-planos');

    // FATURAMENTO CONVENIOS LOTES/PROTOCOLOS
    Route::resource('faturamento-lotes', 'FaturamentoLotes')->names('instituicao.faturamento.lotes')->parameters([
        'faturamento_lotes' => 'faturamento_lotes',
    ]);
    Route::get('faturamento-lotes-guias/{faturamento}', 'FaturamentoLotes@guias')->name('instituicao.faturamento.lotesGuias');
    Route::get('faturamento-lotes-guias-sancoop/{faturamento}', 'FaturamentoLotes@guiasSancoop')->name('instituicao.faturamento.lotesGuiasSancoop');
    Route::post('faturamento-lotes-guias-consulta-agendamentos', 'FaturamentoLotes@guias_agendamentos')->name('instituicao.faturamento.lotesGuiasAgendamentos');
    Route::post("faturamento-lotes-guias-filtros", "FaturamentoLotes@tabelaFiltros")->name('instituicao.faturamento.tabelaFiltros');
    Route::post("faturamento-lotes-guias-adicionar-guias", "FaturamentoLotes@adicionarGuias")->name('instituicao.faturamento.adicionarGuias');
    Route::post("faturamento-lotes-guias-remover-do-lote/{faturamento}", "FaturamentoLotes@removerGuiasLote")->name('instituicao.faturamento.removerGuiasLote');
    Route::post("faturamento-lotes-guias-add-pendente-lote/{faturamento}", "FaturamentoLotes@addGuiasPendenteLote")->name('instituicao.faturamento.addGuiasPendenteLote');

    //CAIXAS CIRURGICOS
    Route::resource('caixas-cirurgicos', 'CaixasCirurgicos')->names('instituicao.caixasCirurgicos')->parameters([
        'caixas-cirurgicos' => 'caixa_cirurgico',
    ]);

    //SANGUE E DERIVADOS
    Route::resource('sangues-derivados', 'SanguesDerivados')->names('instituicao.sanguesDerivados')->parameters([
        'sangues-derivados' => 'sangue_derivado',
    ]);

    // Paineis totem
    Route::resource('paineis-totem', 'PaineisTotem')->names('instituicao.totens.paineis')->parameters([
        'paineis-totem' => 'painel'
    ]);
    Route::resource('tipos-chamada', 'TiposChamadaTotem')->names('instituicao.totens.tipos-chamada')->parameters([
        'tipos-chamada' => 'tipo'
    ]);

    // Chamar senhas
    Route::any('paineis-totem/chamar', 'ChamadasTotem@chamar')->name('instituicao.totens.paineis.chamar');


    //PAINEL ESCALA_MEDICA
    Route::get('painelEscalaMedica/', 'PainelEscalaMedica@index')->name('instituicao.painel_escala_medica.index');

    //Altas Hopitalar
    Route::resource('altas-hospitalar', 'AltasHospitalar')->names('instituicao.altasHospitalar')->parameters([
        'altas-hospitalar' => 'alta-hospitalar',
    ]);
    Route::post('altas-hospitalar/pesquisa-paciente', 'AltasHospitalar@pesquisaPaciente')->name('instituicao.altasHospitalar.pesquisaPaciente');
    Route::post('altas-hospitalar/ver-paciente', 'AltasHospitalar@verPaciente')->name('instituicao.altasHospitalar.verPaciente');
    Route::post('altas-hospitalar/getPaciente', 'AltasHospitalar@getPaciente')->name('instituicao.altasHospitalar.getPaciente');
    Route::post('altas-hospitalar/getAtendimento', 'AltasHospitalar@getAtendimento')->name('instituicao.altasHospitalar.getAtendimento');
    Route::post('altas-hospitalar/ver-internacao', 'AltasHospitalar@verInternacao')->name('instituicao.altasHospitalar.verInternacao');

    //RELATORIO ATENDIMENTO
    Route::get("relatorio-atendimento", "RelatorioAtendimentos@index")->name('instituicao.relatorioAtendimento.index');
    Route::post("relatorio-atendimento-tabela", "RelatorioAtendimentos@tabela")->name('instituicao.relatorioAtendimento.tabela');
    Route::post("relatorio-atendimento-ver-financeiro", "RelatorioAtendimentos@verFinanceiro")->name('instituicao.relatorioAtendimento.verFinanceiro');
    Route::post("relatorio-atendimento-salva-financeiro", "RelatorioAtendimentos@salvaFinanceiro")->name('instituicao.relatorioAtendimento.salvaFinanceiro');
    Route::get("relatorio-atendimento-procedimentos", "RelatorioAtendimentos@getProcedimentos")->name('instituicao.relatorioAtendimento.getProcedimentos');
    Route::get("relatorio-atendimento-get-excel", "RelatorioAtendimentos@exportExcel")->name('instituicao.relatorioAtendimento.exportExcel');

    //RELATORIO Estatistico
    Route::get("relatorio-estatistico-financeiro", "RelatoriosEstatisticos@showFinanceioAmbulatorial")->name('instituicao.relatoriosEstatisticos.showFinanceioAmbulatorial');
    Route::post("relatorio-estatistico-financeiro-result", "RelatoriosEstatisticos@resultFinaceiro")->name('instituicao.relatoriosEstatisticos.resultFinaceiro');

    Route::get("relatorio-estatistico-agenda", "RelatoriosEstatisticos@showAgenda")->name('instituicao.relatoriosEstatisticos.showAgenda');
    Route::post("relatorio-estatistico-agenda-result", "RelatoriosEstatisticos@resultAgenda")->name('instituicao.relatoriosEstatisticos.resultAgenda');

    Route::get("relatorio-estatistico-procedimento", "RelatoriosEstatisticos@showProcedimentos")->name('instituicao.relatoriosEstatisticos.showProcedimentos');
    Route::post("relatorio-estatistico-procedimento-result", "RelatoriosEstatisticos@resultProcedimentos")->name('instituicao.relatoriosEstatisticos.resultProcedimentos');

    Route::get("relatorio-estatistico-convenios", "RelatoriosEstatisticos@showConvenios")->name('instituicao.relatoriosEstatisticos.showConvenios');
    Route::post("relatorio-estatistico-convenios-result", "RelatoriosEstatisticos@resultConvenios")->name('instituicao.relatoriosEstatisticos.resultConvenios');

    Route::get('relatorio-estoque', 'RelatoriosEstoque@index')->name('instituicao.relatoriosEstoque.index');
    Route::post('relatorio-estoque/gerar-entradas', 'RelatoriosEstoque@relatorioEntradas')->name('instituicao.relatoriosEstoque.entradas');
    Route::post('relatorio-estoque/gerar-saidas', 'RelatoriosEstoque@relatorioSaidas')->name('instituicao.relatoriosEstoque.saidas');
    Route::post('relatorio-estoque/gerar-posicao', 'RelatoriosEstoque@relatorioPosicao')->name('instituicao.relatoriosEstoque.posicao');
    Route::post('relatorio-estoque/gerar-arquivo-saidas', 'RelatoriosEstoque@gerarArquivoSaida')->name('instituicao.relatoriosEstoque.arquivo-saidas');
    Route::post('relatorio-estoque/gerar-arquivo-entradas', 'RelatoriosEstoque@gerarArquivoEntrada')->name('instituicao.relatoriosEstoque.arquivo-entradas');
    Route::post('relatorio-estoque/gerar-arquivo-posicao', 'RelatoriosEstoque@gerarArquivoPosicao')->name('instituicao.relatoriosEstoque.arquivo-posicao');

    //Dashboard
    Route::post('dashboard/getAgendamentos', 'Dashboard@getAgendamentos')->name('instituicao.dashboard.getAgendamentos');
    Route::post('dashboard/getAtendimentos', 'Dashboard@getAtendimentos')->name('instituicao.dashboard.getAtendimentos');
    Route::post('dashboard/getConvenios', 'Dashboard@getConvenios')->name('instituicao.dashboard.getConvenios');
    Route::post('dashboard/getPacientes', 'Dashboard@getPacientes')->name('instituicao.dashboard.getPacientes');

    Route::get('dashboard/getEspecialidades', 'Dashboard@getEspecialidades')->name('instituicao.dashboard.getEspecialidades');
    Route::get('dashboard/getMedicos', 'Dashboard@getMedicos')->name('instituicao.dashboard.getMedicos');

    ////Especializacoes
    Route::resource('especializacoes', 'Especializacoes')->names('instituicao.especializacoes')->parameters([
        'especializacoes' => 'especializacao',
    ]);

    //Medicamentos
    Route::resource('medicamentos', 'Medicamentos')->names('instituicao.medicamentos');
    Route::get('medicamentos-formulario', 'Medicamentos@getFormulario')->name('instituicao.medicamentos.getFormulario');

    //MODELO IMPRESSÃO
    Route::resource('modelo-impressao', 'ModelosImpressao')->names('instituicao.modeloImpressao')->parameters([
        'modelo-impressao' => 'modelo'
    ]);

    // Grupos Procedimentos
    Route::resource('grupos-procedimentos', 'GrupoProcedimentos')->names('instituicao.gruposProcedimentos')->parameters([
        'grupos-procedimentos' => 'grupos_procedimento'
    ]);

    // Pacotes Procedimentos
    Route::resource('pacotes-procedimentos', 'PacotesProcedimentos')->names('instituicao.pacotesProcedimentos')->parameters([
        'pacotes-procedimentos' => 'pacote_procedimento'
    ]);
    Route::get('pacotes-procedimentos-vinculo/{pacote_procedimento}', 'PacotesProcedimentos@verVinculo')->name('instituicao.pacotesProcedimentos.verVinculo');
    Route::put('pacotes-procedimentos-vinculo-salvar/{pacote_procedimento}', 'PacotesProcedimentos@salvarVinculo')->name('instituicao.pacotesProcedimentos.salvarVinculo');

    //MODELO ATESTADO
    Route::resource('modelo-atestado', 'ModeloAtestados')->names('instituicao.modeloAtestado')->parameters([
        'modelo-atestado' => 'modelo'
    ]);

    //MODELO RELATORIO
    Route::resource('modelo-relatorio', 'ModeloRelatorios')->names('instituicao.modeloRelatorio')->parameters([
        'modelo-relatorio' => 'modelo'
    ]);

    //MODELO EXAME
    Route::resource('modelo-exame', 'ModeloExames')->names('instituicao.modeloExame')->parameters([
        'modelo-exame' => 'modelo'
    ]);

    //MODELO RECEITUARIO
    Route::resource('modelo-receituario', 'ModeloReceituarios')->names('instituicao.modeloReceituario')->parameters([
        'modelo-receituario' => 'modelo'
    ]);
    Route::get('modelo-receituario-form-medicamentos', 'ModeloReceituarios@formMedicamentos')->name('instituicao.modeloReceituario.formMedicamentos');
    Route::get('modelo-receituario-form-add-medicamentos', 'ModeloReceituarios@formAddMedicamentos')->name('instituicao.modeloReceituario.formAddMedicamentos');

    //CONFIGURAÇÕES DE PRONTUARIO
    Route::resource('configuracao-prontuario', 'ProntuarioConfiguracoes')->names('instituicao.configuracaoProntuario')->parameters([
        'configuracao-prontuario' => 'prontuario'
    ]);

    //MODELO PRONTUARIO
    Route::resource('modelo-prontuario', 'ModeloProntuarios')->names('instituicao.modeloProntuario')->parameters([
        'modelo-prontuario' => 'modelo'
    ]);

    // ATUALIZAÇÃO DO FATURAMENTO SUS
    Route::get('faturamento-sus/import', 'FaturamentosSUS@import')->name('instituicao.faturamento-sus.import');
    Route::match(['PUT','PATCH'],'faturamento-sus/import', 'FaturamentosSUS@importing')->name('instituicao.faturamento-sus.importing');
    // VÍNCULOS SUS
    Route::get('faturamento-sus/bindings', 'FaturamentosSUS@bindings')->name('instituicao.faturamento-sus.bindings');
    Route::match(['PUT','PATCH'],'faturamento-sus/bind', 'FaturamentosSUS@bind')->name('instituicao.faturamento-sus.bind');
    Route::post('faturamento-sus/getVinculos', 'FaturamentosSUS@getVinculos')->name('instituicao.ajax.get-vinculos-sus');

    //REFRACAO
    Route::get('agendamentos/{agendamento}/refracao/{paciente}/refracao-paciente', 'RefracoesPaciente@refracaoPaciente')->name('agendamento.refracao.refracaoPaciente');
    Route::post('agendamentos/{agendamento}/refracao/{paciente}/refracao-salvar', 'RefracoesPaciente@refracaoSalvar')->name('agendamento.refracao.refracaoSalvar');
    Route::get('agendamentos/{agendamento}/refracao/{paciente}/refracao-get-historico', 'RefracoesPaciente@refracaoPacienteHistorico')->name('agendamento.refracao.refracaoPacienteHistorico');
    Route::get('agendamentos/{agendamento}/refracao/{paciente}/paciente-get-refracao/{refracao}', 'RefracoesPaciente@pacienteGetRefracao')->name('agendamento.refracao.pacienteGetRefracao');
    Route::post('agendamentos/{agendamento}/refracao/{paciente}/paciente-excluir-refracao/{refracao}', 'RefracoesPaciente@pacienteExcluirRefracao')->name('agendamento.refracao.pacienteExcluirRefracao');
    // Route::post('agendamentos/{agendamento}/refracao/{paciente}/compartilhar-refracao/{refracao}', 'RefracoesPaciente@compartilharRefracao')->name('agendamento.refracao.compartilharRefracao');
    Route::get('agendamento-imprimir-refracao/{refracao}', 'RefracoesPaciente@imprimirRefracao')->name('agendamento.refracao.imprimirRefracao');
    // Route::get('agendamento-modelo-refracao/{modelo}', 'RefracoesPaciente@modeloRefracao')->name('agendamento.refracao.modeloRefracao');

    //ODONTOLOGICO
    Route::get('agendamentos/{agendamento?}/odontologico/{paciente}/odontologico-paciente', 'OdontologicosPaciente@odontologicoPaciente')->name('agendamento.odontologico.odontologicoPaciente');
    Route::post('agendamentos/odontologico/{grupo}/get-procedimentos', 'OdontologicosPaciente@getProcedimentos')->name('instituicao.odontologico.getProcedimentos');
    Route::post('agendamentos/{agendamento?}/odontologico/{paciente}/salvar-orcamento', 'OdontologicosPaciente@odontologicoSalvar')->name('instituicao.odontologico.odontologicoSalvar');
    Route::post('agendamentos/{agendamento?}/odontologico/{paciente}/editar-orcamento/{orcamento}', 'OdontologicosPaciente@odontologicoEditar')->name('instituicao.odontologico.odontologicoEditar');
    Route::post('agendamentos/{paciente}/odontologico/{orcamento}/visualizar-orcamento', 'OdontologicosPaciente@odontologicoVisualizar')->name('instituicao.odontologico.odontologicoVisualizar');
    Route::get('agendamentos/pacientes-orcamento', 'OdontologicosPaciente@getPacientes')->name('instituicao.odontologico.getPacientes');
    Route::post('agendamentos/{paciente}/odontologico/{orcamento}/alterar-negociador-responsavel-orcamento', 'OdontologicosPaciente@alterarNegociadorResponsavel')->name('instituicao.odontologico.alterarNegociadorResponsavel');
    Route::post('agendamentos/{paciente}/odontologico/{orcamento}/alterar-orcamento-financeiro', 'OdontologicosPaciente@salvarOrcamentoFinanceiro')->name('instituicao.odontologico.salvarOrcamentoFinanceiro');
    Route::post('agendamentos/{paciente}/odontologico-get-tabela-orcamento', 'OdontologicosPaciente@getTableOrcamento')->name('instituicao.odontologico.getTableOrcamento');
    Route::post('agendamentos/{paciente}/odontologico/{orcamento}/concluir-procedimento-orcamento', 'OdontologicosPaciente@odontologicoConcluirProcedimento')->name('instituicao.odontologico.odontologicoConcluirProcedimento');
    Route::post('agendamentos/{paciente}/odontologico/{orcamento}/salvar-procedimento-orcamento-aprovado', 'OdontologicosPaciente@salvarOrcamentoProcedimentosAprovados')->name('instituicao.odontologico.salvarOrcamentoProcedimentosAprovados');
    Route::post('agendamentos/{paciente}/odontologico/{orcamento}/cancelar-procedimento-orcamento-aprovado/{item}', 'OdontologicosPaciente@cancelarItemConcluidoOrcamento')->name('instituicao.odontologico.cancelarItemConcluidoOrcamento');
    Route::post('agendamentos/{paciente}/odontologico/{orcamento}/cancelar-orcamento-aprovado', 'OdontologicosPaciente@cancelarOrcamentoOdontologico')->name('instituicao.odontologico.cancelarOrcamentoOdontologico');
    Route::post('agendamentos/{paciente}/odontologico/{orcamento}/editar-orcamento-odontologico', 'OdontologicosPaciente@editarOrcamentoOdontologico')->name('instituicao.odontologico.editarOrcamentoOdontologico');
    Route::post('agendamentos/{paciente}/odontologico/{orcamento}/excluir-orcamento-odontologico', 'OdontologicosPaciente@excluirOrcamentoOdontologico')->name('instituicao.odontologico.excluirOrcamentoOdontologico');
    Route::get('agendamento/{paciente}/imprimir-orcamento/{orcamento}', 'OdontologicosPaciente@imprimirOrcamento')->name('agendamento.odontologico.imprimirOrcamento');
    Route::get('agendamento/{paciente}/imprimir-orcamento-total/{orcamento}', 'OdontologicosPaciente@imprimirOrcamentoTotal')->name('agendamento.odontologico.imprimirOrcamentoTotal');
    Route::get('agendamento/{paciente}/contrato-orcamento/{orcamento}', 'OdontologicosPaciente@contratoOrcamento')->name('agendamento.odontologico.contratoOrcamento');

    Route::get('odontologico/gera-boleto/{orcamento}', 'OdontologicosPaciente@geraBoelto')->name('instituicao.odontologico.geraBoelto');
    Route::post('agendamentos/{paciente}/odontologico/{orcamento}/alterar-laboratorio-orcamento', 'OdontologicosPaciente@alterarValorLaboratorio')->name('instituicao.odontologico.alterarValorLaboratorio');

    Route::resource('configuracoes-fiscais', 'ConfiguracoesFiscais')->names('instituicao.configuracaoFiscal')->parameters([
        'configuracoes-fiscais' => 'configuracao_fiscal'
    ]);

    //Notas Fiscais
    Route::resource('notas-fiscais', 'NotasFiscais')->names('instituicao.notasFiscais')->parameters([
        'notas-fiscais' => 'nota'
    ]);
    Route::post('notas-fiscais/emitir/', 'NotasFiscais@emitirNfModal')->name('instituicao.notasFiscais.emitirNfe');
    Route::post('notas-fiscais/setEmpresa', 'NotasFiscais@cadastrarEmpresa')->name('instituicao.notasFiscais.cadastrarEmpresa');
    Route::post('notas-fiscais/getStatus/{nota}', 'NotasFiscais@getStatus')->name('instituicao.notasFiscais.getStatus');
    Route::post('notas-fiscais/cancelarNota/{nota}', 'NotasFiscais@cancelarNota')->name('instituicao.notasFiscais.cancelarNota');
    Route::get('notas-fiscais/getPdf/{nota}', 'NotasFiscais@getPDF')->name('instituicao.notasFiscais.getPDF');
    Route::post('notas-fiscais/pesquisa-conta-receber', 'NotasFiscais@pesquisaContaReceber')->name('instituicao.notasFiscais.pesquisaContaReceber');
    Route::post('notas-fiscais/pesquisar-conta-receber', 'NotasFiscais@pesquisarContaReceber')->name('instituicao.notasFiscais.pesquisarContaReceber');
    Route::post('notas-fiscais/criar_nota', 'NotasFiscais@criarNota')->name('instituicao.notasFiscais.criarNota');

    //Relatorio Fluxo Caixa
    Route::get("relatorio-fluxo-caixa", "RelatoriosFluxoCaixa@index")->name('instituicao.relatoriosFluxoCaixa.index');
    Route::post("relatorio-fluxo-caixa-tabela", "RelatoriosFluxoCaixa@tabela")->name('instituicao.relatoriosFluxoCaixa.tabela');
    Route::get("showMovimento", "RelatoriosFluxoCaixa@showMovimentacao")->name('instituicao.relatoriosFluxoCaixa.showMovimentacao');
    Route::post("salvaMovimento", "RelatoriosFluxoCaixa@salvarMovimentacao")->name('instituicao.relatoriosFluxoCaixa.salvarMovimentacao');
    Route::get("export-excel", "RelatoriosFluxoCaixa@export")->name('instituicao.relatoriosFluxoCaixa.exportExcel');
    Route::post("altararCaixa", "RelatoriosFluxoCaixa@altararCaixa")->name('instituicao.relatoriosFluxoCaixa.altararCaixa');
    Route::get("getUsuarios", "RelatoriosFluxoCaixa@getUsuarios")->name('instituicao.relatoriosFluxoCaixa.getUsuarios');

    //Relatorio Sancoop
    Route::get("relatorio-sancoop", "RelatoriosSancoop@index")->name('instituicao.relatoriosSancoop.index');
    Route::post("relatorio-sancoop-tabela", "RelatoriosSancoop@tabela")->name('instituicao.relatoriosSancoop.tabela');
    Route::get("relatorio-sancoop-export-excel", "RelatoriosSancoop@exportExcel")->name('instituicao.relatoriosSancoop.exportExcel');
    Route::get("relatorio-sancoop-export-pdf", "RelatoriosSancoop@exportPdf")->name('instituicao.relatoriosSancoop.exportPdf');
    //MOVIMENTACOES
    Route::resource('movimentacoes', 'Movimentacoes')->names('instituicao.movimentacoes')->parameters([
        'movimentacoes' => 'movimentacao'
    ]);
    Route::post('movimentacao-duplicar', 'Movimentacoes@duplicar')->name('instituicao.movimentacoes.duplicar');

    //Prestadores Solicitantes
    Route::resource('solicitantes', 'PrestadoresSolicitantes')->names('instituicao.solicitantes');

    //DASHBOARD ODONTOLOGICO
    Route::get('dashboard-odontologico', 'DashboardOdontologico@index')->name('instituicao.dashboardOdontologico.index');
    Route::post('dashboard-odontologico-quantidade', 'DashboardOdontologico@getQuantidade')->name('instituicao.dashboardOdontologico.getQuantidade');
    Route::post('dashboard-odontologico-procedimentos', 'DashboardOdontologico@getProcedimentos')->name('instituicao.dashboardOdontologico.getProcedimentos');
    Route::post('dashboard-odontologico-convenios', 'DashboardOdontologico@getConvenios')->name('instituicao.dashboardOdontologico.getConvenios');
    Route::post('dashboard-odontologico-procedimentos-realizados', 'DashboardOdontologico@getProcedimentosRealizados')->name('instituicao.dashboardOdontologico.getProcedimentosRealizados');
    Route::post('dashboard-odontologico-procedimentos-vendidos', 'DashboardOdontologico@getProcedimentosVendidos')->name('instituicao.dashboardOdontologico.getProcedimentosVendidos');
    Route::post('dashboard-odontologico-grupo', 'DashboardOdontologico@getGrupo')->name('instituicao.dashboardOdontologico.getGrupo');

    //DEMONSTRATIVO ODONTOLOGICO
    Route::get('demonstrativo_odontologico', 'RelatorioDemonstrativoOdontologico@index')->name('instituicao.relatorioDemonstrativoOdontologico.index');
    Route::post('demonstrativo_odontologico_tabela', 'RelatorioDemonstrativoOdontologico@tabela')->name('instituicao.relatorioDemonstrativoOdontologico.tabela');

    //REPASSE ODONTOLOGICO
    Route::get('repasse_odontologico', 'RelatorioRepasseOdontologico@index')->name('instituicao.relatorioRepasseOdontologico.index');
    Route::post('repasse_odontologico_tabela', 'RelatorioRepasseOdontologico@tabela')->name('instituicao.relatorioRepasseOdontologico.tabela');
    Route::get('repasse_odontologico_procedimentos', 'RelatorioRepasseOdontologico@getProcedimentoOdontologicosPesquisa')->name('instituicao.relatorioRepasseOdontologico.getProcedimentoOdontologicosPesquisa');

    //DEMONSTRATIVO ODONTOLOGICO GRUPO
    Route::get('odontologico_grupo', 'RelatorioOdontologicoGrupo@index')->name('instituicao.relatorioOdontologicoGrupo.index');
    Route::post('odontologico_grupo_tabela', 'RelatorioOdontologicoGrupo@tabela')->name('instituicao.relatorioOdontologicoGrupo.tabela');
    Route::get('odontologico_grupo_procedimentos', 'RelatorioOdontologicoGrupo@getProcedimentoOdontologicosPesquisa')->name('instituicao.relatorioOdontologicoGrupo.getProcedimentoOdontologicosPesquisa');

    //ORÇAMENTOS
    Route::get('orcamentos', 'RelatorioOrcamentos@index')->name('instituicao.relatorioOrcamentos.index');
    Route::post('orcamentos_tabela', 'RelatorioOrcamentos@tabela')->name('instituicao.relatorioOrcamentos.tabela');

    //ORÇAMENTOS APROVADOS
    Route::get('orcamentos_aprovados', 'RelatorioOrcamentosAprovados@index')->name('instituicao.relatorioOrcamentosAprovados.index');
    Route::post('orcamentos_aprovados_tabela', 'RelatorioOrcamentosAprovados@tabela')->name('instituicao.relatorioOrcamentosAprovados.tabela');

    //PROCEDIMENTOS N REALIZADOS
    Route::get('procedimentos_n_realizados', 'RelatorioProcedimentosNRealizados@index')->name('instituicao.relatorioProcedimentosNRealizados.index');
    Route::post('procedimentos_n_realizados_tabela', 'RelatorioProcedimentosNRealizados@tabela')->name('instituicao.relatorioProcedimentosNRealizados.tabela');

    //ORÇAMENTOS CONCLUIDOS
    Route::get('orcamentos_concluidos', 'RelatorioOrcamentosConcluidos@index')->name('instituicao.relatorioOrcamentosConcluidos.index');
    Route::post('orcamentos_concluidos_tabela', 'RelatorioOrcamentosConcluidos@tabela')->name('instituicao.relatorioOrcamentosConcluidos.tabela');

    //AUDITORIA AGENDAMENTOS
    Route::get('auditoria_agendamento', 'RelatorioAuditoriaAgendamentos@index')->name('instituicao.relatorioAuditoriaAgendamentos.index');
    Route::post('auditoria_agendamento_tabela', 'RelatorioAuditoriaAgendamentos@tabela')->name('instituicao.relatorioAuditoriaAgendamentos.tabela');

    //Encaminhamento
    Route::get('agendamentos/{agendamento}/encaminhamento/{paciente}/encaminhamento-paciente', 'EncaminhamentosPaciente@encaminhamentoPaciente')->name('agendamento.encaminhamento.encaminhamentoPaciente');
    Route::post('agendamentos/{agendamento}/encaminhamento/{paciente}/encaminhamento-salvar', 'EncaminhamentosPaciente@encaminhamentoSalvar')->name('agendamento.encaminhamento.encaminhamentoSalvar');
    Route::get('agendamentos/{agendamento}/encaminhamento/{paciente}/encaminhamento-get-historico', 'EncaminhamentosPaciente@encaminhamentoPacienteHistorico')->name('agendamento.encaminhamento.encaminhamentoPacienteHistorico');
    Route::get('agendamentos/{agendamento}/encaminhamento/{paciente}/paciente-get-encaminhamento/{encaminhamento}', 'EncaminhamentosPaciente@pacienteGetEncaminhamento')->name('agendamento.encaminhamento.pacienteGetEncaminhamento');
    Route::post('agendamentos/{agendamento}/encaminhamento/{paciente}/paciente-excluir-encaminhamento/{encaminhamento}', 'EncaminhamentosPaciente@pacienteExcluirEncaminhamento')->name('agendamento.encaminhamento.pacienteExcluirEncaminhamento');
    Route::post('agendamentos/{agendamento}/encaminhamento/{paciente}/compartilhar-encaminhamento/{encaminhamento}', 'EncaminhamentosPaciente@compartilharEncaminhamento')->name('agendamento.encaminhamento.compartilharEncaminhamento');
    Route::get('agendamento-imprimir-encaminhamento/{encaminhamento}', 'EncaminhamentosPaciente@imprimirEncaminhamento')->name('agendamento.encaminhamento.imprimirEncaminhamento');
    Route::get('agendamento-modelo-encaminhamento/{modelo}', 'EncaminhamentosPaciente@modeloEncaminhamento')->name('agendamento.encaminhamento.modeloEncaminhamento');

    //MODELO ENCAMINHAMENTO
    Route::resource('modelo-encaminhamento', 'ModeloEncaminhamentos')->names('instituicao.modeloEncaminhamento')->parameters([
        'modelo-encaminhamento' => 'modelo'
    ]);

    //MODELO LAUDO
    Route::resource('modelo-laudo', 'ModeloLaudos')->names('instituicao.modeloLaudo')->parameters([
        'modelo-laudo' => 'modelo'
    ]);

    //Laudo
    Route::get('agendamentos/{agendamento}/laudo/{paciente}/laudo-paciente', 'LaudosPaciente@laudoPaciente')->name('agendamento.laudo.laudoPaciente');
    Route::post('agendamentos/{agendamento}/laudo/{paciente}/laudo-salvar', 'LaudosPaciente@laudoSalvar')->name('agendamento.laudo.laudoSalvar');
    Route::get('agendamentos/{agendamento}/laudo/{paciente}/laudo-get-historico', 'LaudosPaciente@laudoPacienteHistorico')->name('agendamento.laudo.laudoPacienteHistorico');
    Route::get('agendamentos/{agendamento}/laudo/{paciente}/paciente-get-laudo/{laudo}', 'LaudosPaciente@pacienteGetLaudo')->name('agendamento.laudo.pacienteGetLaudo');
    Route::post('agendamentos/{agendamento}/laudo/{paciente}/paciente-excluir-laudo/{laudo}', 'LaudosPaciente@pacienteExcluirLaudo')->name('agendamento.laudo.pacienteExcluirLaudo');
    Route::post('agendamentos/{agendamento}/laudo/{paciente}/compartilhar-laudo/{laudo}', 'LaudosPaciente@compartilharLaudo')->name('agendamento.laudo.compartilharLaudo');
    Route::get('agendamento-imprimir-laudo/{laudo}', 'LaudosPaciente@imprimirLaudo')->name('agendamento.laudo.imprimirLaudo');
    Route::get('agendamento-modelo-laudo/{modelo}', 'LaudosPaciente@modeloLaudo')->name('agendamento.laudo.modeloLaudo');

    //Avaliacao
    Route::get('internacoes/{agendamento}/avaliacao/{paciente}', 'AvaliacaoInternacao@index')->name('agendamento.internacoes.avaliacao.index');
    Route::post('internacoes/{agendamento}/avaliacao/{paciente}', 'AvaliacaoInternacao@store')->name('agendamento.internacoes.avaliacao.store');
    Route::get('internacoes/{agendamento}/avaliacao/{paciente}/avaliacao-get-historico', 'AvaliacaoInternacao@avaliacaoHistorico')->name('agendamento.internacoes.avaliacao.avaliacaoHistorico');
    Route::get('internacoes/{agendamento}/avaliacao/{paciente}/get-avaliacao/{avaliacao}', 'AvaliacaoInternacao@getAvaliacao')->name('agendamento.internacoes.avaliacao.getAvaliacao');
    Route::post('internacoes/{agendamento}/avaliacao/{paciente}/excluir-avaliacao/{avaliacao}', 'AvaliacaoInternacao@deleteAvaliacao')->name('agendamento.internacoes.avaliacao.deleteAvaliacaoHistorico');
    Route::get('agendamento-imprimir-avaliacao/{avaliacao}', 'AvaliacaoInternacao@imprimirAvaliacao')->name('agendamento.internacoes.avaliacao.imprimirAvaliacao');

    //GRUPO FATURAMENTO
    Route::resource("grupo_faturamento", "GruposFaturamento")->names("instituicao.grupoFaturamento")->parameters([
        'grupo_faturamento' => 'grupo'
    ]);
    Route::post("grupo_faturamento_ativar_desativar/{grupo}", "GruposFaturamento@ativarDesativar")->name("instituicao.grupoFaturamento.ativarDesativar");

    //FATURAMENTO
    Route::resource("faturamentos", "Faturamentos")->names("instituicao.faturamento");
    Route::get("faturamentos/{faturamento}/procedimentos", "Faturamentos@procedimentos")->name("instituicao.faturamento.procedimentos");
    Route::post("faturamentos/{faturamento}/procedimentos_salvar", "Faturamentos@salvarProcedimento")->name("instituicao.faturamento.salvarProcedimento");
    Route::post("faturamentos/{faturamento}/procedimentos_status/{proc}", "Faturamentos@statusProcedimento")->name("instituicao.faturamento.statusProcedimento");
    Route::get('faturamentos/{faturamento}/importar-procedimentos', 'Faturamentos@selecionarImportacao')->name('instituicao.faturamento.selecionarImportacao');
    Route::post('faturamentos/{faturamento}/importar-procedimentos', 'Faturamentos@importar')->name('instituicao.faturamento.importarProcedimentos');

    //REGRAS COBRANÇAS
    Route::resource("regra_cobranca", "RegrasCobranca")->names("instituicao.regrasCobranca")->parameters([
        'regra_cobranca' => 'regra'
    ]);

    //REGRAS COBRANÇAS ITENS
    Route::resource("regra_cobranca.itens", "RegrasCobrancaItens")->names("instituicao.regrasCobrancaItens")->parameters([
        'regra_cobranca' => 'regra',
        'itens' => 'item',
    ]);

    //PROCEDIMENTOS ATENDIMENTOS
    Route::resource('procedimento_atendimento', 'ProcedimentosAtendimentos')->names('instituicao.procedimentoAtendimentos')->parameters([
        'procedimento_atendimento' => 'procedimento'
    ]);
    Route::post('get_planos_convenio/{convenio}', 'ProcedimentosAtendimentos@getPlanos')->name('instituicao.procedimentoAtendimentos.getPlanos');
    Route::post('get_cod_procedimento/{procedimento}', 'ProcedimentosAtendimentos@getCodProcedimento')->name('instituicao.procedimentoAtendimentos.getCodProcedimento');
    Route::get('procedimentoatendimento/getProcedimentoGerais', 'ProcedimentosAtendimentos@getProcedimentoGerais')->name('instituicao.procedimentosAtendimentos.getProcedimentoGerais');

    //Modelos Recibo
    Route::resource('modelos-recibo', 'ModelosRecibo')->names('instituicao.modelosRecibo')->parameters([
        'modelos-recibo' => 'modelo'
    ]);
    Route::get('modelos-recibo-visualizar/{modelo}', 'ModelosRecibo@getModelo')->name('instituicao.modelosRecibo.getModelo');

    //MODELO ARQUIVO
    Route::resource("modelo-arquivo", "ModeloArquivos")->names('instituicao.modeloArquivo')->parameters([
        'modelo-arquivo' => 'arquivo'
    ]);
    Route::get('model-arquivo/baixar-arquivo/{arquivo}', 'ModeloArquivos@baixarArquivo')->name('instituicao.modeloArquivo.baixarArquivo');
    Route::post('model-arquivo/visualizar-arquivo/{arquivo}', 'ModeloArquivos@visualizarArquivo')->name('instituicao.modeloArquivo.visualizarArquivo');

    //VINCULO TUSS
    Route::get('vinculo_tuss', 'VinculosTuss@index')->name('instituicao.vinculoTuss.index');
    Route::post("vinculo_tuss/salvar", "VinculosTuss@salvarVinculoTuss")->name("instituicao.vinculoTuss.salvarVinculoTuss");
    Route::get('vinculo_tuss/importar-procedimentos', 'VinculosTuss@selecionarImportacao')->name('instituicao.vinculoTuss.selecionarImportacao');
    Route::post('vinculo_tuss/importar-procedimentos', 'VinculosTuss@importar')->name('instituicao.vinculoTuss.importar');
    Route::post('vinculo_tuss/{vinculo}', 'VinculosTuss@destroy')->name('instituicao.vinculoTuss.destroy');
    Route::get('get_vinculo_tuss', 'VinculosTuss@getVinculoTuss')->name('instituicao.vinculoTuss.getVinculoTuss');

    // Guia TISS
    Route::get('guiatiss/sadt', 'GuiaTiss@sadt')->name('instituicao.guiatiss.sadt');
    Route::get('guiatiss/consulta', 'GuiaTiss@consulta')->name('instituicao.guiatiss.consulta');
    Route::get('guiatiss/outras_despesas', 'GuiaTiss@outras_despesas')->name('instituicao.guiatiss.outras_despesas');
    
    //VINCULO Brasindice
    Route::get('vinculo_brasindice', 'VinculosBrasindice@index')->name('instituicao.vinculoBrasindice.index');
    Route::get('vinculo_brasindice/cadastrar', 'VinculosBrasindice@create')->name('instituicao.vinculoBrasindice.cadastrar');
    Route::post('vinculo_brasindice/importar', 'VinculosBrasindice@store')->name('instituicao.vinculoBrasindice.importar');
    Route::post('vinculo_brasindice/apagar/{vinculo}', 'VinculosBrasindice@destroy')->name('instituicao.vinculoBrasindice.apagar');

    //CONTAS AMBULATORIAL
    Route::resource('contas_ambulatorial', 'ContasAmbulatorial')->names('instituicao.contasAmbulatorial');
    Route::get('contas_ambulatorial/{pessoa}/agendamentos', 'ContasAmbulatorial@getAgendamentos')->name('instituicao.contasAmbulatorial.getAgendamentos');
    Route::get('contas_ambulatorial/{agendamento}/get_dados_agendamentos', 'ContasAmbulatorial@getDadosAgendamentos')->name('instituicao.contasAmbulatorial.getDadosAgendamentos');

    // CHAT
    Route::get('chat', 'Chat@index')->name('instituicao.chat.index');
    Route::post('chat/buscar-mensagens', 'Chat@buscarMensagens')->name('instituicao.chat.buscarMensagens');
    Route::post('chat/buscar-contatos', 'Chat@buscarContatos')->name('instituicao.chat.buscarContatos');
    Route::post('chat/enviar-mensagem', 'Chat@enviarMensagem')->name('instituicao.chat.enviarMensagem');
    Route::post('chat/notificacoes', 'Chat@notificacoes')->name('instituicao.chat.notificacoes');
    Route::post('chat/imagem-usuario', 'Chat@getImagemUsuario')->name('instituicao.chat.getImagemUsuario');
    Route::post('chat/buscar-usuarios', 'Chat@buscarUsuarios')->name('instituicao.chat.buscarUsuarios');
    Route::post('chat/adicionar-contatos', 'Chat@adicionarContato')->name('instituicao.chat.adicionarContato');

    //Maquinas de Cartão
    Route::resource('maquinas-cartoes', 'MaquinasCartoes')->names('instituicao.maquinasCartoes')->parameters([
        'maquinas-cartoes' => 'maquina',
    ]);

    //RELATORIO Conciliacao cartao
    Route::get("relatorio-cartao", "RelatoriosConciliacaoCartao@index")->name('instituicao.relatoriosCartao.index');
    Route::post("relatorio-cartao-tabela", "RelatoriosConciliacaoCartao@tabela")->name('instituicao.relatoriosCartao.tabela');

    //Relatorios Financeiros
    Route::get("relatorio-financeiro-a-pagar", "RelatoriosFinanceiros@aPagar")->name('instituicao.relatoriosFinanceiros.aPagar');
    Route::get("relatorio-financeiro-a-receber", "RelatoriosFinanceiros@aReceber")->name('instituicao.relatoriosFinanceiros.aReceber');
    Route::get("relatorio-financeiro-pagas", "RelatoriosFinanceiros@pagas")->name('instituicao.relatoriosFinanceiros.pagas');
    Route::get("relatorio-financeiro-recebidas", "RelatoriosFinanceiros@recebidas")->name('instituicao.relatoriosFinanceiros.recebidas');
    Route::get("relatorio-financeiro-fluxo", "RelatoriosFinanceiros@fluxoCaixa")->name('instituicao.relatoriosFinanceiros.fluxoCaixa');

    Route::post("relatorio-financeiro-a-pagar-tabela", "RelatoriosFinanceiros@aPagarTabela")->name('instituicao.relatoriosFinanceiros.aPagarTabela');
    Route::post("relatorio-financeiro-a-receber-tabela", "RelatoriosFinanceiros@aReceberTabela")->name('instituicao.relatoriosFinanceiros.aReceberTabela');
    Route::post("relatorio-financeiro-pagas-tabela", "RelatoriosFinanceiros@pagasTabela")->name('instituicao.relatoriosFinanceiros.pagasTabela');
    Route::post("relatorio-financeiro-fluxo-tabela", "RelatoriosFinanceiros@fluxoCaixatabela")->name('instituicao.relatoriosFinanceiros.fluxoCaixatabela');

    Route::post("relatorio-financeiro-recebidas-tabela", "RelatoriosFinanceiros@recebidasTabela")->name('instituicao.relatoriosFinanceiros.recebidasTabela');

    Route::get('relatorio-financeiro-export/{relatorio}/{tipo}', "RelatoriosFinanceiros@exportRelatorios")->name('instituicao.relatoriosFinanceiros.exportRelatorios');


    //EDITAR AGENDAMENTOS FINALIZADOS
    Route::get('agendamentos-procedimentos', 'AgendamentosProcedimentoUpdate@index')->name('instituicao.agendamentosProcedimento.index');
    Route::post('agendamentos-procedimentos-tabela', 'AgendamentosProcedimentoUpdate@tabela')->name('instituicao.agendamentosProcedimento.tabela');
    Route::post('agendamentos-procedimentos-salvar', 'AgendamentosProcedimentoUpdate@salvar')->name('instituicao.agendamentosProcedimento.salvar');
    Route::get('get_agendamentos_procedimentos_tabela', 'AgendamentosProcedimentoUpdate@getProcedimentos')->name('instituicao.agendamentosProcedimento.getProcedimentos');

    //COMPROMISSOS
    Route::resource('compromissos', 'Compromissos')->names('instituicao.compromissos');

    //MOTIVO ATENDIMENTO
    Route::resource('motivos_atendimento', 'MotivosAtendimentos')->names('instituicao.motivos_atendimento')->parameters([
        'motivos_atendimento' => 'motivo_atendimento'
    ]);
    
    //MOTIVO ATENDIMENTO
    Route::resource('pessoas.atendimentos_paciente', 'AtendimentosPaciente')->names('instituicao.atendimentos_paciente')->parameters([
        'atendimentos_paciente' => 'atendimento_paciente'
    ]);
    Route::post('pessoa/{pessoa}/atendimentos_paciente/{atendimento_paciente}/excluir', 'AtendimentosPaciente@excluir')->name('instituicao.atendimentos_paciente.excluir');
    Route::get('pessoa/{pessoa}/atendimentos_paciente/lista', 'AtendimentosPaciente@lista')->name('instituicao.atendimentos_paciente.lista');
    Route::post('pessoa/{pessoa}/atendimentos_paciente/{atendimento_paciente}/excluirAgendamento', 'AtendimentosPaciente@excluirAgendamento')->name('instituicao.atendimentos_paciente.excluirAgendamento');
    Route::post('pessoa/{pessoa}/agendamento/{agendamento}/atendimentos_paciente/storeAgendamento', 'AtendimentosPaciente@storeAgendamento')->name('instituicao.atendimentos_paciente.storeAgendamento');
    Route::put('pessoa/{pessoa}/atendimentos_paciente/{atendimento_paciente}/updateAgendamento', 'AtendimentosPaciente@updateAgendamento')->name('instituicao.atendimentos_paciente.updateAgendamento');
    // Entregas de exame
    Route::get('entregas-exame', 'EntregasExame@index')->name('instituicao.entregas-exame.index');
    Route::get('entregas-exame/detalhes', 'EntregasExame@detalhes')->name('instituicao.entregas-exame.detalhes');
    Route::get('entregas-exame/entregar', 'EntregasExame@entregar')->name('instituicao.entregas-exame.entregar');
    Route::post('entregas-exame/finalizar-entrega', 'EntregasExame@finalizarEntrega')->name('instituicao.entregas-exame.finalizar-entrega');
    Route::get('entregas-exame/atualizar', 'EntregasExame@atualizar')->name('instituicao.entregas-exame.atualizar');
    Route::post('entregas-exame/{entrega}/finalizar-atualizacao', 'EntregasExame@finalizarAtualizacao')->name('instituicao.entregas-exame.finalizar-atualizacao');
    Route::resource('locais-de-entrega', 'LocaisEntregaExames')->names('instituicao.locais-entrega-exames')->parameters([
        'locais-de-entrega' => 'local'
    ]);

    // Motivo baixa de estoque
    Route::resource('motivos-baixa', 'MotivosBaixa')->names('instituicao.motivos-baixa')->parameters([
        'motivos-baixa' => 'motivo'
    ]);

    //MOTIVOS CONCLUSOES
    Route::resource('motivos-conclusoes', 'MotivosConclusoes')->names('instituicao.motivoConclusao')->parameters([
        'motivos-conclusoes' => 'motivo'
    ]);

    //MODELO CONCLUSÃO
    Route::resource('modelo-conclusao', 'ModelosConclusoes')->names('instituicao.modeloConclusao')->parameters([
        'modelo-conclusao' => 'modelo'
    ]);

    //CONCLUSÃO
    Route::get('agendamentos/{agendamento}/conclusao/{paciente}/conclusao-paciente', 'ConclusoesPaciente@conclusaoPaciente')->name('agendamento.conclusao.conclusaoPaciente');
    Route::post('agendamentos/{agendamento}/conclusao/{paciente}/conclusao-salvar', 'ConclusoesPaciente@conclusaoSalvar')->name('agendamento.conclusao.conclusaoSalvar');
    Route::get('agendamentos/{agendamento}/conclusao/{paciente}/conclusao-get-historico', 'ConclusoesPaciente@conclusaoPacienteHistorico')->name('agendamento.conclusao.conclusaoPacienteHistorico');
    Route::get('agendamentos/{agendamento}/conclusao/{paciente}/paciente-get-conclusao/{conclusao}', 'ConclusoesPaciente@pacienteGetConclusao')->name('agendamento.conclusao.pacienteGetConclusao');
    Route::post('agendamentos/{agendamento}/conclusao/{paciente}/paciente-excluir-conclusao/{conclusao}', 'ConclusoesPaciente@pacienteExcluirConclusao')->name('agendamento.conclusao.pacienteExcluirConclusao');
    Route::post('agendamentos/{agendamento}/conclusao/{paciente}/compartilhar-conclusao/{conclusao}', 'ConclusoesPaciente@compartilharConclusao')->name('agendamento.conclusao.compartilharConclusao');
    Route::get('agendamento-imprimir-conclusao/{conclusao}', 'ConclusoesPaciente@imprimirConclusao')->name('agendamento.conclusao.imprimirConclusao');
    Route::get('agendamento-modelo-conclusao/{modelo}', 'ConclusoesPaciente@modeloConclusao')->name('agendamento.conclusao.modeloConclusao');

    //RELATÓRIO CONCLUSÃO
    Route::get('relatorio_conclusao', 'RelatorioConclusoes@index')->name('instituicao.relatorioConclusao.index');
    Route::post('relatorio_conclusao_tabela', 'RelatorioConclusoes@tabela')->name('instituicao.relatorioConclusao.tabela');
    Route::post('agendamentos/{agendamento}/paciente/{paciente}/relatorio_conclusao/{conclusao}/conclusao', 'RelatorioConclusoes@conclusao')->name('agendamentos.relatorioConclusao.paciente.conclusao');

    //AGENDAMENTOS LISTA ESPERA
    Route::resource("agendamento_lista_espera", "AgendamentosListaEspera")->names('instituicao.agendamentosListaEspera')->parameters([
        'agendamento_lista_espera' => 'agendamento'
    ]);
    Route::get('lista_espera_agenda/{prestadorInst}', "AgendamentosListaEspera@listaEsperaAgenda")->name('instituicao.agendamentosListaEspera.listaEsperaAgenda');

    //MODELO DE TERMO E FOLHA DE SALA
    Route::resource("modelo_termo_folha_sala", "ModeloTermosFolhaSala")->names('instituicao.modelosTermoFolhaSala')->parameters([
        'modelo_termo_folha_sala' => 'modelo'
    ]);

    Route::get("modelo_termo_folha_sala_tipo", "ModeloTermosFolhaSala@getModelos")->name('instituicao.modelosTermoFolhaSala.getModelos');
    Route::get("modelo_termo_folha_sala_imprimir/{modelo}", "ModeloTermosFolhaSala@imprmirModelo")->name('instituicao.modelosTermoFolhaSala.imprmirModelo');

    //RELATORIO REGISTRO DE LOG
    Route::get('relatorio-registro-log', "RelatorioRegistroLogs@index")->name('instituicao.relatorioRegistroLog.index');
    Route::post('relatorio-registro-log-tabela', "RelatorioRegistroLogs@tabela")->name('instituicao.relatorioRegistroLog.tabela');

    //EXAMES
    
});


