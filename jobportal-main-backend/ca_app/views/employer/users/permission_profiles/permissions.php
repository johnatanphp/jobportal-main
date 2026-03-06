<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title><?php echo $title;?></title>
    <style type="text/css">
      
      .list-modules, .list-modules-actions {
        list-style-type: none;
      }

      .list-modules .list-modules-items {
        padding: 5px;
      }

      .list-modules-actions .list-modules-actions-items {
        padding: 0px 20px;
      }

      .container-permissions {
        padding: 20px 0 0 0;
      }

      .list-modules-container, 
      .list-modules-actions-container {
        display: flex;
        align-items: center;
      }

      .list-modules-container label, 
      .list-modules-actions-container label {
        margin: 0;
        padding: 4px 6px;
        font-weight: normal;
        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none; 
      }

      .list-modules-container label {
        color: #333;
        font-weight: bold;
      }

      .list-modules-actions-container label {
        color: #555;
      }

      .list-modules-container input[type="checkbox"], 
      .list-modules-actions-container input[type="checkbox"] {
        margin: 0;
        padding: 0;
      }

      .form-title {
        color: #333;
        font-size: 16px;
        text-transform: uppercase;
        padding: 8px 4px;
        border-bottom: 1px solid #999;
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
      	<div class="col-md-3"><div class="dashiconwrp">
          <?php $this->load->view('employer/common/menu/sidebar'); ?>
        </div>
      </div>
        
      <div class="col-md-9">
        <?php echo form_open('employer/users/permission_profiles/index/' . $profile->id . '/' . $employer->ID, ['id' => 'form-modules-permissions']); ?>
          <input type="hidden" value="<?php echo $profile->id; ?>" name="profile_id">
          <input type="hidden" value="<?php echo $employer->ID; ?>" name="employer_id">
          <?php echo $this->session->flashdata('msg');?>
            <!--Account info-->
            <div class="formwraper">
              <div class="titlehead">
                <a href="#" style="color:#fff;" class="_link-back">
                  <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
                Permisos Perfiles
              </div>
              <div class="formint">
                <div>
                  <div class="input-group">
                    <label class="input-group-addon">
                      <b>Email</b>
                    </label>
                    <?php echo $employer->email ?>
                  </div>
                  <div class="input-group">
                    <label class="input-group-addon">
                      <b>Nombre</b>
                    </label>
                    <?php echo $employer->first_name; ?>
                  </div>
                  <div class="input-group" style="display: none;">
                    <label class="input-group-addon">
                      <b>Perfil</b>
                    </label>
                    <?php echo $profile->name; ?>
                  </div>
                </div>
                <h3 class="form-title">Permisos para perfil <?php echo $profile->name; ?></h3>
                <div class="container-permissions">
                  <ul class="list-modules">
                    <?php foreach ($module_actions as $module_id => $module_action): ?>
                      <li class="list-modules-items">
                        <div class="list-modules-container">
                          <input id="<?php echo 'module_' . $module_id; ?>" type="checkbox" class="check-permission-module">
                          <label for="<?php echo 'module_' . $module_id; ?>"><?php echo $module_action['name']; ?></label>
                        </div>
                        <ul class="list-modules-actions">
                          <?php foreach ($module_action['actions'] as $action): ?>
                            <li class="list-modules-actions-items">
                              <div class="list-modules-actions-container">
                                <input id="<?php echo 'action_' . $action->action_id; ?>"
                                       type="checkbox" 
                                       name="action_permissions[]" 
                                       value="<?php echo $action->action_id; ?>" 
                                       class="check-permission-action"
                                       <?php echo $action->permission_action_id == $action->action_id ? 'checked' : ''; ?>
                                >
                                <label for="<?php echo 'action_' . $action->action_id; ?>"><?php echo $action->action_name; ?></label>
                              </div>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
                <div align="center">
                  <input type="submit" value="Guardar" class="btn btn-sm btn-primary">
                </div>
                          
              </div>
            </div>
          </div>
          <!--/Job Detail-->
        <?php echo form_close(); ?>
      </div>
    </div>
    </div>
    <?php $this->load->view('common/bottom_ads');?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <?php $this->load->view('common/before_body_close'); ?>

    <script type="text/javascript">
    $(function(){

      $( '#form-modules-permissions' ).submit(function(e){
        e.preventDefault();

        const url = $(this).prop('action');
        const data = $(this).serialize();
        const form = $(this);

        $(form).addClass('load load-image');
        
        $.post(url, data, function(response) {
          const status = response.status;
          if (status) {
            toastr["success"](response.message);
          } else {
            toastr["error"](response.message);
          }
      }, 'json')
        .fail(function(){
          toastr["error"]('¡Ha ocurrido un error!');
        })
        .always(function(){
          $(form).removeClass('load load-image');
        });

        return false;
      });

      $( '.check-permission-module' ).change(function(){
        const parentList = $(this).closest('.list-modules-items');
        $( '.check-permission-action', parentList).prop('checked', $(this).is(':checked'));
      });

      $( '.check-permission-action' ).change(function(){

        const parentList = $(this).closest('.list-modules-items');
        const checkboxTotal = $( '.check-permission-action', parentList).length;
        const checkboxChecked = $( '.check-permission-action:checked', parentList).length;

        $( '.check-permission-module', parentList).removeAttr('checked');
        $( '.check-permission-module', parentList).removeAttr('indeterminate');
        
        if (checkboxTotal == checkboxChecked) {
          $( '.check-permission-module', parentList).prop('checked', true);
          $( '.check-permission-module', parentList).prop('indeterminate', false);
          return;
        }

        if (checkboxTotal > checkboxChecked && checkboxChecked > 0) {
          $( '.check-permission-module', parentList).prop('indeterminate', true);
          return;
        }

        if (checkboxChecked == 0 && checkboxTotal > 0) {
          $( '.check-permission-module', parentList).prop('checked', false);
          $( '.check-permission-module', parentList).prop('indeterminate', false);
          return;
        }
      });

      $( '.check-permission-action' ).change();
    });
    </script>
  </body>
</html>
