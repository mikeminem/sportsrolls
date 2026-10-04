@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Players') )
@section('description',  "Players Administrator ")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('Players')}}</h1>
                    </div>

                    @if (Auth::check() &&  ( (Auth::user()->hasRole('admin')) || (Auth::user()->hasRole('promoter')) ))
                        <a role="button"
                            class="btn btn-primary"
                            href="javascript:void(0);"
                            id="AddRow"
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

                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>{{ __("Photo") }}</th>
                                    <th>{{ __("Player") }}</th>
                                    <th>{{ __("Last Name 1") }}</th>
                                    <th>{{ __("Last Name 2") }}</th>
                                    <th>{{ __("Phone") }}</th>
                                    <th>{{ __("Call in case of emergency") }}</th>
                                    <th>{{ __("Blood Type") }}</th>
                                    <th>{{ __("Status") }}</th>
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
    <div class="modal-dialog modal-xl w-auto" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Datos del Jugador</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="updateForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="names"><b>Nombres *</b></label>
                                <input type="text" class="form-control" id="names" name="names" required maxlength="45">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="last_name1"><b>Apellido Paterno *</b></label>
                                <input type="text" class="form-control" id="last_name1" name="last_name1" required maxlength="45">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="last_name2"><b>Apellido Materno *</b></label>
                                <input type="text" class="form-control" id="last_name2" name="last_name2" required maxlength="45">
                            </div>
                        </div>


                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="state_id"><b>Estado *</b></label>
                                <select id="state_id" name="state_id" class="form-control" required>
                                    <option value="1">Selecciona</option>
                                    @foreach ($states as $state )
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="municipality_id"><b>Municipio</b></label>
                                <select id="municipality_id" name="municipality_id" class="form-control" required>
                                    <option value="1">Selecciona un Estado</option>
                                </select>
                            </div>
                        </div> --}}

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="dob"><b>Fecha de Nacimiento *</b></label>
                                <input type="date" class="form-control" id="dob" name="dob" required >

                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="gender"><b>Genero al Nacer * </b></label>
                                <select id="gender" name="gender" class="form-control" required>
                                    <option value="">Selecciona</option>
                                    <option value="F">FEMENINO</option>
                                    <option value="M">MASCULINO</option>
                                </select>

                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="curp"><b>CURP *</b></label>
                                <input type="text" class="form-control" id="curp" name="curp" required maxlength="45" disabled >
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="age"><b>Edad</b></label>
                                <input type="text" class="form-control" id="age" name="age" required disabled >
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="email"><b>Correo Electrónico *</b></label>
                                <input type="text" class="form-control" id="email" name="email" required maxlength="60">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="phone"><b>Teléfono Celular *</b></label>
                                <input type="text" class="form-control" id="phone" name="phone" required maxlength="10">
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                {{-- A+, A-, B+, B-, AB+, AB-, O+ y O- --}}
                                <label for="blood_type"><b>Tipo de Sangre * </b></label>
                                <select id="blood_type" name="blood_type" class="form-control" required>
                                    <option value="">Selecciona</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB+</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label for="access_pin"><b>Pin de acceso *</b></label>
                                <input type="text" class="form-control" id="access_pin" name="access_pin" maxlength="4" required>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12 col-sm-12">
                            <p><b>En caso de emergencia notificar a</b></p>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="emergency_name"><b>Nombre</b></label>
                                <input type="text" class="form-control" id="emergency_name" name="emergency_name" maxlength="80">
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label for="emergency_phone"><b>Teléfono Celular</b></label>
                                <input type="text" class="form-control" id="emergency_phone" name="emergency_phone" maxlength="80">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <img id="player_photo" src="{{ asset('images/No-photo-m.png') }}" class="img-fluid img-thumbnai" alt="{{ __('Player Photo') }}" width="220px">
                            <div class="form-group">
                                <label for="photo"><b>Foto del Jugador</b></label>
                                <input type="file"
                                    class="form-control"
                                    id="photo"
                                    name="photo"
                                    maxlength="80"
                                    accept="image/*"
                                    oninput="player_photo.src=window.URL.createObjectURL(this.files[0])"
                                >
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

