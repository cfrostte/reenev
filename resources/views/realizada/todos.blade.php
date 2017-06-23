@extends('layouts.app')
@section('title','Estudiantes que han completado las encuestas')
@section('content')
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-default">
					<div class="panel-heading"><h1>Todo loa alumnos que han completado la encuesta ordendos por prioridad</h1></div>
					<div class="panel-body">
						@include('layouts.flashes')
						<?php  
						// dd($resultados);
						?>
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr >
											<th>Fecha realizada</th>
											<th>Estudiante</th>
											<th>No corresponde</th>
											<th>Muy mal</th>
											<th>Mal</th>
											<th>Normal</th>
											<th>Bien</th>
											<th>Muy bien</th>
											<th></th>
										</tr>
									</thead>
									@foreach ($resultados as $key => $estudiante)
									<tbody>
										<tr>
										<?php 
										// dd($estudiante);
										?>
											<td>{{ $estudiante['cuando'] }}</td>
											<td>{{ $estudiante['name1'] }} {{ $estudiante['apellido1'] }}</td>
											<td>{{ $estudiante['nocorresponde'] }}</td>
											<td>{{ $estudiante['muymal'] }}</td>
											<td>{{ $estudiante['mal'] }}</td>
											<td>{{ $estudiante['normal'] }}</td>
											<td>{{ $estudiante['bien'] }}</td>
											<td>{{ $estudiante['muybien'] }}</td>
											<td>
												{{ Form::open(['method' => 'POST', 'route' => ['Realizada.rehacer']]) }}
												{{ Form::hidden('idrealizada', $estudiante['id']) }}
		                                        {{ Form::submit('Rehacer encuesta', ['class' => 'btn btn-xs btn-danger']) }}
		                                        {{ Form::close() }}
											</td>
										</tr>
									</tbody>
									@endforeach
								</table>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>			
@endsection