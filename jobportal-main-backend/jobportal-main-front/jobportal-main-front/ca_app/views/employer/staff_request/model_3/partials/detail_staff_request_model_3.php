<?php 
  $is_session_recruiter = $this->session->userdata('current_profile_id') == 2;
?>
<div>
  <style type="text/css">

    #content-staff-request-form,
    #content-job-profile-staff-request {
      margin-top: 35px;
    }
    
    .tab-content a:hover, .tab-content a:active, .tab-content a:focus {
        outline: none;
        border: 0;
    }
    
    .nav-tabs li a {
      border-top-left-radius: 0;
    }

    #tbl-working-hours tr td {
      padding: 5px;
    }

    #edit-profile-resources {
      display: none;
    }
  </style>
  <!-- Start head request -->
  <div>
     <?php $this->load->view('general/staff_request/common/header_staff_request'); ?>
  </div>
  <div class="content-menu-selection">
    <ul class="nav nav-tabs">
      <li class="active">
        <a data-toggle="tab" href="#content-staff-request-form" >Datos de la Solicitud</a>
      </li>
      <?php if (!empty($request->job_profile_ID)): ?>
        <li>
          <a data-toggle="tab" href="#content-job-profile-staff-request">Perfil de puesto</a>
        </li>
      <?php endif; ?>
      <?php if (!empty($request->job_layout_id)): ?>
        <li>
          <a data-toggle="tab" href="#content-job-layout-staff-request">Layout de puesto</a>
        </li>
      <?php endif; ?>

      <?php foreach ($request_gantt as $gantt_row): ?>
        <li>
          <a data-toggle="tab" href="#content-gantt-<?php echo $gantt_row->type_id; ?>">Gantt de <?php e(strtolower($gantt_row->gantt_type_name)); ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="tab-content">
    <div id="content-staff-request-form" class="tab-pane fade in active">
      <?php $this->load->view('employer/staff_request/model_3/partials/staff_request_model_3'); ?>
    </div>
    <?php if (!empty($request->job_profile_ID)): ?>
      <div id="content-job-profile-staff-request" class="tab-pane fade">
          <?php $this->load->view('employer/job_profile/common/job_profile_detail'); ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($request->job_layout_id)): ?>
      <div id="content-job-layout-staff-request" class="tab-pane fade">
        <?php $this->load->view('employer/job_layouts/common/job_layout_detail'); ?>
      </div>
    <?php endif; ?>

    <?php foreach ($request_gantt as $gantt_row): ?>
      <div id="content-gantt-<?php echo $gantt_row->type_id; ?>" class="tab-pane fade">
        <?php $this->load->view('general/staff_request/common/gantt_activities', ['gantt_row' => $gantt_row]); ?>
      </div>
    <?php endforeach; ?>

  </div>
</div>
