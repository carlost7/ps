@extends('layouts.master')

@section('title')
@parent

| Seleccionar Nuevo Dominio
@stop

@section('content')

<div class="jumbotron">
      <div class="container">
            <h1>
                  Selecciona un nuevo dominio
            </h1>
            <p>
                  Tu dominio anterior no pudo registrarse. pero puedes seleccionar uno nuevo.  
            </p>
      </div>
</div>
<div class="container">
      <div class="comprobacion">
            <div class="alert">                  
                  <p class="result"></p>
            </div>                        
      </div>      
      <div class="clearfix"></div>
      {{ Form::open(array('route'=>'dominio.comprar_nuevo_dominio','id'=>'form_confirm')) }}
      <div class="form-group">                       
            <div class="input-group">                  
                  <input type="text" class="form-control" value="{{$dominio_anterior}}" id="dominio" name="dominio" placeholder="Escribir el nombre del dominio que quieres utilizar">
                  <span class="input-group-btn">
                        <button class="btn btn-primary" id="Comprobar" type="button">Comprobar Disponibilidad</button>
                  </span>
            </div>
            <div class="alert" id="dominios_similares"></div>
      </div>    
      <button type="submit" id="crear" class="btn btn-success" disabled='disabled'>Elegir Dominio</button>
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

      $(document).ready(function() {
            $(".comprobacion").hide();
            $("#dominios_similares").hide();
            $('#crear').attr('disabled', true);            
      });
</script>
@stop

