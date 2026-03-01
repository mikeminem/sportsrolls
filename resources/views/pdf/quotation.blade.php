
@extends('pdf.layout')

@section('title',"Pedido a SZ")

@section('content')
<h3>Detalles del pedido</h3>



<small>Fecha de impresión: @php
    echo Date('d/m/Y h:s:i');

    $registros = 0;
    $total = 0;
    @endphp

</small>
    <table class="table">
       
            <tr>
                <th width="50%">Datos del Distribuidor</th>
                <th width="50%">Detalles</th>
            </tr>   
            <tr>
                <td>
                        <b>{{ $client->name }} {{ $client->last_name }}</b> <br>
                        {{ $client->email }} <br> 
                        {{ $client->phone }} <br>
                        {{ $client->configuration->calle }} #{{ $client->configuration->no_ext }}, {{ $client->configuration->colonia }}<br /> 
                        {{ $client->configuration->municipio }},  {{ $client->configuration->estado }},  {{ $client->configuration->pais }}, C.P.  {{ $client->configuration->cp }} <br>

                </td>
                <td>
                    <b># Pedido:</b> {{ $row->id }} <br>
                    <b>Fecha de alta:</b> {{ $row->created_at}}<br>
                    <b>Estado:</b> {{ $row->status }} <br>
                    <b># de Guía:</b>  -- <br>
                    <b>Paquetería:</b> -- <br>

                </td>
            </tr>                        
    </table>

  

    <h3>Listado de Productos</h3>

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="10%">Cantidad</th>
                <th >Descripción de Producto</th>
                <th width="20%">Precio Unitario</th>
                <th width="20%">Importe</th>
               


            </tr>                            
        </thead>
        <tbody>
            @foreach($detalles as $s)
            <tr>
                <td align="right">{{ $s->quantity }}</td>
                <td>{{ $s->producto->title }}</td>
                <td align="right">${{ number_format($s->sell_price,'2') }}</td>
                <td align="right">${{ number_format($s->importe,'2') }}</td>   
            </tr>

            @php
                $registros++;
                $total += $s->importe;
            @endphp
            @endforeach

            <tr >
                <td colspan="2"><b>Registros: {{ $registros}} / Productos: {{ $row->products }}</b></td>
                <td><b>Total</b></td>
                <td align="right"><b>$ {{ number_format($total,'2') }}</b></td>   
            </tr>


        </tbody>
     
    </table>


    <table class="table">
            <tr>
                <td width="60%">Observaciones</td>
                <td align="right"><b>Subtotal: </b></td>
                <td align="right"><b>${{ number_format($total,'2') }} </b></td>
            </tr>   
            <tr>
                <td rowspan="2">{{ $row->obs }}</td>
                <td align="right"><b>Paquetería:</b></td>
                <td align="right"><b>${{ $row->total_shipping }}</b></td>
            </tr>    

             <tr>
               
                <td align="right"><b>Total:</b></td>
                <td align="right"><b>${{ $row->total }}</b></td>
            </tr>  

    </table>

    <p>
        <b>Condiciones de Compra:
        <ul>
            <li>Precios expresados en Moneda Nacional (MXN)</li>
            <li>Vigencia del pedido pedido 24 horas</li>
            <li>En caso de no existir algunos productos, se notificará al distribuidor</li>
        </ul>
        </b>
    </b>


    @if( $row->status == "Pendiente" )
        <center><h4> *** El pedido se encuentra en Pendiente, en cuanto el equipo de distribución de SupleZac empiece a surtirlo, se cambiará de estatus
         ***</h4>
        </center>

    @endif









   
@endsection