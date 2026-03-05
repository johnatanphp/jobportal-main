<!-- Modal -->
<div>
  <style type="text/css">
    
    .content-link-profiles {
      padding: 5px 8px;
      text-align: center;
    }

    .link-profile {
      
      display: block;
      padding: 5px 3px;
      font-size: 16px;
      text-align: center;
      background: #fff;
      margin: 5px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
  </style>
  <div class="modal-dialog" style="max-width: 400px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cuenta</h4>
      </div>
      <div class="modal-body">
        <div class="list-profile">
          <a class="link-profile" href="<?php echo site_url('user/app_user_change_password'); ?>">
            Cambiar contraseña
          </a>
        </div>

        <div class="list-profile">
            <a class="link-profile" href="<?php echo site_url('logout');?>">
                Cerrar sesión
            </a>
        </div>

        <?php 
          $app_user = $this->Employer->find($this->session->userdata('user_id'));
          $company = $this->Company->find($app_user->company_ID);
        ?>
        <?php if ($company->system_internal): ?>
          <br />
          <div class="content-link-profiles">
            <h5 class="modal-title">Perfiles de la cuenta</h5>
            <br />
            <?php foreach ($profiles as $row): ?>
              <div class="list-profile">
                <a class="link-profile" href="<?php echo site_url('user/select_profile/' . $row->id); ?>">
                  <?php echo $row->name; ?>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>