<!DOCTYPE html>
<html lang="en">
<head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title><?php echo $title;?></title>
  <?php $this->load->view('common/before_head_close'); ?>

  <style type="text/css">

    .formint {
      padding: 15px;
    }

    .btn-remove-item {
      margin:0;
      padding: 0;
      border:0;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      background: #e76767;
      color: #fff;
      font-size: 10px;
    }
  </style>
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
    <?php $this->load->view('employer/common/menu/sidebar'); ?>
  </div>
    </div>
    
    <div class="col-md-9">
      <?php if ($this->session->flashdata('added_action') == true): ?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div>
                  <h4>Layout de puesto creado con éxito</h4>
              </div>
          </div>
        <?php endif;?>

        <?php if ($this->session->flashdata('update_action') == true): ?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div>
                  <h4>Layout de puesto editado con éxito</h4>
              </div>
          </div>
        <?php endif;?>
      
      <div class="formwraper">
        <div class="titlehead">
          <a href="#" style="color:#fff;" class="_link-back">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </a>
          Mostrar layout de puesto
        </div>
        <div class="formint">
          
          <div class="row">
            <div class="col-xs-8">
              <h4 style="font-weight: 600;"><?php e($job_layout->job_title); ?></h4>
            </div>
            <div class="col-xs-4">

              <div class="row">
                <div class="col-md-12" style="text-align: right;">

                  <?php 
                    $toggle_disabled = !has_permission_action('job_layouts', 'active_inactive')  || $this->session->userdata('current_profile_id') != 2 ? 'disabled' : '';
                  ?>
                  <div class="checkbox-wrapper-3">
                    <input class='btn-toggle-jl-status tgl tgl-ios' 
                            id='toggle-jl-status-<?php echo $job_layout->id; ?>' 
                            type='checkbox'
                            data-id="<?php echo $job_layout->id; ?>"
                            <?php echo $toggle_disabled; ?>
                            <?php echo $job_layout->active ? 'checked' : ''; ?>
                            value="1">
                    <label class='tgl-btn' for='toggle-jl-status-<?php echo $job_layout->id; ?>' style="width: 70px; height: 22px;"></label>
                  </div>
                
                </div>
              </div>
              <div class="row">
                <div class="col-md-12" style="text-align: right;">
                  
                  <?php if ($this->session->userdata('current_profile_id') == 2): ?>
                    <a class="btn btn-xs btn-default" 
                        href="<?php echo site_url('employer/job_layouts/job_layouts/edit/' . $job_layout->id); ?>"
                        style="margin: 0 0px;">
                      Editar
                    </a> 
                  <?php endif; ?>
                </div>
              </div>

            </div>
          </div>
          <?php $this->load->view('employer/job_layouts/common/job_layout_detail'); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<?php $this->load->view('employer/job_layouts/modal/edit_resources'); ?>
<?php $this->load->view('employer/job_layouts/modal/add_disability_eligibles'); ?>
<script>
  $(function(){    
    $( '#form-job-layout-edit-resources' ).submit(function(e){
      e.preventDefault();

      const url = $(this).prop('action');
      const data = $(this).serialize();

      const btnSubmit = $( 'button[type="submit"]', this );
      btnSubmit.prop('disabled', true);
      btnSubmit.html('Guardando...');

      $.post(url, data, function(response) {
        
        toastr["success"]('¡Recursos actualizados!');
        
        setTimeout(function(){
          window.location.reload();
        }, 2000);
        
      }, 'json').fail(function(){
        btnSubmit.prop('disabled', false);
        btnSubmit.html('Guardar');
        toastr["error"]('¡No se pudo guardar los datos!');
      });

      return false;
    });

    $( '#add-row-disability-eligibles' ).click(function(){
      console.log("11");
      var index = $( "#table-disability-eligibles" ).generateSequence();
      var row = `
        <tr>
          <td width="40%">
            <input type="text" name="disability_eligibles[${index}][disability]" class="form-control" required="true">
          </td>
          <td width="40%">
            <input type="text" name="disability_eligibles[${index}][resources]" class="form-control">
          </td>
          <td width="5%">
            <button type="button" class="btn-remove-item"><i class="glyphicon glyphicon-remove"></i></button>
          </td>
        </tr>
      `;

      $( "#table-disability-eligibles tbody" ).prepend(row);
    });

    $(document).on("click", "#table-disability-eligibles .btn-remove-item", function(){
      $(this).closest("tr").remove();
    });

    $( '#form-job-layout-save-disability-elegibles' ).submit(function(e){
      e.preventDefault();

      if ($( "#table-disability-eligibles tbody tr" ).length == 0) {
        toastr["error"]('¡Por favor agregue una discapacidad al menos!');
        return;
      }
      
      const url = $(this).prop('action');
      const data = $(this).serialize();

      const btnSubmit = $('button[type="submit"]', this);
      btnSubmit.prop('disabled', true);
      btnSubmit.html('Guardando...');

      $.post(url, data, function(response) {
        
        toastr["success"]('¡Discapacidades Guardadas!');
        
        setTimeout(function(){
          window.location.reload();
        }, 2000);
        
      }, 'json').fail(function(){
        toastr["error"]('¡No se pudo guardar los datos!');
        btnSubmit.prop('disabled', false);
        btnSubmit.html('Guardar');
      });

      return false;
    });

    $( '.btn-toggle-jl-status' ).change(function(){
      const newStatus = $(this).is(':checked') ? 1 : 0;
      const jobLayoutId = $(this).data('id');
      const btnToggle = $(this);

      btnToggle.closest('.checkbox-wrapper-3').addClass('load load-image');
      
      const data = {
        id: jobLayoutId,
        sts: newStatus 
      };

      const url = "<?php echo site_url('employer/job_layouts/job_layouts/update_sts'); ?>"

      $.post(url, data, function(response){

        btnToggle.closest('.checkbox-wrapper-3').removeClass('load load-image');

        if (!response.status) {
          btnToggle.prop('checked', newStatus ? false : true);
          toastr["error"](response.message);
          return;
        }
      }, 'json')
      .fail(function(){
        toastr["error"]('¡Ha ocurrido un error!');
        btnToggle.closest('.checkbox-wrapper-3').removeClass('load load-image');
        btnToggle.prop('checked', newStatus ? false : true);
      }).always(function(){});

    });

  });

  $( "#type_emo" ).change(function(){
    var emo = $(this).val();

    if (emo == 'PROTOCOLO 10. ESTABLECIDO POR EL CLIENTE') {
      $( "#emo-detail" ).show();
      $( "#protocol-detail" ).prop('required', true);
    } else {
      $( "#emo-detail" ).hide();
      $( "#protocol-detail" ).prop('required', false);
    }
  });

  $( '#occupational-exams-approved' ).click(function(){

    btnApproved = $(this);
    btnApproved.prop('disabled', true);

    url = "<?php echo site_url('employer/job_layouts/job_layouts/occupational_exams_approved'); ?>";
    data = {
      id: $(this).data('id'),
      approved: $(this).data('approved')
    };

    $.post(url, data, function(response) {
      
      toastr["success"]('¡Listo!');
      
      setTimeout(function(){
        window.location.reload();
      }, 2000);
      
    }, 'json').fail(function(){
      toastr["error"]('¡No se pudo guardar los datos!');
      btnApproved.prop('disabled', false);
    });
  });

  $( "#type_emo" ).change();

  $( 'select' ).each(function(){
    $(this).select2();
  });
</script>
</body>
</html>
