@extends('layouts.master')

@section('title')
@parent

| Dominios
@stop



@section('content')
<div class="container">

      <div class="page-header">
            <h1>Dominio</h1>            
      </div>

      <ol class="breadcrumb">
            <li class="active">
                  Inicio
            </li>            
      </ol>

      <h2>Empezaremos por lo básico.</h2>
      <ul>
            <li>
                  <p>Necesitas un dominio para poder estar en internet.</p>      
            </li>
            <li>
                  <p>Un dominio es un nombre que le permite a las personas saber quien eres</p>
            </li>
            <li>
                  <p>El dominio es del tipo <i>dominio.com</i></p>      
            </li>
      </ul>

      <p>Si aún no tienes un dominio da click para conseguirlo</p>
      {{ HTML::linkRoute('dominio/nuevo','Crear nuevo dominio',null,array('class'=>'btn btn-primary btn-lg')) }}

      <p>Si ya tienes un dominio da click aquí para utilizarlo</p>
      {{ HTML::linkRoute('dominio/existente','Ya tengo un dominio',null,array('class'=>'btn btn-primary btn-lg')) }}
</div>

@stop

@section('footer')
.@parent
@stop
