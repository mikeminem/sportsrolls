@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Games') )
@section('description',  "Administrador de Juegos ")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('Games')}}</h1>
                    </div>

                    @if (Auth::check() &&  ( (Auth::user()->role == 'Admin') || (Auth::user()->role == 'Promoter') ))
                        <a role="button" 
                            class="btn btn-primary" 
                            href="javascript:void(0);" 
                            id="btnAdd"
                             >
                            <span class="fa fa-plus"></span> {{ __("New")}}
                        </a>

                        <a role="button" class="btn btn-primary" href="javascript:void(0);" id="btnPrint">
                            <span class="fa fa-print"></span> {{ __("Print")}}
                        </a>
                    @endif

                    <a role="button" class="btn btn-primary" href="javascript:void(0);" id="btnRefresh">
                        <span class="fa fa-refresh"></span> {{ __("Refresh")}}
                    </a>
                    <hr />
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="filterTournaments"><b>Torneos Activos</b></label>
                                    <select class="form-control" required id="filterTournaments" name="filterTournaments"  >
                                        <option value="">Todos</option>
                                        @foreach($tournaments as $tournament)
                                            <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="filterJourneys"><b>Jornadas</b></label>
                                    <select class="form-control" required id="filterJourneys" name="filterJourneys"  >
                                        <option value="">Selecciona Torneo</option>
                                    </select>
                                    
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="branch"><b>Rama *</b></label>
                                    <select class="form-control" required id="FilterBranchs" name="FilterBranchs"  >
                                        <option value="">Todas</option>
                                        <option value="Varonil">Varonil</option>
                                        <option value="Femenil">Femenil</option>
                                        <option value="Mixto">Mixto</option>
                                    </select>  
                                </div>
                            </div>
    
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="title"><b>Categoria *</b></label>
                                    <select class="form-control" required id="FilterCategories" name="FilterCategories">
                                        <option value="">Todas</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id}}">{{ $category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                        </div>

                    <hr />

                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>Torneo</th>
                                    <th>Jornada</th>
                                    <th>Fecha / Hora</th>
                                    <th>Equipo 1 (Visitante)</th>
                                    <th>Marcador</th>
                                    <th>Equipo 2 (Casa)</th>
                                    <th>Rama</th>
                                    <th>Categoria</th>
                                    <th>Estado</th>
                                    <th>Id</th>
                                    
                                    <th> - </th>
                                </tr>
                            </thead>
        
                            <tbody>
        
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="formModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg mw-100 w-50" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Datos del Juego</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>          
            </div>
            <!--mandamos la forma-->
            <form id="updateForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" class="form-control" readonly id="sport_id" name="sport_id">

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="tournament_id"><b>Torneo *</b></label>
                                <!--input type="hidden" class="form-control" readonly id="tournament_id" name="tournament_id"><br /-->

                                <select class="form-control" required id="tournament_id" name="tournament_id">
                                    <option value="">Selecciona</option>
                                    @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="journey_id"><b>Jornada *</b></label>
                                <!--input type="hidden" class="form-control" readonly id="journey_id" name="journey_id"><br /-->
                                <select class="form-control" required id="journey_id" name="journey_id">
                                    <option value="">Selecciona un torneo</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="branch"><b>Rama *</b></label>
                                <select class="form-control" required id="branch" name="branch"  >
                                    <option value="">Selecciona</option>
                                    <option value="Varonil">Varonil</option>
                                    <option value="Femenil">Femenil</option>
                                    <option value="Mixto">Mixto</option>
                                </select>  
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Categoria *</b></label>
                                <select class="form-control" required id="category_id" name="category_id">
                                    <option value="">Selecciona</option>
                                    @foreach($categories as $category)
                                            <option value="{{ $category->id}}">{{ $category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="game_day"><b>Fecha dej Juego *</b></label>
                                <input type="date" class="form-control" id="game_day" name="game_day" required maxlength="80"> 
                            </div>
                        </div>


                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="game_time"><b>Hora del Juego *</b></label>
                                <input type="time" class="form-control" id="game_time" name="game_time" required max="80" min="5"> 
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="team1_id"><b>Equipo 1 (Visitante)</b></label>
                                <select class="form-control" required id="team1_id" name="team1_id">
                                    <option value="">Selecciona torneo, rama y cateogria</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="team2_id"><b>Equipo 2 (Casa)</b></label>
                                <select class="form-control" required id="team2_id" name="team2_id">
                                    <option value="">Selecciona torneo, rama y cateogria</option>
                                </select>
                                
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <b>Datos marcados con * son obligatorios</b>
                            <input type="hidden" class="form-control" id="id" name="id" readonly>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="form-group text-right">
                        {{ csrf_field() }}

                            <button type="submit" class="btn btn-primary btn-lg">
                                <span class="fa fa-save"></span> 
                                {{__('Save')}}
                            </button>

                            <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                                <span class="fa fa-remove"></span> {{__('Cancel')}}
                            </button>
                        </div>
                    </div>
            </form>
                <!--fin de la forma-->
         </div>
    </div>
</div>
<!--end of the modal window-->

<!-- Score Modal Form -->
<div id="scoreModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Capturar Resultado del Juego</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>          
            </div>
            <!--mandamos la forma-->
            <form id="scoreForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="idscore" name="id">

                    <div class="row">
        
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="score_team1"><span id="team1score" >Equipo 1 (Visitante)</span></label>
                                <input type="number" class="form-control" id="score_team1" name="score_team1">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="score_team2"><span id="team2score" >Equipo 2 (Casa)</span></label>
                                <input type="number" class="form-control" id="score_team2" name="score_team2">

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="game_day"><b>Notas</b></label>
                            
                                <textarea class="form-control" id="notes" name="notes" rows="5"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <b>Datos marcados con * son obligatorios</b>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="form-group text-right">
                        {{ csrf_field() }}

                            <button type="submit" class="btn btn-primary btn-lg">
                                <span class="fa fa-save"></span> 
                                {{__('Save')}}
                            </button>

                            <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                                <span class="fa fa-remove"></span> {{__('Cancel')}}
                            </button>
                        </div>
                    </div>
            </form>
                <!--fin de la forma-->
         </div>
    </div>
</div>
<!--end of the Score modal-->

<!-- delete modal -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog">
      <div class="modal-content">
  
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Borrar Rregistro</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
  

        <!-- Modal body -->
        <form id="deleteForm">
            @method('DELETE')

            <div class="modal-body">
            <h4>¿Deseas borrar la categoria <mark><span class="text-blod" id="row_name"></span></mark>?</h4>
            </div>
            {{ csrf_field() }}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-lg">
                    <span class="fa fa-check"></span> 
                    {{__('Delete')}}
                </button>
                <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                    <span class="fa fa-remove"></span> {{__('Cancel')}}
                </button>
            </div>
        </form>
  
      </div>
    </div>
  </div>	

<!-- end of the delete modal -->

@endsection

@section('js')
<script type="text/javascript">
        jQuery(document).ready(function($){
            
            var Table = $('#table').DataTable({
                order: [[9, 'desc']],
                "language": {
                    "sProcessing":    "Procesando...",
                    "sLengthMenu":    "Mostrar _MENU_ registros",
                    "sZeroRecords":   "No se encontraron resultados",
                    "sEmptyTable":    "No se encontró información",
                    "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":   "",
                    "sSearch":        "Buscar:",
                    "sUrl":           "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "...Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":    "Último",
                        "sNext":    "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: '{{ route('games.index') }}',
                columns: [
                    { data: 'tournament_id', name: 'tournament_id', visible:false},
                    { data: 'journey_id', name: 'journey_id', visible:false},
                    { data: 'date_time', name: 'date_time'},
                    { data: 'team1', name: 'team1', className: "text-right"},
                    { data: 'score', name: 'score', className: "text-center"},
                    { data: 'team2', name: 'team2'},
                    { data: 'branch', name: 'branch'},
                    { data: 'category', name: 'category'},
                    { data: 'status', name: 'status'},
                    { data: 'id', name: 'id', visible:false},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            //filters
            $(document).on("change", "#filterTournaments", function () {
                Table.columns(0).search(this.value).draw();

                //send the journeys availables
                selected=$(this).val();
                $("#journey_id").html('<option valu="">Selecciona un torneo</option>');
                getJourneys("filterJourneys",selected,""); 

            });

            $(document).on("change", "#filterJourneys", function () {
                Table.columns(1).search(this.value).draw();
            });

            
            $(document).on("change", "#FilterBranchs", function () {
                Table.columns(6).search(this.value).draw();
            });

            $(document).on("change", "#FilterCategories", function () {
                Table.columns(7).search(this.value).draw();
            });


            $(document).on("click", "#btnAdd", function () {
                $("#updateForm").trigger('reset');
                $("#id").val("");

                var tournament = $('#filterTournaments option:selected').val();
                var journeySelected = $('#filterJourneys option:selected').val();
                var branch = $('#FilterBranchs option:selected').val();

                $('#tournament_id').val(tournament);
                // $('#tournament').html($('#filterTournaments option:selected').text());

                $("#journey_id").html('<option valu="">Selecciona un torneo</option>');
                getJourneys("journey_id",tournament,journeySelected);

                $('#branch').val(branch);

                //journey
                //$('#journey_id').val($('#filterJourneys option:selected').val());
                // $('#journey').html($('#filterJourneys option:selected').text());
                
                $("#sport_id").val("{{ Auth::user()->league->sport->id}}");
                //$("#category_id").html(getCategories("{{ Auth::user()->id}}"));

                jQuery.noConflict(); 
                jQuery("#formModal").modal('show');
            });


            $(document).on("change", "#tournament_id", function (e) {
                $("#tournament_id option:selected").each(function () {
                    selected=$(this).val();

                    $("#journey_id").html('<option valu="">Selecciona un torneo</option>');
                    getJourneys("journey_id",selected,"");
                    
                });
            });

            $(document).on("change", "#category_id", function (e) {
                $("#category_id option:selected").each(function () {
                    selected=$(this).val();

                    $("#team1_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');
                    $("#team2_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');

                    getTeams("team1_id",selected);
                    getTeams("team2_id",selected);
                    
                });
            });

            $(document).on("click", "#btnScore", function (e) {

                e.preventDefault();
                $("#scoreForm").trigger('reset');

                $("#idscore").val($(this).attr("data-id"));
                $("#team1score").html($(this).attr("data-team1"));
                $("#team2score").html($(this).attr("data-team2"));

                jQuery.noConflict(); 
                jQuery("#scoreModal").modal('show');    

            });


            $(document).on("click", "#btnDelete", function (e) {
                e.preventDefault();

                $('#row_name').text($(this).attr('data-name'));
                $('#deleteForm').attr('action', $(this).attr('href'));
                jQuery("#deleteModal").modal('show');
            });

            $(document).on("click", "#btnUpdate", function (e) {
                e.preventDefault();
                $("#updateForm").trigger('reset');

                $.ajax({
                    type: "GET",
                    url: $(this).attr("href"),
                    dataType: "json",
                    success: function (data) { 
                        console.log(data)
                        var tournament_id = data["tournament_id"];
                        var journey_id = data["journey_id"];
                        var category_id = data["category_id"];
                        var team1_id = data["team1_id"];
                        var team2_id = data["team2_id"];

                        getJourneys("journey_id", tournament_id, journey_id);

                        $("#team1_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');
                        $("#team2_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');

                        getTeams("team1_id",category_id, team1_id);
                        getTeams("team2_id",category_id, team2_id);

                        for(obj in data) {
                            $("#" + obj).val(data[obj]);
                        }

                        jQuery.noConflict(); 
                        jQuery("#formModal").modal('show');
                    },
                });

                return false;
            });

            $(document).on("click", "#btnRefresh", function () {
                Table.ajax.reload();
            });

            
            $(document).on("submit", "#scoreForm", function (e) {

                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ url('score_game') }}",
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                    success: function (data) {                
                        if (data.type == "error") {
                            
                            $.notify(data.message + "\n" + errors, data.type, { position:"center" } );                            
                            return false;
                        }

                        $("#scoreForm").trigger('reset');
                        
                        jQuery.noConflict(); 
                        jQuery("#scoreModal").modal('toggle');
                        Table.ajax.reload();
                        $.notify(data.message, data.type);
                        
                    },
                    error:function(){
                        $.notify("Hubo un error al intentar capturar el resultado del juego", "error");
                    },
                });

                return false;

            });



            $(document).on("submit", "#updateForm", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('games.store') }}",
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                    success: function (data) {                
                        if (data.type == "error") {
                            // var errors = "";
                            // $.each(data.errors, function (i, val) {
                            //     errors = errors  +  val + "\n";
                            // });
                            
                            $.notify(data.message + "\n" + data.errors, data.type, { position:"center" } );
                            
                            return false;
                        }

                        $("#updateForm").trigger('reset');
                        
                        jQuery.noConflict(); 
                        jQuery("#formModal").modal('toggle');
                        Table.ajax.reload();
                        $.notify(data.message, data.type);
                        
                    },
                    error:function(){
                        $.notify("Hubo un error al intentar agregar el registro", "error");
                    },
                });

                return false;
            });

            $(document).on("submit", "#deleteForm", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.type == "error") {
                            $.notify(data.message + "\n" + errors, data.type);
                            return false;
                        }
                        
                        $("#deleteForm").trigger('reset');
                        jQuery.noConflict(); 
                        jQuery("#deleteModal").modal('toggle');
                        Table.ajax.reload();
                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("Hubo un error al intentar borrar el registro", "error");
                    },
                });

                return false;
            });
        });

</script>
@endsection