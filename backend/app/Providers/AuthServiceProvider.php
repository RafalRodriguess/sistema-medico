<?php

namespace App\Providers;

use App\Administrador;
use App\Comercial;
use App\ComercialUsuario;
use App\Instituicao;
use App\InstituicaoUsuario;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public static $habilidadesMap = [];

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('habilidade_admin', function (Administrador $administrador, $nome_unico) {
            $administrador->loadMissing([
                'habilidades',
                'perfil',
                'perfil.habilidades'
            ]);

            $habilidade = $administrador->habilidades->where('nome_unico', $nome_unico)->first();
            if ($habilidade !== null) {
                return (string) $habilidade->pivot->habilitado === '1';
            }

            $habilidade = $administrador->perfil->habilidades->where('nome_unico', $nome_unico)->first();
            if ($habilidade !== null) {
                return (string) $habilidade->pivot->habilitado === '1';
            }

            return false;
        });
        
        //INSTITUIÇÃO HABILIDADES
        // Habilidade instituição precisa de contexto
        Gate::define('habilidade_instituicao', function (InstituicaoUsuario $usuario, int $instituicaoId, $nome_unico) {
            $chave = "U{$usuario->id}-I{$instituicaoId}";

            if (!isset(static::$habilidadesMap[$chave])) {
                $instituicao = Instituicao::find($instituicaoId);
                $instituicaoHabilidades = $usuario->instituicaoHabilidades()
                    ->wherePivot('instituicao_id', $instituicao->id)
                    ->wherePivot('habilitado', '1')
                    ->whereHas('ramo', function($q) use($instituicao){
                        $q->where('ramo_id', $instituicao->ramo_id);
                    })
                    ->get();

                static::$habilidadesMap[$chave] = $instituicaoHabilidades
                    ->keyBy('nome_unico')
                    ->map(function ($h) { return (string) $h->pivot->habilitado === '1'; })
                    ->toArray();
            }

            return Arr::get(static::$habilidadesMap, "{$chave}.{$nome_unico}", false);
        });


        // Ai criamos uma para já pegar o contexto da sessao
        // Isso vai porque ai se precisarmos em algum momento verificar a habilidade em outro instituição, usamos o de cima
        Gate::define('habilidade_instituicao_sessao', function (InstituicaoUsuario $usuario, $nome_unico) {
            $request = request();
            if (!$request->session()->has('instituicao')) {
                return false;
            }

            // DEpois texsta, mas é isso basciamente
            return Gate::forUser($usuario)->check('habilidade_instituicao', [$request->session()->get('instituicao'), $nome_unico]);
        });
    }
}
