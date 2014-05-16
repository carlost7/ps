@extends('layouts.master')

@section('title')
@parent

| Acerca de Nosotros
@stop



@section('content')
<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
      <div class="container">
            <h1>Primer Server!</h1>
            <p>Somos una nueva empresa, creamos un servicio especial para ti que quieres entrar a este gran mundo que es internet</p>
            <p>Si tienes dudas llamanos, Te ayudaremos</p>
            <p>{{ HTML::link('contacto','Contacto',array('class'=>'btn btn-primary btn-lg')) }}
      </div>
</div>
@stop

@section('footer')
@parent
@stop
