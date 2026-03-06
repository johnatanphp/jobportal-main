<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title><?php echo $title;?></title>
    <style type="text/css">
      .profile .active {
        text-decoration: none;
      }

      .profile .inactive {
        text-decoration: line-through
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
        
      <?php echo form_open_multipart('employer/change_password', array('id' => 'm'));?>
        <div class="col-md-9">
        <?php echo $this->session->flashdata('msg');?>
          <!--Account info-->
          <div class="formwraper">
            <div class="titlehead">
              <a href="<?php echo site_url('employer/users/list_users/search'); ?>" style="color:#fff;">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
              </a>
              Perfiles del usuario
            </div>
            <div style="margin-top: 5px;">
              <div>
                <div class="input-group">
                  <label class="input-group-addon">
                    <b>Email</b>
                  </label>
                  <?php echo $user_info->email ?>
                </div>
                <div class="input-group">
                  <label class="input-group-addon">
                    <b>Nombre</b>
                  </label>
                  <?php echo $user_info->first_name; ?>
                </div>
              </div>
              <br />
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Perfiles del usuario</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                  <?php foreach ($profiles as $row): ?>
                    <tr>
                      <td>
                        <?php echo $row->profile_name; ?>
                      </td>
                      <td>
                        <div class="checkbox-wrapper-3">
                          <?php $toggle_disabled = $user_info->is_admin == 'yes' && $row->profile_id == 1 ? 'disabled' : ''; ?>
                          <input class='btn-toggle-profile-status tgl tgl-ios' 
                                  id='toggle-profile-status-<?php echo $row->profile_id; ?>' 
                                  type='checkbox'
                                  data-user-id="<?php echo $user_info->ID; ?>"
                                  data-profile-id="<?php echo $row->profile_id; ?>"
                                  <?php echo $toggle_disabled; ?>
                                  <?php echo $row->profile_user_id ? 'checked' : ''; ?>
                                  value="1">
                          <label class='tgl-btn' for='toggle-profile-status-<?php echo $row->profile_id; ?>' style="width: 70px; height: 22px;"></label>
                        </div> 
                      </td>
                      <td width="120">
                        <a <?php echo in_array($row->profile_id, [1, 2, 3]) ? 'enabled' : 'disabled'; ?> 
                           class="btn btn-sm btn-default" 
                           href="<?php echo in_array($row->profile_id, [1, 2, 3]) ? site_url('employer/users/permission_profiles/index/' . $row->profile_id . '/' . $user_info->ID) : '#'; ?>"> 
                            Permisos
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>              
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
      $(function(){

        $( ".btn-toggle-profile-status" ).change(function() {
        
          const btnToggle = $(this);
          const url = "<?php echo site_url('employer/users/profiles/change_profile'); ?>";
          const data = {
            'user_id' : btnToggle.data('user-id'),
            'profile_id': btnToggle.data('profile-id'),
            'sts': btnToggle.is(':checked') ? 1 : 0
          };

          const newStatus = btnToggle.is(':checked') ? 1 : 0
          btnToggle.closest('.checkbox-wrapper-3').addClass('load load-image');

          $.post(url, data, function(response){

            if (!response.success) {
              btnToggle.prop('checked', newStatus ? false : true);
              toastr["error"](response.message);
              return;
            }
            btnToggle.closest('.checkbox-wrapper-3').removeClass('load load-image');
          
          }, 'json').fail(function(){
            toastr["error"]('¡Ha ocurrido un error!');
            btnToggle.closest('.checkbox-wrapper-3').removeClass('load load-image');
            btnToggle.prop('checked', newStatus ? false : true);
          });
        });

      });


    </script>
  </body>
</html>
