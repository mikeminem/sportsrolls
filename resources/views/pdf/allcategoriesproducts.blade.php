
@extends('pdf.layout')

@section('title',"Listado de Productos por Categorias")

@section('content')
<h2>Listado de Productos </h2>
<h4>por Categoria</h4>
<small>Fecha: @php
	echo Date('d/m/Y h:s:i');

	
	@endphp

</small>
<hr />
    @foreach($categories as $category)
    <h2>{{ $category->name}}</h2>

    @php
        $registros = 0;
    @endphp
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
            @foreach($products as $product)
            <tr>
                @if($product->category_id == $category->id)
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->title }}</td>
                    <td align="right">${{ number_format($product->precio_venta,2) }}</td>
                    <td align="right">${{ number_format($product->precio_medio,2) }}</td>
                    <td align="right">${{ number_format($product->precio_mayoreo,2) }}</td>
                    <td align="right">${{ number_format($product->precio_master,2) }}</td>
                    <td align="right">${{ number_format($product->precio_vip,2) }}</td>

                     @php
                        $registros++;
                    @endphp

            
                @endif
            </tr>

           
            @endforeach
        </tbody>
        
    </table>
    <b>Registros encontrados: {{ $registros}} </b>
    <br />
    @endforeach
@endsection