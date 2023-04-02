<?php

namespace App\Http\ViewComposers;

use App\Instituicao;
use Illuminate\View\View;

class InstituicaoNavbarComposer {

    public function compose(View $view) {
        $request = request();

        $usuario_logado = $request->user('instituicao');
        $view->with("instituicao_usuario", $usuario_logado->instituicao);
        
        if (!$request->session()->has('instituicao')) {
            return $view;
        }

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        // $comerciaisUsuarios = $usuario_logado->instituicao();
        // foreach ($comerciaisUsuarios as $comerciaisUsuario) {
        //     $comerciaisUsuario->setRelation('comercialUsuarios', $usuario_logado);
        // }

        $view->with('instituicao_sessao', $instituicao);        

        return $view;
    }

}