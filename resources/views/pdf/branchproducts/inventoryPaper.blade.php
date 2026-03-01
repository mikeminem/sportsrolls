
@extends('pdf.layout')

@section('title',"Hoja de Inventario")

@section('content')
<h2>Hoja de Inventario</h2>
<small>Fecha: @php
	echo Date('d/m/Y h:s:i');

	

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
             
            </tr>                             
        </thead>
        <tbody>
            @for($i=1;$i<=20; $i++)
            <tr>
                <td>{{ $i }}.-</td>
                <td> </td>
                <td> </td>
                <td> </td>
              
                
            </tr>
            @endfor

        </tbody>
        
    </table>

    <br />
@endsection