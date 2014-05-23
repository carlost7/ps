@extends('layouts.master')

@section('title')
@parent

- Correos
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('admin.usuarios.index','Usuarios') }} > Correos</h2>
            <br />
            <p>{{ HTML::linkRoute('admin.correos.create','Agregar Correo',null,array('class'=>'btn btn-primary btn-lg')) }}</p>            
      </div>
</div>
<div class="container">
      <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Lista de Correos ({{$total.'/'.Session::get('dominio_usuario')->plan->numero_correos }})</div>

            <div class="table-responsive">
                  <table class="table">
                        <tr>
                              <th>Nombre</th>
                              <th>Correo</th>                    
                              <th>Redireccion</th>
                              <th>Espacio Utilizado</th>
                              <th>Mostrar</th>
                              <th>Editar</th>
                              <th>Eliminar</th>
                        </tr>
                        @foreach($correos as $correo)
                        <tr>

                              <td>{{ $correo->nombre }}</td>
                              <td>{{ HTML::link('admin/correos/'.$correo->id,$correo->correo) }}</td>
                              <td>{{ $correo->redireccion }}</td>
                              <td>{{ $quotas[$correo->correo]['diskused'].'Mb / '.$quotas[$correo->correo]['diskquota'].'Mb'}}</td>
                              <td>{{ HTML::link('admin/correos/'.$correo->id,'Mostrarr',array('class'=>'btn btn-primary btn-xs')) }}</td>
                              <td>{{ HTML::link('admin/correos/'.$correo->id.'/edit','Editar',array('class'=>'btn btn-primary btn-xs')) }}</td>
                              <td>
                                    {{ Form::open(array('route' => array('admin.correos.destroy',$correo->id),'method'=>'DELETE')) }}
                                    {{ Form::submit('Eliminar', array('class' => 'btn btn-danger btn-xs')) }}
                                    {{ Form::close() }}
                              </td>                        
                        </tr>
                        @endforeach
                  </table>
            </div>
      </div>
</div>



@stop
