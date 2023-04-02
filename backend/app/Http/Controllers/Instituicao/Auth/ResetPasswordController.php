<?php

namespace App\Http\Controllers\Instituicao\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\InstituicaoUsuario;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function showResetForm(Request $request, $token = null)
    {
        $valido = DB::table('password_resets')->where('token',$token)->first();

        return view('instituicao.auth.reset_password')->with(
            ['token' => $token, 'valido' => $valido]
        );
    }

    public function password_reset(Request $request){

        //Validate input
        $validator = Validator::make($request->all(), [
            'token' => 'required|exists:password_resets,token',
            'password' => 'required|string|min:8|confirmed'
        ])->validate();



        $password = $request->password;
        $tokenData = DB::table('password_resets')
        ->where('token', $request->token)->first();


        $usuario = InstituicaoUsuario::where('email', $tokenData->email)->first();

        if (!$usuario) return redirect()->back();
        $usuario->password = $password;
        $usuario->update();

        Auth::guard('instituicao')->login($usuario);

        DB::table('password_resets')->where('email', $usuario->email)
        ->delete();

        return redirect()->route('instituicao.home')
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Sua senha foi alterada com sucesso!'
        ]);

    }

}
