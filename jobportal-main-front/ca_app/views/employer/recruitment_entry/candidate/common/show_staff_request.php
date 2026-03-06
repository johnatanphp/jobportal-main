<div style="padding: 10px 0;">
  <div class="candidate-section-content">
    <h4 class="candidate-section-content-title">
      Detalle de la solicitud de personal
    </h4>
  </div>
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
</div> 
<?php $this->load->view('general/staff_request/common/modal_show_assigned_employers'); ?>
</html>