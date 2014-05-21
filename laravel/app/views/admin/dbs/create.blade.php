@extends('layouts.master')

@section('title')
@parent

- Agregar Base de Datos
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('admin.dbs.index','Bases de Datos') }} > Agregar Base de Datos</h2>
            <p>Para crear una nueva base de datos, rellena los siguiente campos</p>            
      </div>
</div>
<div class="container">

      @foreach($errors->all() as $error)
      <div class="alert alert-danger">{{ $error }}</div>
      @endforeach

      <br />
      {{ Form::open(array('url'=>'admin/dbs','id'=>'form_confirm')) }}

      <div class="form-group">
            <label for="username">Nombre de Usuario</label>
            <input type="text" name="username" value="{{ Input::old('username')}}" class="form-control" id="Nombre" placeholder="Nombre de usuario">
      </div>
      <div class="form-group">
            <label for="dbname">Nombre de la base</label>
            <input type="text" name="dbname" value="{{ Input::old('dbname')}}" class="form-control" id="Correo" placeholder="Nombre de la base de datos">
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
      <button type="submit" id='confirmar' class="btn btn-success">Agregar Correo</button>
      {{ Form::close() }}

</div>


@include('layouts.modal_password')

@stop