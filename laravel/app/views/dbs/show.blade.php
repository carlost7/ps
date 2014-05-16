@extends('layouts.master')

@section('title')
@parent

- Correo {{ $correo->correo }} 
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('correos.index','Correos') }} > {{ $correo->correo }}</h2>
      </div>
</div>
<div class="container">

      <h1>Aqui se mostrarán los datos del correo</h1>
      
      <ul>
            <li>
                  {{ $correo->correo }}
            </li>
            <li>
                  {{ $correo->nombre }}
            </li>
      </ul>
      
      
</div>


@include('layouts.menu_usuario', array('activo'=>'correos'))

@stop

@section('footer')@stop