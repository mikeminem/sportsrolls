@extends('layouts.app')

@section('title',  $tournament->title . ' - ' . __('Página del Torneo')  )
@section('description', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )
@section('url',  url('tournament_info/' . $tournament->slug) )
@section('keywords', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )


@section('content')

@include('components.menuTournament', ['active' => 'front-page'])

<div class="container">
    <div class="row">
        <div class="col-md-8">

            {{-- jornada de la semana --}}
            @foreach ($journeys as $journey)

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="title"><b> {{ $journey->name }}</b></h3>
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        @foreach ($game_days as $game_day)
                                            @php
                                                $day = $game_day;
                                            @endphp
                                            {{-- <div class="col-md-12 text-center m-1">
                                                <h4>

                                                    @php
                                                        $dayName = date('D', strtotime($day));
                                                        $diassemana = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
                                                        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

                                                        //echo $diassemana[date('w', strtotime($day))]." ".date('d', strtotime($day))." de ".$meses[date('n', strtotime($day))-1]. " de ".date('Y', strtotime($day)) ;
                                                    @endphp
                                                </h4>
                                            </div> --}}

                                            @foreach ($stadiums as $stadium)
                                                @php
                                                    $displayStadium = false;
                                                @endphp

                                                @foreach ($games as $game)
                                                    @if($game->journey_id == $journey->id
                                                        && $game->game_day == $game_day
                                                        && $game->stadium == $stadium
                                                    )
                                                        @php
                                                            $displayStadium = true;
                                                        @endphp
                                                    @endif
                                                @endforeach

                                                @if ($displayStadium)
                                                    <div class="col-md-12 text-center m-1">
                                                        <h5>
                                                            {{ $stadium }}
                                                        </h5>
                                                    </div>
                                                @endif

                                                @foreach ($games as $game)
                                                    @if($game->journey_id == $journey->id
                                                        && $game->game_day == $game_day
                                                        && $game->stadium == $stadium
                                                    )
                                                        @include('components/btnGame')
                                                    @endif
                                                @endforeach

                                            @endforeach
                                            <hr>
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <b>Nota: {{ $journey->notes ?? 'Sin incidencias' }}</b>
                                </div>
                            </div>
                    </div>
                </div>

            @endforeach



            {{-- noticias y contenidos web --}}

            @if (count($webContents) ==0)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                ¡No se ha publicado contenido, regresa pronto!
                            </div>
                        </div>

                    </div>
                </div>
            @else
                @foreach ($webContents as $webContent)
                    <a href="{{ asset('webpage/' . $webContent->slug ) }}" alt="{{ $webContent->title }}" title="{{ $webContent->title }}" class="btnDZ">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($webContent->image)
                                            <img class="rounded img-fluid"
                                            src="{{ asset('web_pages/' . $webContent->image) }} "
                                            alt="{{ $webContent->title }}"
                                            title="{{ $webContent->title }}"
                                            /><br>
                                        @endif
                                        <h3><b>{{ $webContent->title }}</b></h3>
                                        <b>{{ $webContent->description }}</b>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </a>
                @endforeach

            @endif

        </div>

        <div class="col-md-4">

            @if ($tournament->enable_enrollment == 'Si')
                <div class="card mb-2">
                    <div class="card-header bg-dark text-light">Inscripción de Jugadores</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 m-1">
                                <p><b>El administrador del torneo habilitó el <b>registro de jugadores</b>, si no conoces el código de registro,
                                    pregunta por él en el grupo de whatsapp del torneo.</b>
                                </p>

                                <div class="form-group">
                                    <label for="code"><b>Código de Registro</b></label>
                                    <input class="form-control" type="text" id="code_enrollment" name="code_enrollment" maxlength="10">
                                </div>

                                <button class="btn btn-success btn-lg w-100" id="btnEnrollment" name="btnEnrollment">
                                    <span class="fa fa-edit"></span> Me quiero registrar
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
            <div class="card mb-2">
                <div class="card-header bg-dark text-light">Primeras posiciones</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-1">
                            @foreach ($categories as $category )
                                <h4>{{ $category->name }}</h4>
                                <ul class="list-group list-group-flush mb-2">
                                    @php
                                        $place = 1;
                                    @endphp
                                    @foreach ( $scores as $score )
                                        @if($score->category_id == $category->id
                                            && $place <=3)
                                            <a href="#">
                                                <li class="list-group-item d-flex justify-content-between align-items-left">
                                                        @if( $score->logo )
                                                            <img class="img" src="{{ asset('team_logo/' . $score->logo) }} " style="width:40px">
                                                        @else
                                                            <img class="img" src="{{ asset('images/_shield.png') }} " style="width:40px">
                                                        @endif
                                                        {{ $score->team }}
                                                        <span class="badge badge-light badge-pill">{{ $place }}</span>
                                                    </li>
                                            </a>
                                            @php
                                                $place++;
                                            @endphp
                                        @endif
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row text-right">
                        <div class="col-md-12">
                            <div class="fb-share-button"
                                data-href="{{ url('tournament_info/' . $tournament->slug) }}"
                                data-layout="button_count" data-size="large">
                                    <a target="_blank"
                                        href="{{ url('tournament_info/' . $tournament->slug) }}"
                                        class="fb-xfbml-parse-ignore">Share
                                    </a>
                            </div>
                            <a class="btn btn-primary whatsapp"
                                href="whatsapp://send?text={{ url('tournament_info/' . $tournament->slug) }}"
                            target="_blank"><span class="fa fa-whatsapp"></span> Share </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- forms --}}

