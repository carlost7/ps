@extends('layouts.master')

@section('title')
@parent

- FTP
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>FTP's</h2>
            <p>Aqui encontrarás una lista de todos los ftp's</p>
            @if($total < Session::get('dominio')->plan->numero_ftps)
            <p>Da click en el boton si quieres agregar uno nuevo</p>
            <p>{{ HTML::linkRoute('ftps.create','Agregar Ftp',null,array('class'=>'btn btn-primary btn-lg')) }}</p>
            @endif
      </div>
</div>
<div class="container">
      <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Lista de Ftps ({{$total.'/'.Session::get('dominio')->plan->numero_ftps }})</div>


            @if($ftps->count())
            <!-- Table -->
            <div class="table-responsive">
                  <table class="table">
                        <tr>
                              <th>Nombre de usuario</th>
                              <th>Host</th>
                              <!--<th>Directorio</th>-->
                              <th>Puerto</th>
                              <th>Editar</th>                              
                        </tr>
                        @foreach($ftps as $ftp)
                        <tr>
                              <td>{{ $ftp->username }}</td>
                              <td>{{ $ftp->hostname }}</td>
                              <!--<td>{{ $ftp->homedir }}</td>-->
                              <td>21</td>
                              <td>{{ HTML::link('ftps/'.$ftp->id.'/edit','Editar',array('class'=>'btn btn-primary btn-xs')) }}</td>                                                      
                        </tr>
                        @endforeach
                  </table>
            </div>

            @else
            <p>Aún no hay Ftp's</p>
            <br />
            <p>Para agregar un nuevo ftp, solo da click en el boton</p>
            <p>{{ HTML::linkRoute('ftps.create','Agregar Ftp',null,array('class'=>'btn btn-primary btn-lg')) }}</p>
            @endif
      </div>
</div>
<div class="container">
      <div class="row">
            <div class="col-xs-12">
                  <h2>Programas recomendados</h2>
                  <ul>
                        <li>
                              <h3><a href="https://filezilla-project.org/" target="_blank" >Filezilla</a></h3>
                        </li>
                        <li>
                              <h3><a href="http://www.coreftp.com/download.html" target="_blank">Core Ftp</a></h3>
                        </li>
                        <li>
                              <h3><a href="http://cyberduck.io/">CyberDuck</a></h3>
                        </li>
                  </ul>
                  
            </div>
      </div>
</div>

@include('layouts.menu_usuario', array('activo'=>'correos'))

@stop

@section('footer')@stop