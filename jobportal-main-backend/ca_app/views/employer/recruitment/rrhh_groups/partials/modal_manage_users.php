<!-- Modal -->
<div id="modal-group-users" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
            Usuarios RRHH del grupo
        </h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('employer/recruitment/rrhh_groups/add_user', ['id' => 'form-add-user-group']); ?>
            <table width="100%">
                <tr>
                    <td>
                        <input id="group-user-id" type="hidden" name="group_id" value="">
                        <input id="group-user-email" type="text" class="form-control" name="email" placeholder="Ingrese el email del RRHH" required>
                    </td>
                    <td align="center">
                        <button class="btn btn-xs btn-primary">Agregar</button>
                    </td>
                </tr>
            </table>
        <?php echo form_close(); ?>
        
        <br>

        <div class="row">
            <div class="col-md-12">
                <table id="tbl-group-users" class="table">
                    <tr>
                        <thead>
                            <th></th>
                            <th>
                                Nombre
                            </th>
                            <th>
                                Email
                            </th>
                        </thead>
                    </tr>
                </table>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
