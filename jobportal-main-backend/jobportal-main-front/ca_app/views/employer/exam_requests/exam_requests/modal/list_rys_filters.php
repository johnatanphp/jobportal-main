<div id="modal-filter" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open('employer/exam_requests/requests/list_rys', ['method' => 'get']); ?>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Filtrar</h4>
                </div>

                <div class="modal-body">
                    <div class="panel-filter"> 
                        <div class="filter-title">
                            <h4>Por Tipo Examen</h4>
                        </div>
                        <select name="exam_type_id" class="form-control">
                            <option value="">Todos</option>
                            <option value="1" 
                                    <?php echo 1 == $filters['exam_type_id'] ? 'selected="selected"' : ''; ?>
                            >
                                EMPO
                            </option>
                            <option value="2" 
                                    <?php echo 2 == $filters['exam_type_id'] ? 'selected="selected"' : ''; ?>>
                                Prueba COVID
                            </option>
                            <option value="3" <?php echo 3 == $filters['exam_type_id'] ? 'selected="selected"' : ''; ?>>
                                Screening
                            </option>
                        </select>
                    </div>
                    <div class="panel-filter"> 
                        <div class="filter-title">
                            <h4>Estado</h4>
                        </div>
                        <select name="status" class="form-control">
                            <option value="">Todos</option>
                            <option value="1" 
                                    <?php echo 1 == $filters['status'] ? 'selected="selected"' : ''; ?>>
                                Pendiente
                            </option> 
                            <option value="2"
                                    <?php echo 2 == $filters['status'] ? 'selected="selected"' : ''; ?>>
                                Conforme
                            </option> 
                            <option value="3"
                                    <?php echo 3 == $filters['status'] ? 'selected="selected"' : ''; ?>>
                                Programado
                            </option> 
                        </select>
                    </div>
                    <div class="panel-filter"> 
                        <div class="filter-title">
                            <h4>Fecha de creación</h4>
                        </div>
                        <table width="100%">
                            <tr>
                                <td>
                                    <input type="date" name="date_start" value="<?php echo $filters['date_start']; ?>">
                                </td>
                                <td>
                                    <input type="date" name="date_end" value="<?php echo $filters['date_end']; ?>">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" 
                            class="btn btn-default" 
                            onclick="window.location='<?php echo site_url('employer/exam_requests/requests/list_rys'); ?>';">
                        Resetear
                    </button>
                    <button type="submit" class="btn btn-primary" >Filtrar</button>
                    <input style="display: none;"id="form-filter-reset" type="reset" class="btn btn-default" value="Limpiar"/>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>