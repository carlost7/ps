@extends('layouts.master')

@section('title')
@parent

- Correo {{ $correo->correo }} 
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('admin.correos.index','Correos') }} > Editar: {{ $correo->correo }}</h2>
            <p>Agrega la contraseña en el campo de contraseña</p>
            <p>Modifica el correo de reenvio en el campo de reenvio</p>
      </div>
</div>
<div class="container">

      @foreach($errors->all() as $error)
      <div class="alert alert-danger">{{ $error }}</div>
      @endforeach

      {{ Form::model($correo, array('url'=> array('admin/correos/'.$correo->id),'method'=>'PUT')) }}

      <div class="form-group">
            {{ Form::label('nombre','Nombre') }}
            {{ Form::text('nombre',null,array('class'=>'form-control','disabled'=>'disabled')) }}            
      </div>
      <div class="form-group">
            {{ Form::label('correo','Correo') }}
            {{ Form::email('correo',null,array('class'=>'form-control','disabled'=>'disabled')) }}            
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
            {{ Form::label('redireccion','Redireccion') }}
            {{ Form::text('redireccion',null,array('class'=>'form-control')) }}            
      </div>                  
      <button type="submit" id='confirmar' class="btn btn-success">Editar Correo</button>
      {{ Form::close() }}

</div>


@include('layouts.modal_password')

@stop

@section('footer')@stop