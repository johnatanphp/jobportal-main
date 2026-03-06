<style type="text/css">
    .msg-info {
        padding: 8px 10px;
        border: 1px solid #07709b;
        border-left: 3px solid #07709b;
        font-size: 14px;
    }

    .document-uploaded {
        text-align:center;
        display:block; 
        background: #eee;
        border: 1px solid #e0e0e0;
        padding: 2px 15px;
    }
</style>
 
<?php $this->load->view('common/after_body_open'); ?>

<div class="formint">              
    <div class="msg-info">
        Por favor carga una copia del documento de identidad (DNI, Carnet de extranjería, PTP, otros).
        <div class="info-upload-doc">
            <span class="info-upload-doc__info">Consideraciones al subir los documentos</span>
            <ul class="info-upload-doc__list">
                <li>
                Buena resolución.
                </li>
                <li>
                Los formatos permitidos son: png, jpg, jpeg y pdf.
                </li>
                <li>
                La foto no puede superar los 4MB de tamaño.
                </li>
            </ul>
        </div>
    </div>
        
    <?php if ($seeker->document_type != '1'): ?>
        <div>
            <b>Nota para extranjeros:</b> 
            Si solo posees Pasaporte o CPP debes cumplir los siguientes requisitos, de lo contrario hacer caso omiso de esta nota.
            <div>Requisitos:</div>
            <ul class="info-upload-doc__list">
            <li>
                Si solo adjuntas pasaporte, deberás adjuntar también tu permiso especial para firma de contratos.
            </li>
            <li>
                Si solo adjuntas CPP, deberás adjuntar también tu pasaporte (Solo para ciudadanos Venezolanos).
            </li>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-sm-8 col-md-offset-2">
            <div id="content-load-file">
                <table class="table table-striped">
                <?php foreach ($documents as $doc): ?>
                    <tr>
                    <td><?php echo $doc->name; ?></td>
                    <td align="center">

                        <?php if (empty($doc->path) != true): ?>
                            <a class="document-uploaded" 
                            href="<?php echo file_url($doc->path); ?>"
                            target="_blank">
                            <i class="glyphicon glyphicon-file"></i>
                            Ver
                            </a>
                        <?php else: ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-xs load-file" data-type="<?php echo $doc->doc_id; ?>">
                        <?php echo empty($doc->path) != true ? 'Cambiar' : 'Subir'; ?>
                        </button>
                    </td>
                    </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

    <?php if ($seeker->document_type != '1'): ?>
        <br />
        <div id="content-load-file-sign">
            <b>Permiso especial para firma de contratos</b>         
            <table class="table table-striped" style="margin-top: 10px;">
            <tr>
                <td>
                Permiso
                </td>
                <td align="center">

                    <?php if (empty($sign_contract->file_path) != true): ?>
                        <a class="document-uploaded" 
                        href="<?php echo file_url($sign_contract->file_path); ?>"
                        target="_blank">
                        <i class="glyphicon glyphicon-file"></i>
                        Ver
                        </a>
                    <?php else: ?>
                    <?php endif; ?>
                </td>
                <td>
                    <button class="btn btn-primary btn-xs load-file-sign">
                    <?php echo empty($sign_contract->file_path) != true ? 'Cambiar' : 'Subir'; ?>
                    </button>
                </td>
            </tr>
            </table>
        </div>
    <?php endif; ?>

