<div id="modal-confirm-ignore-rightful-claimants" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Inconveniente al contratar</h4>
      </div>
      <div class="modal-body">
        Ha ocurrido un inconveniente al tratar de enviar los datos de los Derechohabientes.
        <br>
        <br>
        Puede enviar la contratación sin los datos de los Derechohabientes, al realizar esta acción debe ingresar o actualizar los datos de los derechohabientes manualmente en eplani.
      </div>
      <div class="modal-footer">
        <button id="hire-candidate-ignore-rightful-claimants" type="button" class="btn btn-primary">Acepto y contratar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
<script>
$(function(){

  $( '#hire-candidate-ignore-rightful-claimants' ).click(function(){
    $( '#form-hire-candidate' ).find('input[name="ignore_rightful_claimants"]').val('1');
    $( '#form-hire-candidate' ).submit();
    $( '#modal-confirm-ignore-rightful-claimants' ).modal('hide');
  });
});
</script>