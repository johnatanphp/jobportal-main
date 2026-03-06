<!-- Modal -->
<div>
    <style type="text/css">

    .content-link-profiles {
        background: #e0e0e0;
        padding: 5px 8px;
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
                    <a class="link-profile" href="<?php echo base_url('jobseeker/change_password');?>">
                        Cambiar contraseña
                    </a>
                </div>

                <div class="list-profile">
                    <a class="link-profile" href="<?php echo site_url('logout');?>">
                        Cerrar sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
