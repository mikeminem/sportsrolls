<div class="col-12 col-md-6 col-lg-4 mb-3" data-dashboard-card="{{ $cardKey }}">
    <div class="card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
            <h3 class="h5"><i class="fa fa-{{ $card['icon'] }} text-primary mr-2" aria-hidden="true"></i>{{ $card['title'] }}</h3>
            <p class="text-muted">{{ $card['description'] }}</p>
            @if (isset($card['url']))
                <a class="btn btn-outline-primary mt-auto align-self-start" href="{{ $card['url'] }}">{{ $card['title'] }}</a>
            @elseif (isset($card['calendar']))
                @if ($tournaments->where('slug', '!=', '')->count())
                    <form id="dashboard-calendar" class="mt-auto">
                        <label for="dashboard-tournament">Torneo</label>
                        <select id="dashboard-tournament" class="form-control mb-2" required>
                            <option value="">Selecciona un torneo</option>
                            @foreach ($tournaments as $tournament)
                                @if ($tournament->slug)
                                    <option value="{{ url('/tournament_info/' . rawurlencode($tournament->slug) . '/calendar') }}">{{ $tournament->title }}</option>
                                @endif
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary">Ver calendario</button>
                    </form>
                @else
                    <p class="text-muted mt-auto mb-0">Aún no tienes torneos con calendario disponible.</p>
                @endif
            @elseif (isset($card['links']))
                <ul class="list-unstyled mb-0">
                    @foreach ($card['links'] as $link)
                        <li class="mb-2"><a href="{{ $link['url'] }}">{{ $link['title'] }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
