<style>
  #modal-create-screening label span {
    color:red;
  }
</style>
<div id="modal-create-screening" class="modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Crear Screening</h4>
      </div>
      <div class="modal-body">
        <div style="text-align:right;">
          <label><span>*</span> Campos obligatorios</label>
        </div>
        <?php echo form_open('employer/screening/search/do_screening', ['id'=> 'form-create-screening', 'method' => 'post']); ?>
            <input type="hidden" name="document_number" required value="<?php echo $document_number; ?>">
            <label for="">Tipo <span>*</span></label>
            <select name="type" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="1">Básico</option>
                <option value="2">Integral</option>
            </select>
            <br>
            <label for="">Puesto <span>*</span></label>
            <input type="text" name="job_title" class="form-control" required maxlength="140">
            <br>
            <label>Tipo Egreso <span>*</span></label> 
            <select class="form-control"
                    name="type_expense"
                    required
                    >
                <option value="">Seleccione</option>
                <option value="EI">EI</option>
                <option value="EONF">EONF</option>
                <option value="EOF">EOF</option>
                <option value="EOFDP">EOFDP</option>
                <option value="EODT">EODT</option>
            </select>
            <br>
            <label>Centro de costo <span>*</span></label>
            <select id="cost_centers" 
                    name="cost_center" 
                    class="form-control" 
                    required
                    style="width:100%">
              <option value="">Seleccione</option>

              <?php foreach ($cost_centers as $row): ?>
                <option value="<?php echo $row->COD_CCOSTO; ?>">
                  <?php echo $row->COD_CCOSTO; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <br>
            <br>
            <label for="">Estructura de costo (código)</label>
            <input type="text" name="eecc_code" class="form-control" maxlength="20">
            <br>
            <label for="">Centro de costo cliente</label>
            <input type="text" name="cost_center_client" class="form-control" maxlength="50">
            <br>
            <br>
            <div align="center">
                <button type="submit" class="btn btn-primary">Crear</button>
            </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>

<script type="module">
$(function(){
  $( "#btn-create-screening" ).click(function(){
    $( '#modal-create-screening' ).modal('show');
  });

  $( '#form-create-screening' ).submit(function(e){
    e.preventDefault();

    if (!window.confirm("¿Está seguro el sreening?")) {
      return false;
    }

    $( '#modal-create-screening .modal-content' ).addClass('load load-image');
    const form = $(this);
    const btn = $(this).find('button[type="submit"]');
    btn.prop('disabled', true);
    btn.html("Generando...");
    
    const url = $(this).prop('action');
    const data = $(this).serialize();

    $.post(url, data, function(response) {
      if (response.status) {
        window.location.reload();
      }

      if (!response.status) {
        toastr["error"](response.message);
        $( '#modal-create-screening .modal-content' ).removeClass('load load-image');
        btn.prop('disabled', false);
        btn.html("Crear");
      }
    }, 'json');

    return false;
  });
});
</script>