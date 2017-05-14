@extends('layouts.app')
@section('title', 'Administrar Usuarios')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h1>Administrar Usuarios</h1></div>
                <div class="panel-body">
                <div class="row">
                  
                  <div class="col-md-12">

                      <form class="navbar-form navbar-left">
                        <div class="input-group">
                          <input type="text" class="form-control" placeholder="Apellido o C.I.">
                          <span class="input-group-btn">
                            <button class="btn btn-default" type="button">Buscar</button>
                          </span>
                        </div>
                      </form>
                      
                      <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Tareas administrativas <span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <li><a href="#">Crear un curso</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Ayuda</a></li>
                          </ul>
                        </li>
                      </ul>

                  </div>

                </div>

                <hr>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Primer nombre</th>
                                    <th>Segundo nombre</th>
                                    <th>Primer apellido</th>
                                    <th>Segundo apellido</th>
                                    <th>Fecha de nacimiento</th>
                                    <th>Generacion</th>
                                    <th>C.I.</th>
                                    <th>eMail</th>
                                    <th>Tipo</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            @foreach ($users as $key => $user)
                            <tbody>
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name1 }}</td>
                                    <td>{{ $user->name2 }}</td>
                                    <td>{{ $user->apellido1 }}</td>
                                    <td>{{ $user->apellido2 }}</td>
                                    <td>{{ $user->uyNacimiento($user->nacimiento) }}</td>
                                    <td>{{ $user->generacion }}</td>
                                    <td>{{ $user->ci }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->tipo($user->esAdmin) }}</td>
                                    <td>
                                        {{ Form::open(['method' => 'GET', 'route' => ['Users.show', $user->id]]) }}
                                        {{ Form::hidden('id', $user->id) }}
                                        {{ Form::submit('Ver', ['class' => 'btn btn-xs btn-info']) }}
                                        {{Form::close()}}
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
</div>
@endsection