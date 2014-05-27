@extends('layouts.master')

@section('title')
@parent

- {{ $ftp->username }} 
@stop



@section('content')
<div class="jumbotron">
      <div class="container">
            <h2>{{ HTML::linkRoute('ftps.index','Ftps') }} > {{ $ftp->username }}</h2>
      </div>
</div>
<div class="container">

      <h1>Aqui se mostrarán los datos del Ftp</h1>

      <ul>
            <li>
                  {{ $ftp->username }}
            </li>
            <li>
                  {{ $ftp->homedir }}
            </li>
      </ul>


</div>

@stop

