@extends('layouts.master')

@section('title')
@parent

- Usuario {{ $usuario->email }} 
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('admin.usuarios.index','Usuarios') }} > {{ $usuario->email }}</h2>            
      </div>
</div>

<div class="container">

      
      {{ Form::model($usuario, array('url'=> array('admin/usuarios/'.$usuario->id),'method'=>'PUT')) }}

      @foreach($errors->all() as $error)
      <div class="alert alert-danger">{{ $error }}</div>
      @endforeach

      <div class="form-group">
            <label for="Nombre">Nombre</label>
            <input type="text" name="nombre" value="{{ $usuario->username }}" class="form-control" id="Nombre" placeholder="Escribe tu nombre">
      </div>
      <div class="form-group">
            <label for="Correo">Correo</label>
            <input type="email" name="correo" value="{{ $usuario->email }}" class="form-control" id="Correo" placeholder="Escribe tu correo">
      </div>
      <div class="form-group">
            <label for="password">Password</label>            
            <div class="input-group">
                  <input type="password" name="password" class="form-control" id="Password" placeholder="Contraseña">
                  <span class="input-group-btn">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#ModalPassword" onclick="get_password()">
                              Generar Contraseña
                        </button>
                  </span>                  
            </div>
      </div>
      <div class="form-group">
            <label for="password_confirmation">Confirmar</label>
            <input type="password" name="password_confirmation" class="form-control" id="Password_confirmation" placeholder="Confirma tu contraseña">
      </div>
      <div class="form-group">
            {{ Form::checkbox('is_activo')}}Activo
      </div>
      <div class="form-group">            
            {{ Form::checkbox('is_deudor')}}Deudor      
      </div>
      <button type="submit" id='confirmar' class="btn btn-success">Editar Usuario</button>
      {{ Form::close() }}
</div>

@include('layouts.modal_password')

@stop

