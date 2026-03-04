<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
<style>
.ui-button {
  margin-left: -1px;
}
.ui-button-icon-only .ui-button-text {
  padding: 0.35em;
}
.ui-autocomplete-input {
  margin: 0;
  padding: 0.48em 0 0.47em 0.45em;
}
</style>
<?php $this->load->view('common/before_head_close'); ?>
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
      <div class="formwraper">
        <div class="titlehead">
          <a href="<?php echo site_url('employer/users/profiles/index/' . $app_user_info->ID); ?>" style="color:#fff;">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </a>
          Perfil solicitante
        </div>
        <div class="row"> 

          <div class="col-md-12">
            <div class="formint">
              <h5 class="sub-title-h3">Datos cuenta</h5>
              <?php echo form_open('employer/users/profile_actions/assign_profile_recruiters/save/' . $app_user_info->ID, ['id' => 'form-save-profile']); ?>
                <input type="hidden" name="user_id" value="<?php echo $app_user_info->ID; ?>">
                <div class="input-group <?php echo (form_error('type_recruiter')) ? 'has-error':'';?>">
                  <label class="input-group-addon">Tipo de solicitante <span>*</span></label>
                  <select id="type-recruiter" name="type_recruiter" class="form-control" style="width:50%">
                    <option value="">Seleccione</option>
                    <option value="external" <?php echo @$recruiter_info->type == 'external' ? 'selected="selected"' : ''; ?>>Externo</option>
                    <option value="internal" <?php echo @$recruiter_info->type == 'internal' ? 'selected="selected"' : ''; ?>>Interno</option>
                  </select>
                  <?php echo form_error('type_recruiter'); ?>
                </div>
                <div align="center">
                  <button class="btn btn-sm btn-primary">Guardar</button>
                </div>
              <?php echo form_close(); ?>
              <br>

              <div class="content-permission" style="display:none;">
                <h5 class="sub-title-h3">Permisos</h5>

                <ul class="nav nav-tabs">
                  <li class="active" ><a data-toggle="tab" href="#content-center-cost" data-tab-id="#content-center-cost">Clientes</a></li>
                  <li><a data-toggle="tab" href="#content-areas" data-tab-id="#content-areas">Areas</a></li>
                  <li><a data-toggle="tab" href="#content-job-charges" data-tab-id="#content-job-charges">Grupo ocupacional</a></li>
                </ul>

                <div class="tab-content">
                  <div id="content-center-cost" class="tab-pane in active">
                    <br>
                      <div id="content-other-info">
                        <button type="button" 
                                class="btn btn-xs btn-primary pull-right" 
                                data-toggle="modal" 
                                data-target="#modal-add-companies"
                                style="margin-bottom:10px;">
                          Agregar
                        </button>
                        <div style="margin-top: 5px;">
                          <table id="tbl-companies" class="table table-striped">
                            <thead>
                              <tr>
                                <th></th>
                                <th>Consultora</th>
                                <th>Unidad de negocio</th>
                                <th>Empresa cliente</th>
                                <th>Centro de costo</th>
                              </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                          </table>
                        </div>
                      </div>
                  </div>
                  <div id="content-areas" class="tab-pane">
                      <br>
                      <div>
                        <button id="btn-add-areas"
                                type="button" 
                                class="btn btn-xs btn-primary pull-right" 
                                style="margin-bottom:10px;">
                          Agregar
                        </button>
                        <div style="margin-top: 5px;">
                          <table id="tbl-permission-areas" class="table table-striped">
                            <thead>
                              <tr>
                                <th></th>
                                <th>Area</th>
                              </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                          </table>
                        </div>
                      </div>
                  </div>
                  <div id="content-job-charges" class="tab-pane">
                    <br>
                      <div>
                        <button id="btn-add-job-charges"
                                type="button" 
                                class="btn btn-xs btn-primary pull-right" 
                                style="margin-bottom:10px;">
                          Agregar
                        </button>
                        <div style="margin-top: 5px;">
                          <table id="tbl-permission-job-charges" class="table table-striped">
                            <thead>
                              <tr>
                                <th></th>
                                <th>Cargos</th>
                              </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                          </table>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('employer/users/permission_clients/common/modal_add_client'); ?>
<?php $this->load->view('employer/users/permission_internal_areas/common/modal_add_areas'); ?>
<?php $this->load->view('employer/users/permission_job_charges/common/modal_add_job_charges'); ?>

<?php $this->load->view('common/bottom_ads'); ?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<script src="<?php echo base_url('public/js/bad_words.js'); ?>"></script>
<?php $this->load->view('common/before_body_close'); ?>

<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>  

<script src="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.2.11/js/dataTables.checkboxes.min.js"></script>

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
<script>
  $(function(){
    
    $( '#type-recruiter' ).change(function(){
  
      $( '.content-permission' ).hide(); 
    
      $( '.nav-tabs a' ).each(function(i, e){
        tabId = $(e).data('tab-id');
        $(e).closest('li').hide();
      });

      val = $(this).val();

      if (val != '') {
        $( '.content-permission').show(); 
      }

      if (val == 'internal') {

        $( 'a[href="#content-areas"]' ).closest('li').show();
        $( 'a[href="#content-areas"]' ).tab('show');
        
        $( 'a[href="#content-job-charges"]' ).closest('li').show();
      }

      if (val == 'external') {
        $( 'a[href=#content-center-cost]' ).closest('li').show();
        $( 'a[href="#content-center-cost"]' ).tab('show');
      }
    });

    $( '#form-save-profile' ).submit(function(e){
      e.preventDefault();

      var url = $(this).prop('action');
      var data = $(this).serialize();

      $.post(url, data, function(res) {
        
        if (res.status) {
          toastr["success"](res.message);
          return;
        }

        if (!res.status) {
          toastr["error"](res.message);
        }
      }, 'json')
      .fail(function() {
          alert('¡Ha ocurrido un error al tratar de listar las empresas clientes!');
      }).always(function() {
          $( "#client-company" ).find("option:eq(0)").text("Seleccione");
          $( "#client-company" ).prop('disabled', false);
      }); 

      return false;
    });

    $( '#type-recruiter' ).change();
  });
</script>
<?php $this->load->view('employer/users/permission_clients/scripts/manage_js'); ?>
<?php $this->load->view('employer/users/permission_internal_areas/scripts/manage_js'); ?>
<?php $this->load->view('employer/users/permission_job_charges/scripts/manage_js'); ?>
</body>
</html>