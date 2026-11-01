@extends('layouts.app')

@section('title', 'Mi inicio | ' . config('app.name'))

@section('css')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="player-dashboard pt-0 pb-4" id="container-dashboard">
    <section class="container-fluid px-0 player-hero" id="mi-perfil" aria-labelledby="player-name" data-dashboard-profile>
        <div class="player-portrait">
            <span class="player-portrait-number" aria-hidden="true">{{ optional($profile)->favorite_number ?: 'DZ' }}</span>
            @if (optional($profile)->photo)
                <img src="{{ asset('photo/' . rawurlencode($profile->photo)) }}" alt="Foto de {{ $user->name }} {{ $user->last_name }}" class="player-portrait-image">
            @else
                <div class="player-portrait-placeholder" role="img" aria-label="Perfil sin fotografía">
                    <span>{{ mb_substr($user->name, 0, 1, 'UTF-8') }}{{ mb_substr($user->last_name ?: '', 0, 1, 'UTF-8') }}</span>
                    <a href="{{ url('/my_profile') }}">Agrega tu fotografía <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                </div>
            @endif
            <span class="player-portrait-caption">DEPORTE ZACATECAS</span>
        </div>
        <div class="player-identity">
            <div class="player-hero-topline">
                <span class="player-eyebrow">Tu espacio en el deporte</span>
                <span class="player-member">MI PERFIL</span>
            </div>
            <h1 id="player-name"><span>{{ $user->name }}</span><strong>{{ $user->last_name }}</strong></h1>
            <div class="player-roles">
                @forelse ($user->roles as $assignedRole)
                    <span>{{ $assignedRole->name }}</span>
                @empty
                    <span>Miembro de la comunidad</span>
                @endforelse
            </div>
            @if (optional($profile)->about_me)
                <p class="player-bio">{{ $profile->about_me }}</p>
            @else
                <p class="player-bio">Cada partido cuenta. Este es el lugar para construir tu historia en el deporte.</p>
            @endif
            <dl class="player-facts">
                <div><dt>Fecha de nacimiento</dt><dd>{{ $birthDate ?: 'Por completar' }}</dd></div>
                <div><dt>Sexo</dt><dd>{{ optional($profile)->sex && $profile->sex !== 'NA' ? $profile->sex : 'Por completar' }}</dd></div>
                <div><dt>Número favorito</dt><dd>{{ optional($profile)->favorite_number ?: 'Por completar' }}</dd></div>
            </dl>
            <div class="player-hero-footer">
                <p><i class="fa fa-user-circle-o" aria-hidden="true"></i> Tu identidad, dentro y fuera de la cancha.</p>
                <a class="player-edit" href="{{ url('/my_profile') }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar mi perfil</a>
            </div>
        </div>
    </section>

    <div class="container">
    <nav class="player-navigation" aria-label="Secciones de mi dashboard">
        <a href="#datos-personales">Información personal</a>
        @if ($isPlayer)
            <a href="#estadisticas">Estadísticas</a>
        @endif
        @if (count($sections))
            <a href="#herramientas">Mis herramientas <i class="fa fa-arrow-down" aria-hidden="true"></i></a>
        @endif
    </nav>

    <section class="player-personal" id="datos-personales" aria-labelledby="personal-heading">
        <div class="player-section-heading"><span class="player-eyebrow">FUERA DE LA CANCHA</span><h2 id="personal-heading">Información personal</h2></div>
        <dl class="player-contact-grid">
            <div><dt><i class="fa fa-envelope-o" aria-hidden="true"></i> Correo electrónico</dt><dd>{{ $user->email ?: 'Por completar' }}</dd></div>
            <div><dt><i class="fa fa-phone" aria-hidden="true"></i> Teléfono</dt><dd>{{ $user->phone ?: 'Por completar' }}</dd></div>
            <div><dt><i class="fa fa-black-tie" aria-hidden="true"></i> Talla de playera</dt><dd>{{ optional($profile)->tshirt_size ?: 'Por completar' }}</dd></div>
        </dl>
    </section>

    @if ($isPlayer)
        <section class="player-statistics" id="estadisticas" aria-labelledby="statistics-heading" data-player-statistics>
            <div class="player-section-heading player-statistics-heading">
                <div><span class="player-eyebrow">DENTRO DE LA CANCHA</span><h2 id="statistics-heading">Mi rendimiento</h2></div>
                <span class="player-status"><span aria-hidden="true"></span> Estadísticas próximamente</span>
            </div>
            <div class="player-stat-grid">
                @foreach (['PTS' => 'Puntos por partido', 'REB' => 'Rebotes por partido', 'AST' => 'Asistencias por partido'] as $abbreviation => $label)
                    <div class="player-stat">
                        <span class="player-stat-label">{{ $abbreviation }}</span><strong aria-label="Sin datos disponibles">—</strong><span>{{ $label }}</span>
                    </div>
                @endforeach
            </div>
            <div class="player-stat-empty"><i class="fa fa-bar-chart" aria-hidden="true"></i><div><h3>Tu juego contará su historia</h3><p>Aquí podrás consultar tu rendimiento cuando las estadísticas de tus partidos estén disponibles.</p></div></div>
        </section>
    @endif

    @if (count($sections))
    <div id="herramientas" class="player-tools-heading"><span class="player-eyebrow">SIGUE EN MOVIMIENTO</span><h2>Mis herramientas</h2><p>Todo lo que necesitas para participar y administrar tu actividad.</p></div>
    @foreach ($sections as $sectionKey => $section)
        <section class="mb-4" aria-labelledby="section-{{ $sectionKey }}">
            <h2 class="h4 mb-3" id="section-{{ $sectionKey }}">{{ $section['title'] }}</h2>
            <div class="row">
                @foreach ($section['cards'] as $cardKey => $card)
                    @include('components.dashboard-card')
                @endforeach
            </div>
        </section>
    @endforeach
    @endif
    </div>
</div>
@endsection

@section('js')
<script>
    var calendarForm = document.getElementById('dashboard-calendar');
    if (calendarForm) {
        calendarForm.addEventListener('submit', function (event) {
            event.preventDefault();
            var destination = document.getElementById('dashboard-tournament').value;
            if (destination) window.location.assign(destination);
        });
    }
</script>
@endsection
