<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('public/css/app/styles/staff_request/staff_request_detail.css?t=1595698816');?>" rel="stylesheet" type="text/css" />
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); ?>
<!--/Header-->
<div class="container detailinfo">
	<div class="row">
	<div class="col-md-3">
	<div class="dashiconwrp">
		<?php $this->load->view('employer/common/menu/sidebar');?>
	</div>
	</div>
		<div class="col-md-9"> 
			<div class="formwraper">
				<div class="titlehead">
					<div class="row" style="margin-bottom: 0px;">
						<div class="col-md-12">
							<a class="_link-back" href="#" style="color:#fff;">
								<i class="fa fa-arrow-left" aria-hidden="true"></i>
							</a>
							<b>Ver solicitud - <?php echo $request->job_title; ?></b>					
						</div>
					</div>
				</div>
			
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
	</div>
</div>

<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script> 

<!-- Modal request -->
<div id="modal-request-assign" class="modal" role="dialog" data-request-id="<?php echo $request->ID; ?>"></div>
<div id="modal-create-rys-process" class="modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Crear proceso RyS</h4>
      </div>
      <div class="modal-body">
        <p>Esta a punto de crear un proceso RyS sin publicar el empleo, antes de seguir por favor confirmar que esta seguro de esta acción.</p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" 
           href="<?php echo site_url('employer/post_new_job?r=' . $request->ID) . '&open_rys=true'; ?>">Publicar empleo primero
        </a>
        <button type="button" 
                class="btn btn-primary btn-create-rys-process-confirm"
                data-request-id="<?php echo $request->ID; ?>">
                Crear proceso RyS
        </button>
      </div>
    </div>
  </div>
</div>

<div id="modal-observe-request" class="modal" role="dialog">
  <?php echo form_open('employer/staff_requests/observe', array('id' => 'form-observe-request')); ?>
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Observar solicitud</h4>
        </div>
        <div class="modal-body">
          <div class="formint">
            <input type="hidden" name="request_id" value="<?php echo $request->ID; ?>">
            <div class="alert alert-info" role="alert">
              Se le enviará un email con la observación que usted ingrese al solicitante del puesto.
            </div>
            <div>
              <label style="display: block;">Observación <span>*</span></label>
              <textarea name="observation" 
                        class="form-control"
                        cols="30" 
                        rows="10"
                        required><?php e($request->observation); ?></textarea>
            </div>    
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </div>
    </div>
  <?php echo form_close(); ?>
</div>

<div id="modal-request-edit" class="modal" role="dialog"></div>

<div id="modal-request-profile-survey" class="modal" role="dialog">
  <div class="modal-dialog" >
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Historial Levantamiento de perfil</h4>
      </div>
      <div class="modal-body">
          <table class="table table-striped" style="width:100%;">
            <thead>
              <tr>
                <th>
                  Empleador
                </th>
                <th>
                  Fecha
                </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($profile_survey_logs as $log): ?>
                <tr>
                  <td>  
                    <?php e($log->employer_name); ?>
                  </td>
                  <td>
                    <?php e(date('d/m/Y h:i a', strtotime($log->date))); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('general/staff_request/common/modal_show_assigned_employers'); ?>

<script type="text/javascript">
  $(function(){
    messageSuccess = "<?php echo trim((string)$this->session->flashdata('success')); ?>";
    if (messageSuccess != '') {
      toastr["success"](messageSuccess);
    }

    messageDanger = "<?php echo trim((string)$this->session->flashdata('error')); ?>";
    if (messageDanger != '') {
      toastr["error"](messageDanger);
    }
  });
</script> 

<script type="text/javascript">
  $(function(){
    $( ".btn-assign-employer" ).click(function(e) {
      e.preventDefault();
      var url = $(this).attr('href');
      $( "#modal-request-assign" ).load(url, function(view) {
        $(this).html(view).modal('show');
      });  
    });

    $( "#btn-del-assign-employer" ).click(function(){
      
      if (!confirm("¿Está seguro de elimnar la asignación de la solicitud?")) {
        return false;
      }

      var data = {
        request_id : $(this).data('request-id'), 
      };

       $.post("<?php echo site_url('employer/staff_requests/delete_assignment'); ?>", data, function(response) {

        var status = response.status;

        if (status) {
          window.location.reload();
        } else {
          toastr["error"]("No se ha podido quitar la asignación");
        }

       }, 'json').fail(function(){
        toastr["error"]("Ha ocurrido un error inesperado");
       });
    });

    $( '.btn-create-rys-process' ).click(function(){
      $( '#modal-create-rys-process' ).modal('show');
    });

    $( '#form-observe-request' ).submit(function(e){

      e.preventDefault();

      if (!confirm("¿Está seguro de enviar la observación?")) {
        return false;
      }

      var data = $(this).serialize();
      var url = $(this).prop('action');

      btn = $(this).find('button[type="submit"]');
      btn.prop('disabled', true);

      $.post(url, data, function(response) {

        if (response.success) {
          window.location.reload();
        } else {
          toastr["error"](response.message);
        }
      }, 'json').fail(function(){
        btn.prop('disabled', false);
        toastr["error"]("Ha ocurrido un error inesperado");
      });

      return false;
    });

    $( '.btn-create-rys-process-confirm' ).click(function(){

      var data = {
        request_id : $(this).data('request-id'), 
      };

      btn_confirm = $(this);
      btn_confirm.prop('disabled', true);

       $.post("<?php echo site_url('employer/staff_requests/create_rys_process'); ?>", data, function(response) {

        var status = response.status;

        if (!status) {
          btn_confirm.prop('disabled', false);   
          toastr["error"](response.message);
          return;
        }

        toastr["success"](response.message);

        setTimeout(function() {
          window.location.reload();
        }, 1200);

       }, 'json').fail(function(){
        btn_confirm.prop('disabled', false);
        toastr["error"]("Ha ocurrido un error");
       });

    });

    $( '#btn-observe-request' ).click(function(){
      $( '#modal-observe-request' ).modal('show');
    });

    $( '#btn-request-edit' ).click(function(e){
      url = "<?php echo site_url('employer/staff_request/staff_requests/edit/' . $request->ID); ?>";

      image_loading_url = "<?php echo img_loading_url(); ?>";

      $( '#modal-request-edit' ).html(`
        <div class="modal-dialog" >
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Levantamiento de perfil</h4>
            </div>
            <div class="modal-body" align="center">
              <img src="${image_loading_url}" style="width:24px; height:24px;display:inline;margin: 0 5px;">
            </div>
          </div>
        </div>`
      );

      $( '#modal-request-edit' ).modal('show');

      $.post(url, {}, function(res){
        $( '#modal-request-edit' ).html(res);
      });
    });

    $( '.show-profile-survey' ).click(function(){
      $( '#modal-request-profile-survey' ).modal('show');
    });
  });
</script>
</body>
</html>