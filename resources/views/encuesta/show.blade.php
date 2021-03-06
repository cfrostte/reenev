@extends('layouts.app')
@section('title', 'Ver una encuesta')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h1>
						<span class="glyphicon glyphicon-stats"></span>
						Encuesta
					</h1>
				</div>
				<div class="panel-body">
					<table class="table">
						<thead>
							<caption>Datos de la encuesta</caption>
						</thead>
						<tbody>
							<tr>
								<td>
									<strong>
										<i class="fa fa-circle text-info" aria-hidden="true"></i>
										Asunto
									</strong>
								</td>
								<td>{{ $encuesta->asunto }}</td>
							</tr>
							<tr>
								<td>
									<strong>
										<i class="fa fa-circle-o text-info" aria-hidden="true"></i>
										Descripción
									</strong>
								</td>
								<td>{{ $encuesta->descripcion }}</td>
							</tr>
							<tr>
								<td>
									<strong>
										<i class="fa fa-calendar-o text-info" aria-hidden="true"></i>
										Inicio
									</strong>
								</td>
								<td>{{ $encuesta->inicio }}</td>
							</tr>
							<tr>
								<td>
									<strong>
										<i class="fa fa-calendar-times-o text-info" aria-hidden="true"></i>
										Vence
									</strong>
								</td>
								<td>{{ $encuesta->vence }}</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<table class="table">
						<caption>Preguntas de la encuesta</caption>
						<thead>
							<tr>
								<th>ID</th>
								<th>Enunciado</th>
							</tr>
						</thead>
						<tbody>
							@if ($preguntas->count()<1)
							<tr>
								<td>No hay preguntas,
									<a href="{{ route('Encuestas.Preguntas.create',
									$encuesta->id) }}"> agregar una nueva</a>
								</td>
							</tr>
							@endif
							@foreach ($preguntas as $key => $p)
							<tr>
								<td>{{$p->id}}</td>
								<td>{{$p->enunciado}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<table class="table">
						<tbody>
							<tr>
								<td>@include('encuesta.btn_editarDatos')</td>
								@if ($preguntas->count()>0)
								<td>@include('encuesta.btn_editarPreguntas')</td>
								@endif
							</tr>
						</tbody>
					</table>
					<hr>
					<a class="btn btn-default" href="{{ route('Encuestas.index') }}">
						<span class="glyphicon glyphicon-arrow-left"></span></a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
