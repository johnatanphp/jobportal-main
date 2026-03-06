<div class="candidate-section-content">
  <h4 class="candidate-section-content-title">
    Encuestas
  </h4>
</div> 

<div class="row">
  <div class="col-md-12">     
    <?php if (count($forms) > 0): ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Encuesta</th>
            <th>Estado</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($forms as $form_row): ?>
            <tr>
              <td><?php e($form_row->name); ?></td>
              <td><?php echo $form_row->form_is_answered ? ' Respondida' : 'Sin responder'; ?></td>
              <td>
                <button data-href="<?php echo site_url('candidate/form_detail/' . $form_row->form_assignment_id . '/' . $job_id); ?>" 
                    class="btn btn-xs btn-primary btn-show-form-detail" 
                    data-form-name="<?php echo $form_row->name ; ?>" 
                    <?php echo !$form_row->form_is_answered ? 'disabled' : ''; ?>>
                  Ver
              </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <?php if (count($forms) == 0): ?>
      <div align="center">No hay encuestas</div>
    <?php endif; ?>
  </div>
</div>

<script>
$(function(){

  $(document).off('click', '.btn-show-form-detail');
  $(document).on('click', '.btn-show-form-detail', function(e){
    e.preventDefault();
  
    const formId = $(this).data('form-id');
    const url = $(this).data('href');
    const formName = $(this).data('form-name');

    $( '#modal-show-form-detail' ).load(url, function(response) {
      $(this).html(`
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">${formName}</h4>
            </div>
            <div class="modal-body">
              ${response}
            </div>
          </div>
        </div>`
      ).modal('show');
    });

    return false;
  });
});
</script>