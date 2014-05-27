@extends('layouts.master')

@section('title')
@parent

| Recuperar Contraseña
@stop



@section('content')

<div class="jumbotron">

      <div class="container">
            <h1>Casí listo</h1>
            <h3>Rellena los campos para regenerar tu contraseña</h3>            
      </div>

</div>
<div class="container">

      {{ Form::open() }}
      @if($errors->count())            
      <div class="alert alert-danger">{{ $errors }}</div>
      @endif

      <div class="form-group">
            <label for="Correo">Correo</label>
            <input type="email" name="email" value="{{ Input::old('email') }}" class="form-control" id="Correo" placeholder="Escribe tu correo">            
      </div>
      <div class="form-group">
            <label for="password">Contraseña</label>            
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
            <label for="password_confirmation">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" id="Password_confirmation" placeholder="Confirma tu contraseña">
      </div>
      <button type="submit" id='resetear' class="btn btn-success btn-lg">Modificar Contraseña</button>
      {{ Form::close() }}

</div>

@include('layouts.modal_password')

@stop

@section('footer')
@parent
@stop


@section('scripts')

@stop