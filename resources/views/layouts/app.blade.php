<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <meta name="robots" content="index, follow">
    <meta name="title" content="@yield('title')">
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name=image content="@yield('image')">
    <meta name="author" content="DeporteZacatecas.com" />
    <meta name="copyright" content="Copyright DeporteZacatecas.com" />
    <meta name="revisit-after" content="1 days" />
    <meta http-equiv="cache-control" content="no-cache"/>
    <link rel="icon" href="{{ asset('images/icoDeporteZacatecas.png') }}" type="image/x-icon">



    <!--social media metas-->
    <meta property="og:url"             content="@yield('url')" />
    <meta property="og:title"           content="@yield('title')"/>
    <meta property="og.type"            content="website" />
    <meta property="og:description"     content="@yield('description')" />
    <meta property="og:keywords"        content="@yield('keywords')" />
    <meta property="og:image:alt"       content="@yield('title')"/>
    {{-- <meta property="og:image"           content="{{ asset('images/imgSportRoles.jpg') }}" /> --}}
    <meta property="og:image"           content="@yield('image')" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">


        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-1X7MS1GBYC"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-1X7MS1GBYC');
        </script>
</head>
<body>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v15.0" nonce="n3xha6Hh"></script>

    <!--div class="content-fluid top">
        <div class="content">
            <div class="row">
                <div class="col-sm-6 col-md-6 text-center">
                    <i class="fa fa-envelope"></i> {{ __("Contact Us")}}
                </div>

                <div class="col-sm-6 col-md-6 text-center">
                    <i class="fa fa-whatsapp"></i> {{ __("WhatsApp")}}
                </div>
            </div>
        </div>
    </div-->
    <div id="app">
        @include('layouts.menu')

        <main class="py-0 mb-5">

            @guest
            {{-- if guest, we display the following teams section --}}

                @if (!isset($follower))
                    <div class="container">
                        <div class="row m-2">
                            <div class="col-12 text-right">
                                <button class="btn btn-success btn-lg" id="btnFollow" alt="Sigue a tu equipo" title="Sigue a tu equipo">
                                    <span class="fa fa-arrow-right"></span>
                                    Sigue a tu equipo
                                </button>
                            </div>
                        </div>
                    </div>

                @else
                <div class="container">
                    <div class="row m-2">
                        <div class="col-xl-6 col-md-6 col-sm-12 ">

                            <div class="card">
                                <div class="card-header">Equipos que sigues</div>
                                <div class="panel-body">

                                    <table class="table table-hover">
                                        <thead>
                                          <tr>
                                            <th>Equipo</th>
                                            <th>Ganados</th>
                                            <th>Perdidos</th>
                                            <th>Posición</th>
                                          </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($followTournament as $tournament)
                                            <tr clsss="text-center">
                                                <td colspan="4">{{ $tournament->title }}</td>
                                            </tr>

                                            @foreach($followTeam as $team)
                                                @if($tournament->id == $team->tournament_id)
                                                    <tr>
                                                        <td><b>{{ $team->name }}</b></td>
                                                        <td>{{ $team->wins }}</td>
                                                        <td>{{ $team->losses }}</td>
                                                        <td><b>{{ $team->position }}</b> </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach

                                        </tbody>
                                      </table>
                                </div>
                              </div>

                        </div>

                        <div class="col-xl-6 col-md-6 col-sm-12 ">
                            <div class="card">
                                <div class="card-header">Próximos juegos</div>
                                <div class="panel-body">
                                    @foreach ($followGames as $game )
                                        @include('components/btnGame')
                                    @endforeach
                                </div>
                              </div>
                        </div>
                    </div>
                </div>

                @endif
            @endguest

            {{-- <div class="alert alert-danger text-center">
                <i class="fa fa-warning"></i> <strong>Aviso!</strong> A todos los usuarios de la página, se les informa que cambiaremos de nombre de dominio de sportroles.com a deportezacatecas.com.
              </div> --}}

            @yield('content')
        </main>

        @include('layouts.footer')
    </div>



@guest

