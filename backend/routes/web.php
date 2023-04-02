<?php

use Illuminate\Support\Facades\Route;
use PagarMe\Client;
use App\Libraries\Pagarme;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::post('notificacao-pagar-me', function (Request $request) {
//     $client = new Client(config('services.pagarme.api_key'));
//     $signature = $request->server('HTTP_X_HUB_SIGNATURE');
//     $bodyRequest = http_build_query($request->toArray(), '', '&', PHP_QUERY_RFC3986);
//     if ($client->postbacks()->validate($bodyRequest, $signature)) {
//         if( $request->event == 'transaction_status_changed'){

//             $pagarme = new Pagarme();
//             $pagarme->atualizarStatus($request->all());
//         }

//     }
// })->name('notificacao-pagarme');

Route::get('/', 'PublicController@index');

Route::get('acesso_externo', 'PublicController@acessoExterno');

