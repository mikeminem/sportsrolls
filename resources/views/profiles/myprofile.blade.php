@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('My Profile') )
@section('description',  "Mi perfil, " . config('app.name') )

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('My Profile')}}</h1>
                        <form id="frmProfile">
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <div class="card">
                                        @if ($profile->photo)
                                            <img class="card-img-top" id="thumbnil" src="{{ asset('images/noimage.png')}}" alt="" >
                                        @endif
                                        <img class="card-img-top" id="thumbnil" src="{{ asset('images/noimage.png')}}" alt="" >
                                        <div class="card-body text-center">
                                            <h4 class="card-title">{{ Auth::user()->name}}</h4>
                                            <span class="btn btn-primary btn-file">
                                                    <span class="fa fa-photo"></span> 
                                                    Buscar foto
                                                    <input type="file" accept="image/*" id="btnFoto" onchange="showMyImage(this)">
                                                    
                                            </span>
                                            <a href="javascript:void(0)" class="btn btn-primary" id="btnDeletePhoto" ><span class="fa fa-remove"></span>  Borrar foto</a>



                                            <hr />
                                            <div class="row">
                                                
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="dob"><b>Fecha de nacimiento *</b></label>
                                                        <input type="date" class="form-control" id="dob" name="dob" placeholder="Fecha de nacimiento" value="{{ ($profile)?$profile->dob:""}}">
                                                    </div>
                                                </div>
        
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="tshirt_size"><b>Talla Playera *</b></label>
                                                        <input type="text" class="form-control" id="tshirt_size" name="tshirt_size" placeholder="Talla playera" maxlength="10" value="{{ ($profile)?$profile->tshirt_size:""}}">
                                                    </div>
                                                </div>
        
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="favorite_number"><b>Número favorito *</b></label>
                                                        <input type="text" class="form-control" id="favorite_number" name="favorite_number" placeholder="Número favorito" maxlength="5" value="{{ ($profile)?$profile->favorite_number:""}}">
                                                    </div>
                                                </div>
        
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="footware_meansure"><b>Calzado cms *</b></label>
                                                        <input type="text" class="form-control" id="footware_meansure" name="footware_meansure" placeholder="Mi # de calzado" maxlength="4" value="{{ ($profile)?$profile->footware_meansure:""}}">
                                                    </div>
                                                </div>
        
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="footware_meansure"><b> Sexo *</b></label>
                                                        <select id="sex" name="sex" class="form-control">
                                                            <option value="Masculino">Masculino</option>
                                                            <option value="Femenino">Femenino</option>
                                                            <option value="NA">NA</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                      </div>
                                </div>

                                <div class="col-md-9 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="about_me"><b>Acerca de *</b></label>
                                                <input type="text" class="form-control" id="about_me" name="about_me" placeholder="Descripción breve de mi en los deportes" maxlength="500" value="{{ ($profile)?$profile->about_me:""}}">
                                            </div>
                                        </div>
    
                                    </div>
                                    
                                </div>
                            </div>
        
                                
                                

                            

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    Datos marcados con * son obligatorios
                                </div>
                            
                                <div class="col-md-6 col-sm-12">
                                
                                    <div class="form-group text-right">
                                        {{ csrf_field() }}
            
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <span class="fa fa-save"></span> 
                                            {{__('Save')}}
                                        </button>

                                    </div>
                                </div>
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

            $(document).on("click", "#btnDeletePhoto", function () {
                alert('Borrar Photo');
//                $('#thumbnil').attr('src','{{ asset('images/noimage.png')}}');
                var img=document.getElementById("thumbnil");        
                img.src = '{{ asset('images/noimage.png')}}';
    
            });
            
            $(document).on("submit", "#frmProfile", function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "profiles",
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

        });


        function showMyImage(fileInput) {
            var files = fileInput.files;
        
            for (var i = 0; i < files.length; i++) {           
                var file = files[i];
                var imageType = /image.*/;     
                if (!file.type.match(imageType)) {
                    continue;
                }           
                var img=document.getElementById("thumbnil");            
                img.file = file;    
                var reader = new FileReader();
                reader.onload = (function(aImg) { 
                    return function(e) { 
                        aImg.src = e.target.result; 
                    }; 
                })(img);
                reader.readAsDataURL(file);
            }    
            files = null;
        }

</script>
@endsection