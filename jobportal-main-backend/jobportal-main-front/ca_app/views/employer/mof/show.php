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
                <h4>Nuevo MOF creado con éxito</h4>
            </div>
        </div>
      <?php endif;?>

      <?php if ($this->session->flashdata('update_action') == true): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <div>
                <h4>MOF editado con éxito</h4>
            </div>
        </div>
      <?php endif;?>

      <div class="formwraper">
        <div class="titlehead">
          <a href="#" style="color:#fff;" class="_link-back">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </a>
          Mostrar MOF
        </div>
        <div class="formint">
          <table width="100%">
              <tr>
                  <td width="50%" align="left">
                  </td>
                  <td align="right">
                      <?php if ($this->session->userdata('current_profile_id') == 2 && $this->config->item('mof_module_enabled')): ?>
                      <a href="<?php echo site_url('employer/mofs/mofs/edit/' .  $mof->ID); ?>">
                          Editar
                      </a> 
                      <?php endif; ?>
                  </td>
              </tr>
          </table>
          <?php $this->load->view('employer/mof/common/mof_detail'); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('common/bottom_ads');?>
<?php $this->load->view('employer/mof/modal/edit_mof_resources'); ?>
<?php $this->load->view('employer/mof/modal/add_disability_eligibles'); ?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>

<script>
  $(function(){
    $( '#form-mof-edit-resources' ).submit(function(e){
      e.preventDefault();

      const url = $(this).prop('action');
      const data = $(this).serialize();
      const btn = $('button[type=submit]', this);

      btn.attr('disabled', true);
      btn.html('Guardando...');

      $.post(url, data, function(response) {
        
        toastr["success"]('¡Recursos actualizados!');
        
        setTimeout(function(){
          window.location.reload();
        }, 2000);
        
      }, 'json').fail(function(){
        toastr["error"]('¡No se pudo guardar los datos!');
        btn.attr('disabled', false);
        btn.html('Guardar');
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

    $( '#form-mof-save-disability-elegibles' ).submit(function(e){
      e.preventDefault();

      if ($( "#table-disability-eligibles tbody tr" ).length == 0) {
        toastr["error"]('¡Por favor agregue una discapacidad al menos!');
        return;
      }

      const url = $(this).prop('action');
      const data = $(this).serialize();

      const btn = $('button[type="submit"]', this);
      btn.prop('disabled', true);
      btn.html('Guardando...');

      $.post(url, data, function(response) {
        
        toastr["success"]('¡Discapacidades Guardadas!');
        
        setTimeout(function(){
          window.location.reload();
        }, 2000);
        
      }, 'json').fail(function(){
        toastr["error"]('¡No se pudo guardar los datos!');
        btn.prop('disabled', false);
        btn.html('Guardar');
      });

      return false;
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

  $( "#type_emo" ).change();

  $( '#occupational-exams-approved' ).click(function(){

    btnApproved = $(this);
    btnApproved.prop('disabled', true);

    url = "<?php echo site_url('employer/mofs/mofs/occupational_exams_approved'); ?>";
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

  $( 'select' ).each(function(){
    $(this).select2();
  });
</script>
</body>
</html>
