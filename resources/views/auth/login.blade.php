@extends('layouts.app')
@section('title', 'Entrar - Reenev')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading"><h1>Iniciar sesión en Reenev</h1></div>
				<div class="panel-body">
					<form onsubmit="return validarCampos();" class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
						{{ csrf_field() }}
						<div class="form-group{{ $errors->has('ci') ? ' has-error' : '' }}">
							<label for="ci" class="col-md-4 control-label">Cedula</label>
							<div class="col-md-6">
								<input id="ci" type="cedula" class="form-control" name="ci" value="{{ old('ci') }}" placeholder="1.234.567-8" required autofocus>
								@if ($errors->has('ci'))
								<span class="help-block">
									<strong>{{ $errors->first('ci') }}</strong>
								</span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
							<label for="password" class="col-md-4 control-label">Contraseña</label>
							<div class="col-md-6">
								<input id="password" type="password" class="form-control" name="password" required>
								@if ($errors->has('password'))
								<span class="help-block">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
								@endif
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Recordar credenciales
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-8 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Entrar
								</button>
								<a class="btn btn-link" href="{{ route('password.request') }}">
									¡Perdi mi contraseña!
								</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
