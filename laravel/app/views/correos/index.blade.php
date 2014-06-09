@extends('layouts.master')

@section('title')
@parent

- Correos
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>Correos</h2>
            <p>Aqui encontrarás una lista de todos los correos</p>
            @if($total < Session::get('dominio')->plan->numero_correos)
            <p>Da click en el boton si quieres agregar uno nuevo</p>
            <p>{{ HTML::linkRoute('correos.create','Agregar Correo',null,array('class'=>'btn btn-primary btn-lg')) }}</p>
            @endif
      </div>
</div>
<div class="container">
      <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Lista de Correos ({{$total.'/'.Session::get('dominio')->plan->numero_correos }})</div>


            @if($correos->count())
            <!-- Table -->
            <div class="table-responsive">
                  <table class="table">
                        <tr>
                              <th>Nombre</th>
                              <th>Correo</th>                    
                              <th>Redireccion</th>
                              <th>Espacio Utilizado</th>
                              <th>Ingresar</th>
                              <th>Editar</th>
                              <th>Eliminar</th>
                        </tr>
                        @foreach($correos as $correo)
                        @if($quotas[$correo->correo]!=null)
                        <tr>                              
                              <td>{{ $correo->nombre }}</td>
                              <td>{{ HTML::link('correos/'.$correo->id,$correo->correo) }}</td>
                              <td>{{ $correo->redireccion }}</td>                                                            
                              <td>{{ $quotas[$correo->correo]['diskused'].'mb / '.$quotas[$correo->correo]['diskquota'].'mb'}}</td>                              
                              <td>{{ HTML::link('https://rs4.websitehostserver.net:2096/?locale=es','Webmail') }}</td>
                              <td>{{ HTML::link('correos/'.$correo->id.'/edit','Editar',array('class'=>'btn btn-primary btn-xs')) }}</td>
                              <td>
                                    {{ Form::open(array('route' => array('correos.destroy',$correo->id),'method'=>'DELETE','id'=>$correo->id)) }}
                                    <input type="submit" value="Eliminar" name="eliminar" class='btn btn-danger btn-xs' onclick="confirmDelete('{{$correo->correo}}','{{$correo->id}}')"/>
                                    {{ Form::close() }}
                              </td>                        
                        </tr>
                        @endif
                        @endforeach
                  </table>
            </div>

            @else
            <p>Aún no hay correos</p>
            <br />
            <p>Para agregar un nuevo correo, solo da click en el boton</p>
            <p>{{ HTML::linkRoute('correos.create','Agregar Correo',null,array('class'=>'btn btn-primary btn-lg')) }}</p>
            @endif
      </div>
</div>

@include('layouts.menu_usuario', array('activo'=>'correos'))

@stop

@section('scripts')
{{ HTML::script('js/vendor/bootbox.min.js') }}

<script>
      function confirmDelete(email,id){
            bootbox.confirm("Estas seguro que deseas eliminar la cuenta "+email, function(result) {
                  if(result){
                        $('#'+id).submit();
                  }
            });
            return false;
      }
</script>
@stop