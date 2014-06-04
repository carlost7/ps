@extends('layouts.master')

@section('title')
@parent

- Usuario {{ $usuario->email }} 
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('admin.usuarios.index','Usuarios') }} > {{ $usuario->email }}</h2>            
      </div>
</div>
<div class="container">
      
            <p>{{ $usuario->email }}</p>
            <p>{{ HTML::linkRoute('admin.correos.index','correos') }}</p>
            <p>{{ HTML::linkRoute('admin.ftps.index','ftps') }}</p>
            <p>{{ HTML::linkRoute('admin.dbs.index','Bases de datos') }}</p>
      
</div>      

@stop