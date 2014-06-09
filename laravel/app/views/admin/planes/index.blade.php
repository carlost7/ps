@extends('layouts.master')

@section('title')
@parent

- Planes
@stop



@section('content')
<div class="container">
      <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Lista de Planes</div>

            <div class="table-responsive">
                  <table class="table">
                        <tr>
                              <th>Nombre</th>
                              <th>Dominio</th>
                              <th>Name_server</th>
                              <th>Numero_correos</th>
                              <th>Quota_correos</th>
                              <th>Numero_ftps</th>
                              <th>Quota_ftps</th>
                              <th>Numero_dbs</th>
                              <th>Quota_dbs</th>
                              <th>Editar</th>
                              <th>Eliminar</th>
                        </tr>
                        @if($planes->count())

                        @foreach($planes as $plan)
                        <tr>

                              <td>{{ HTML::link('admin/planes/'.$plan->id,$plan->nombre) }}</td>
                              <td>{{$plan->domain}}</td>
                              <td>{{$plan->name_server}}</td>
                              <td>{{$plan->numero_correos}}</td>
                              <td>{{$plan->quota_correos}}</td>
                              <td>{{$plan->numero_ftps}}</td>
                              <td>{{$plan->quota_ftps}}</td>
                              <td>{{$plan->numero_dbs}}</td>
                              <td>{{$plan->quota_dbs}}</td>
                              <td>{{ HTML::link('admin/planes/'.$plan->id.'/edit','Editar',array('class'=>'btn btn-primary btn-xs')) }}</td>
                              <td>
                                    {{ Form::open(array('route' => array('admin.planes.destroy',$plan->id),'method'=>'DELETE')) }}
                                    {{ Form::submit('Eliminar', array('class' => 'btn btn-danger btn-xs')) }}
                                    {{ Form::close() }}
                              </td>                        
                        </tr>
                        @endforeach

                        @endif
                  </table>
            </div>
      </div>

      <p>{{ HTML::linkRoute('admin.planes.create','Agregar Plan',null,array('class'=>'btn btn-primary btn-lg')) }}</p>

</div>



@stop

