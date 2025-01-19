<div class="table-responsive">
    <table class="table table-striped table-standings" id="table">
        <thead inert>
            <tr class="text-center">
                <th width="20px">{{ __('#') }}</th>
                <th width="200px" class="text-left">{{ __('Equipo') }}</th>
                <th width="20px">JJ</th>
                <th width="20px">JG</th>
                @if ($tournament->draw_points > -1)
                    <th width="20px">JE</th>
                @endif
                <th width="20px">JP</th>
                @if ($tournament->enable_penalties_scoring === 'Si')
                    <th width="20px">JGP</th>
                    <th width="20px">JPP</th>
                @endif
                @if ($tournament->sport->name === 'Futbol')
                    <th width="20px" class="hideColumn">GF</th>
                    <th width="20px" class="hideColumn">GC</th>
                @else
                    <th width="20px" class="hideColumn">PF</th>
                    <th width="20px" class="hideColumn">PC</th>
                @endif
                <th width="20px">DIF</th>
                @if ($tournament->enable_points_system=="Si")
                    <th width="20px">PTOs</th>
                @endif

            </tr>
        </thead>

        <tbody inert>
            @php
                $lugar = 1;
            @endphp
            @foreach ( $scores as $score )
                @if($score->category_id == $category->id)

                    @if( $category->has_groups === 'Si')

                        @if($score->group_id == $group->id)
                            @include('components.tableStandingRow')
                            @php
                                $lugar++;
                            @endphp
                        @endif

                    @else
                        @include('components.tableStandingRow')
                        @php
                            $lugar++;
                        @endphp

                    @endif

                @endif

            @endforeach

        </tbody>
    </table>

</div>
