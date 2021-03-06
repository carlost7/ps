@extends('layouts.master')

@section('title')
@parent

- Usuarios {{ $usuario->email }} 
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('admin.usuarios.index','Usuarios') }} > {{ $usuario->email }}</h2>
      </div>
</div>
<div class="container">

      <ul>
            <li>Usuario: {{ $usuario->email }}</li>
            <li>Nominio: {{ $usuario->dominio->dominio }}</li>
            <li>Plan: {{ $usuario->dominio->plan->nombre }}</li>
            <li>N. Correos: {{ $usuario->dominio->correos->count() }}</li>
            <li>N. Ftps: {{ $usuario->dominio->ftps->count() }}</li>
            <li>N. Database: {{ $usuario->dominio->dbs->count() }}</li>
      </ul>      

</div>

<div class="container">
      
            <p>{{ $usuario->email }}</p>
            <p>{{ HTML::linkRoute('admin.correos.index','correos') }}</p>
            <p>{{ HTML::linkRoute('admin.ftps.index','ftps') }}</p>
            <p>{{ HTML::linkRoute('admin.dbs.index','Bases de datos') }}</p>
      
</div>      


@stop

