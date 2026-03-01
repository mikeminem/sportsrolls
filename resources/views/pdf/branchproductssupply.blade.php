
@extends('pdf.layout')
@section('title',"Reporte de Productos faltantes")
@section('content')
<h2>Listado de Productos faltantes</h2>
<h3>{{ $sucursal }} / {{ $categoria }}</h3>
<small>Fecha: @php
	echo Date('d/m/Y h:s:i');

	$registros = 0;

	@endphp

</small>
<hr />
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="10%">Código Barras</th>
                <th width="30%">Descripción</th>
                <th width="10%">Existencias</th>
                <th width="10%">Minimo</th>
                <th width="10%">Existencias Reales</th>
            </tr>                             
        </thead>
        <tbody>
             @foreach($rows as $row)

              @php
                $registros++;
            @endphp


            <tr>
                <td>{{ $registros }}</td>                
                <td align="right">{{ $row->codigo_barras }}</td>
                <td>{{ $row->title}}</td>
                <td align="right"><b>{{ $row->stock }}</b></td>
                <td align="right">{{ $row->minimum }}</td>
                <td align="right"> </td>
            </tr>

           
            @endforeach
            
           
        </tbody>
        
    </table>
    <p><b>Registros encontrados: {{ $registros}} </b></p>
    <br />
@endsection