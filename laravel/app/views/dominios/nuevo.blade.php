@extends('layouts.master')

@section('title')
@parent

| Nuevo Dominio
@stop



@section('content')
<div class="container">



      <div class="page-header">
            <h1>Nuevo dominio</h1>      
      </div>


      <ol class="breadcrumb">
            <li>
                  {{ HTML::linkRoute('dominio/inicio','Inicio') ;}} 
            </li>            
            <li class="active">
                  Nuevo dominio
            </li>            
      </ol>

      <p>Empezaremos por agregar un dominio</p>
      <p>Para hacerlo comprueba primero que el nombre que quieres utilizar exista</p>
      <p>Debe ser de la forma <i>ejemplo.com</i></p>


      {{ Form::open(array('route'=>'dominio/confirmar','method'=>'get')) }}
      <div class="form-group">                       
            <div class="input-group">                        
                  <input type="text" class="form-control" id="dominio" name="dominio" placeholder="Escribir el nombre del dominio">
                  <span class="input-group-btn">
                        <button class="btn btn-info" id="comprobar" type="button">Checar Disponibilidad</button>
                  </span>
            </div><!-- /input-group -->
      </div>
      <button type="submit" id="crear" class="btn btn-success" disabled='disabled'>Crear Dominio</button>
      {{ Form::close() }}
</div>

@stop

@section('footer')
@parent
@stop


@section('scripts')
<script>
      $('#comprobar').click(function() {
            $('#crear').removeAttr('disabled');
            $('#crear').addClass('btn-success');
      });
</script>

@stop