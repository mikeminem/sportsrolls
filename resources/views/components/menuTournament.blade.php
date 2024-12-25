
<div class="container-fluid text-center header">
    <div class="row">
        <div class="col-md-12">
            <h1 >{{ $tournament->title }}</h1>
        </div>
    </div>
</div>


{{-- small devices --}}
<div class="container py-1 d-block d-sm-none fixed-bottom bg-dark py-3 text-center">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ url('tournament_info/' . $tournament->slug) }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'front-page') ? 'primary': 'light' }}">
                <span class="fa fa-newspaper-o p-2"></span>
            </a>
            <a href="{{ url('tournament_info/' . $tournament->slug . '/results') }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'results') ? 'primary': 'light' }}">
                    <span class="fa fa-align-left p-2"></span>
            </a>
            {{-- <a href="{{ url('tournament_info/' . $tournament->slug . '/calendar') }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'calendar') ? 'primary': 'light' }}">
                <span class="fa fa-calendar p-2"></span>
            </a> --}}
            <a href="{{ url('tournament_info/' . $tournament->slug . '/standings') }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'standings') ? 'primary': 'light' }}">
                <span class="fa fa-list p-2"></span>
            </a>

            <a href="{{ url('tournament_info/' . $tournament->slug . '/photos') }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'photos') ? 'primary': 'light' }}">
                <span class="fa fa-image p-2"></span>
            </a>


            <a href="{{ url('tournament_info/' . $tournament->slug . '/teams') }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'teams') ? 'primary': 'light' }}">
                <span class="fa fa-users p-2"></span>
            </a>
        </div>
    </div>
</div>

{{-- all devices --}}
<div class="container py-1 d-none d-sm-block">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ url('tournament_info/' . $tournament->slug) }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'front-page') ? 'primary': 'light' }}">
                <span class="fa fa-newspaper-o"></span>
                    {{ __('Front Page')}}
            </a>
            <a href="{{ url('tournament_info/' . $tournament->slug . '/results') }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'results') ? 'primary': 'light' }}">
                <span class="fa fa-align-left"></span>
                    {{ __('Scores')}}
            </a>
            {{-- <a href="{{ url('tournament_info/' . $tournament->slug . '/calendar') }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'calendar') ? 'primary': 'light' }}">
                <span class="fa fa-calendar"></span>
                 Calendario
            </a> --}}
            <a href="{{ url('tournament_info/' . $tournament->slug . '/standings') }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'standings') ? 'primary': 'light' }}">
                <span class="fa fa-list"></span>
                    {{ __('Standings')}}
            </a>

            <a href="{{ url('tournament_info/' . $tournament->slug . '/photos') }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'photos') ? 'primary': 'light' }}">
                <span class="fa fa-image"></span>
                    {{ __('Photo Gallery')}}
            </a>


            <a href="{{ url('tournament_info/' . $tournament->slug . '/teams') }}"
                alt="{{ $tournament->title }}"
                title="{{ $tournament->title }}"
                type="button"
                class="btn btn-{{ ($active == 'teams') ? 'primary': 'light' }}">
                <span class="fa fa-users"></span> {{ __('Teams')}}
            </a>
        </div>
    </div>
</div>
