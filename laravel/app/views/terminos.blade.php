@extends('layouts.master')

@section('title')
@parent

| Contacto
@stop



@section('content')
<!-- Main jumbotron for a primary marketing message or call to action -->

<div class="jumbotron">

      <div class="container">

            <h1>¿Tienes dudas?, Envianos un mensaje</h1>
            <h2>Te responderemos todas tus preguntas</h2>

      </div>
</div>
<div class="container">


      {{ Form::open() }}
      <div class="form-group">
            <label for="Email">Correo Electrónico</label>
            <input type="email" class="form-control" name="Email" id="Email" placeholder="Escribe tu correo">
      </div>
      <div class="form-group">
            <label for="Nombre">Nombre</label>
            <input type="text" class="form-control" id="Nombre" name="Nombre" placeholder="Escribe tu nombre">
      </div>
      <div class="form-group">
            <label for="Mensaje">Mensaje</label>
            <textarea class="form-control" rows="10"></textarea>
      </div>
      <button type="submit" class="btn btn-success">Enviar Mensaje</button>
      <button type="reset" class="btn btn-default">Limpiar</button>
      {{ Form::close() }}
</div>
@stop

@section('footer')
@parent
@stop
