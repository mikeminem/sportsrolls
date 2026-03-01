
@extends('pdf.layout')

@section('title',"Reporte de Cuentas Bancarias")

@section('content')
<h2>Listado Cuentas Bancarias</h2>
<small>Fecha: @php
	echo Date('d/m/Y h:s:i');

	$registros = 1;
	@endphp

</small>
<hr />
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="10">#</th>
                <th>Beneficiario</th>
                <th>Banco</th>
                <th># Cuenta</th>
                <th>Clabe Interbancaria</th>
                <th width="10">Activo</th>

            </tr>
        </thead>
        <tbody>

        </tbody>

    </table>
    <p><b>Registros encontrados: {{ $registros-1}} </b></p>
    <br />
@endsection
