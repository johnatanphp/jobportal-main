<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title>
      <?php echo $title;?>    
    </title>
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
      	<div class="col-md-3"><div class="dashiconwrp">
        <?php $this->load->view('employer/common/menu/sidebar');?>
      </div></div>
        
      <?php echo form_open_multipart('employer/settings/save',array('id' => 'form-company-settings')); ?>
        <div class="col-md-9">
        <?php echo $this->session->flashdata('msg');?>
          <!--Account info-->
          <div class="formwraper">
            <div class="titlehead">Ajustes</div>
            <div class="formint">
                <div class="input-group">
                  <label class="input-group-addon">Email de notificación al crear <br />una solititud de requerimiento<span></span></label>
                  <input name="email_notify_create_staff_request" type="text" class="form-control" placeholder="Email" value="<?php echo $this->Company_account_setting->item('email_notify_create_staff_request'); ?>"> 
                </div>

                <div align="center">
                <input type="submit" name="submit_button" id="submit_button" value="Guardar" class="btn btn-primary" />
              </div>
            </div>
          </div>
          
        </div>
        <!--/Job Detail-->
        <?php echo form_close();?>
      </div>
    </div>
    <?php $this->load->view('common/bottom_ads');?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <?php $this->load->view('common/before_body_close'); ?>

    <script type="text/javascript">
        $(document).ready(function(){

          $( "#form-company-settings" ).submit(function(e) {
            e.preventDefault();
            var url = $(this).prop('action');
            var data = $(this).serialize();

            $.post(url, data, function(response) {

              if (response.success) {
                toastr["success"]("¡Cambios guardados!");
              } else {
                toastr["error"]("¡Error al guardar los cambios!");
              }
            }, 'json');

            return false;
          });
        });
    </script>
  </body>
</html>
