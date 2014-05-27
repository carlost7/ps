@extends('layouts.master')

@section('title')
@parent

- Ftp {{ $ftp->username }} 
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('admin.ftps.index','Ftps') }} > Editar: {{ $ftp->username }}</h2>
            <p>Agrega la contraseña en el campo de contraseña</p>
      </div>
</div>
<div class="container">

      @foreach($errors->all() as $error)
      <div class="alert alert-danger">{{ $error }}</div>
      @endforeach

      {{ Form::model($ftp, array('url'=> array('admin/ftps/'.$ftp->id),'method'=>'PUT')) }}

      <div class="form-group">
            {{ Form::label('username','Nombre de Usuario') }}
            {{ Form::text('username',null,array('class'=>'form-control','disabled'=>'disabled')) }}            
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
      <button type="submit" id='confirmar' class="btn btn-success">Editar Ftp</button>
      {{ Form::close() }}

</div>

@include('layouts.modal_password')

@stop