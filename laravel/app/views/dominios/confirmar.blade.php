@extends('layouts.master')

@section('title')
@parent

| Dominios
@stop



@section('content')

{{ Session::get('posible_dominio') }}
{{ Session::get('existente') }}

<div class="jumbotron">

      <div class="container">
            <h1>{{ $dominio }} casí esta listo</h1>
            <h2>Solo llena los datos para terminar</h2>
      </div>
</div>
<div class="container">

      {{ Form::open(array('route'=>'pagos/confirmar_registro','id'=>'form_confirm')) }}

      @foreach($errors->all() as $message)
      <div class="alert alert-danger">{{ $message }}</div>
      @endforeach

      <input type="hidden" name="dominio" value="{{ $dominio }}">
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
            <div class="radio">
                  <label>
                        <input type="radio" name="plan" id="planBasico" value="basico">
                        Básico
                  </label>
            </div>
            <div class="radio">
                  <label>
                        <input type="radio" name="plan" id="planStartup" value="startup" checked="checked">
                        Startup
                  </label>
            </div>
            <div class="radio">
                  <label>
                        <input type="radio" name="plan" id="planEnterprise" value="enterprise">
                        Enterprise
                  </label>
            </div>

      </div>
      <div class="form-group">
            <div class="checkbox">
                  <label>
                        <input type="checkbox" name="aceptar" id="aceptar" value="1" checked="checked"> Aceptar los {{ HTML::LinkRoute('terminos','Terminos y condiciones') ;}}
                  </label>
            </div>
      </div>                  
      <button type="submit" id='confirmar' class="btn btn-success">Confirmar Compra</button>
      {{ Form::close() }}
</div>

@include('layouts.modal_password')

@stop

@section('footer')
@parent
@stop

@section('scripts')
<script>
      $('#form_confirm').submit(function(e) {
            if ($('#aceptar').is(':checked')) {
                  $('#form_confirm').submit();
            } else {
                  alert('Para continuar tienes que dar click en aceptar los terminos');
                  e.preventDefault();
            }

      });
</script>
@stop