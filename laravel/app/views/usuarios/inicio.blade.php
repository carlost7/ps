@extends('layouts.master')

@section('title')
@parent

| Dashboard
@stop



@section('content')

<div class="jumbotron">
      <div class="container">
            <h2>{{ Session::get('dominio')->dominio }}</h2>
      </div>
</div>

<div class="container">
      <p>{{ HTML::linkRoute('usuario/cambiar_password','Cambiar Contraseña',null,array('class'=>'btn btn-primary btn-lg')) }}</p>
      <p>{{ HTML::linkRoute('usuario/cambiar_correo','Cambiar Correo',null,array('class'=>'btn btn-primary btn-lg'))}}</p>      
</div>


@include('layouts.menu_usuario', array('activo'=>''))

@stop



@section('footer')
@stop
