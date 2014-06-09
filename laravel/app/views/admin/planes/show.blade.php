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
            <li>N. Ftps: {{ $plan->numero_ftps }}</li>
            <li>Q. Ftps: {{ $plan->quota_ftps }}</li>
            <li>N. Database: {{ $plan->numero_dbs }}</li>
            <li>Q. Database: {{ $plan->quota_dbs }}</li>
      </ul>    
      
      <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Lista de Planes</div>
      
      <div class="table-responsive">
            <h2>Costo de los planes</h2>
                  <table class="table">
                        <tr>
                              <th>Costo Mensual</th>
                              <th>Costo Anual</th>
                              <th>Moneda</th>                              
                              <th>Editar</th>
                              <th>Eliminar</th>
                        </tr>
                        @if($costo_plan->count())

                        @foreach($costo_plan as $costo)
                        <tr>

                              <td>{{$costo->costo_mensual}}</td>
                              <td>{{$costo->costo_anual}}</td>
                              <td>{{$costo->moneda}}</td>                              
                              <td>{{ HTML::link('admin/costos_planes/'.$costo->id.'/edit','Editar',array('class'=>'btn btn-primary btn-xs')) }}</td>
                              <td>
                                    {{ Form::open(array('route' => array('admin.costos_planes.destroy',$plan->id),'method'=>'DELETE')) }}
                                    {{ Form::submit('Eliminar', array('class' => 'btn btn-danger btn-xs')) }}
                                    {{ Form::close() }}
                              </td>                        
                        </tr>
                        @endforeach

                        @endif
                  </table>
                  
            <p>{{ HTML::link('admin/costos_planes/'.$plan->id.'/add_costo','Agregar Nuevo Costo',array('class'=>'btn btn-primary btn-lg')) }}</p>  
            
            </div>
      </div>

</div>



@stop

