@extends('layouts.master')

@section('title')
@parent

- Agregar Correos
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('correos.index','Correos') }} > Agregar Correos</h2>
            <p>Para crear un nuevo correo, rellena los siguiente campos</p>            
      </div>
</div>
<div class="container">

      @foreach($errors->all() as $error)
      <div class="alert alert-danger">{{ $error }}</div>
      @endforeach

      <br />
      {{ Form::open(array('url'=>'correos','id'=>'form_confirm')) }}

      <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" value="{{ Input::old('nombre')}}" class="form-control" id="Nombre" placeholder="Nombre del correo">
      </div>
      <div class="form-group">
            <label for="correo">Correo</label>
            <div class="input-group">
                  <input type="text" name="correo" value="{{ Input::old('correo')}}" class="form-control" id="Correo" placeholder="Escribe el correo">
                  <span class="input-group-addon">{{ '@'.Session::get('dominio')->dominio }}</span>
            </div>

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
            <label for="redireccion">Redirección</label>
            <input type="text" name="redireccion" value="{{ Input::old('redireccion')}}" class="form-control" id="Nombre" placeholder="Escribe el correo al que se redireccionara">
      </div>                  
      <button type="submit" id='confirmar' class="btn btn-success">Agregar Correo</button>
      {{ Form::close() }}

</div>

@include('layouts.menu_usuario', array('activo'=>'correos'))
@include('layouts.modal_password')

@stop



@section('footer')@stop