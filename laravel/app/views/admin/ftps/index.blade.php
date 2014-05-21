@extends('layouts.master')

@section('title')
@parent

- FTP
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>FTP's</h2>
            <p>{{ HTML::linkRoute('admin.ftps.create','Agregar Ftp',null,array('class'=>'btn btn-primary btn-lg')) }}</p>
      </div>
</div>
<div class="container">
      <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Lista de Ftps ({{$total.'/'.Session::get('dominio_usuario')->plan->numero_ftps }})</div>

            <div class="table-responsive">
                  <table class="table">
                        <tr>
                              <th>Nombre de usuario</th>
                              <th>Host</th>
                              <th>Directorio</th>
                              <th>Configuración</th>
                              <th>Editar</th>                              
                        </tr>
                        @foreach($ftps as $ftp)
                        <tr>
                              <td>{{ $ftp->username }}</td>
                              <td>{{ $ftp->hostname }}</td>
                              <td>{{ $ftp->homedir }}</td>
                              <td></td>
                              <td>{{ HTML::link('admin/ftps/'.$ftp->id.'/edit','Editar',array('class'=>'btn btn-primary btn-xs')) }}</td>
                        </tr>
                        @endforeach
                  </table>
            </div>            
      </div>
</div>


@stop
