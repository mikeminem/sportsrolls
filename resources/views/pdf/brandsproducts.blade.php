
@extends('pdf.layout')

@section('title',"Reporte de Marcas")

@section('content')
<h2>Listado de Productos</h2>
<h4>Marca {{$brand->name}}</h4>
<small>Fecha: @php
	echo Date('d/m/Y h:s:i');

	$registros = 0;
	@endphp

</small>
<hr />
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="20">#</th>
                <th width="100">Nombre</th>
                <th width="20">P.Público</th>
                <th width="20">P.Mayoreo</th>
                <th width="20">P.Distribuidor</th>
                <th width="20">P.Master</th>
                <th width="20">P..VIP</th>


            </tr>                            
        </thead>
        <tbody>
            @foreach($products as $b)
            <tr>
                <td>{{ $b->id }}</td>
                <td>{{ $b->title }}</td>
                <td class="text-right">${{ $b->precio_venta }}</td>
                <td class="text-right">${{ $b->precio_medio }}</td>
                <td class="text-right">${{ $b->precio_mayoreo }}</td>
                <td class="text-right">${{ $b->precio_master }}</td>
                <td class="text-right">${{ $b->precio_vip }}</td>
            </tr>

            @php
            	$registros++;
            @endphp
            @endforeach
        </tbody>

     
    </table>
    <b>Registros encontrados: {{ $registros}} </b>
    <br />
@endsection