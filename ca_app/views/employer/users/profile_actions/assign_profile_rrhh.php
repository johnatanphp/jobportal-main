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
  <div class="dashiconwrp">`
    <?php $this->load->view('employer/common/menu/sidebar'); ?>
  </div>
  </div>
    <div class="col-md-9">
      <div class="formwraper">
        <div class="titlehead">
          <a href="<?php echo site_url('employer/users/profiles/index/' . $employer->ID); ?>" style="color:#fff;">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </a>
          Perfil RRHH
        </div>
        <div class="row"> 
          <div class="col-md-12">
            <div class="formint">
              <h5 class="sub-title-h3">Datos cuenta</h5>
              <?php echo form_open('employer/users/profile_actions/assign_profile_rrhh/save/' . $employer->ID, ['id' => 'form-save-profile']); ?>
                <input type="hidden" name="user_id" value="<?php echo $employer->ID; ?>">
                <div class="input-group <?php echo (form_error('rrhh_type_id')) ? 'has-error':'';?>">
                  <label class="input-group-addon">Tipo RRHH <span>*</span></label>
                  <select id="rrhh-type" name="rrhh_type_id" class="form-control" style="width:50%" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($rrhh_types as $row_type): ?>
                      <option value="<?php echo $row_type->id; ?>" <?php echo @$employer->rrhh_type_id == $row_type->id ? 'selected="selected"' : ''; ?>>
                        <?php e($row_type->name); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <?php echo form_error('rrhh_type_id'); ?>
                </div>
                <div align="center">
                  <button class="btn btn-sm btn-primary">Guardar</button>
                </div>
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('common/bottom_ads'); ?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>

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
  $( '#form-save-profile' ).submit(function(e){
    e.preventDefault();

    const url = $(this).prop('action');
    const data = $(this).serialize();

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
      toastr["error"]('¡Ha ocurrido un error al tratar de guardar los datos');
    }).always(function() {}); 

    return false;
  });
});
</script>
</body>
</html>