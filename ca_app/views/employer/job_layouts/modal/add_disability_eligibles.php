<!-- Modal -->
<div id="modal-add-disability-eligibles" class="modal fade" role="dialog">
    <style>
        #table-disability-eligibles tr td, 
        #table-disability-eligibles tr th {
            padding: 4px;
        }
    </style>
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Discapacidades aptas</h4>
            </div>
            <div class="modal-body">
                <div>
                    <button id="add-row-disability-eligibles" type="button" class="btn pull-right">Agregar</button>
                </div>
                <br />
                <?php echo form_open_multipart('employer/job_layouts/job_layouts/save_disability_elegibles', ['id' => 'form-job-layout-save-disability-elegibles']); ?>
                    <input type="hidden" name="id" value="<?php  echo $job_layout->id; ?>">
                    <table id="table-disability-eligibles" width="100%">
                        <thead>
                            <tr>
                                <th>Discapacidades</th>
                                <th>Recursos</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($jl_disability_eligibles) == 0): ?>
                                <tr>
                                    <td><input type="text" name="disability_eligibles[-1][disability]" class="form-control" value="" required="true"></td>
                                    <td><input type="text" name="disability_eligibles[-1][resources]" class="form-control" value=""></td>
                                    <td></td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($jl_disability_eligibles as $key => $row): ?>
                                <tr>
                                    <td><input type="text" name="disability_eligibles[<?php echo $key; ?>][disability]" class="form-control" value="<?php echo $row->disability; ?>" required="true"></td>
                                    <td><input type="text" name="disability_eligibles[<?php echo $key; ?>][resources]" class="form-control" value="<?php echo $row->resources; ?>"></td>
                                    <td>
                                        <button type="button" class="btn-remove-item"><i class="glyphicon glyphicon-remove"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br />
                    <div style="text-align:center;">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>