@extends('layouts.master')

@section('title')
@parent

- Plan {{ $plan->nombre }} 
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('admin.planes.index','Planes') }} > {{ $plan->nombre }}</h2>
      </div>
</div>
<div class="container">

      <ul>
            <li>Nombre:  {{ $plan->nombre }}</li>
            <li>Nominio: {{ $plan->domain }}</li>
            <li>Name server: {{ $plan->name_server }}</li>
            <li>N. Correos: {{ $plan->numero_correos }}</li>
            <li>Q. Correos: {{ $plan->quota_correos }}</li>
            <li>N. Ftps: {{ $plan->dominio->numero_ftps }}</li>
            <li>Q. Ftps: {{ $plan->dominio->quota_ftps }}</li>
            <li>N. Database: {{ $plan->numero_dbs }}</li>
            <li>Q. Database: {{ $plan->quota_dbs }}</li>
      </ul>      
      
</div>



@stop

