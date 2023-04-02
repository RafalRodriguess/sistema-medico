<?php

use Illuminate\Support\Facades\Route;

Route::get('login', 'Auth\LoginController@showLoginForm')->name('comercial.login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('comercial.logout');

Route::post('send_token_recover_password', 'Auth\ForgotPasswordController@send_token_recover_password')->name('comercial.send_token_recover_password');
Route::get('recover_password/{token}', 'Auth\ResetPasswordController@showResetForm')->name('comercial.recover_password');
Route::post('/password_reset', 'Auth\ResetPasswordController@password_reset')->name('comercial.password_reset');


// Escolher estabelecimento / rotas de perfil
Route::group([
    'middleware' => [
        'auth:comercial',
    ]
], function () {
    Route::get('/eu/comerciais', 'EuController@comerciais')->name('comercial.eu.comerciais');
    Route::get('/eu/comerciais/{comercial}/accessar', 'EuController@accessarComerciais')->name('comercial.eu.escolher_comercial');
});

// Estabelecimento foi escolhido
Route::group([
    'middleware' => [
        'auth:comercial',
        'comercial'
    ],
], function () {
    Route::get('/', 'PainelInicial@index')->name("comercial.home");

    //USUARIO COMERCIAL
    Route::resource('comercial_usuarios', 'Usuarios_comercial')->names('comercial.comerciais_usuarios');
    Route::get('comercial_usuarios/{comercial_usuario}/habilidades','Usuarios_comercial@editHabilidade')->name('comercial.comerciais_usuarios.habilidade');
    Route::put('comercial_usuarios/{comercial_usuario}/habilidades','Usuarios_comercial@updateHabilidade');

    Route::get('verficiaCpfExistente','Usuarios_comercial@verificaCpfExistente')->name('comercial.verificaCpfExistente');

    //CATEGORIA
    Route::resource('categorias', 'Categorias')->names('comercial.categorias');

    //SUB CATEGORIA
    Route::resource('sub_categorias', 'Sub_categorias')->names('comercial.sub_categorias');

    Route::get('getsubcategorias', 'Produtos@getsubcategorias')->name('comercial.getsubcategorias');

    //PRODUTO
    Route::resource('produtos', 'Produtos')->names('comercial.produtos');
    Route::get('produtos/{produto}/promocao', 'Produtos@editPromocao')->name('comercial.produtos.promocao');
    Route::put('produtos/{produto}/promocao', 'Produtos@updatePromocao');

    Route::get('produtos/{produto}/estoque', 'Produtos@editEstoque')->name('comercial.produtos.estoque');
    Route::put('produtos/{produto}/estoque', 'Produtos@updateEstoque');

    Route::put('produtos/{produto}/desativar', 'Produtos@desativar')->name('comercial.produtos.desativar');

    //PRODUTO PERGUNTAS
    Route::resource('produto.produtoPerguntas', 'ProdutoPerguntas')->names('comercial.produtoPerguntas')->parameters([
        'produtoPerguntas' => 'pergunta'
    ]);

    //COMERCIAL
    Route::get('editar_comercial', 'Comercial_loja@edit')->name('comercial.comercial_loja.edit');
    Route::put('editar_comercial', 'Comercial_loja@update')->name('comercial.comercial_loja.update');

    Route::get('editar_parcelas', 'Comercial_loja@edit_parcelas')->name('comercial.parcelas.edit');
    Route::put('editar_parcelas', 'Comercial_loja@update_parcelas')->name('comercial.parcelas.update');

    //FRETES
    Route::get('entregas', 'FretesComercial@entregas')->name('comercial.fretes_entregas');
    Route::get('retiradas', 'FretesComercial@retiradas')->name('comercial.fretes_retiradas');
    Route::post('update_frete_entrega', 'FretesComercial@update_frete_entrega')->name('comercial.fretes_entregas.update_frete_entrega');
    Route::post('update_frete_retirada', 'FretesComercial@update_frete_retirada')->name('comercial.fretes_retiradas.update_frete_retirada');

    //FRETES ENTREGA
    Route::resource('fretes_entrega', 'Fretes_entregas')->names('comercial.fretes_entrega')->parameters([
        'fretes_entrega' => 'filtro'
    ]);

    //FRETES RETIRADA
    Route::resource('fretes_retirada', 'Fretes_retiradas')->names('comercial.fretes_retirada')->parameters([
        'fretes_retirada' => 'filtro'
    ]);

    //HORARIO FUNCIONAMENTO
    Route::get('horarios_funcionamento', 'HorariosFuncionamento@index')->name('comercial.horarios_funcionamento.index');
    Route::put('horarios_funcionamento', 'HorariosFuncionamento@update')->name('comercial.horarios_funcionamento.update');
    //PEDIDOS
    Route::resource('pedidos', 'Pedidos')->names('comercial.pedidos');
    Route::post('get_modal_produtos', 'Pedidos@getModalProdutos')->name('comercial.get_modal_produtos');
    Route::post('get_modal_mensagens', 'Pedidos@getModalMensagens')->name('comercial.get_modal_mensagens');
    Route::post('enviar/{pedido}/mensagem', 'Pedidos@enviaMensagem')->name('comercial.envia_mensagem');
    Route::post('altera/{pedido}/status', 'Pedidos@alteraStatus')->name('comercial.altera_status');

});
