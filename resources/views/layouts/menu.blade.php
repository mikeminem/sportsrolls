<!--div class="container-fluid d-block d-sm-none top">
    <dov class="row">
        <div class="col-xs-12">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('/images/imgLogo.png') }}" 
                    alt="{{ config('app.name', 'DeporteZacatecas.com') }}" 
                    title="{{ config('app.name', 'DeporteZacatecas.com') }}"
                    class="img-fluid" >
            </a>
        </div>
    </dov>
</div-->
<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{  url('/') }}">
                    <!--img src="{{ asset('/images/imgLogo.png') }}" 
                        title="{{ config('app.name', 'DeporteZacatecas.com') }}" 
                        alt="{{ config('app.name', 'DeporteZacatecas.com') }}"
                        width="220px" class="d-none d-sm-block"-->
                        <span class="brand-title">{{ config('app.name') }}</span>

                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest

                            @if ($pageCategories)
                                @foreach ($pageCategories as $pageCategory)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/pagecategory/' . $pageCategory->slug) }}"><i class="fa fa-{{ ($pageCategory->icon) ?: $pageCategory->icon }}"></i> {{ $pageCategory->name }}</a>
                                </li>
                                @endforeach

                            @endif

                            {{-- <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-cube"></i> {{ __('Services') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-bullhorn"></i> {{ __('Events') }}</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fa fa-bolt"></i> {{ __('Sports') }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">{{ __('No data') }}</a>
                                </div>
                            </li> --}}

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}"><i class="fa fa-sign-in"></i> {{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}"><i class="fa fa-pencil"></i>  {{ __('Register') }}</a>
                                </li>
                            @endif

                            {{-- <form class="form-inline" action="#">
                                <input class="form-control mr-sm-2" type="text" placeholder="{{ __('Search')}}">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> {{ __('Search')}}</button>
                            </form> --}}

                        @else

                        <li class="nav-item">
                            <a class="nav-link" href=" {{ url('/home') }} "><i class="fa fa-home"></i> {{ __('Home') }}</a>
                        </li>

                        {{-- <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-comments"></i> {{ __('Mensajes') }}</a>
                        </li> --}}

                            @if (Auth::user()->role == "Promoter")
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fa fa-trophy"></i> {{ __('League Catalogs') }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ url('/stadiums') }}"><i class="fa fa-map-marker"></i>  {{ __('Stadiums') }}</a>
                                        
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ url('/tournaments') }}"><i class="fa fa-trophy"></i> {{ __('Tournaments') }}</a>
                                        <a class="dropdown-item" href="{{ url('/categories') }}"><i class="fa fa-cubes"></i> {{ __('Categories') }}</a>
                                        <a class="dropdown-item" href="{{ url('/groups') }}"><i class="fa fa-circle-o"></i> {{ __('Groups') }}</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ url('/teams') }}"><i class="fa fa-group"></i> {{ __('Teams') }}</a>
                                        <a class="dropdown-item" href="{{ url('/players') }}"><i class="fa fa-user"></i> {{ __('Players') }}</a>
                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item" href="{{ url('/journeys')}}"><i class="fa fa-calendar"></i> {{ __('Journeys') }}</a>
                                        <a class="dropdown-item" href="{{ url('/games')}}"><i class="fa fa-tasks"></i> {{ __('Games') }}</a>

                                        {{-- <div class="dropdown-divider"></div> --}}
                                        <a class="dropdown-item" href="{{ url('/alerts') }}"><i class="fa fa-warning"></i>  {{ __('Alerts') }}</a>
                                        
                                    </div>
                                </li>

                            @endif
                            

                            @if (Auth::user()->role == "Admin")

                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fa fa-folder"></i> {{ __('Sport Catalogs') }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ url('/sports') }}" title="Catálogo de Deportes"><i class="fa fa-cube"></i> {{ __('Sports') }}</a>
                                        <a class="dropdown-item" href="{{ url('/actions') }}" title="Catálogo de Acciones"><i class="fa fa-gamepad"></i> {{ __('Actions') }}</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#"><i class="fa fa-trophy"></i> {{ __('Tournaments') }}</a>
                                        <a class="dropdown-item" href="#"><i class="fa fa-users"></i> {{ __('Players') }}</a>
                                    </div>
                                </li>


                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fa fa-bars"></i> {{ __('Catalogs') }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ url('/states') }}"><i class="fa fa-square"></i> {{ __('States') }}</a>
                                        <a class="dropdown-item" href="{{ url('/municipalities')}}"><i class="fa fa-square"></i> {{ __('Municipalities') }}</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ url('/users') }}"><i class="fa fa-users"></i> {{ __('Users') }}</a>
                                        <a class="dropdown-item" href="#"><i class="fa fa-money"></i> {{ __('Plan') }}</a>

                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#"><i class="fa fa-bank"></i> {{ __('Banks') }}</a>

                                    </div>
                                </li>


                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fa fa-sitemap"></i> {{ __('Portal') }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ url('/alerts') }}"><i class="fa fa-square"></i> {{ __('Alerts') }}</a>
                                        <a class="dropdown-item" href="{{ url('/pagecategories')}}"><i class="fa fa-file"></i> {{ __('PageCategories') }}</a>
                                        <a class="dropdown-item" href="{{ url('/webpages')}}"><i class="fa fa-file"></i> {{ __('Pages') }}</a>
                                        <div class="dropdown-divider"></div>

                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ url('/sponsors')}}"><i class="fa fa-file"></i> {{ __('Sponsors') }}</a>

                                    </div>
                                </li>


                            @endif

                                
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fa fa-user"></i>  {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#"><i class="fa fa-certificate"></i> Usted es {{ $role }}</a>
                                    @if (Auth::user()->role == "Promoter")
                                        <a class="dropdown-item" href=" {{ url('league_info')}}" title="{{ __('League Info') }}"><i class="fa fa-info"></i> {{ __('League Info') }}</a>
                                    @endif

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ url('my_profile') }}"><i class="fa fa-photo"></i> {{ __('My Profile') }}</a>
                                    <!--a class="dropdown-item" href="#"><i class="fa fa-money"></i> {{ __('Plan Info') }}</a-->

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>

                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#" title="{{ __('Report a Bug')}}"><i class="fa fa-bug"></i></a>
                            </li>
                            
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>