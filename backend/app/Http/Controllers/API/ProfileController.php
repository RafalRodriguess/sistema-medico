<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Usuario;
use Illuminate\Http\Request;
use App\Http\Requests\API\AtualizarPerfilRequest;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $user->imagem = $user->imagem ? asset(Storage::cloud()->url($user->imagem)) : null;
        return response()->json([
            'user' => $user,
            'nascimento_formatado' => $request->user()->data_nascimento->format('Y/m/d'),
            'nascimento_iso' => $request->user()->data_nascimento->toIso8601String(),
            'device' => json_decode($request->user()->currentAccessToken()->device_data),
        ]);
    }

    public function atualizar(AtualizarPerfilRequest $request)
    {
        // cade a validação???????
        //return $request;

        //$nascimento = Carbon::createFromFormat(Carbon::ISO8601, $request->data_nascimento);

        $data = array(
          'nome' => $request->nome,
          'telefone' => $request->telefone,
          'data_nascimento' => Carbon::createFromFormat(Carbon::ISO8601, $request->data_nascimento),
          'password' => $request->password,
          'email' => $request->email,
          'convenio_id' => $request->convenio_id,
        );

        if ($data['password'] == null) {
            unset($data['password']);
        }else{
            $data['password'] = Hash::make($request->password);
        }

        // if ($request->hasFile('imagem')) {
        //     dd($request->imagem);
        //     $path = $request->imagem->store("/usuarios", config('filesystems.cloud'));
        //     // $data['imagem'] = $path;
        //     // Ideia é basicamente esta
        //     // Quiser redimencionar é a mesma coisa dos outros controllers
        // }

        if ($request->hasFile('imagem')) {
            $random = Str::random(20);
            $imageName = $random. '.' . $request->imagem->extension();
            $path = $request->imagem->storeAs("/usuarios", $imageName, config('filesystems.cloud'));
            $data['imagem'] = $path;
            // Ideia é basicamente esta
            // Quiser redimencionar é a mesma coisa dos outros controllers
        }

        return response()->json(Usuario::query()
                       ->where('id', $request->user()->id)
                       ->update($data));

    }
}
