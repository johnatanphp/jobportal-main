<div id="modal-screening-report-filter" class="modal fade" role="dialog">
  <style>
    #modal-screening-report-filter table td {
      padding: 2px;
    }
  </style>
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Listado Screening filtro</h4>
      </div>
      <?php echo form_open('employer/screening/search/export', ['id' => 'form-screening-report-filter', 'method' => 'get']); ?>
        <div class="modal-body">               
          <div class="input-group">
            <table>
                <tr>
                  <td>Fecha inicio</td>
                  <td>Fecha fin</td>
                </tr>
                <tr>
                  <td>
                    <input id="start-date" type="date" name="start_date" class="form-control date-custom" required="true">
                  </td>
                  <td>
                    <input id="end-date" type="date" name="end_date" class="form-control date-custom" required="true" >
                  </td>
                </tr>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Generar</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<script type="module">
$(function(){
  $( '#form-screening-report-filter' ).submit(function(e){
    const startTime =  new Date($( '#start-date' ).val()).getTime();
    const endTime = new Date($( '#end-date' ).val()).getTime();

    if (startTime > endTime) {
      e.preventDefault();
      toastr["error"]('La fecha fin debe ser mayor o igual a la fecha de inicio');
      return false;
    }

    const diff = endTime - startTime;
    const diffMonth = diff / 2629800000;

    if (diffMonth > 2) {
      e.preventDefault();
      toastr["error"]('El rango de fecha seleccionada no debe superar los 2 meses');
      return false;
    }

    return true;
  });
});
</script>