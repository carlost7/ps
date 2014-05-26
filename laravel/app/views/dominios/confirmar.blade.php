@extends('layouts.master')

@section('title')
@parent

| Dominios
@stop



@section('content')

<div class="jumbotron">

      <div class="container">
            <h1>{{ $dominio }} casí esta listo</h1>
            <h2>Solo llena los datos para terminar</h2>
      </div>
</div>
<div class="container">

      {{ Form::open(array('route'=>'pagos/confirmar_registro','id'=>'form_confirm')) }}

      @foreach($errors->all() as $message)
      <div class="alert alert-danger">{{ $message }}</div>
      @endforeach

      <input type="hidden" name="dominio" value="{{ $dominio }}">
      <div class="form-group">
            <label for="Nombre">Nombre</label>
            <input type="text" name="nombre" value="{{ Input::old('nombre')}}" class="form-control" id="Nombre" placeholder="Escribe tu nombre">
      </div>
      <div class="form-group">
            <label for="Correo">Correo</label>
            <input type="email" name="correo" value="{{ Input::old('correo')}}" class="form-control" id="Correo" placeholder="Escribe tu correo">
      </div>
      <div class="form-group">
            <label for="password">Password</label>            
            <div class="input-group">
                  <input type="password" name="password" class="form-control" id="Password" placeholder="Contraseña">
                  <span class="input-group-btn">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#ModalPassword" onclick="get_password()">
                              Generar Contraseña
                        </button>
                  </span>                  
            </div>
      </div>
      <div class="form-group">
            <label for="password_confirmation">Confirmar</label>
            <input type="password" name="password_confirmation" class="form-control" id="Password_confirmation" placeholder="Confirma tu contraseña">
      </div>
      <div class="form-group">
            <label>Elegir plan</label>
            @foreach($planes as $plan)
            <div class="radio">
                  <label>
                        {{Form::radio('plan', $plan->id) }}
                        {{ $plan->nombre }}
                  </label>                  
            </div>                  
            @endforeach
      </div>

      <div class="form-group">
            <label>Elegir Tipo de Pago</label>
            <div class="radio">
                  <label>
                        <input type="radio" id="pagoanual" name="tipo_pago" value="anual" checked>
                        Anual 
                  </label>                  
            </div>
            <div class="radio">
                  <label>
                        <input type="radio" id="pagomensual" name="tipo_pago" value="mensual">
                        Mensual
                  </label>
            </div>       

      </div>
      <div class="form-group hidden" id="Tiemposervicio">
            <label>Tiempo de servicio</label>
            @for($i = 1; $i <= 6; $i++)
            <label class="radio-inline">
                  {{Form::radio('tiempo_servicio', $i) }}
                  {{$i}}
            </label>
            @endfor
            | meses
      </div>

      <div class="form-group">
            <div class="checkbox">
                  <label>
                        <input type="checkbox" name="aceptar" id="aceptar" value="1" checked="checked"> Aceptar los {{ HTML::LinkRoute('terminos','Terminos y condiciones') ;}}
                  </label>
            </div>
      </div>
      
      <div class="alert hidden" id="resultado"><p></p></div>
      
      <button type="submit" id='confirmar' class="btn btn-success">Confirmar Compra</button>
      {{ Form::close() }}
</div>

@include('layouts.modal_password')

@stop

@section('footer')
@parent
@stop

@section('scripts')
<script>
      $('[name="tipo_pago"]').change(function(e) {
            if ($('#pagomensual').is(':checked')) {
                  $('#Tiemposervicio').addClass('show');
                  $('#Tiemposervicio').removeClass('hidden');                  
                  obtenerCosto();
            } else {
                  $('#Tiemposervicio').addClass('hidden');
                  $('#Tiemposervicio').removeClass('show');
                  obtenerCosto();
            }
      });
      
      $('[name="tiempo_servicio"]').change(function(e){
            obtenerCosto();
      });
      
      $('[name="plan"]').change(function(e) {
            obtenerCosto();
      });


      $('#form_confirm').submit(function(e) {
            if ($('#aceptar').is(':checked')) {
                  $('#form_confirm').submit();
            } else {
                  alert('Para continuar tienes que dar click en aceptar los terminos');
                  e.preventDefault();
            }

      });

      function obtenerCosto() {

            var plan = $('[name="plan"]:checked').val();
            var tipo_pago = $('[name="tipo_pago"]:checked').val();
            var tiempo_servicio = $('[name="tiempo_servicio"]:checked').val();
            
            obtener_descripcion_costo(plan,tipo_pago,tiempo_servicio, function(result) {
                  $('#resultado').removeClass('hidden');
                  $('#resultado').addClass('show');
                  $('#resultado').text("Total: "+result['total']+" Descripción: "+result['descripcion']);
            });


      }


</script>
@stop