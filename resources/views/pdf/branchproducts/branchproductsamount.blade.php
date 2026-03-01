
@extends('pdf.layout')

@section('title',"Avalúo de Inventario")

@section('content')
<h2>Avalúo de Inventario</h2>
<small>Avalúo de Inventario al: @php
	echo  Date('d/m/Y h:s:i');

	$registros = 0;
    $total = 0;

	@endphp

</small>
<hr />
    <p>
        <b>El avalúo de inventario es lo que se tiene invertido en almacenes en la empresa, puede filtrar todas y cada una de las sucursales, o el avalúo total.</b>
    </p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="20%">Sucursal</th>
                <th width="10%">Código Barras</th>
                <th width="30%">Descripción</th>
                <th width="10%">Existencias en Sistema</th>
                <th width="10%">Importe</th>
                <th width="10%">Total</th>
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
                <td align="right">${{ number_format($row->precio_compra,2,".",",") }}</td>
                <td align="right">${{ number_format($row->total,2,".",",") }}</td>
            </tr>

            @php
                //mandamos el total de totales
                $total += $row->total;

            @endphp

           
            @endforeach



             <tr>
                <td colspan="5"></td>
                <td><b>Total:</b></td>
                <td align="right"><b>${{ number_format($total,2,".",",") }}</b></td>
                
            </tr>



            
           
        </tbody>
        
    </table>
    <p><b>Productos encontrados: {{ $registros}} </b></p>
    <br />

   
@endsection