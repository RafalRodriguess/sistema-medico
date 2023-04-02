<ul id="sidebarnav">

    {{-- @foreach($menus as $menu)
        <li>
            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="{{ $menu['icone'] }}"></i>
                <span class="hide-menu">{{ $menu['titulo'] }}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                @foreach($menu['submenus'] as $titulo => $rota)
                    <li class="{{ request()->route()->getName() === $rota ? 'active' : '' }}">
                        <a href="{{ route($rota) }}">{{ $titulo }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
        @endforeach --}}

        {{-- <li class="nav-small-cap">MENUS</li> --}}
        @if (\Gate::check('habilidade_admin', 'visualizar_administrador') || \Gate::check('habilidade_admin', 'visualizar_perfis_usuarios') )

        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account-card-details"></i><span
            class="hide-menu">Administração </span></a>
            <ul aria-expanded="false" class="collapse">

                @can('habilidade_admin', 'visualizar_administrador')
                <li><a href="{{ route('administradores.index') }}">Administradores</a></li>
                @endcan
                @can('habilidade_admin', 'visualizar_perfis_usuarios')
                <li><a href="{{ route('perfis_usuarios.index') }}">Perfis de Usuários</a></li>
                @endcan
            </ul>
        </li>
        @endif
        @if (\Gate::check('habilidade_admin', 'visualizar_comercial')  )
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-cart"></i><span
            class="hide-menu">Comercial</span></a>
            <ul aria-expanded="false" class="collapse">
                {{-- @can('habilidade_admin', 'visualizar_usuario_comercial')
                    <li><a href="{{ route('comercial_usuarios.index')}}">Administradores</a></li>
                    @endcan --}}
                    @can('habilidade_admin', 'visualizar_comercial')
                    <li><a href="{{ route('comercial.index')}}">Comerciais</a></li>
                    @endcan
                </ul>
            </li>
            @endif
            @if (\Gate::check('habilidade_admin', 'visualizar_medicamentos') || \Gate::check('habilidade_admin', 'visualizar_procedimentos') || \Gate::check('habilidade_admin', 'visualizar_grupos') )
            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-pharmacy"></i><span
                class="hide-menu">Clinica </span></a>
                <ul aria-expanded="false" class="collapse">
                    {{-- <li><a href="#">Comerciais</a></li> --}}
                    @can('habilidade_admin', 'visualizar_medicamentos')
                    <li><a href="{{route('medicamentos.index')}}">Medicamentos</a></li>
                    @endcan
                    @can('habilidade_admin', 'visualizar_procedimentos')
                    <li><a href="{{route('procedimentos.index')}}">Procedimentos</a></li>
                    @endcan
                    @can('habilidade_admin', 'visualizar_atendimentos')
                    <li><a href="{{route('admin.atendimentos.index')}}">Atendimentos</a></li>
                    @endcan
                    @can('habilidade_admin', 'visualizar_grupos')
                    <li><a href="{{route('grupos_procedimentos.index')}}">Grupos</a></li>
                    @endcan
                </ul>
            </li>
            @endif

            @if (\Gate::check('habilidade_admin', 'visualizar_instituicao') ||
            \Gate::check('habilidade_admin', 'visualizar_prestador') ||
            \Gate::check('habilidade_admin', 'visualizar_especialidade') ||
            \Gate::check('habilidade_admin', 'visualizar_especializacao') ||
            \Gate::check('habilidade_admin', 'visualizar_ramo') ||
            \Gate::check('habilidade_admin', 'visualizar_perfil_instituicao') ||
            \Gate::check('habilidade_admin', 'visualizar_setor_exame')
            )
            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-hospital"></i><span
                class="hide-menu">Instituição</span></a>
                <ul aria-expanded="false" class="collapse">
                    {{-- <li><a href="#">Comerciais</a></li> --}}

                    @can('habilidade_admin', 'visualizar_instituicao')
                    <li><a href="{{ route('instituicoes.index')}}">Instituições</a></li>
                    @endcan
                    @can('habilidade_admin', 'visualizar_ramo')
                    <li><a href="{{ route('ramos.index')}}">Ramos de atividade</a></li>
                    @endcan
                    @can('habilidade_admin', 'visualizar_perfil_instituicao')
                    <li><a href="{{ route('perfis-usuarios-instituicoes.index')}}">Perfil de usuario</a></li>
                    @endcan
                    @can('habilidade_admin', 'visualizar_especializacao')
                    <li><a href="{{route('especializacoes.index')}}">Especializações</a></li>
                    @endcan
                    @can('habilidade_admin', 'visualizar_prestador')
                    <li><a href="{{route('prestadores.index')}}">Prestadores</a></li>
                    @endcan
                    @can('habilidade_admin', 'visualizar_especialidade')
                    <li><a href="{{route('especialidades.index')}}">Especialidades</a></li>
                    @endcan
                    @can('habilidade_admin', 'visualizar_setor_exame')
                    <li><a href="{{route('setorExame.index')}}">Setor Exame</a></li>
                    @endcan
                    <li><a href="{{route('admin.relatorioBoletos.index')}}">Relatório Boleto</a></li>
                </ul>
            </li>
            @endif

            @if (\Gate::check('habilidade_admin', 'visualizar_marcas') || \Gate::check('habilidade_admin', 'visualizar_usuario'))
            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-cellphone"></i><span
                class="hide-menu">Aplicativo </span></a>
                <ul aria-expanded="false" class="collapse">
                    {{-- <li><a href="#">Comerciais</a></li> --}}
                    @can('habilidade_admin', 'visualizar_usuario')
                    <li><a href="{{route('usuarios.index')}}">Usuários</a></li>
                    @endcan
                    @can('habilidade_admin', 'visualizar_marcas')
                    <li><a href="{{route('marcas.index')}}">Marcas</a></li>
                    @endcan
                </ul>
            </li>
            @endif

            @if (\Gate::check('habilidade_admin', 'visualizar_logs') )
                <li> <a class="has-arrow waves-effect waves-dark" href="{{route('logs.index')}}" aria-expanded="false"><i class="mdi mdi-bug"></i><span
                    class="hide-menu">Erros </span></a>
                    {{-- <ul aria-expanded="false" class="collapse">
                        @can('habilidade_admin', 'visualizar_logs')
                        <li><a href="{{route('logs.index')}}">Pedidos</a></li>
                        @endcan
                    </ul> --}}
                </li>
            @endif


















        </ul>
