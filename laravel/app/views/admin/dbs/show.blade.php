@extends('layouts.master')

@section('title')
@parent

- Base de Datos {{ $database->nombre }} 
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('dbs.index','Bases de datos') }} > {{ $database->nombre }}</h2>
      </div>
</div>
<div class="container">

      <h1>Aqui se mostrarán los datos de la base de datos</h1>

      <ul>
            <li>
                  Nombre: {{ $database->nombre }}
            </li>
            <li>
                  Usuario: {{ $database->usuario }}
            </li>
      </ul>


</div>

@stop