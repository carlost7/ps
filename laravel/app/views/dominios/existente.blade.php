@extends('layouts.master')

@section('title')
@parent

| Dominio Existente
@stop



@section('content')
<div class="container">

      <div class="page-header">
            <h1>Dominio Existente</h1>      
      </div>


      <ol class="breadcrumb">
            <li>
                  {{ HTML::linkRoute('dominio/inicio','Inicio') ;}} 
            </li>            
            <li class="active">
                  Dominio existente
            </li>            
      </ol>

      <p>Si ya tienes un dominio, escribelo en el cuadro de texto</p>
      {{ Form::open(array('route'=>'dominio/confirmar','method'=>'get')) ;}}
      <div class="form-group">                       
            <input type="text" class="form-control" id="dominio" name="dominio" placeholder="Escribir el nombre del dominio">
      </div>
      <button type="submit" id="crear" class="btn btn-primary">Agregar Dominio</button>
      {{ Form::close() }}
</div>

@stop

@section('footer')
@parent
@stop

