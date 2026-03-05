
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <?php echo form_open('employer/staff_requests/reassign_employer/', array('id' => 'form-assign-employer')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reasignación de la solicitud</h4>
      </div>  
      <div class="modal-body">
        <div class="formint">
          <div class="alert alert-info">
            <i class="glyphicon glyphicon-info-sign"></i> Para asignar o cambiar un empleador a la solicitud ingrese el nombre o correo en el campo de búsqueda y seleccione.
          </div>
          <input type="hidden" name="request_id" value="<?php echo $request->ID; ?>">
          <div>
            <label style="display: block;">Asignars a <span>*</span></label>
            <select id="assignment-employer" name="assignment_employers[]" class="form-control" multiple="multiple" style="display: block;width: 100%;">
              <option value="">Seleccione</option>
              <?php foreach($result_employers as $row_employer): ?>
                <?php $employer_selected = isset($assigned_employers[$row_employer->ID]) ? 'selected="selected"' : ''; ?>
                <option value="<?php echo $row_employer->ID; ?>" <?php echo  $employer_selected; ?>>
                  <?php echo $row_employer->first_name . ' ' . $row_employer->last_name . ' (' . $row_employer->email . ')'; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>    
          <div>
            <br />
            <label style="display: block;">Incluir Jefe inmediatos / Coordinadores para hacer seguimiento</label>
            <select id="tracing-employers" name="tracing_employers[]" class="form-control" multiple="multiple" style="display: block;width: 100%;">
              <?php foreach($result_employers as $row_employer): ?>
                <?php $employer_selected = isset($tracing_employers[$row_employer->ID]) ? 'selected="selected"' : ''; ?>
                <option value="<?php echo $row_employer->ID; ?>" <?php echo  $employer_selected; ?>>
                  <?php echo $row_employer->first_name . ' ' . $row_employer->last_name . ' (' . $row_employer->email . ')'; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <br>
            <label style="display: block;">Gestor de programaciones <span></span></label>
            <select id="exam-request-employers" 
                    name="exam_request_employers[]" 
                    class="form-control" 
                    multiple="multiple" 
                    style="display: block;width: 100%;">
              <option value="">Seleccione</option>
              <?php foreach($result_exam_request_employers as $row_employer): ?>
                <?php $employer_selected = isset($exam_request_employers[$row_employer->ID]) ? 'selected="selected"' : ''; ?>
                <option value="<?php echo $row_employer->ID; ?>" <?php echo  $employer_selected; ?>>
                  <?php echo $row_employer->first_name . ' ' . $row_employer->last_name . ' (' . $row_employer->email . ')'; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <br />
          <div>
            <label style="display: block;">
              Motivo de la reasignación<span>*</span>
            </label>
            <textarea class="form-control" name="reason_reassing"></textarea>
          </div>
        </div>
      
      </div>
      <div class="modal-footer">
        <div>
          <input type="submit" class="btn btn-primary pull-right" value="Guardar">
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

  <script type="text/javascript">
    $(function() {

      function assignEmployer(form) {
        const employers = $( 'select[name="assignment_employers[]"]', form).val();
        const employerSelectedMax = "<?php echo $this->config->item('staff_request_assignment_employers_limit'); ?>";

        if (!employers || employers.length == 0) {
          toastr["error"](`Debe seleccionar al menos 1 empleador`);
          return false;
        }

        if (employers.length > employerSelectedMax) {
          toastr["error"](`Solo puede asignar hasta ${employerSelectedMax} empleadores a la solicitud`);
          return false;
        }

        if ($.trim($( 'textarea[name="reason_reassing"]', form).val()) == '') {
          toastr["error"](`Motivo debe ser ingresado`);
          return false;
        }

        const data = $(form).serialize();
        const url = $(form).attr('action');
        const btnSubmit = $(form).find('input[type="submit"]');
        btnSubmit.prop('disabled', true);

        $.post(url, data, function(response) {
          
          var status = response.status;
        
          if (status) {
              window.location.reload();
          } else {
            btnSubmit.prop('disabled', false);
            toastr["error"](response.message);
          }

        }, 'json').fail(function(){
          btnSubmit.prop('disabled', false);
          toastr["error"]("Ha ocurrido un error.");
        });
      }

      $( "#form-assign-employer" ).submit(function(e){
        e.preventDefault();
        assignEmployer(this);
        return false;
      });

      $( '#tracing-employers' ).select2();
      $( '#assignment-employer' ).select2();
      $( '#exam-request-employers').select2();
    });
  </script>

