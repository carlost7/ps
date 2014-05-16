@extends('layouts.master')

@section('title')
@parent

| Dashboard
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h1>Página de administración</h1>
      </div>
</div>
<div class="container">
      <div class="container">


            <ul class="list-group">
                  <li class="list-group-item">{{ HTML::linkRoute('admin/usuarios','Usuarios')}}</li>
                  <li class="list-group-item">{{ HTML::linkRoute('admin/usuarios','Dominios')}}</li>
                  <li class="list-group-item">{{ HTML::linkRoute('admin/usuarios','Configuracion')}}</li>            
            </ul>

      </div>    
</div>
@stop



@section('footer')
@stop
