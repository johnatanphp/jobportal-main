<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Detalle solicitud</h4>
</div>
<div class="modal-body">
  <div>
    <div class="wrapper-request">
      <?php if ($request->request_model_id == 1): ?>
      <?php $this->load->view('general/staff_request/common/detail_internal_staff_request'); ?>
      <?php endif; ?>

      <?php if ($request->request_model_id == 2): ?>
      <?php $this->load->view('general/staff_request/common/detail_external_staff_request'); ?>
      <?php endif; ?>

      <?php if ($request->request_model_id == 3): ?>
      <?php $this->load->view('employer/staff_request/model_3/partials/detail_staff_request_model_3'); ?>
      <?php endif; ?>

      <?php if ($request->request_model_id == 4): ?>
      <?php $this->load->view('employer/staff_request/model_4/common/detail_staff_request_model_4'); ?>
      <?php endif; ?>
    </div>    
  </div> 
  <?php $this->load->view('general/staff_request/common/modal_show_assigned_employers'); ?>
</div>    
      