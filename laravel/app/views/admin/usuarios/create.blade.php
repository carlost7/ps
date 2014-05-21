@extends('layouts.master')

@section('title')
@parent

| Usuarios
@stop



@section('content')

<div class="jumbotron">
      <div class="container">
            <h3>Agregar Usuario</h3>
      </div>
</div>
<div class="container">

      {{ Form::open(array('route'=>'admin.usuarios.store','id'=>'form_confirm')) }}

      @foreach($errors->all() as $message)
      <div class="alert alert-danger">{{ $message }}</div>
      @endforeach

      <div class="form-group">
            <label for="dominio">Dominio</label>
            <input type="text" name="dominio" value="{{ Input::old('dominio')}}" class="form-control" id="Dominio" placeholder="Escribe el dominio">
      </div>
      <div class="form-group">
            <label for="Nombre">Nombre</label>
            <input type="text" name="nombre" value="{{ Input::old('nombre')}}" class="form-control" id="Nombre" placeholder="Escribe tu nombre">
      </div>
      <div class="form-group">
            <label for="Correo">Correo</label>
            <input type="email" name="correo" value="{{ Input::old('correo')}}" class="form-control" id="Correo" placeholder="Escribe tu correo">
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
            @foreach($planes as $plan)
            <div class="radio">
                  <label>
                        {{Form::radio('plan', $plan->nombre) }}
                        {{ $plan->nombre }}
                  </label>                  
            </div>                  
            @endforeach
      </div>      
      <button type="submit" id='confirmar' class="btn btn-success">Confirmar Dominio</button>
      {{ Form::close() }}
</div>

@include('layouts.modal_password')

@stop

