@extends('layouts.master')

@section('title')
@parent

| Recuperar Contraseña
@stop



@section('content')

<div class="jumbotron">

      <div class="container">
            <h1>¿Olvidaste tu contraseña?</h1>
            <h3>No te preocupes, llena la información y te enviaremos un correo para restaurarla</h3>            
      </div>

</div>
<div class="container">

      {{ Form::open(array('url'=>'usuario/recuperar')) }}
      @if (Session::get("error"))
      {{ Session::get("error") }}<br />
      @endif
      @if (Session::get("status"))
      {{ Session::get("status") }}<br />
      @endif
      <div class="form-group">
            <label for="Correo">Correo</label>
            <input type="email" name="email" value="{{ Input::old('correo') }}" class="form-control" id="Correo" placeholder="Escribe tu correo">
      </div>            
      <button type="submit" id='recordar' class="btn btn-success btn-lg">Enviar correo</button>
      {{ Form::close() }}
</div>

@stop

@section('footer')
@parent
@stop
