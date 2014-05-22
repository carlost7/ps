@extends('layouts.master')

@section('title')
@parent

| Cambiar Contraseña
@stop



@section('content')

<div class="jumbotron">

      <div class="container">
            <h1>Cambiar Contraseña</h1>
            <h3>Rellena los campos para cambiar tu contraseña</h3>            
      </div>

</div>
<div class="container">

      {{ Form::open(array('url'=>'usuario/cambiar_password')) }}
      @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>      
      @endforeach
      <div class="form-group">
            <div class="form-group">
                  <label for="old_password">Contraseña antigua</label>
                  <input type="password" name="old_password" class="form-control" id="Old_password" placeholder="Escribe tu antigua contraseña">
            </div>
            <div class="form-group">
                  <label for="password">Contraseña nueva</label>            
                  <div class="input-group">
                        <input type="password" name="password" class="form-control" id="Password" placeholder="Escribe tu nueva contraseña">
                        <span class="input-group-btn">
                              <button class="btn btn-primary" data-toggle="modal" data-target="#ModalPassword" onclick="get_password()">
                                    Generar Contraseña
                              </button>
                        </span>                  
                  </div>
            </div>
            <div class="form-group">
                  <label for="password_confirmation">Confirmar contraseña</label>
                  <input type="password" name="password_confirmation" class="form-control" id="Password_confirmation" placeholder="Confirma tu contraseña">
            </div>
      </div>            
      <button type="submit" id='recordar' class="btn btn-success btn-lg">Enviar correo</button>
      {{ Form::close() }}
</div>

@include('layouts.modal_password')

@stop



@section('footer')
@parent
@stop
