@extends('layouts.app')

@section('title', ' :: ' . $webpage->title )
@section('description', $webpage->description )
@section('keywords', $webpage->description )
@section('image',  asset('web_pages/' . $webpage->image) )

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            {{-- image header --}}
            <div class="text-center"
            style="background-image: url('{{ asset('web_pages/' . $webpage->image) }}');
                background-size: cover;
                background-repeat: no-repeat;
                /* background-attachment: fixed; */
                background-position: center;
                background-position-x: center;
                background-position-y: center;
                vertical-align: middle;
                height: 600px;  ">
            </div>
        </div>
    </div>
  </div>

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <p><a href="{{ url()->previous() }}" class="btn btn-primary"><span class="fa fa-arrow-left"></span> Regresar</a></p>
                        <h1>{{ $webpage->title }}</h1>
                        <h3>{{ $webpage->description }}</h3>
                    </div>
                    {!! $webpage->content !!}

                    <hr>
                    <div class="col-md-8">

                        <p>Publicado el {{ date('d M, Y', strtotime($webpage->created_at)) }}
                            | Por: {{ ($webpage->user->name ?? 'Desconocido') . ' ' .  ($webpage->user->last_name ?? '') }}
                        </p>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>

<script type="text/javascript">

</script>
@endsection
