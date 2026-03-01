
@extends('pdf.layout')

@section('title',"Movimiento de Almacen")

@section('content')
<h3>Movimiento de Almacen</h3>



<small>Fecha de impresión: @php
    echo Date('d/m/Y h:s:i');

    $registros = 0;
    $total = 0;
    @endphp

</small>
    <table class="table">
       
            <tr>
                <th colspan="6">Datos del Movimiento</th>
             
            </tr>   
            <tr>
                <td>
                    <b># Movimiento:</b> <br> {{ $row->id }}
                </td>
                <td>
                    <b>Fecha alta:</b> <br> {{ $row->created_at}}
                </td>

                <td>
                    <b>Estado:</b> <br> {{ $row->status}}
                </td>

                <td>
                    <b>Tipo:</b> <br> {{ $row->tipo}}
                </td>

                <td>
                    <b> Entrega:</b> <br> {{ $row->entrega}}
                </td>

                <td>
                    <b> Recibe:</b> <br> {{ $row->recibe}}
                </td>
            </tr>    

              <tr>
                <td colspan="2">
                    <b>Documento:</b> <br> {{ $row->documento }}
                </td>
                <td colspan="2">
                    <b>Folio Documento:</b> <br> {{ $row->folio}}
                </td>

                <td colspan="2">
                    <b>Fecha Documento:</b> <br> {{ $row->fecha_documento}}
                </td>

           


               
            </tr>                         
    </table>

  

    <h3>Listado de Productos</h3>

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="10%">Cantidad</th>
                <th >Descripción de Producto</th>
                <th width="20%">Stock</th>
                <th width="20%">Stock Actual</th>
                <th width="10%">Verificar</th>
               


            </tr>                            
        </thead>
        <tbody>
            @foreach($detalles as $s)
            <tr>
                <td align="right">{{ $s->quantity }}</td>
                <td>{{ $s->producto->title }}</td>
                <td align="right">{{$s->stock }}</td>
                <td align="right">{{ $s->quantity + $s->stock }}</td>   
                <td>  </td>   
            </tr>

            @php
                $registros++;
                $total += $s->quantity;
            @endphp
            @endforeach

            <tr >
                <td colspan="5"><b>Número de Registros: {{ $registros}}</b></td>
                  
            </tr>


        </tbody>
     
    </table>



    <table class="table">
            <tr>
                <td width="80%">Observaciones</td>
                <td align="right"><b>Total Productos</b></td>
              
            </tr>   
            <tr>
                <td>{{ $row->obs }}</td>
                <td align="right"><b>{{ number_format($total,'2') }} </b></td>
            </tr>    

        

    </table>

     <b>Marque con una X el renglón para confirmar el conteo de productos</b>

  


    @if( $row->status == "Espera" )
        <center><h4> *** Debes Aceptar el Movimiento de Almacen para afectar las existencias
         ***</h4>
        </center>

    @endif









   
@endsection