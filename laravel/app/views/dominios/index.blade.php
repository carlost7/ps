@extends('layouts.master')

@section('title')
@parent

| Inicio Primer Server
@stop

@section('content')

<div class="jumbotron">
      <div class="container">
            <h1>
                  Iniciamos
            </h1>
      </div>
</div>
<div class="container">
      <div class="comprobacion">
            <div class="alert">                  
                  <p class="result"></p>
            </div>                        
      </div>
      <div class="clearfix"></div>
      {{ Form::open(array('route'=>'dominio.datos_usuario','id'=>'form_confirm')) }}
      <div class="form-group">                       
            <div class="input-group">
                  <span class="input-group-addon">
                        <input type="checkbox" id="ajeno" name="ajeno" value="1"> Ya tengo dominio:
                  </span>
                  <input type="text" class="form-control" id="dominio" name="dominio" placeholder="Escribir el nombre del dominio que quieres utilizar">
                  <span class="input-group-btn">
                        <button class="btn btn-primary" id="Comprobar" type="button">Comprobar Disponibilidad</button>
                  </span>
            </div>
            <div class="alert" id="dominios_similares"></div>
      </div>    
      <button type="submit" id="crear" class="btn btn-success" disabled='disabled'>Crear Dominio</button>
      {{ Form::close() }}
</div>

@stop

@section('scripts')
<script>
      $('#Comprobar').click(function() {
            var dom = $('#dominio').val();
            if (dom == '') {
                  $(".comprobacion").show();
                  $(".comprobacion").addClass('alert-danger');
                  $(".result").text("Debe escribir un dominio para poder continuar");
            } else {
                  comprobar_dominio(dom, function(result) {
                        if (result['resultado']) {
                              $(".comprobacion").removeClass('alert-danger');
                              $(".comprobacion").addClass('alert-success');
                              $(".result").text(result['mensaje']);
                              $(".comprobacion").show();
                              proceedDomain();
                        } else {
                              $(".comprobacion").removeClass('alert-success');
                              $(".comprobacion").addClass('alert-danger');
                              $(".result").text(result['mensaje']);
                              addDominiosSimilares(result['dominios']);
                              $(".comprobacion").show();
                              stopDomain();
                        }
                  });
            }
      });


      function addDominiosSimilares(dominios) {

            if (dominios != null && dominios.length) {
                  listadominios = "<p>Dominios similares que podrias utilizar</p><br/>";
                  listadominios += "<ul class='list-group'>";
                  for (i = 0; i < dominios.length; i++) {
                        listadominios += "<li class='list-group-item'>";
                        listadominios += "<button type='button' class='btn btn-primary btn-sm' onclick=\"selectSimilar('"+dominios[i].toLowerCase()+"')\" >seleccionar</button> > "+dominios[i].toLowerCase()+'';
                        listadominios += "</li>";
                  }
                  listadominios += "</ul>";
                  $("#dominios_similares").html(listadominios);
                  $("#dominios_similares").show();
            }

      }

      function proceedDomain() {
            $('#crear').removeAttr('disabled');
      }

      function stopDomain() {
            $('#crear').attr('disabled', true);
      }

      function selectSimilar(dominio) {
            $("#dominios_similares").html();
            $("#dominios_similares").hide();
            $("#dominio").val(dominio);
            stopDomain();
      }

      $('#ajeno').click(function() {
            if ($('#ajeno').is(':checked')) {
                  $('#crear').removeAttr('disabled');
                  $('#crear').addClass('btn-success');
                  $('#Comprobar').attr('disabled', 'disabled');
            } else {
                  $('#crear').attr('disabled', 'disabled');
                  $('#Comprobar').removeAttr('disabled');
            }
      });

      $(document).ready(function() {
            $(".comprobacion").hide();
            $("#dominios_similares").hide();
            $('#crear').attr('disabled', true);
      });
</script>
@stop

