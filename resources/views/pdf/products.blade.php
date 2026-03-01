
@extends('pdf.layout')

@section('title',"Reporte de Productos")

@section('content')
<h2>Listado de Productos</h2>
<small>Fecha: @php
	echo Date('d/m/Y h:s:i');

	$registros = 0;

    

	@endphp

</small>
<hr />
    <table class="table table-striped">
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
             @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->title }}</td>
               <td align="right">${{ number_format($product->precio_venta,2) }}</td>
                <td align="right">${{ number_format($product->precio_medio,2) }}</td>
                <td align="right">${{ number_format($product->precio_mayoreo,2) }}</td>
                <td align="right">${{ number_format($product->precio_master,2) }}</td>
                <td align="right">${{ number_format($product->precio_vip,2) }}</td>
            </tr>

            @php
                $registros++;
            @endphp
            @endforeach
            
           
        </tbody>
        
    </table>
    <p><b>Registros encontrados: {{ $registros}} </b></p>
    <br />
@endsection