</div>
<script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/compressor/compressor.min.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">

    function showSelectorFiles(element) {
        var mimeTypes = 'image/jpeg,image/jpg,application/pdf,image/png';
        var fileDocument = $( `<input type="file" name="file" style="display: none;" accept="${mimeTypes}">`);
        type = element.data('type');

        $(fileDocument).fileupload({
            dataType: 'json',
            url: "<?php echo site_url('employer/recruitment_requested_docs/send_identification_document/upload'); ?>",
            formData: {
                'seeker_id': "<?php echo $seeker->ID; ?>",
                'doc_type_id': type
            },
            autoUpload: true,
            add: function (e, data) {

                var row = element.closest('tr');
                $( "td:eq(1)", row).html('Espere...');

                file = data.files[0];

                //Si el archivo es una imagen se comprime
                if (file.type == 'image/png' || 
                    file.type == 'image/jpg' || 
                    file.type == 'image/jpeg') {
                    new Compressor(file, {
                        quality: 0.7,

                        // The compression process is asynchronous,
                        // which means you have to access the `result` in the `success` hook function.
                        success(result) {
                            fileCompress = new File([result], file.name, {lastModified: file.lastModified, type: file.type});
                            data.files[0] = fileCompress;
                            data.submit();
                        },
                        error(err) {
                            console.log(err.message);
                        },
                    });
                    return;
                }

                data.submit();
            },
            progress: function(e, data) {
                var row = element.closest('tr');

                var progress = parseInt(data.loaded / data.total * 100, 10);
                $( "td:eq(1)", row).html("Cargando (" + progress + "%)" );
            }, 
            done: function (e, data) {

                var row = element.closest('tr');
                var success = data.result.success;

                if (!success) {        
                    $( ".load-file", row).text('Intentar de nuevo').show();
                    $( "td:eq(1)", row).html(data.result.message);
                    return;
                }

                $( "td:eq(1)", row).html(`
                <a class="document-uploaded" 
                    href="${data.result.url_file}"
                    target="_blank">
                    <i class="glyphicon glyphicon-file"></i>
                    Ver
                </a>`
                );

                $( ".load-file", row).text('Cambiar').show();
            }
        });

        $( "#content-load-file" ).append(fileDocument);
        fileDocument[0].click();
    }

    function showSelectorFilesSign(element) {
        var fileDocument = $( '<input type="file" name="file" style="display: none;">');

        $(fileDocument).fileupload({
        dataType: 'json',
        url: "<?php echo site_url('employer/recruitment_requested_docs/send_permission_signs_contracts/upload'); ?>",
        formData: {
            'seeker_id': "<?php echo $seeker->ID; ?>"
        },
        autoUpload: true,
        add: function (e, data) {

            var row = element.closest('tr');
            $( "td:eq(1)", row).html('Espere...');

            file = data.files[0];

            //Si el archivo es una imagen se comprime
            if (file.type == 'image/png' || 
                file.type == 'image/jpg' || 
                file.type == 'image/jpeg') {
                new Compressor(file, {
                    quality: 0.7,

                    // The compression process is asynchronous,
                    // which means you have to access the `result` in the `success` hook function.
                    success(result) {
                        fileCompress = new File([result], file.name, {lastModified: file.lastModified, type: file.type});
                        data.files[0] = fileCompress;
                        data.submit();
                    },
                    error(err) {
                        console.log(err.message);
                    },
                });
                return;
            }

            data.submit();
        },
        progress: function(e, data) {
            var row = element.closest('tr');

            var progress = parseInt(data.loaded / data.total * 100, 10);
            $( "td:eq(1)", row).html("Cargando (" + progress + "%)" );
        }, 
        done: function (e, data) {

            var row = element.closest('tr');

            var success = data.result.success;

            if (!success) {        
                $( ".load-file-sign", row).text('Intentar de nuevo').show();
                $( "td:eq(1)", row).html(data.result.message);

                return;
            }

            $( "td:eq(1)", row).html(`
                <a class="document-uploaded" 
                    href="${data.result.url_file}"
                    target="_blank">
                    <i class="glyphicon glyphicon-file"></i>
                    Ver
                </a>`
            );

            $( ".load-file-sign", row).text('Cambiar').show();
        }
        });

        $( "#content-load-file-sign" ).append(fileDocument);
        fileDocument[0].click();
    }

    $( ".load-file" ).click(function() {
        showSelectorFiles($(this));
    });

    $( ".load-file-sign" ).click(function() {
        showSelectorFilesSign($(this));
    });

</script>