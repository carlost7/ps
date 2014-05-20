@extends('layouts.master')

@section('title')
@parent

- Agregar Ftp
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('admin.ftps.index','Ftps') }} > Agregar Ftp</h2>
            <p>Para crear un nuevo ftp, rellena los siguiente campos</p>            
      </div>
</div>
<div class="container">

      @foreach($errors->all() as $error)
      <div class="alert alert-danger">{{ $error }}</div>
      @endforeach

      <br />
      {{ Form::open(array('url'=>'admin/ftps','id'=>'form_confirm')) }}

      <div class="form-group">
            <label for="username">Nombre de Usuario</label>
            <input type="text" name="username" value="{{ Input::old('username')}}" class="form-control" id="UserName" placeholder="Nombre del usuario">
      </div>
      <div class="form-group">
            <label for="home_dir">Directorio</label>
            <div class="input-group">
                  <span class="input-group-addon">{{ Session::get('dominio_usuario')->dominio.'/' }}</span>
                  <input type="text" name="home_dir" value="{{ Input::old('home_dir')}}" class="form-control" id="HomeDir" placeholder="Escribe el directorio a donde llegará el FTP">
            </div>
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
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" id="Password_confirmation" placeholder="Confirma tu contraseña">
      </div>
      <button type="submit" id='confirmar' class="btn btn-success">Agregar Ftp</button>
      {{ Form::close() }}

</div>

@include('layouts.modal_password')

@stop



@section('footer')@stop