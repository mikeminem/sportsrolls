<div id="formModal" class="modal fade" role="dialog">
    <div class="modal-dialog mw-100 w-75">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Contenido Web</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="updateForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-9 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Título *</b></label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="120">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Torneo </b></label>
                                <select class="form-control"  id="tournament_id" name="tournament_id">
                                    <option value="">Selecciona dónde ver el contenido</option>
                                    <option value="0">Vista global</option>
                                    @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-9 col-sm-12">
                            <div class="form-group">
                                <label for="description"><b>Descripción corta*</b></label>
                                <input type="text" class="form-control" id="description" name="description" required maxlength="120">
                            </div>
                        </div>

                        @if(Auth::user()->hasRole('admin'))
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="page_category_id"><b>Categoría </b></label>
                                    <select class="form-control"  id="page_category_id" name="page_category_id">
                                        <option value="0">Sin Categoría</option>
                                        @foreach($pCategories as $pCategory)
                                            <option value="{{ $pCategory->id}}">{{ $pCategory->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif





                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="content"><b>Contenido *</b></label><br />
                                <textarea name="content" id="content" class="form-control editor" cols="30" rows="6" required></textarea>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="status"><b>Estatus *</b></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="En Desarrollo">En Desarrollo</option>
                                    <option value="Por Aprobar">Por Aprobar</option>
                                    <option value="Aprobado">Aprobado</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="type"><b>Tipo *</b></label>
                                <select id="type" name="type" class="form-control" required>
                                    <option value="Pagina Web">Página Web</option>
                                    <option value="Publicidad">Publicidad</option>
                                    <option value="Anuncio">Anuncio</option>
                                    <option value="Convocatoria">Convocatoria</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="location"><b>Ubicación *</b></label>
                                <select id="location" name="location" class="form-control" required>
                                    <option value="No Ubicacion">No Ubicación</option>
                                    <option value="Encabezado">Encabezado</option>
                                    <option value="Carrusel Principal">Carrusel Principal</option>
                                    <option value="Lado Izquierdo">Lado Izquierdo</option>
                                    <option value="Lado Derecho">Lado Derecho</option>
                                    <option value="Abajo">Abajo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="image"><b>Imagen</b></label>
                                <input type="file" class="form-control" id="image" name="image">
                                <small>La imagen de la nota debe estar en las medidas 1000 x 500 px</small><br>
                                <img src="" id="logo"  width="200px" >
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
