<style type="text/css">
    .list-options {
        list-style: none;
        padding-bottom: 3px;
    }

    #tbl-add-email td {
        padding: 5px;
    }

</style>
<div class="modal-dialog" style="width: 55%;">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Envio Solicitud</h4>
        </div>
        <div class="modal-body">
            <div class="formwraper">
                <div style="padding: 10px;">
                    <?php echo form_open('employer/exam_requests/requests/send', ['id' => 'form-send-request']); ?>
                        <input type="hidden" name="exam_requests[]" value="<?php echo $request->request_id; ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <label>Enviar copia a: </label>
                                <table id="tbl-add-email" width="100%">
                                    <tr>
                                        <td>
                                            <input id="input-email" 
                                                   type="text" 
                                                   class="form-control"
                                                   placeholder="Agregar correo">
                                        </td>
                                        <td>
                                            <button id="btn-add-email"
                                                    type="button" 
                                                    class="btn btn-xs btn-primary">
                                                Agregar
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                                <br />
                                <table id="tbl-list-email" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Email</th>
                                            <th width="50"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($medical_center_emails as $row): ?>
                                            <tr>
                                                <td>
                                                    <input name='emails[]' 
                                                           type='hidden' 
                                                           value="<?php echo $row->email; ?>" 
                                                           class="input-request-send-emails">
                                                    <?php echo $row->email; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-xs btn-danger"
                                                            type="button" 
                                                            onclick="$(this).closest('tr').remove();">
                                                        Quitar
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php foreach ($overall_emails as $row): ?>
                                            <tr>
                                                <td>
                                                    <input name='emails[]' 
                                                           type='hidden' 
                                                           value="<?php echo $row->email; ?>" 
                                                           class="input-request-send-emails">
                                                    <?php echo $row->email; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-xs btn-danger"
                                                            type="button" 
                                                            onclick="$(this).closest('tr').remove();">
                                                        Quitar
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <label>Comentario (Opcional)</label>
                                <textarea class="form-control"
                                          name="comment"
                                        rows="10"><?php echo $request->send_comment; ?></textarea>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" 
                                        class="btn btn-default pull-right form-cancel" 
                                        data-dismiss="modal" style="margin-left: 5px;">
                                    Cancelar    
                                </button>
                                <input type="submit" 
                                       value="Enviar" 
                                       class="btn btn-primary pull-right form-submit">

                            </div>
                        </div>
                        
                    <?php echo form_close(); ?>
                  
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $( "#btn-add-email" ).click(function(){

        var inputEmail = $.trim($( "#input-email" ).val());

        emailRegex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

        if (inputEmail == '') {
            toastr["error"]("¡Debe ingresar un correo!");
            return;
        }

        if (!emailRegex.test(inputEmail)) {
            toastr["error"]("¡Debe ingresar un correo con formato válido!");
            return;
        }

        $( "#tbl-list-email tbody" ).prepend(`
            <tr>
                <td>
                    <input name='emails[]' 
                           type='hidden' 
                           value="${inputEmail}" 
                           class="input-request-send-emails">
                    ${inputEmail}
                </td>
                <td>
                    <button class="btn btn-xs btn-danger"
                            type="button" 
                            onclick="$(this).closest('tr').remove();">
                        Quitar
                    </button>
                </td>
            </tr>`
        );

        $( "#input-email" ).val("");
    });
</script>