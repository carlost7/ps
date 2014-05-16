@extends('layouts.master')

@section('title')
@parent

| Dashboard
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h1>Usuarios</h1>
      </div>
</div>
<div class="container">
      <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Lista de usuarios</div>


            @if($usuarios)
            <!-- Table -->
            <div class="table-responsive">
                  <table class="table">
                        <tr>
                              <th>Dominio</th>
                              <th>Plan</th>                    
                              <th>Nombre</th>
                              <th>Email</th>
                              <th>Creado</th>
                              <th>Eliminar</th>
                        </tr>
                        @foreach($usuarios as $usuario)
                        <tr>
                              {{ Form::open(array('route'=>'admin/eliminar_usuario')) }}
                              <td>{{ $usuario->dominio->dominio }}</td>
                              <td>{{ $usuario->dominio->plan->nombre }}</td>
                              <td>{{ $usuario->username }}</td>
                              <td>{{ $usuario->email }}</td>
                              <td>{{ $usuario->created_at }}</td>
                              <td><button type="submit" class="btn btn-danger btn-xs">Eliminar</button></td>
                        <input type="hidden" name="id" value="{{$usuario->id }}" />
                        <input type="hidden" name="email" value="{{$usuario->email }}" />
                        {{ Form::close() }}
                        </tr>
                        @endforeach
                  </table>
            </div>

            @else
            <h2>Aún no hay usuarios, deberias agregar alguno</h2>
            @endif
            <div class="title">
                  <h4>Agregar Nuevo Usuario</h4>      
            </div>        

            <div class="panel-body">
                  {{ Form::open(array('route'=>'admin/agregar_usuario','role'=>'form')) }}            

                  @foreach($errors->all() as $message)
                  <div class="alert alert-danger">{{ $message }}</div>
                  @endforeach

                  <div class="form-group">
                        <label for="Nombre">Nombre</label>
                        <input type="text" name="nombre" value="{{ Input::old('nombre') }}" class="form-control" id="Nombre" placeholder="Escribe tu nombre">
                  </div>
                  <div class="form-group">
                        <label for="Correo">Correo</label>
                        <input type="email" name="correo" value="{{ Input::old('correo') }}" class="form-control" id="Correo" placeholder="Escribe tu correo">
                  </div>
                  <div class="form-group">
                        <label for="Password">Password</label>
                        <input type="password" name="password" class="form-control" id="Password" placeholder="Contraseña">                  
                        <label for="Confirmar">Confirmar</label>
                        <input type="password" name="password_confirmation" class="form-control" id="Confirmar" placeholder="Confirma tu contraseña">
                  </div> 
                  <div class="form-group">
                        <label for="dominio">Dominio</label>
                        <input type="text" name="dominio"  value="{{ Input::old('dominio') }}"  class="form-control" id="dominio" placeholder="Cual es el dominio">
                  </div>
                  <div class="form-group">

                        <label class="radio-inline">
                              <input type="radio" name="plan" id="planBasico" value="basico">
                              Básico
                        </label>
                        <label class="radio-inline">
                              <input type="radio" name="plan" id="planStartup" value="startup" checked="checked">
                              Startup
                        </label>
                        <label class="radio-inline">
                              <input type="radio" name="plan" id="planEnterprise" value="enterprise">
                              Enterprise
                        </label>
                  </div>            
                  <button type="submit" id='confirmar' class="btn btn-success">Agregar Usuario</button>            
                  {{ Form::close() }}
            </div>

      </div>
</div>
@stop
