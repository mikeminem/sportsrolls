@extends('layouts.app')

@section('title', 'Mi inicio | ' . config('app.name'))

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h1 class="h2">Hola, {{ $user->name }}</h1>
        <p class="text-muted mb-2">Tu actividad y herramientas de Deporte Zacatecas en un solo lugar.</p>
        @foreach ($user->roles as $assignedRole)
            <span class="badge badge-secondary mr-1">{{ $assignedRole->name }}</span>
        @endforeach
    </div>

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
