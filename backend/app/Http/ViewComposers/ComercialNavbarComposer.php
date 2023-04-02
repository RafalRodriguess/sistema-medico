<?php

namespace App\Http\ViewComposers;

use App\Comercial;
use Illuminate\View\View;

class ComercialNavbarComposer {

    public function compose(View $view) {
        $request = request();

        $usuario_logado = $request->user('comercial');
        $view->with("comerciais_usuario", $usuario_logado->comercial);
        
        if (!$request->session()->has('comercial')) {
            return $view;
        }

        $comercial = Comercial::find($request->session()->get('comercial'));

        // $comerciaisUsuarios = $usuario_logado->comercial();
        // foreach ($comerciaisUsuarios as $comerciaisUsuario) {
        //     $comerciaisUsuario->setRelation('comercialUsuarios', $usuario_logado);
        // }

        $view->with('comercial_sessao', $comercial);        

        return $view;
    }

}