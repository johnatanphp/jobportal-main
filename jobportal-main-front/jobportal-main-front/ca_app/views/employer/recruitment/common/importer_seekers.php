<div class="formwraper">
    <?php echo form_open_multipart('employer/recruitment/jobseeker_importer/import', ['id' => 'form-rys-jobseekers-import']); ?>
        <input type="hidden" name="process_id" value="<?php echo $rs_process->id; ?>">
        <div>
            <a href="#" data-toggle="modal" data-target="#modal-import-template" class="pull-right" style="text-decoration: underline;">
                <i class="fa fa-file"></i>
                Descargar Plantillas
            </a>
        </div>
        <div class="input-group">
            <label class="input-group-addon">Empleo</label>
            <span><?php echo $job->job_title; ?></span>
        </div>
        <div class="input-group">
            <label class="input-group-addon">Plantilla <span>*</span></label>
            <select name="template" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="computrabajo">Computrabajo</option>
                <option value="otros">Otros</option>
            </select>
        </div>
        <div class="input-group">
            <label class="input-group-addon">Archivo a importar <span>*</span></label>
            <input type="file" name="file_import" required>
        </div>

        <div class="input-group" style="margin-top: 20px;">
            <label class="input-group-addon" style="vertical-align: top;line-height: 1.3;">¿Notificar al postulate?<span></span></label>
            <div>
                <div class="row">
                    <div class="col-xs-8">
                        <label style="font-weight: normal;font-size: 13px;">Por Correo</label>
                    </div>
                    <div class="col-xs-4">
                        <div class="checkbox-wrapper-2" style="float: right;">
                            <input class="tgl tgl-light" id="notify-import-candidate-by-mail" type="checkbox" name="notify_candidate_by_mail" value="1" />
                            <label class="tgl-btn" for="notify-import-candidate-by-mail" style="width: 35px;height: 19px;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <label style="font-weight: normal;font-size: 13px;">Por WhatsApp</label>
                    </div>
                    <div class="col-xs-4">
                        <div class="checkbox-wrapper-2" style="float: right;">
                            <input class="tgl tgl-light" id="notify-import-candidate-by-whatsapp" type="checkbox" name="notify_candidate_by_whatsapp" value="1" />
                            <label class="tgl-btn" for="notify-import-candidate-by-whatsapp" style="width: 35px;height: 19px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="border-top: 1px solid #ddd;padding: 0 10px;">
            <div align="right">
                <br/>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Importar</button>
            </div>
        </div>
    <?php echo form_close(); ?>
</div>

<script>
    $(function(){
        $( '#form-rys-jobseekers-import' ).submit(function(e){
            e.preventDefault();

            form = this;

            var formData = new FormData(this);
            formData.append('add_rys_stage', $( "#current_stage" ).val());

            var url = $(this).prop('action');

            $.ajax({
                url: url,
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            })
            .done(function(res){
                if (!res.status) {
                    toastr["error"](res.message);
                    return;
                }

                form.reset();
                reloadDataCandidates();

                toastr["success"](res.message);
            });

            return false;
        });
    });
</script>