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
                        @if($quotas[$correo->correo]!=null)
                        <tr>

                              <td>{{ $correo->nombre }}</td>
                              <td>{{ HTML::link('admin/correos/'.$correo->id,$correo->correo) }}</td>
                              <td>{{ $correo->redireccion }}</td>
                              <td>{{ $quotas[$correo->correo]['diskused'].'Mb / '.$quotas[$correo->correo]['diskquota'].'Mb'}}</td>
                              <td>{{ HTML::link('admin/correos/'.$correo->id,'Mostrar',array('class'=>'btn btn-primary btn-xs')) }}</td>
                              <td>{{ HTML::link('admin/correos/'.$correo->id.'/edit','Editar',array('class'=>'btn btn-primary btn-xs')) }}</td>
                              <td>
                                    {{ Form::open(array('route' => array('admin.correos.destroy',$correo->id),'method'=>'DELETE')) }}
                                    {{ Form::submit('Eliminar', array('class' => 'btn btn-danger btn-xs')) }}
                                    {{ Form::close() }}
                              </td>                        
                        </tr>
                        @endif
                        @endforeach
                  </table>
                  @if($correos)
                        {{ $correos->links(); }}
                  @endif
            </div>
      </div>
</div>
<div class="container">
      <div class="row">
            <div class="col-xs-6">
                  <h3>Segura SSL / TLS (Recomendado)</h3>
                  <ul>
                        <li>
                              Usuario: &GT;Correo&Lt; 
                        </li>
                        <li>
                              Servidor entrante: rs4.websitehostserver.net
                              <br>
                              Puerto POP3: 995
                        </li>
                        <li>
                              Servidor de correo saliente: 	rs4.websitehostserver.net
                              Puerto SMTP: 465
                        </li>
                        <li>
                              <strong>Es necesaria la autenticación para POP3 y SMTP.</strong>
                        </li>
                  </ul>
            </div>
            <div class="col-xs-6">
                  <h3>Segura SSL / TLS (Recomendado)</h3>
                  <ul>
                        <li>
                              Usuario: &GT;Correo&Lt; 
                        </li>
                        <li>
                              Servidor entrante: mail.leitmedic.com 
                              <br>
                              Puerto POP3: 110
                        </li>
                        <li>
                              Servidor de correo saliente: mail.leitmedic.com 
                              Puerto SMTP: 26
                        </li>
                        <li>
                              <strong>Es necesaria la autenticación para POP3 y SMTP.</strong>
                        </li>
                  </ul>
            </div>
      </div>
</div>


@stop
