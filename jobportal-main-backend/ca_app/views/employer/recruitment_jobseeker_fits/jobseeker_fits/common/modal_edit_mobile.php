<div id="modal-seeker-edit-mobile" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar N° Celular</h4>
            </div>
            <div class="modal-body">

                <?php echo form_open('employer/recruitment_jobseeker_fits/jobseeker_fits/edit_mobile', ['id' => 'form-update-mobile', 'method' => 'post']); ?>
                    <div class="formwraper" style="border:0;">
                        <div class="input-group">
                            <input type="hidden" name="seeker_id" value="">
                            <label class="input-group-addon">N° Celular<span></span></label>
                            <table width="100%">
                                <tr>
                                    <td width="150">
                                        <select id="mobile_phone_code" name="mobile_code" class="form-control" style="width:150px;">
                                            <option value="">Seleccione</option>
                                            
                                            <?php 
                                            foreach ($result_countries as $row_country):
                                                if (empty($row_country->phone_code)) {
                                                    continue;
                                                }
                                            ?>
                                                <option value="<?php echo '+' . $row_country->phone_code; ?>">
                                                    <?php echo $row_country->country_name . " (+" . $row_country->phone_code . ")"; ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>     
                                    </td>
                                    <td>
                                        <input name="mobile" type="text" class="form-control" id="mobile" value="" maxlength="15" />
                                    </td>
                                </tr>
                            </table>                            
                        </div>
                        <div style="text-align:center;">
                            <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>