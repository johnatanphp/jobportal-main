<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                Certificado <?php echo $document->name; ?>
            </h4>
        </div>
        <div class="modal-body">
            <?php if ($exam_request_cert && $exam_request_cert->certificate_path): ?>
                <div class="attach-file-item"> 
                    <a href="<?php echo file_url($exam_request_cert->certificate_path); ?>"
                       target="_blank">
                        Ver certificado
                    </a>
                </div>
            <?php else: ?>
                ¡Certificado no subido!
            <?php endif; ?>
        </div>
    </div>
</div>
