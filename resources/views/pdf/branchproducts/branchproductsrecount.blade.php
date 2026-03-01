
@extends('pdf.layout')

@section('title',"Reconteo de Productos")

@section('content')
<h2>Reconteo de Productos</h2>
<small>Fecha: @php
	echo Date('d/m/Y h:s:i');

	$registros = 0;

	@endphp

</small>
<hr />
    <p>
        <b>El presente reporte sirve para realizar un reconteo de productos en tienda o sucursal, llene adecuadamente lo que se le pide.</b>
    </p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="20%">Sucursal</th>
                <th width="10%">Código Barras</th>
                <th width="30%">Descripción</th>
                <th width="10%">Existencias en Sistema</th>
                <th width="10%">Existencias Reales</th>
                <th width="10%">Diferencia</th>
            </tr>                             
        </thead>
        <tbody>
             @foreach($rows as $row)

              @php
                $registros++;
            @endphp


            <tr>
                <td>{{ $registros }}</td>
                <td>{{ $row->name }}</td>
                
                <td align="right">{{ $row->codigo_barras }}</td>
                <td>{{ $row->title}}</td>
                <td align="right"><b>{{ $row->stock }}</b></td>
                <td align="right"> </td>
                <td align="right"> </td>
            </tr>

           
            @endforeach
            
           
        </tbody>
        
    </table>
    <p><b>Registros encontrados: {{ $registros}} </b></p>
    <br />

    <table class="table table-striped">
        <tr>
            <td align="center"><b>Persona que realizó el Reconteo</b></td>
            <td align="center"><b>Persona que capturó en Sistema SZ</b></td>
        </tr>

        <tr>
            <td align="center"><br><br><br><br><b>Nombre y firma</b></td>
            <td align="center"><br><br><br><br><b>Nombre y Firma</b></td>
        </tr>
    </table>
@endsection