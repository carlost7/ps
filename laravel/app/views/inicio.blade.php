@extends('layouts.master')

@section('title')
@parent
@stop


@section('menu usuario')
@parent
@stop


@section('content')
<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
      <div class="container">
            <h1>Bienvenido a Primer Server!</h1>
            <p>La forma más facil de tener tu página y correos en internet.</p>
            <p>{{ HTML::linkRoute('dominio.inicio','Iniciar',null,array('class'=>'btn btn-primary btn-lg')) }}</p>
      </div>
</div>

<div class="container">
      <!-- Example row of columns -->
      <div class="row">
            <div class="col-lg-4">
                  <h2>Beneficios</h2>
                  <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                  <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                  <h2>Es para ti</h2>
                  <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                  <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                  <h2>Costos</h2>
                  <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                  <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
            </div>
      </div>

</div> <!-- /container -->                    
@stop

@section('footer')
@parent
@stop
