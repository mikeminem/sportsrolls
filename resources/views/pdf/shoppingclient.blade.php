
@extends('pdf.layout')
@section('title',"Reporte de Compras")

@section('content')
<h2>Listado de Compras</h2>
<h4>Cliente: {{ $client[0]->name }} {{ $client[0]->last_name }}</h4>
<p>Fecha: @php
	echo Date('d/m/Y h:s:i');

	$registros = 0;
    $total = 0;
	@endphp
</p>
    <table class="table">
        <thead>
            <tr>
                <th >#</th>
                <th >Fecha</th>
                <th >Forma Pago</th>
                <th >Total</th>
            </tr>                            
        </thead>
        <tbody>
            @foreach($shopping as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->created_at }}</td>
                <td>{{ $s->formaPago }}</td>
                <td align="right">${{ number_format($s->total,'2') }}</td>   
            </tr>

            @php
            	$registros++;
                $total += $s->total;
            @endphp
            @endforeach
        </tbody>

        <tfoot>
            <tr >
              
                <td></td>
                <td></td>
                <td><b>Total</b></td>
                <td align="right"><b>${{ number_format($total,'2') }}</b></td>   
            </tr>
        </tfoot>

     
    </table>
    <b>Registros encontrados: {{ $registros}} </b>
    <br />
@endsection