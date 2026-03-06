<div>
    <table width="100%">
        <tr>
            <td width="85%">
                <input id="notification-email" type="text" class="form-control" placeholder="Ingrese email">
            </td>
            <td>
                <button id="btn-add-email" class="btn btn-primary btn-xs pull-right">Agregar</button>
            </td>
        </tr>
    </table>
    <?php echo form_open('employer/exam_requests/overall_emails/save', ['id' => 'form-save-emails']); ?>
        <table id="tbl-notification-list-email" class="table" width="100%">
            <thead>
                <tr>
                    <th style="text-align:left;">Email</th>
                    <th width="50"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emails as $row): ?>
                    <tr>
                        <td style="text-align:left;">
                            <input name='emails[]' 
                                    type='hidden' 
                                    value="<?php echo $row->email; ?>" 
                                    class="form-control input-request-send-emails">
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
        <br />
        <div align="right">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    
    $( "#form-save-emails" ).submit(function(e){
                
        e.preventDefault();

        if ($( ".input-request-send-emails" ).length == 0) {
            toastr["error"]("¡Debe agregar al menos 1 correo!");
            return;
        }

        var url = $(this).prop('action');
        var data = $(this).serialize(); 

        btnSend = $(this).find('*[type="submit"]');
        btnSend.val("Enviando...").prop('disabled', true);

        $.post(url, data, function(response) {

            if (!response.success) {
                toastr["error"]("¡Error al enviar al guardar los datos!");
                return;
            }

            toastr["success"]("¡Emails guardados!");
        }, 'json')
        .fail(function(){
            toastr["error"]("¡Error al realizar la transacción!");
        })
        .always(function(){
            btnSend.val("Enviar").prop('disabled', false);
            btnCancel.prop('disabled', false);
        });

        return false;
    });

    $( "#btn-add-email" ).click(function(){

        var inputEmail = $.trim($( "#notification-email" ).val());

        emailRegex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

        if (inputEmail == '') {
            toastr["error"]("¡Debe ingresar un correo!");
            return;
        }

        if (!emailRegex.test(inputEmail)) {
            toastr["error"]("¡Debe ingresar un correo con formato válido!");
            return;
        }

        $( "#tbl-notification-list-email tbody" ).prepend(`
            <tr>
                <td style="text-align:left;">
                    <input name='emails[]' 
                           type='hidden' 
                           value="${inputEmail}" 
                           class="form-control input-request-send-emails">
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

        $( "#notification-email" ).val("");
    });
</script>