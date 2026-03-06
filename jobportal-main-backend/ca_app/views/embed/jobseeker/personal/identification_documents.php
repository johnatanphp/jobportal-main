<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />

<style type="text/css">
  .ui-autocomplete { 
    z-index:99999999; 
  }
  
  .ui-autocomplete-input {
    width: 100%;
  }

</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div>
<!--/Header-->
<div class="containe">
    <div class="row" style="padding:0;margin:0;">  
        <div class="col-md-12">

            <div><?php echo $this->session->flashdata('msg');?></div>

            <div class="msg-info">
                Por favor carga una copia del documento de identidad (DNI, Carnet de extranjería, PTP, otros).
                <div class="info-upload-doc">
                  <span class="info-upload-doc__info">Consideraciones al subir los documentos</span>
                  <ul class="info-upload-doc__list">
                    <li>
                      Buena resolución.
                    </li>
                    <li>
                      Los formatos permitidos son: jpg, jpeg y pdf.
                    </li>
                    <li>
                      La foto no puede superar los 4MB de tamaño.
                    </li>
                  </ul>
                </div>
            </div>
            <br />
            <div id="content-load-file" class="row">
                <div class="col-sm-6 col-md-offset-3">
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
                            -
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
    </div>  
</div>

<!--Footer-->

<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>
  
<script type="text/javascript">

    function showSelectorFiles(element) {
        $( "#file-document" ).remove();
        var fileDocument = $( '<input type="file" name="file" style="display: none;">');
        type = element.data('type');
        seekerId = "<?php echo $seeker->ID; ?>";

        obParams = {
            'type': type,
            'seeker_id': seekerId,
            "<?php echo embed_token_name(); ?>" : "<?php echo embed_token(); ?>"
        };

        params = $.param(obParams);

        $(fileDocument).fileupload({
        dataType: 'json',
        url: "<?php echo site_url('embed/jobseeker/personal/identification_document/upload?'); ?>" + params,
        autoUpload: true,
        add: function (e, data) {

            var row = element.closest('tr');
            $( "td:eq(1)", row).html('Espere...');

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
            $( "td:eq(1)", row).html(data.result.error);

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

    $( ".load-file" ).click(function() {
        showSelectorFiles($(this));
    });

</script>
</body>
</html>