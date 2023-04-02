<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\AuthController\AuthenticateRequest;
use App\Http\Requests\API\AuthController\RegisterRequest;
use App\Http\Requests\API\AuthController\SelfRevokeRequest;
use App\Http\Requests\API\AuthController\ResetPasswordRequest;
use App\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Guard as SanctumGuard;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\mail\EnviarTokenPassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;


/**
 * AuthController
 */
class AuthController extends Controller
{
    /**
     * new AuthController()
     */
    public function __construct()
    {
        $this->middleware('guest')->only(['authenticate', 'register']);
        $this->middleware('auth:sanctum')->only(['selfRevoke']);
    }


    /**
     * authenticate()
     *
     * @param AuthenticateRequest $request
     * @return string
     * @throws ValidationException
     */
    public function authenticate(AuthenticateRequest $request)
    {
        $data = $request->validated();
        $guard = $this->guard();
        $userProvider = $guard->getProvider();
        $credentials = Arr::only($data, ['cpf', 'password']);

        $user = $userProvider->retrieveByCredentials($credentials);
        if (!(
            $user instanceof Usuario
            && !is_null($user->password)
            && $userProvider->validateCredentials($user, $credentials)
        )) {
            throw ValidationException::withMessages([
                'cpf' => [trans('auth.failed')]
            ]);
        }

        $deviceData = json_encode($data['device']);
        $tokenName = hash('sha512', $deviceData);
        // if ($user->tokens()->where('name', $tokenName)->count() !== 0) {
        //     throw ValidationException::withMessages([
        //         'device' => ['Dispositivo já cadastrado!'],
        //     ]);
        // }

        $newAccessToken = $user->createToken($tokenName);
        $newAccessToken->accessToken->device_data = $deviceData;
        $newAccessToken->accessToken->save();

        return response()->json([
            'user' => $user,
            'token' => $newAccessToken->plainTextToken,
        ]);
    }

    /**
     * register()
     *
     * @param RegisterRequest $request
     * @return string
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        if($data['convenio_id'] == 0){
            $data['convenio_id'] = null;
        }
        $return = DB::transaction(function () use ($request, $data) {
            /** @var Usuario $user */
            $user = Usuario::query()->create($data);
            event(new Registered($user));

            $deviceData = json_encode($data['device']);
            $tokenName = hash('sha512', $deviceData);
            $newAccessToken = $user->createToken($tokenName);
            $newAccessToken->accessToken->device_data = $deviceData;
            $newAccessToken->accessToken->save();

            return response()->json([
                'user' => $user,
                'token' => $newAccessToken->plainTextToken,
            ]);
        });

        return $return;

    }

    public function send_token_recover_password(Request $request){
        $usuario = Usuario::select('nome', 'email')->where('email',$request->email)->first();

        if (!$usuario) {
            return response()->json(['status'=>'error','msg'=>'Email não encontrado','title'=>'Erro!']);
        }

        $tokenData = DB::table('password_resets')->where('tipo', 'usuario')->where('email', $request->email)->first();

        if($tokenData){
            try {
                Mail::to($usuario->email)->send(new EnviarTokenPassword($usuario->nome,$tokenData->token));
                return response()->json(['status'=>'success','msg'=>'O código de mudança de senha foi enviado ao seu email','title'=>'Email enviado!']);
            } catch (\Exception $e) {
                return response()->json(['status'=>'error','msg'=>'Ocorreu um erro ao enviar o email, tente novamente mais tarde!','title'=>'Erro!']);
            }
        }else{

            $tokenData = DB::table('password_resets')->insert([
                'tipo' => 'usuario',
                'email' => $request->email,
                'token' => strtoupper(Str::random(8)),
                'created_at' => Carbon::now()
            ]);

            $tokenData = DB::table('password_resets')->where('tipo', 'usuario')->where('email', $request->email)->first();

            try {
                Mail::to($usuario->email)->send(new EnviarTokenPassword($usuario->nome,$tokenData->token));
                return response()->json(['status'=>'success','msg'=>'O código de mudança de senha foi enviado ao seu email','title'=>'Email enviado!']);
            } catch (\Exception $e) {
                return response()->json(['status'=>'error','msg'=>'Ocorreu um erro ao enviar o email, tente novamente mais tarde!','title'=>'Erro!']);
            }
        }

    }

    public function verify_token_recover_password(Request $request){
        $tokenData = DB::table('password_resets')->where('tipo', 'usuario')->where('token', $request->codigo)->first();
        if($tokenData){
            return response()->json(['status'=>'success','msg'=>'Insira a nova senha!','title'=>'Insira a nova senha!']);
        }else{
            return response()->json(['status'=>'error','msg'=>'Código inválido!','title'=>'Código inválido!']);
        }
    }

    public function password_reset(ResetPasswordRequest $request){


        $password = $request->password;
        $tokenData = DB::table('password_resets')
        ->where('token', $request->codigo)->first();


        $usuario = Usuario::where('email', $tokenData->email)->first();

        if (!$usuario) return response()->json(['status'=>'error','msg'=>'Código inválido!','title'=>'Erro!']);

        $usuario->password = $password;
        $usuario->update();

        DB::table('password_resets')
        ->where('token', $request->codigo)->delete();

        return response()->json([
            'status' => 'success',
            'title' => 'Sucesso.',
            'msg' => 'Sua senha foi alterada com sucesso!'
        ]);

    }

    public function selfRevoke(SelfRevokeRequest $request)
    {
        $data = $request->validated();

        /** @var Usuario $user */
        $user = $request->user('sanctum');

        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        $tokenName = hash('sha512', json_encode($data['device']));
        if ($tokenName !== $token->name) {
            throw ValidationException::withMessages([
                'device' => ['Dispositivo não reconhecido!'],
            ]);
        }

        $user->tokens()->where('name', $token->name)->delete();
        return response()->json([
            'message' => 'Dispositivo removido!',
        ]);
    }

    /**
     * AuthController->getGuard()
     *
     * @return Guard|SessionGuard|SanctumGuard
     */
    private function guard(): Guard
    {
        return Auth::guard('web');
    }
}
