@extends('layouts.master')

@section('title')
@parent

| Lista de pagos
@stop

@section('content')

<div class="jumbotron">
      <div class="container">
            <h1>
                  Lista de todos los pagos del usuario
            </h1>
      </div>
</div>

<div class="container">
      <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Lista de Pagos</div>

            <div class="table-responsive">
                  <table class="table">
                        <tr>

                              <td>Tipo</td>
                              <td>Monto</td>
                              <td>Descripción</td>
                              <td>Inicio</td>
                              <td>Vencimiento</td>
                              <td>Vigente</td>
                              <td>Status</td>
                              <td>Link de pago</td>
                        </tr>
                        @if($pagos->count())

                        @foreach($pagos as $pago)
                        <tr>
                              <td>{{$pago->tipo_pago }}</td>
                              <td>{{$pago->monto . 'MXN'}}</td>
                              <td>{{$pago->descripcion }}</td>
                              <td>{{$pago->inicio }}</td>
                              <td>{{$pago->vencimiento }}</td>
                              @if($pago->activo == 1)
                              <td>Activo</td>
                              @else
                              <td>Terminado</td>
                              @endif
                              <td>{{$pago->status}}</td>
                              <td></td>
                        </tr>
                        @endforeach

                        @endif
                  </table>
            </div>
      </div>

      @stop

      @section('scripts')

      @stop

