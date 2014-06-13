@extends('layouts.master')

@section('title')
@parent

| Entrar 
@stop



@section('content')

<div class="jumbotron">

      <div class="container">
            <h1>Escribe tus datos para entrar</h1>
            <h3>Si aun no eres usuario</h3>
            <p>{{ HTML::linkRoute('dominio.inicio','Registrate',null,array('class'=>'btn btn-primary btn-lg')) }}</p>
            <h3>Si olvidaste tu contraseña</h3>
            <p>{{ HTML::linkRoute('usuario/recuperar','Recuperar Contraseña',null,array('class'=>'btn btn-danger btn-lg')) }}</p>                
      </div>
</div>
<div class="container">

      {{ Form::open(array('url'=>'usuario/login')) }}
      @if($errors->has())
      @foreach ($errors->all() as $error)
      <div class="alert alert-danger">{{ $error }}</div>
      @endforeach
      @endif      
      <br />
      <div class="form-group">
            <label for="Correo">Correo</label>
            <input type="email" name="correo" value="{{ Input::old('correo') }}" class="form-control" id="Correo" placeholder="Escribe tu correo">
      </div>
      <div class="form-group">
            <label for="Password">Password</label>
            <input type="password" name="password" class="form-control" id="Password" placeholder="Contraseña">             
      </div>      
      <div class="form-group">
            <div class="checkbox">
                  <label>
                        <input type="checkbox"> Seguir conectado
                  </label>
            </div>
      </div>                  
      <button type="submit" id='confirmar' class="btn btn-success btn-lg">Entrar</button>        
      {{ Form::close() }}
</div>

@stop

@section('footer')
@parent
@stop

