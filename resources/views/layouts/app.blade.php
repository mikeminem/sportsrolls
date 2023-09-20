<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <meta name=”robots” content=”index, follow”>
    <meta name=”description” content=”@yield('description')”/>
    <meta name=”keywords” content=”@yield('keywords')”/>
    <meta name="author" content="SportRoles.com" />
    <meta name="copyright" content="Copyright SportRoles.com" />
    <meta name="revisit-after" content="1 days" />
    <meta http-equiv="cache-control" content="no-cache"/>


    <!--social media metas-->
    <meta property="og:url"             content="@yield('url')" />
    <meta property=”og:title”           content=”@yield('title')”/>
    <meta property=”og.type”            content="website" />
    <meta property=”og:description”     content=”@yield('description')” />
    <meta property=”og:keywords”        content=”@yield('keywords')” />
    <meta property="og:image:alt"       content="@yield('title')"/>
    <meta property=”og:image”           content=”{{ asset('images/imgSportRoles.jpg') }}” />

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
        
        <main class="py-2 mb-5">

            <div class="alert alert-danger text-center">
                <i class="fa fa-warning"></i> <strong>Aviso!</strong> A todos los usuarios de la página, se les informa que cambiaremos de nombre de dominio de sportroles.com a deportezacatecas.com.
              </div>

            @yield('content')
        </main>

        @include('layouts.footer')
    </div>

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
        
        function getCategories(component, tournament_id, selected="") {
            var options = "";

            if (tournament_id) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('categories_list') }}/" + tournament_id,
                    dataType: "json",
                    success: function (data) { 
                        options = '<option value="">Selecciona</option>';
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
                        options += '<option value="">Selecciona</option>';
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
            console.log("categoriId id", categoriId);

            if (categoriId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('teams_list') }}/" + categoriId,
                    dataType: "json",
                    success: function (data) { 
                        options += '<option value="">Selecciona</option>';
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
                        options += '<option value="">Selecciona</option>';
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
                        options += '<option value="">Selecciona</option>';
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
                        options += '<option value="">Selecciona</option>';
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