@include('forms/formPlayer')

@endsection

@section('js')
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="{{ asset('js/jquery.cropbox.js') }}"></script>
<link href="{{ asset('css/jquery.cropbox.css') }}" rel="stylesheet">


<script type="text/javascript">
    jQuery(document).ready(function($){

        $('#btnEnrollment').click( function () {

            let code = $('#code_enrollment').val();

            if (code == null || code ==="") {
                Swal.fire({
                    title: 'Error!',
                    text: 'Escribe el código de acceso al torneo',
                    icon: 'error',
                    confirmButtonText: 'Enterado'
                });
                return false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "GET",
                url: "{{ url('/tournament_enrollment') }}/" + code,
                dataType: "json",
                data: $(this).serialize(),
                success: function (data) {
                    if (data.access == true) {
                        //display the player form
                        $('#code_enrollment').val('');

                        let tournament = {{ $tournament->id }}
                        let categories = '';
                        getCategories("category_id",tournament);
                        $('#hide_name').val('Si');
                        $('#player_photo').attr('src', '{{ asset('images/No-photo-m.png') }}');

                        jQuery.noConflict();
                        jQuery("#formPlayerModal").modal('toggle');

                    } else {
                        $('#code_enrollment').val('');

                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        })
                    }

                },
                error:function(){
                    $.notify("Hubo un error al intentar validar el cídog de registro!", "error");
                },
            });

            return false;
        });

        $(document).on("change", "#category_id", function (e) {
            $("#category_id option:selected").each(function () {
                categoryId=$(this).val();
                $("#team_id").html('<option valu="">{{ __("Selecciona un torneo and Category") }}</option>');
                getTeams("team_id",categoryId);
            });
        });

        $(document).on("submit", "#formPlayer", function (e) {
            e.preventDefault();

            let data = new FormData(this);
            data.delete('photo2')
            data.append("photo",$('#player_photo').data('cropbox').getBlob(),"cropped-"+(new Date)+".jpg")

    	    //data.append('photo', $("#player_photo").val().replace(/^data:image\/(png|jpg);base64,/, ""));

            $.ajax({
                type: "POST",
                url: "{{ route('rosters.store') }}",
                data : data,
    			cache : false,
	    		contentType : false,
		    	processData : false,
                dataType:'JSON',
                //data: new FormData(this),
                success: function (data) {
                    if (data.type == "error") {
                        var errors = "";
                        $.each(data.errors, function (i, val) {
                            errors = errors  +  val + "\n";
                        });
                        //$.notify(data.message + "\n" + errors, data.type, { position:"center" } );

                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        })

                        return false;
                    }

                    $("#formPlayer").trigger('reset');

                    jQuery.noConflict();
                    jQuery("#formPlayerModal").modal('toggle');
                    //Table.ajax.reload();
                    $('#table').DataTable().ajax.reload();
                    //$.notify(data.message, data.type);
                    Swal.fire({
                            title: 'Bienvenido!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Gracias'
                        })

                },
                error:function(){
                    $.notify("There is an error trying to save the player information! FE", "error");
                },
            });

            return false;
        });

        $('#photo2').on('change', uploadImageProfile);

        // var uploadImageProfile = function () {
        //         var file    = $('#photo')[0].files[0] ;
        //         var reader  = new FileReader();
        //         alert('here');

        //         var preview = document.getElementById('player_photo');
        //         preview.src = "";

        //         // funcion para mostrar la imagen cargada en el crop
        //         reader.onloadend = function () {
        //                 preview.src = reader.result;

        //                 $('#player_photo').cropbox({
        //                     width: 300,
        //                     height: 300,
        //                 }).on('cropbox', function (e, result, img) {
        //                     // cuando se hace el crop se agrega el resultado a al input file
        //                     $('#photo').val(img.getDataURL());
        //                 });
        //             }

        //             reader.readAsDataURL(file);
        //     }


    //     //handle the image photo
    // //function uploadImageProfile() {
    //     $(document).on("change", "#photo", function (e) {
    //         //showLoading();
    //         // $('#loaderFile').show();
    //         //let file    = document.querySelector('input[type=file]').files[0];
    //         //let file    = $('#photo')[0].files[0];
    //         //let file = document.getElementById("photo").files[0]; // will return a File object containing information about the selected file

    //         let file    = $('#photo')[0].files[0] ;
    // 	  	var reader  = new FileReader();

    //         let preview = document.getElementById('player_photo');

    //         preview.src = "";

    //         reader.onloadend = function (data) {
    //             preview.src = reader.result;

    //             $('#player_photo').attr('crossorigin', "anonymous");
    //             $('#player_photo').css('width', "auto");

    //             $('#player_photo').cropbox({
    //                 width: 300,
    //                 height: 300,
    //             }).on('cropbox', function (e, results, img) {
    //                 //$('#theHidden').attr('data', img.getDataURL());
    //                 //$('#saveImageProfile').attr('data', img.getDataURL());
    //                 //$("#theHidden").val ( JSON.stringify(results) );
    //                 //$('#photo').attr('data',img.getDataURL());
    //                 //$('#photo').prop('data', img.getDataURL());
    //                 $(this).attr('data', img.getDataURL());
    //                 //$('#photo').attr('data', img.getBlob());



    //                 //$('#photo').attr('data', JSON.stringify(img.getDataURL()) );

    //             });
    //         }

    //         reader.readAsDataURL(file);

    //     })
    });


    var uploadImageProfile = function () {
        var file    = $('#photo2')[0].files[0];
        var reader  = new FileReader();

        var preview = document.getElementById('player_photo');
        preview.src = "";

        // funcion para mostrar la imagen cargada en el crop
        reader.onloadend = function () {
                preview.src = reader.result;

                $('#player_photo').cropbox({
                    width: 200,
                    height: 200,
                }).on('cropbox', function (e, results, img) {
                    // NOTHING TO DO
                    //$('#photo').val(img.getDataURL());

                    //console.log( JSON.stringify(results) )
                    //#$("#ptoho").val ( JSON.stringify(results) );

                    //$('#photo').val(img.getDataURL());
                    //$('#photo').val(img.getBlob());

                });
            }

            reader.readAsDataURL(file);
    }


</script>

@endsection
