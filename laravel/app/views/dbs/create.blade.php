@extends('layouts.master')

@section('title')
@parent

- Agregar Base de Datos
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('dbs.index','Bases de Datos') }} > Agregar Base de Datos</h2>
            <p>Para crear una nueva base de datos, rellena los siguiente campos</p>            
      </div>
</div>
<div class="container">

      @foreach($errors->all() as $error)
      <div class="alert alert-danger">{{ $error }}</div>
      @endforeach

      <br />
      {{ Form::open(array('url'=>'dbs','id'=>'form_confirm')) }}
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
      <button type="submit" id='confirmar' class="btn btn-success">Agregar Base de Datos</button>
      {{ Form::close() }}

</div>

@include('layouts.menu_usuario', array('activo'=>'correos'))
@include('layouts.modal_password')

@stop