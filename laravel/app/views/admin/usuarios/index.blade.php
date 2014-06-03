@extends('layouts.master')

@section('title')
@parent

- Usuarios
@stop



@section('content')
<div class="container">
      <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Lista de Usuarios</div>

            <div class="table-responsive">
                  <table class="table">
                        <tr>
                              <th>Nombre de usuario</th>
                              <th>Correo</th>
                              <th>Dominio</th>                              
                              <th>Plan</th>
                              <th>Agregar Elementos</th>
                              <th>Editar</th>
                              <th>Eliminar</th>
                        </tr>
                        @if($usuarios->count())

                        @foreach($usuarios as $usuario)
                        <tr>

                              <td>{{ $usuario->username }}</td>
                              <td>{{ HTML::link('admin/usuarios/'.$usuario->id,$usuario->email) }}</td>
                              
                              @if($usuario->dominio != null)
                              <td>{{ $usuario->dominio->dominio }}</td>
                              <td>{{ $usuario->dominio->plan->nombre }}</td>
                              <td>{{ HTML::link('admin/usuarios/'.$usuario->id.'/edit','Agregar',array('class'=>'btn btn-primary btn-xs')) }}</td>
                              
                              @else
                              <td>pendiente</td>
                              <td>pendiente</td>
                              <td>pendiente</td>                             
                              
                              @endif
                              <td>{{ HTML::link('admin/usuarios/'.$usuario->id.'/edit','Editar',array('class'=>'btn btn-primary btn-xs')) }}</td>
                              <td>
                                    {{ Form::open(array('route' => array('admin.usuarios.destroy',$usuario->id),'method'=>'DELETE')) }}
                                    {{ Form::submit('Eliminar', array('class' => 'btn btn-danger btn-xs')) }}
                                    {{ Form::close() }}
                              </td>                        
                        </tr>
                        @endforeach

                        @endif
                  </table>
            </div>
      </div>

      <p>{{ HTML::linkRoute('admin.usuarios.create','Agregar Usuario',null,array('class'=>'btn btn-primary btn-lg')) }}</p>

</div>



@stop

