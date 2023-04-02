<?php

namespace App\Http\Controllers\Comercial\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\ComercialUsuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\mail\RedefinirSenha;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function send_token_recover_password(Request $request){
        $usuario = ComercialUsuario::select('nome', 'email')->where('email',$request->email)->first();

        if (!$usuario) {
            return response()->json(['status'=>'error','msg'=>'Email não encontrado','title'=>'Erro!']);
        }

        DB::table('password_resets')->insert([
            'tipo' => 'comercial_usuario',
            'email' => $request->email,
            'token' => Str::random(60),
            'created_at' => Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')->where('tipo', 'comercial_usuario')->where('email', $request->email)->first();

        // return response()->json($tokenData );
        if ($this->sendResetEmail($request->email, $tokenData->token)) {
            return response()->json(['status'=>'success','msg'=>'Um link de mudança de senha foi enviado ao seu email','title'=>'Email enviado!']);
        } else {
            return response()->json(['status'=>'error','msg'=>'Ocorreu um erro ao enviar o email, tente novamente!','title'=>'Erro!']);
        }
    }

    private function sendResetEmail($email, $token)
    {
        //Retrieve the user from the database
        $usuario = ComercialUsuario::where('email', $email)->select('nome', 'email')->first();
        //Generate, the password reset link. The token generated is embedded in the link
        $link = route('comercial.recover_password', $token);

        try {
            Mail::to($usuario->email)->send(new RedefinirSenha($usuario->nome,$link));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}
