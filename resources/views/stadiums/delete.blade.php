{!! Form::open(['url'=>'/users/'.$user->id, 'method'=>'DELETE', 'class'=>'inline-block']) !!}

<button type="submit" ><span class="glyphicon glyphicon-trash"></span></button>
<!--input type="submit" class=" no-padding no-margin no-text-alter" value="Eliminar"-->

{!! Form::close() !!}
