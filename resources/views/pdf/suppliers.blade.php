
@extends('pdf.layout')

@section('title',"Listado de Proveedores")

@section('content')
<h2>Listado de proveedores</h2>
<small>Fecha: @php
	echo Date('d/m/Y h:s:i');

	$registros = 0;

    

	@endphp

</small>
<hr />
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="10%">Nombre</th>
                <th width="40%">Contacto</th>
                <th width="10%">Teléfono</th>
                <th width="10%">Celular</th>
                
            </tr>                             
        </thead>
        <tbody>
             @foreach($rows as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->contact_name }}</td>
                <td>{{ $row->phone }}</td>
                <td>{{ $row->movil }}</td>
              
            </tr>

            @php
                $registros++;
            @endphp
            @endforeach
            
           
        </tbody>
        
    </table>
    <p><b>Registro(s) encontrado(s): {{ $registros}} </b></p>
    <br />
@endsection