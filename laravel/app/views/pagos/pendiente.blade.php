@extends('layouts.master')

@section('title')
@parent

| Inicio Primer Server
@stop

@section('content')

<div class="jumbotron">
      <div class="container">
            <h1>
                  :) Muchas gracias
            </h1>
            <h2>
                  El sistema esta procesando tu pago
            </h2>
            <h3>
                  En cuanto el pago este completado te enviaremos un correo con los datos de tu dominio
            </h3>
      </div>
</div>

<div class="container">
      {{ HTML::linkRoute('dominio.comprar_dominio','Comprar Dominio') }}
</div>

@stop

@section('scripts')

@stop

