@extends('layouts.master')

@section('title')
@parent

| Costo Planes
@stop



@section('content')

<div class="jumbotron">
      <div class="container">
            <h3>Agregar Costo Plan</h3>
      </div>
</div>
<div class="container">

      {{ Form::open(array('route'=>'admin.costo_planes.store','id'=>'form_confirm')) }}

      @foreach($errors->all() as $message)
      <div class="alert alert-danger">{{ $message }}</div>
      @endforeach

      <input type="hidden" name="plan_id" value="{{ $plan->id }}" />
      
      <div class="form-group">
            <label for="nombre">Nombre plan</label>
            <input type="text" name="nombre" value="{{ $plan->nombre }}" class="form-control" id="nombre" disabled="disabled" >
      </div>
      <div class="form-group">
            <label for="costo_mensual">Costo Mensual</label>
            <input type="text" name="costo_mensual" value="{{ Input::old('nombre')}}" class="form-control" id="Costo_mensual" >
      </div>
      <div class="form-group">
            <label for="costo_anual">Costo Anual</label>
            <input type="text" name="costo_anual" value="{{ Input::old('costo_anual')}}" class="form-control" id="Costo_anual" >
      </div>
      <div class="form-group">
            <label for="moneda">Moneda</label>
            <input type="text" name="moneda" value="{{ Input::old('moneda')}}" class="form-control" id="Moneda" >
      </div>      
      <button type="submit" id='confirmar' class="btn btn-success">Crear Plan</button>
      {{ Form::close() }}
</div>

@stop

