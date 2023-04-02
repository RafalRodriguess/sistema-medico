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
    {{-- @if (\session()->get('comercial')) --}}
        @if (\Gate::check('habilidade_comercial_sessao', 'visualizar_usuario') || \Gate::check('habilidade_comercial_sessao', 'editar_comercial') || \Gate::check('habilidade_comercial_sessao', 'editar_horarios_funcionamento') )
            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account-card-details"></i><span
                        class="hide-menu">Administração </span></a>
                <ul aria-expanded="false" class="collapse">
                    @can('habilidade_comercial_sessao', 'visualizar_usuario')
                        <li><a href="{{ route('comercial.comerciais_usuarios.index') }}">Usuários</a></li>
                    @endcan
                    @can('habilidade_comercial_sessao', 'editar_comercial')
                        <li><a href="{{ route('comercial.comercial_loja.edit') }}">Comercial</a></li>
                    @endcan
                    @can('habilidade_comercial_sessao', 'editar_horarios_funcionamento')
                        <li><a href="{{ route('comercial.horarios_funcionamento.index') }}">Horarios Funcionamento</a></li>
                    @endcan

                </ul>
            </li>
        @endif
        @if (\Gate::check('habilidade_comercial_sessao', 'visualizar_categoria') || \Gate::check('habilidade_comercial_sessao', 'visualizar_sub_categoria') || \Gate::check('habilidade_comercial_sessao', 'visualizar_produto') || \Gate::check('habilidade_comercial_sessao', 'editar_parcelas'))
            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-cart"></i><span
                class="hide-menu">Loja </span></a>
                <ul aria-expanded="false" class="collapse">
                {{-- <li><a href="#">comerciais</a></li> --}}
                @can('habilidade_comercial_sessao', 'editar_parcelas')
                    <li><a href="{{ route('comercial.parcelas.edit') }}">Parcelas</a></li>
                @endcan
                @can('habilidade_comercial_sessao', 'visualizar_categoria')
                    <li><a href="{{route('comercial.categorias.index')}}">Categorias</a></li>
                @endcan
                @can('habilidade_comercial_sessao', 'visualizar_sub_categoria')
                    <li><a href="{{route('comercial.sub_categorias.index')}}">Sub Categorias</a></li>
                @endcan
                {{-- OBS CATEGORIAS, FAZER SUBCATEGORIAS --}}
                @can('habilidade_comercial_sessao', 'visualizar_produto')
                    <li><a href="{{route('comercial.produtos.index')}}">Produtos</a></li>
                @endcan
                </ul>
            </li>
        @endif
        @if (\Gate::check('habilidade_comercial_sessao', 'visualizar_pedidos'))
            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-file-document"></i><span
                class="hide-menu">Pedidos </span></a>
                <ul aria-expanded="false" class="collapse">
                @can('habilidade_comercial_sessao', 'visualizar_pedidos')
                    <li><a href="{{ route('comercial.pedidos.index') }}">Meus Pedidos</a></li>
                @endcan
           
                </ul>
            </li>
        @endif
        @if (\Gate::check('habilidade_comercial_sessao', 'visualizar_fretes'))
        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-cube-send"></i><span class="hide-menu">Fretes </span></a>
            <ul aria-expanded="false" class="collapse">
        
                @can('habilidade_comercial_sessao', 'visualizar_fretes')
                <li><a href="{{route('comercial.fretes_entregas')}}">Entregas</a></li>
                @endcan
                
                @can('habilidade_comercial_sessao', 'visualizar_fretes')
                <li><a href="{{route('comercial.fretes_retiradas')}}">Retiradas</a></li>
                @endcan

            </ul>
        </li>
        @endif
    {{-- @endif --}}


















    </ul>