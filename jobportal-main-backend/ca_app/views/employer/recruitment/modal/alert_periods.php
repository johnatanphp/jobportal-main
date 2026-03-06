<div id="modal-alert-periods" class="modal" role="dialog">
    <style>
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
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reglas de alertas para periodos</h4>
      </div>
      <div class="modal-body">
        <div>
            <div class="row">
                <div class="col-md-12">
                    <button id="period-rules-add" class="btn btn-xs btn-primary pull-right">
                        Agregar
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php echo form_open('employer/recruitment_period_rules/save', ['id' => 'form-period-rules']); ?>
                        <table id="tbl-period-rules" width="100%" class="table">
                            <thead>
                                <tr>
                                    <th>Año(s) Inicio</th>
                                    <th>Año(s) Final</th>
                                    <th>Color</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alert_period_rules as $key => $row): ?>
                                    
                                        <tr>
                                            <td>
                                                <input type="number" name="period_rules[<?php echo $key; ?>][start]" class="form-control" value="<?php echo $row->start; ?>" step="0.01" required/>
                                            </td>
                                            <td>
                                                <input type="number" name="period_rules[<?php echo $key; ?>][end]" class="form-control" value="<?php echo $row->end; ?>" step="0.01"/>
                                            </td>
                                            <td>
                                                <input type="color" name="period_rules[<?php echo $key; ?>][color]" value="<?php echo $row->color; ?>" required/>
                                            </td>
                                            <td>
                                                <button class="btn-remove-item" onclick="$(this).closest('tr').remove();">
                                                    <i class="glyphicon glyphicon-remove"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" value="Guardar"/>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>