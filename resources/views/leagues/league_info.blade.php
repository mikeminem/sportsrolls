@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('League Info') )
@section('description',  "Información de la liga, " . config('app.name') )

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('League Info')}}</h1>
                        <form id="frmLeague">
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="sport_id"><b>Deporte *</b></label>
                                        <select id="sport_id" name="sport_id" class="form-control" required >
                                            <option value="" >Selecciona...</option>

                                            @foreach ($sports as $sport)

                                                <option value="{{ $sport->id }}" 
                                                    @if (isset($league->sport_id))
                                                        @if ($league->sport_id == $sport->id)
                                                            selected
                                                        @endif
                                                    @endif
                                                >{{ $sport->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-9 col-sm-12">
                                    <div class="form-group">
                                        <label for="name"><b>Nombre *</b></label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nombre" required maxlength="80" value="{{ ($league)?$league->name:""}}">
                                    </div>
                                </div>
        
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="slogan">Subtítulo *</label>
                                        <input type="text" class="form-control" id="slogan" name="slogan" placeholder="Slogan" maxlength="80" value="{{ ($league)?$league->slogan:""}}">
                                    </div>
                                </div>
        
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="summary">Acerca de * </label>
                                        <textarea class="form-control" id="summary" name="summary" placeholder="Acerca de la liga">{{ ($league)?$league->summary:""}}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">      
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="state_id"><b>Estado *</b></label>
                                        <select id="state_id" name="state_id" class="form-control">
                                            <option value="" >Selecciona...</option>
                                        @if (isset($states))
                                            @foreach($states as $state)
                                            @if (isset($league))
                                                @if ($league->state_id == $state->id)
                                                    <option value="{{ $state->id}}" selected="selected">{{ $state->name}}</option>
                                                @else
                                                    <option value="{{ $state->id}}">{{ $state->name}}</option>
                                                @endif
                                            @else
                                                <option value="{{ $state->id}}">{{ $state->name}}</option>

                                            @endif
                                            @endforeach
                                        @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="municipality_id"><b> Municipio *</b></label>
                                        <select id="municipality_id" name="municipality_id" class="form-control">
                                            @if (isset($municipalities))
                                                @foreach($municipalities as $municipality)
                                                    @if (isset($league))
                                                        @if ($league->municipality_id == $municipality->id)
                                                            <option value="{{ $municipality->id}}" selected="selected">{{ $municipality->name}}</option>
                                                        @else
                                                            <option value="{{ $municipality->id}}">{{ $municipality->name}}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $municipality->id}}">{{ $municipality->name}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="facebook">facebook</label>
                                        <input type="text" class="form-control" id="facebook" name="facebook" placeholder="Si cuenta con facebook"  maxlength="80" value="{{ ($league)?$league->facebook:""}}" >
                                    </div>
                                </div>
        
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="twitter">Twitter</label>
                                        <input type="text" class="form-control" id="twitter" name="twitter" placeholder="Si cuenta con Twitter" maxlength="80" value="{{ ($league)?$league->twitter:""}}" >
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="instagram">Instagram</label>
                                        <input type="text" class="form-control" id="instagram" name="instagram" placeholder="Si cuenta con instagram"  maxlength="80" value="{{ ($league)?$league->instagram:""}}" >
                                    </div>
                                </div>
        
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="website">Página web</label>
                                        <input type="text" class="form-control" id="website" name="website" placeholder="Si cuenta con página web" maxlength="80" value="{{ ($league)?$league->website:""}}" >
                                    </div>
                                </div>
                            </div>
                            <hr />

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="footer1">Pie de página</label>
                                        <textarea class="form-control" id="footer1" name="footer1" placeholder="Pie de página">{{ ($league)?$league->footer1:""}}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    Datos marcados con * son obligatorios
                                </div>
                            </div>

                            <div class="form-group text-right">
                                {{ csrf_field() }}
    
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <span class="fa fa-save"></span> 
                                    {{__('Save')}}
                                </button>

                            </div>
                        </form>
                    </div>     
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
        jQuery(document).ready(function($){
            
            $(document).on("submit", "#frmLeague", function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "leagues",
                    dataType: "json",
                    data: $(this).serialize(),
                    success: function (data) {                
                        if (data.type == "error") {
                            var errors = "";
                            $.each(data.errors, function (i, val) {
                                errors = errors  +  val + "\n";
                            });
                            $.notify(data.message + "\n" + errors, data.type, { position:"center" } );
                            return false;
                        }
                        $.notify(data.message, data.type);
                    },
                    error:function(){
                        $.notify("Hubo un error al intentar agregar el registro", "error");
                    },
                });
                return false;
            });

            $(document).on("change", "#state_id", function (e) {
                $("#state_id option:selected").each(function () {
                    selected=$(this).val();
                    $("#municipality_id").html(getMunicipalities(selected));
                });
            });


            function getMunicipalities(sport, selected="") {
            var options = "";

            if (sport) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('municipalities_list') }}/" + sport,
                    dataType: "json",
                    success: function (data) { 
                        options = '<option value="">Selecciona</option>';
                        var municipalities = -1
                        for(obj in data) {
                            if ( selected == data[obj]['id'] ) {
                                options += '<option value="' +data[obj]['id'] + '" selected="selected" >' + data[obj]['name'] + '</option>';            
                            } else {
                                options += '<option value="' +data[obj]['id'] + '" >' + data[obj]['name'] + '</option>';            
                            }
                            municipalities++;
                        }
                        
                        if (municipalities <=-1) {
                            $.notify("No hay Municipios en este estado, selecciona otro o agrega municioios al estado", "error");
                        
                        }
                        $("#municipality_id").html(options);

                    },
                });
            }
            return options;
        }

        });

</script>
@endsection