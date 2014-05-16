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

      <h1>Esta página contendra tutoriales de como usar el sistema</h1>

</div>


@include('layouts.menu_usuario', array('activo'=>''))

@stop



@section('footer')
@stop
