<!-- Modal Form -->
<div id="formPlayerModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Información del Jugador</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="formPlayer" enctype="multipart/form-data" >
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Torneo *</b></label>
                                @if(isset($tournaments))
                                <select class="form-control" required id="tournament_id" name="tournament_id">
                                    <option value="">Selecciona</option>
                                        @foreach($tournaments as $tournament)
                                            <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control" id="tournament_name" name="tournament_name" disabled value="{{ $tournament->title }}">
                                    <input type="hidden" class="form-control" id="tournament_id" name="tournament_id" readonly value="{{ $tournament->id }}">
                                    <input type="hidden" class="form-control" id="user_id" name="user_id" readonly value="{{ $tournament->user_id }}">
                                    user_id
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Categoría *</b></label>
                                <select class="form-control" required id="category_id" name="category_id">
                                    <option value="">{{ __("Selecciona un torneo") }}</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Equipos *</b></label>
                                <select class="form-control" required id="team_id" name="team_id">
                                    <option value="">{{ __("Selecciona un torneo") }}</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="name"><b>Nombres *</b></label>
                                <input type="text" class="form-control text-uppercase" id="name" name="name" required maxlength="45">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="last_name"><b>Apellidos *</b></label>
                                <input type="text" class="form-control text-uppercase" id="last_name" name="last_name" required maxlength="45">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="hide_name"><b>Mostrar mi nombre completo *</b></label>
                                <select id="hide_name" name="hide_name" class="form-control" required>
                                    <option value="No">No</option>
                                    <option value="Si">Si</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="number"><b>Número en playera *</b></label>
                                <input type="text" class="form-control text-uppercase" id="number" name="number" required maxlength="10">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="phone"><b>Teléfono celular *</b></label>
                                <input type="text" class="form-control text-uppercase" id="phone" name="phone" required maxlength="10">
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="dob"><b>Fecha de Nacimiento</b></label>
                                <input type="date" class="form-control" id="dob" name="dob" required >

                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <p><b>En caso de emergencia notifica a:</b></p>
                        </div>

                        <div class="col-md-9 col-sm-12">
                            <div class="form-group">
                                <label for="emergency_name"><b>Nombre Completo</b></label>
                                <input type="text" class="form-control text-uppercase" id="emergency_name" name="emergency_name" maxlength="80">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="emergency_phone"><b>Teléfono Emergencia</b></label>
                                <input type="text" class="form-control text-uppercase" id="emergency_phone" name="emergency_phone" maxlength="10">
                            </div>
                        </div>



                        <div class="col-md-12 col-sm-6">
                            <div class="form-group">
                                <label for="photo2" id="lblFoto"><b>Foto</b></label>
                                <input
                                    type="file"
                                    class="form-control"
                                    id="photo2"
                                    name="photo2"
                                    accept="image/*"
                                    {{-- onchange="uploadImageProfile(this)" --}}
                                    {{-- oninput="player_photo.src=window.URL.createObjectURL(this.files[0])" --}}
                                >

                                {{-- <input type="text" class="form-control" id="photo" name="photo"> --}}


                            </div>
                        </div>
                        <div class="col-md-12 col-sm-6">
                            <img id="player_photo" src="{{ asset('images/No-photo-m.png') }}" class="img-fluid img-thumbnai" alt="{{ __('Player Photo') }}" width="200px">
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
