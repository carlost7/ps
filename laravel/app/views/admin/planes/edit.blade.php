@extends('layouts.master')

@section('title')
@parent

- Plan {{ $plan->nombre }} 
@stop



@section('content')

<div class="jumbotron">
      <div class="container">
            <h3>Agregar Plan</h3>
      </div>
</div>
<div class="container">

      {{ Form::model($plan,, array('url'=> array('admin/planes/'.$correo->id),'method'=>'PUT')) }}

      @foreach($errors->all() as $message)
      <div class="alert alert-danger">{{ $message }}</div>
      @endforeach

      <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" value="{{ $nombre }}" class="form-control" id="nombre" >
      </div>
      <div class="form-group">
            <label for="domain">Domain</label>
            <input type="text" name="domain" value="{{ $domain }}" class="form-control" id="Dominio" >
      </div>
      <div class="form-group">
            <label for="name_server">Name server</label>
            <input type="text" name="name_server" value="{{ $name_server }}" class="form-control" id="Name_Server" >
      </div>
      <div class="form-group">
            <label for="numero_correos">Numero correos</label>
            <input type="text" name="numero_correos" value="{{ $numero_correos }}" class="form-control" id="Numero_correos">
      </div>
      <div class="form-group">
            <label for="quota_correos">Quota correos</label>
            <input type="text" name="quota_correos" value="{{ $quota_correos }}" class="form-control" id="Quota_correos">
      </div>
      <div class="form-group">
            <label for="numero_ftps">Numero ftps</label>
            <input type="text" name="numero_ftps" value="{{ $numero_ftps }}" class="form-control" id="Numero_ftps">
      </div>
      <div class="form-group">
            <label for="quota_ftps">Quota ftps</label>
            <input type="text" name="quota_ftps" value="{{ $quota_ftps }}" class="form-control" id="Quota_ftps">
      </div>
      <div class="form-group">
            <label for="numero_dbs">Numero dbs</label>
            <input type="text" name="numero_dbs" value="{{ $numero_dbs }}" class="form-control" id="Numero_dbs">
      </div>
      <div class="form-group">
            <label for="quota_dbs">Quota dbs</label>
            <input type="text" name="quota_dbs" value="{{ $quota_ftps }}" class="form-control" id="Quota_dbs">
      </div>      
      <button type="submit" id='confirmar' class="btn btn-success">Confirmar Dominio</button>
      {{ Form::close() }}
</div>

@stop


