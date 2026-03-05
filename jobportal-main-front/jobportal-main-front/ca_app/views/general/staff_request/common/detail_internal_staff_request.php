<?php 
  $is_session_recruiter = $this->session->userdata('current_profile_id') == 2;
?>
<style type="text/css">
	
	#table-person-requirement-1,
	#table-person-requirement-2,
	#table-person-requirement-header {
		border: 1px solid #333;
		width: 100%;
	}

	#table-person-requirement-1 tr td,
	#table-person-requirement-2 tr td,
	#table-person-requirement-header tr td {
		padding: 6px !important;
		font-size: 15px;
	}

	.header-column {
		font-weight: bold;
	}

	#table-person-requirement-2 td {
		text-align: center;
	}

	#content-mof-detail,
	#content-staff-request-form,
    #content-gantt-activities
	{
		margin-top: 35px;
	}

	#content-gantt-activities {
		overflow-y: hidden;
		overflow-x: auto;
	}

	.nav-tabs li a {
		border-top-left-radius: 0;
	}

	a:hover, a:active, a:focus {
	    outline: none;
	    border: 0;
	}

	#edit-mof-resources {
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
			<a data-toggle="tab" href="#content-staff-request-form" >Planilla de requerimiento</a>
		</li>

		<?php if ($request->mof_ID): ?>
			<li>
				<a data-toggle="tab" href="#content-mof-detail">Detalle del MOF</a>
			</li>
		<?php endif; ?>

		<?php if ($request->job_layout_id): ?>
			<li>
				<a data-toggle="tab" href="#content-job-layout-detail">Layout de puesto</a>
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
	<?php if ($request->mof_ID): ?>
		<div id="content-mof-detail" class="tab-pane fade">
			<?php $this->load->view('employer/mof/common/mof_detail'); ?>
		</div>
	<?php endif; ?>

	<?php if ($request->job_layout_id): ?>
		<div id="content-job-layout-detail" class="tab-pane fade">
			<?php $this->load->view('employer/job_layouts/common/job_layout_detail'); ?>
		</div>
	<?php endif; ?>

	<?php foreach ($request_gantt as $gantt_row): ?>
      <div id="content-gantt-<?php echo $gantt_row->type_id; ?>" class="tab-pane fade">
        <?php $this->load->view('general/staff_request/common/gantt_activities', ['gantt_row' => $gantt_row]); ?>
      </div>
    <?php endforeach; ?>

	<div id="content-staff-request-form" class="tab-pane fade in active" >

		<div style="text-align: right;padding: 10px 0;display:none;">
      	  <?php if (($request->sts_process == 'pending' || $request->sts_process == 'unassigned') && $is_session_recruiter): ?>
		      <a href="<?php echo site_url('employer/staff_request/edit_internal_staff_request/e/' . $request->ID); ?>">
		        Editar solicitud
		      </a>
	  	  <?php endif; ?>
	  	</div>

		<?php $this->load->view('general/staff_request/common/personal_requirement'); ?>
	</div>
</div>