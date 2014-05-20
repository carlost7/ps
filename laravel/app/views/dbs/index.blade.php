@extends('layouts.master')

@section('title')
@parent

- Bases de Datos

@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>Bases de datos</h2>
            <p>Aqui encontrarás una lista de todas las bases de datos</p>
            @if($total < Session::get('dominio')->plan->numero_dbs)
            <p>Da click en el boton si quieres agregar uno nuevo</p>
            <p>{{ HTML::linkRoute('dbs.create','Agregar Base de datos',null,array('class'=>'btn btn-primary btn-lg')) }}</p>
            @endif
      </div>
</div>
<div class="container">
      <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Lista de Bases de Datos ({{$total.'/'.Session::get('dominio')->plan->numero_dbs }})</div>


            @if($dbs->count())
            <!-- Table -->
            <div class="table-responsive">
                  <table class="table">
                        <tr>
                              <th>Nombre</th>
                              <th>Usuario</th>                    
                              <th>Eliminar</th>
                        </tr>
                        @foreach($dbs as $db)
                        <tr>

                              <td>{{ HTML::link('dbs/'.$db->id,$db->nombre) }}</td>
                              <td>{{ $db->usuario }}</td>
                              <td>
                                    {{ Form::open(array('route' => array('dbs.destroy',$db->id),'method'=>'DELETE')) }}
                                    {{ Form::submit('Eliminar', array('class' => 'btn btn-danger btn-xs')) }}
                                    {{ Form::close() }}
                              </td>                        
                        </tr>
                        @endforeach
                  </table>
            </div>

            @else
            <p>Aún no hay bases de datos</p>
            <br />
            @if(Session::get('dominio')->plan->numero_dbs == 0)
            <p>Tu plan no incluye Bases de datos</p>
            @else
            <p>Da click en el boton si quieres agregar una nueva base de datos</p>
            <p>{{ HTML::linkRoute('dbs.create','Agregar Base de Datos',null,array('class'=>'btn btn-primary btn-lg')) }}</p>
            @endif            
            @endif
      </div>
</div>

@include('layouts.menu_usuario', array('activo'=>'correos'))

@stop