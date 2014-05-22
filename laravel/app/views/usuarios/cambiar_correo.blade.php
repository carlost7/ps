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

      {{ Form::open(array('url'=>'usuario/cambiar_correo')) }}
      @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>      
      @endforeach
      <div class="form-group">
            <div class="form-group">
                  <label for="password">Contraseña</label>
                  <input type="password" name="password" class="form-control" id="password" placeholder="Escribe tu contraseña">
            </div>
            <div class="form-group">
                  <label for="old_email">Correo Antiguo</label>
                  <input type="email" name="old_email" class="form-control" id="Old_mail" placeholder="Correo Antiguo">
            </div>
            <div class="form-group">
                  <label for="new_email">Nuevo Correo</label>
                  <input type="email" name="new_email" class="form-control" id="New_email" placeholder="Nuevo Correo">
            </div>
      </div>            
      <button type="submit" id='recordar' class="btn btn-success btn-lg">Cambiar correo</button>
      {{ Form::close() }}
</div>


@stop



@section('footer')
@parent
@stop