<!-- delete modal -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">{{ __("Delete a Row") }}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        <form id="deleteForm">
            @method('DELETE')

            <div class="modal-body">
            <h4>Do you want to delete player <mark><span class="text-blod" id="row_name"></span></mark>?</h4>
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
@endsection

@section('js')
<script type="text/javascript">
        jQuery(document).ready(function($){

            fetch_data();

            function fetch_data(tournament = '') {

                $('#table').DataTable({
                    "language": {
                        "sProcessing":    "Procesando...",
                        "sLengthMenu":    "Mostrar _MENU_ registros",
                        "sZeroRecords":   "No se encontraron resultados",
                        "sEmptyTable":    "No se encontró información...",
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
                    ajax: {
                        url: '{{ route('players.index') }}'
                    },
                    columns: [
                        { data: 'photo', name: 'photo'},
                        { data: 'names', name: 'names'},
                        { data: 'last_name1', name: 'last_name1'},
                        { data: 'last_name2', name: 'last_name2'},
                        { data: 'phone', name: 'phone'},
                        { data: 'emergency_name', name: 'emergency_name'},
                        { data: 'blood_type', name: 'blood_type'},
                        { data: 'status', name: 'status'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                });
            }


            //$("#dob").change(function(){
            $(document).on("change", "#dob", function () {
                //alert('Calculate the age');

                var today = new Date();
                var birthDate = new Date($('#dob').val());
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                $('#age').val(age);
            });

            $(document).on("click", "#AddRow", function () {

                $("#player_photo").attr("src","");
                $("#player_photo").attr("src","images/No-photo-m.png");

                $("#updateForm").trigger('reset');
                $("#id").val("");

                jQuery.noConflict();
                jQuery("#formModal").modal('show');
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
                        let tournament_id = 0;
                        let category_id = 0;
                        let image = '';

                        for(obj in data) {

                            if (obj != 'photo') {
                                $("#" + obj).val(data[obj]);
                            }

                            if (obj == "photo") {
                                image = data[obj];
                                if (image) {
                                    $("#player_photo").attr("src","players_photos/" + image);
                                } else {
                                    $("#player_photo").attr("src","");
                                    $("#player_photo").attr("src","images/No-photo-m.png");
                                }
                            }

                        }

                        //calculate the age
                        var today = new Date();
                        var birthDate = new Date($('#dob').val());
                        var age = today.getFullYear() - birthDate.getFullYear();
                        var m = today.getMonth() - birthDate.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }
                        $('#age').val(age);
                        //getTeams("team_id",category_id, data['team_id']);
                        //$("#sport").val("{{ Auth::user()->league->sport->name}}");
                        //$("#sport_id").val("{{ Auth::user()->league->sport->id}}");

                        jQuery.noConflict();
                        jQuery("#formModal").modal('show');
                    },
                });

                return false;
            });

            $(document).on("click", "#btnRefresh", function () {
                //Table.ajax.reload();
                $('#table').DataTable().ajax.reload();

            });

            $(document).on("submit", "#updateForm", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('players.store') }}",
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                    success: function (data) {
                        if (data.type == "error") {
                            var errors = "";
                            $.each(data.errors, function (i, val) {
                                errors = errors  +  val + "\n";
                            });

                            $.notify(data.message + "\n" + errors, data.type, { position:"center" } );

                            return false;
                        }

                        $("#updateForm").trigger('reset');

                        jQuery.noConflict();
                        jQuery("#formModal").modal('toggle');
                        //Table.ajax.reload();
                        $('#table').DataTable().ajax.reload();
                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("There is an error trying to save the player information! FE", "error");
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
                        //Table.ajax.reload();
                        $('#table').DataTable().ajax.reload();

                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("Error trying to delete player", "error");
                    },
                });

                return false;
            });
        });

</script>
@endsection