{{-- modal follow --}}
<div class="modal" id="followModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Sigue a tu equipo</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        <div class="modal-body">
        <h5>Sigue tu equipo y ve cuando juegas sin necesidad de navegar en la página.</h5>
        <br>
        <div class="container">
            <form id="followForm">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="title"><b>Torneo *</b></label>
                            <select class="form-control" required id="follow_tournament_id" name="follow_tournament_id">
                                <option value="">Selecciona</option>
                                @foreach($followTournaments as $tournament)
                                    <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="title"><b>Categoría *</b></label>
                            <select class="form-control" required id="follow_category_id" name="follow_category_id">
                                <option value="">Selecciona un torneo</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="title"><b>Equipo *</b></label>
                            <select class="form-control" required id="follow_team_id" name="follow_team_id">
                                <option value="">Selecciona un torneo</option>
                            </select>
                        </div>
                    </div>
                    {{ csrf_field() }}
                    <input type="hidden" class="form-control" id="follower" name="follower" value="{{ $fingerPrint }}">
                </div>

                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary btn-lg" id="btnAddFollowTeam">
                            <span class="fa fa-plus"></span>
                            Agregar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <h4>Equipos que sigues</h4>
        <table class="table table-striped table-bordered" id="followTable">
            <thead>
                <tr>
                    <th>Torneo</th>
                    <th>Categoría</th>
                    <th>Equipo</th>
                    <th></th>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td colspan="4">
                        <b>No estás siguiendo ningún equipo</b>
                    </td>
                </tr>
            </tbody>

        </table>
        </div>


        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                <span class="fa fa-remove"></span> {{__('Cerrar')}}
            </button>
        </div>

      </div>
    </div>
  </div>
{{-- end modal follow --}}
@endguest
    <!--  Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script-->


    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Notify -->
    <script src="{{ asset('js/notify/notify.min.js') }} "></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <!-- DataTables -->
    {{-- <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script> --}}
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>



    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!--page js-->
     @yield('js')


     <script type="text/javascript">

    jQuery(document).ready(function($){

        $(document).on("click", "#btnFollow", function () {
            $('#follower').val('{{ $fingerPrint }}');
            jQuery("#followModal").modal('show');
        });

        $(document).on("change", "#follow_tournament_id", function () {
            var tournament=$(this).val();

            if (tournament =="") {
                $("#follow_category_id").empty();
                $("#follow_category_id").html('<option value="">Selecciona un torneo</option>');
                $("#follow_team_id").empty();
                $("#follow_team_id").html('<option valu="">Selecciona un torneo</option>');
            } else {
                getCategories("follow_category_id",tournament);
            }

        });

        $(document).on("change", "#follow_category_id", function () {
            var category=$(this).val();

            if (category =="") {
                $("#follow_team_id").empty();
                $("#follow_team_id").html('<option valu="">Selecciona un torneo</option>');
            } else {
                getTeams("follow_team_id",category);
            }

        });


        $(document).on("submit", "#followForm", function (e) {
            e.preventDefault();
            alert('Guardar mi lista');
        });


    });


        function getCategories(component, tournament_id, has_groups="no", selected="") {
            let options = "";

            if (tournament_id) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('categories_list') }}/" + tournament_id + "/" + has_groups,
                    dataType: "json",
                    success: function (data) {
                        options = '<option value="">{{ __("Selecciona...") }}</option>';
                        var categories = -1
                        for(obj in data) {
                            if ( selected == data[obj]['id'] ) {
                                options += '<option value="' +data[obj]['id'] + '" selected="selected" >' + data[obj]['name'] + '</option>';
                            } else {
                                options += '<option value="' +data[obj]['id'] + '" >' + data[obj]['name'] + '</option>';
                            }
                            categories++;
                        }

                        if (categories <0) {
                            $.notify("No hay categorías en este torneo, selecciona otro o agrega categorias al torneo", "error");
                        }
                        //$("#category_id").html(options);
                        $("#" + component).html(options);

                    },
                });
            }
            return options;
        };

        function getJourneys(component, tournament, selected="") {
            var options = "";

            if (tournament) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('journaments_list') }}/" + tournament,
                    dataType: "json",
                    success: function (data) {
                        options += '<option value="">{{ __("Select...") }}</option>';
                        var journaments = -1
                        for(obj in data) {
                            if ( selected == data[obj]['id'] ) {
                                options += '<option value="' +data[obj]['id'] + '" selected="selected" >' + data[obj]['name'] + '</option>';
                            } else {
                                options += '<option value="' +data[obj]['id'] + '" >' + data[obj]['name'] + '</option>';
                            }
                            journaments++;
                        }

                        if (journaments <0) {
                            $.notify("No hay Jornadas en este torneo, selecciona otro o agrega jornadas al torneo", "error");
                        }
                        $("#" + component).html(options);

                    },
                });
            }
        }

        function getTeams(component, categoriId, selected="") {
            var options = "";

            if (categoriId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('teams_list') }}/" + categoriId,
                    dataType: "json",
                    success: function (data) {
                        options += '<option value="">{{ __("Selecciona...") }}</option>';
                        var journaments = -1
                        for(obj in data) {
                            if ( selected == data[obj]['id'] ) {
                                options += '<option value="' +data[obj]['id'] + '" selected="selected" >' + data[obj]['name'] + '</option>';
                            } else {
                                options += '<option value="' +data[obj]['id'] + '" >' + data[obj]['name'] + '</option>';
                            }
                            journaments++;
                        }

                        if (journaments <0) {
                            $.notify("No hay Equipos en este torneo, selecciona otro o agrega equipos al torneo", "error");
                        }
                        $("#" + component).html(options);

                    },
                });
            }
        }

        function getTeamsByCategoryAndBranch(component, categoriId,  branch, selected="") {
            var options = "";

            if (categoriId && branch) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('getTeamsbyCategoryAndBranch') }}/" + categoriId + '/' + branch,
                    dataType: "json",
                    success: function (data) {
                        options += '<option value="">{{ __("Select...") }}</option>';
                        var journaments = -1
                        for(obj in data) {
                            if ( selected == data[obj]['id'] ) {
                                options += '<option value="' +data[obj]['id'] + '" selected="selected" >' + data[obj]['name'] + '</option>';
                            } else {
                                options += '<option value="' +data[obj]['id'] + '" >' + data[obj]['name'] + '</option>';
                            }
                            journaments++;
                        }

                        if (journaments <0) {
                            $.notify("No hay Equipos en este torneo, selecciona otro o agrega equipos al torneo", "error");
                        }
                        $("#" + component).html(options);

                    },
                });
            }
        }

        function getTeamsByBranch(components, tournament, branch, selected="") {
            var options = "";

            if (tournament) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('teams_list_by_branch') }}/" + tournament + '/' + branch,
                    dataType: "json",
                    success: function (data) {
                        options += '<option value="">{{ __("Select...") }}</option>';
                        var journaments = -1
                        for(obj in data) {
                            if ( selected == data[obj]['id'] ) {
                                options += '<option value="' +data[obj]['id'] + '" selected="selected" >' + data[obj]['name'] + '</option>';
                            } else {
                                options += '<option value="' +data[obj]['id'] + '" >' + data[obj]['name'] + '</option>';
                            }
                            journaments++;
                        }

                        if (journaments <0) {
                            $.notify("No hay Equipos en este torneo, selecciona otro o agrega equipos al torneo", "error");
                        }
                        components.forEach( function(component) {
                            $("#" + component).html(options);
                        })
                    },
                });
            }
        }

        //getGroups
        function getGroups(components, tournament, selected="") {
            var options = "";

            if (tournament) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('get_groups') }}/" + tournament,
                    dataType: "json",
                    success: function (data) {
                        options += '<option value="">{{ __("Select...") }}</option>';
                        var journaments = -1
                        for(obj in data) {
                            if ( selected == data[obj]['id'] ) {
                                options += '<option value="' +data[obj]['id'] + '" selected="selected" >' + data[obj]['name'] + '</option>';
                            } else {
                                options += '<option value="' +data[obj]['id'] + '" >' + data[obj]['name'] + '</option>';
                            }
                            journaments++;
                        }

                        // if (journaments <0) {
                        //     $.notify("No hay Grupos en este torneo, selecciona otro o agrega equipos al torneo", "error");
                        // }
                        components.forEach( function(component) {
                            $("#" + component).html(options);
                        })
                    },
                });
            }
        }

</script>

</body>
</html>
