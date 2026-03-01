
@extends('pdf.layout')

@section('title',"Reporte de Marcas")

@section('content')
<h2>Listado de Marcas</h2>
<small>Fecha: @php
	echo Date('d/m/Y h:s:i');

	$registros = 1;
	@endphp

</small>
<hr />
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="100">#</th>
                <th>Nombre</th>
                <th>Productos Registrados</th>
            </tr>                            
        </thead>
        <tbody>
            @foreach($brands as $b)
            <tr>
                <td>{{ $registros }}</td>
                <td>{{ $b->name }}</td>
                <td>{{ $b->producto->count() }} </td>

            </tr>

            @php
            	$registros++;
            @endphp
            @endforeach
        </tbody>

       
    </table>
    <p><b>Registros encontrados: {{ $registros-1}} </b></p>
    <br />
@endsection