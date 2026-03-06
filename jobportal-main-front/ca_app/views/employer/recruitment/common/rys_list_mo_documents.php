<div style="padding: 10px 0;">
    <div class="candidate-section-content">
        <h4 class="candidate-section-content-title">
            Documentos SSO
        </h4>
    </div> 
    <div>
        <table class="table table-striped">
            <tr>
                <th></th>
                <th>Certificado</th>
                <th colspan="5">Resultado</th>
            </tr>
            <?php foreach ($documents as $doc): ?>
                <tr>
                    <td>
                        <b><?php echo $doc->doc_name; ?></b>
                    </td>

                    <td>
                        <?php if ($doc->certificate_path != null): ?>
                            <a href="<?php echo file_url($doc->certificate_path); ?>" 
                               target="_blank">
                                <i class="glyphicon glyphicon-list-alt"></i>
                                Certificado
                            </a>
                            <br />
                            <span>
                                <?php echo 'R: ' . 
                                    $this->Exam_request_result_type->find(
                                        $doc->certificate_result_status
                                    )
                                    ->result_name; 
                                ?>        
                            </span> 
                        <?php else: ?>
                            Sin cargar
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($doc->doc_path != null): ?>
                            <span>
                                <?php echo 'R: ' . 
                                    $this->Exam_request_result_type->find(
                                        $doc->result_status
                                    )
                                    ->result_name; 
                                ?>        
                            </span> 
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($documents)): ?>
                <tr>
                   <td colspan="3">
                       Ningún documento cargado
                   </td> 
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>