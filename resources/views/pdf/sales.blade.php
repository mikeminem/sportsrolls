
@extends('pdf.layout')

@section('title',"Reporte de Ventas")

@section('content')


@section('title_report')
<h4>Listado de ventas del Día</h4>
<small>Fecha del reporte: @php
	echo Date('d/m/Y h:s:i');

	$registros = 0;
	@endphp

</small>

@endsection('title')



    <table class="table">
			<thead>
				<tr>
					<th>Folio</th>
					<th>Cliente</th>
					<th>Forma de Pago</th>
					<th>Estado</th>
					@if ((Auth::user()->tipo == 'Dueño') || (Auth::user()->tipo == 'Administrador'))
						<th>Vendrió</th>
					@endif
					<th>Subtotal</th>
					<th>Descuento</th>
					<th>Total</th>
				</tr>
			</thead>

			@php 
					$total = 0;
					$subtotal = 0;
					$descuento = 0;
			@endphp
			<tbody>

				@if (count($ventas)==0)
				
					<tr>
						<td colspan="9">
							<b>No se encontraron registros</b>
						</td>
					</tr>
				@endif


				@foreach($ventas as $venta)

					<tr>
						

						<td>
							@if(strlen($venta->serie)>0)
								{{ $venta->serie}} - 
							@endif	
							{{ $venta->folio}}
						</td>
					

						<td>{{ $venta->client->name}} {{ $venta->client->last_name}}</td>

						
						<td> {{ $venta->formaPago }}</td>
						<td> {{ $venta->status }}</td>
						@if ((Auth::user()->tipo == 'Dueño') || (Auth::user()->tipo == 'Administrador'))
							<td> {{ $venta->usuario->name }} </td>
						@endif
						
						<td align="right">$ {{ number_format($venta->subtotal,2,'.',' ,')  }}</td>
						<td align="right">$ {{ number_format($venta->descuento,2,'.',' ,')  }}</td>
						<td align="right">$ {{ number_format($venta->total,2,'.',' ,')}}</td>
						@php  $subtotal = $subtotal + $venta->subtotal;
							$descuento = $descuento + $venta->descuento;
							$total = $total + $venta->total;

							$registros++;

						@endphp

					</tr>
				@endforeach

			</tbody>
			<tfoot>
			<tr>
					
				
					
					<td></td>
					<td></td>
					<td></td>

					@if ((Auth::user()->tipo != 'Usuario'))
						<th></th>
					@endif
					<td>Totales: </td>
					<td align="right">$ {{ number_format($subtotal,2,'.',' ,')}}</td>
					<td align="right">$ {{ number_format($descuento,2,'.',' ,')}}</td>
					<td align="right">$ {{ number_format($total,2,'.',' ,')}}</td>
					
					
				</tr>
			</tfoot>
		</table>
		<b>Registros encontrados: {{ $registros }}</b>
@endsection