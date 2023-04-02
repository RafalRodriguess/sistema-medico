<?php

use Illuminate\Support\Facades\Route;


// Login
Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout');

Route::post('send_token_recover_password', 'Auth\ForgotPasswordController@send_token_recover_password')->name('admin.send_token_recover_password');
Route::get('recover_password/{token}', 'Auth\ResetPasswordController@showResetForm')->name('admin.recover_password');
Route::post('/password_reset', 'Auth\ResetPasswordController@password_reset')->name('admin.password_reset');

Route::group([
    'middleware' => [
        'auth:admin'
    ],
], function () {

    Route::get('/', 'PainelInicial@index')->name('admin.home');

    //PERFIS DE USUARIOS
    Route::resource('perfis_usuarios', 'Perfis_usuarios')->names('perfis_usuarios');
    Route::get('perfis_usuarios/{perfis_usuario}/habilidades', 'Perfis_usuarios@editHabilidades')->name('perfis_usuarios.habilidades');
    Route::put('perfis_usuarios/{perfis_usuario}/habilidades', 'Perfis_usuarios@updateHabilidades');


    //ADMINISTRADORES
    Route::resource('administradores', 'Administradores')->names('administradores')->parameters([
        'administradores' => 'administrador'
    ]);
    Route::get('administradores/{administrador}/habilidades', 'Administradores@editHabilidades')->name('administradores.habilidades');
    Route::put('administradores/{administrador}/habilidades', 'Administradores@updateHabilidades');

    //COMERCIAIS
    Route::resource('comercial', 'Comerciais')->names('comercial');

    Route::get('comercial/{comercial}/conta_bancaria','Comerciais@editBanco')->name('comercial.banco');
    Route::put('comercial/{comercial}/conta_bancaria/update','Comerciais@updateBanco')->name('comercial.banco.update');

    //COMERCIAIS USUARIOS
    Route::resource('comercial.comercial_usuarios', 'Comercial_usuarios')->names('comercial_usuarios');

    Route::get('comercial/{comercial}/comercial_usuarios/{comercial_usuario}/habilidades','Comercial_usuarios@editHabilidade')->name('comercial_usuarios.habilidade');

    Route::put('comercial/{comercial}/comercial_usuarios/{comercial_usuario}/habilidades','Comercial_usuarios@updateHabilidade');

    Route::get('verficiaCpfExistente','Comercial_usuarios@verificaCpfExistente')->name('verificaCpfExistente');

    //USUARIO APLICATIVO
    Route::resource('usuarios', 'Usuarios')->names('usuarios');

    Route::get('usuario/{usuario}/usuarioDevice','Usuarios@usuarioDevice')->name('usuario.usuarioDevice');
    Route::delete('usuario/{usuario}/usuarioDevice','Usuarios@usuarioRemoveDevice');

    //USUARIOS APLICATIVO ENDEREÇOS
    Route::resource('usuario.enderecos', 'UsuarioEnderecosController')->names('usuario_enderecos');


    //MEDICAMENTOS
    Route::resource('medicamentos', 'Medicamentos')->names('medicamentos');

    //PROCEDIMENTOS
    Route::resource('procedimentos', 'Procedimentos')->names('procedimentos');

    //ATENDIMENTOS
    Route::resource('atendimentos', 'Atendimentos')->names('admin.atendimentos');

    //GRUPOS DE PROCEDIMENTOS
    Route::resource('grupos_procedimentos', 'GrupoProcedimentos')->names('grupos_procedimentos');

    //MARCAS
    Route::resource('marcas','Marcas')->names('marcas');

    //INSTITUIÇÃO
    Route::resource('instituicoes', 'Instituicoes')->names('instituicoes')->parameters([
        'instituicoes' => 'instituicao'
    ]);

    Route::get('instituicoes/{instituicao}/conta_bancaria','Instituicoes@editBanco')->name('instituicoes.banco');
    Route::put('instituicoes/{instituicao}/conta_bancaria/update','Instituicoes@updateBanco')->name('instituicoes.banco.update');

    Route::put('habilitarDesabilitar/{instituicao}', 'Instituicoes@habilitarDesabilitar')->name('habilitarDesabilitar');

    Route::get('instituicoes/{instituicao}/backup','Instituicoes@exportDados')->name('instituicoes.backup');

    // Route::post("getprestadorSancoop", "Instituicoes@consultarCooperadoSancoop")->name('getprestadorSancoop');
    Route::get('getprestadorSancoop/{instituicao}','Instituicoes@consultarCooperadoSancoop')->name('getprestadorSancoop');

    //INSTITUIÇÃO USUÁRIOS
    Route::resource('instituicoes.instituicao_usuarios', 'Instituicao_usuarios')->names('instituicao_usuarios')->parameters([
        'instituicoes' => 'instituicao',
    ]);
    Route::post('instituicoes/{instituicao}/instituicao_usuarios_status', "Instituicao_usuarios@status")->name('admin.instituicao_usuarios.status');

    //INSTITUIÇÃO ESPECIALIDADES
    Route::resource('especialidades', 'Especialidades')->names('especialidades');
    Route::post('getespecialidades', 'Especialidades@getespecialidades')->name('getespecialidades');

    //INSTITUIÇÃO PRESTADORES
    Route::resource('prestadores', 'Prestadores')->names('prestadores');
    Route::post('getprestador', 'Prestadores@getPrestador')->name('getprestador');

    //DOCUMENTOS DE PRESTADORES
    Route::resource('prestadores.documentos', 'PrestadoresDocumentos')->names('prestadores.documentos')->parameters([
        'prestadores' => 'prestador',
        'documentos' => 'documento',
    ]);

    // INSTITUIÇÃO ESPECIALIZAÇÕES
    Route::resource('especializacoes', 'Especializacoes')->names('especializacoes')->parameters([
        'especializacoes' => 'especializacao'
    ]);

    

    Route::get('instituicoes/{instituicao}/instituicao_usuarios/{instituicao_usuario}/habilidades','Instituicao_usuarios@editHabilidadeInstituicao')->name('habilidadesInstituicao');

    Route::put('instituicoes/{instituicao}/instituicao_usuarios/{instituicao_usuario}/habilidades','Instituicao_usuarios@updateHabilidadeInstituicao');

    Route::get('verficiaCpfExistenteInstituicao','Instituicao_usuarios@verificaCpfExistenteInstituicao')->name('verificaCpfExistenteInstituicao');
    Route::get('logs', 'Logs@index')->name('logs.index');

    Route::get('estoqueEntrada/criar', 'EstoqueEntrada@create')->name('estoque_entrada.criar');
    Route::get('estoqueEntrada/', 'EstoqueEntrada@index')->name('estoque_entrada.index');
    Route::post('estoqueEntrada/store', 'EstoqueEntrada@store')->name('estoque_entrada.store');
    Route::get('estoqueEntrada/{id}/editar', 'EstoqueEntrada@edit')->name('estoque_entrada.editar');
    Route::get('estoqueEntrada/{id}/destroy', 'EstoqueEntrada@destroy')->name('estoque_entrada.destroy');
    Route::post('estoqueEntrada/{id}/update', 'EstoqueEntrada@update')->name('estoque_entrada.update');


    Route::get('estoqueEntradaProdutos/create/{id}', 'EstoqueEntradaProduto@create')->name('estoque_entrada_produtos.create');
    Route::get('estoqueEntradaProdutos/criar', 'EstoqueEntradaProduto@criar')->name('estoque_entrada_produtos.criar');
    Route::post('estoqueEntradaProdutos/store/', 'EstoqueEntradaProduto@store')->name('estoque_entrada_produtos.store');
    Route::get('estoqueEntradaProdutos/{id}', 'EstoqueEntradaProduto@index')->name('estoque_entrada_produtos.index');
    Route::get('estoqueEntradaProdutos/{id}/editar', 'EstoqueEntradaProduto@edit')->name('estoque_entrada_produtos.editar');
    Route::post('estoqueEntradaProdutos/{id}/update', 'EstoqueEntradaProduto@update')->name('estoque_entrada_produtos.update');
    Route::get('estoqueEntradaProdutos/{id}/destroy', 'EstoqueEntradaProduto@destroy')->name('estoque_entrada_produtos.destroy');

    // Ramos
    Route::resource('ramos', 'Ramos')->names('ramos');
    Route::get('ramos/{ramo}/habilidades', 'Ramos@habilidades')->name('ramos.habilidades');
    Route::post('ramos/{ramo}/habilidade/', 'Ramos@habilidade')->name('ramos.habilidade');

    // Perfis de user instituicão
    Route::resource('perfis-usuarios-instituicoes', 'PerfisUsuariosInstituicoes')->names('perfis-usuarios-instituicoes')->parameters(['perfis-usuarios-instituicoes' => 'perfil_usuario',]);
    Route::get('perfis-usuarios-instituicoes/{perfil_usuario}/habilidades', 'PerfisUsuariosInstituicoes@habilidades')->name('perfis-usuarios-instituicoes.habilidades');
    Route::post('perfis-usuarios-instituicoes/{perfil_usuario}/habilidade/', 'PerfisUsuariosInstituicoes@habilidade')->name('perfis-usuarios-instituicoes.habilidade');

    // Setor Exames
    Route::resource('setor-exame', 'SetoresExames')->names('setorExame')->parameters(['setor-exame' => 'setor']);
    Route::put('setor-exame/{setor}/desativar-ativar', 'SetoresExames@desativarAtivar')->name('setorExame.desativar');

    //RELATORIO BOLETOS
    Route::get('relatorio-boletos', 'Boletos@index')->name('admin.relatorioBoletos.index');
    Route::post('relatorio-boletos-tabela', 'Boletos@tabela')->name('admin.relatorioBoletos.tabela');

});
