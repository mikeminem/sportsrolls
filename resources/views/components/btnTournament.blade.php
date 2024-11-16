
<div class="col-md-4 col-sm-6 col-12 mb-1 d-flex">
    <a href="{{ url('tournament_info/' . $tournament->slug) }}" alt="{{ $tournament->title }}" title="{{ $tournament->title }}" class="btnDZ">
        <div class="card h-100">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-4 my-auto">
                        @if($tournament->user->league->logo1)
                            <img class="img-fluid rounded" src="{{ asset('league_logo/' . $tournament->user->league->logo1 )}}" alt="{{ $tournament->title }}">
                        @else
                            @if($tournament->sport->image)
                                <img class="img-fluid rounded" src="{{ asset('sport/' . $tournament->sport->image)}}" alt="{{ $tournament->title }}">
                            @else
                                <img class="img-fluid" src="img_avatar1.png" alt="{{ $tournament->title }}">
                            @endif
                        @endif
                    </div>

                    <div class="col-md-8 col-sm-8 col-8 text-center my-auto">
                        <h3>{{ $tournament->title }}</h3>
                        <p class="m-3">
                            {{ $tournament->sport->name }} 
                            <br>
                        
                                <span class="fa fa-list"></span>
                                @php
                                $prints = $tournament->prints;
                                if ($prints>=1000) {
                                    $prints = number_format($prints /1000,1) . "K";
                                }
                                @endphp   
                                {{ $prints }} 
                                <span class="fa fa-eye"></span>
                                @php
                                $clicks = $tournament->clicks;
                                if ($clicks>=1000) {
                                    $clicks = number_format($clicks /1000,1) . "K";
                                }
                                @endphp   
                                {{ $clicks }}
                            
                        </p>
                    </div>

                </div>
            </div>
          </div>
    </a>
</div>