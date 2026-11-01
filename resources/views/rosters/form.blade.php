<!-- Modal Form -->
<div id="formModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Información del Jugador</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="updateForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Torneo *</b></label>
                                <select class="form-control" required id="tournament_id" name="tournament_id">
                                    <option value="">Selecciona</option>
                                    @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>{{ __("Category") }} *</b></label>
                                <select class="form-control" required id="category_id" name="category_id">
                                    <option value="">{{ __("Select a Tournament") }}</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Equipo *</b></label>
                                <select class="form-control" required id="team_id" name="team_id">
                                    <option value="">{{ __("Select a Tournament") }}</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="player_id"><b>Jugador *</b></label>
                                <select class="form-control" id="player_id" name="player_id" required>
                                    <option value="">Selecciona un jugador</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="name"><b>Nombres *</b></label>
                                <input type="text" class="form-control" id="name" name="name" required maxlength="45" readonly>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="last_name"><b>Apellidos *</b></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required maxlength="45" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="number"><b>Número / Dorsal *</b></label>
                                <input type="text" class="form-control" id="number" name="number" required maxlength="10">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="license"><b>Número Licencia </b></label>
                                <input type="text" class="form-control" id="license" name="license" maxlength="10">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="verified"><b>Verificado * </b></label>
                                <select id="verified" name="verified" class="form-control" required>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <img id="player_photo" src="{{ asset('images/No-photo-m.png') }}" class="img-fluid img-thumbnail" alt="{{ __('Player Photo') }}">
                        </div>

                        <div class="col-md-9 col-sm-6">
                            <div class="form-group">
                                <label for="photo"><b>Fotografía</b></label>
                                <input type="file" class="form-control" id="photo" name="photo" maxlength="80" accept="image/*">
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
                                Guardar
                            </button>

                            <button type="button" class="btn btn-primary btn-lg" class ="close" data-dismiss="modal">
                                <span class="fa fa-remove"></span> Cancelar
                            </button>
                        </div>
                    </div>
            </form>
                <!--fin de la forma-->
         </div>
    </div>
</div>
<!--end of the modal window-->
