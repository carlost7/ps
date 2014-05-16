<!-- Modal -->
<div class="modal fade" id="ModalPassword" tabindex="-1" role="dialog" aria-labelledby="ModalPassword" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="ModalPasswordTitle">Generar Contraseña</h3>
      </div>
      <div class="modal-body">
            <p>Copia la siquiente contraseña en un lugar seguro</p>
            <h3 id='Usarpass'></h3>            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="get_password()">Generar Otra</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id='SelectedPassword' onclick="usar_password()" >Usar Contraseña</button>
      </div>
    </div>
  </div>
</div>