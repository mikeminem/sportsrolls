<div class="card product text-left">
		@if(Auth::check() && $product->user_id == Auth::user()->id)

		<div class="absolute actions">
			<a href=" {{ url('/products/'.$product->id.'/edit')}}" alt="Editar Producto" >Editar</a> | @include('products.delete',['product'=>$product])
		</div>

		@endif
		<h2>{{ $product->title}}</h2>
		<div class="row">
			
			<div class="col-sm-6 col-xs-12">
				@if($product->extension)
					<img src=" {{ url( '/products/images/p-'.$product->id.'.'.$product->extension) }}" alt="{{ $product->title}}" title="{{ $product->title}}" class="product-imagen" >
				@else
					No imagen
				@endif
			</div>

			<div class="col-sm-6 col-xs-12">
					<p>
						<strong>Descripción</strong>
					</p>
					<p>
						{{ $product->description}}
					</p>
					<p align="right">
						@include("in_shopping_carts.form",['product'=>$product])
					</p>
			</div>

		</div>
	</div>