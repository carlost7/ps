@extends('layouts.master')

@section('title')
@parent

- Editar Costo Plan {{ $costo_plan->plan->nombre }} <
@stop



@section('content')

<div class="jumbotron">
      <div class="container">
            <h3>Editar Plan {{$costo_plan->plan->nombre}}</h3>
      </div>
</div>
<div class="container">

      {{ Form::model($costo_plan, array('url'=>array('admin/costos_planes/'.$costo_plan->id),'method'=>'PUT')) }}
      

      @foreach($errors->all() as $message)
      <div class="alert alert-danger">{{ $message }}</div>
      @endforeach

      
      
      <div class="form-group">
            <label for="costo_mensual">Costo Mensual</label>
            <input type="text" name="costo_mensual" value="{{ $costo_plan->costo_mensual }}" class="form-control" id="Costo_mensual" >
      </div>
      <div class="form-group">
            <label for="costo_anual">Costo Anual</label>
            <input type="text" name="costo_anual" value="{{ $costo_plan->costo_anual}}" class="form-control" id="Costo_anual" >
      </div>
      <div class="form-group">
            <label for="moneda">Moneda</label>
            <input type="text" name="moneda" value="{{ $costo_plan->moneda}}" class="form-control" id="Moneda" >
      </div>      
      <button type="submit" id='confirmar' class="btn btn-success">Editar Plan</button>
      {{ Form::close() }}
</div>

@stop


