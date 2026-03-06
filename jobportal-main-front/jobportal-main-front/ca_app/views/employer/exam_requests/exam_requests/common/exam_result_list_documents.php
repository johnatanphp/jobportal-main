<table class="table table-striped">
   <tr>
        <th>Examen</th>
        <th>Tipo</th>
        <th>Fecha examen</th>
        <th>Archivo</th>
    </tr>
    <?php foreach ($documents as $doc): ?>
        <tr>
            <td><?php echo $doc->exam_name; ?></td>
            <td>
                <?php echo $doc->exam_document_name; ?>
                <?php if ($doc->exam_type_id == 2): ?>
                    <?php 
                        $covid19_options = get_options_exam_type_covid();
                    ?>
                    <?php echo isset($covid19_options[$doc->type]) ? ' - ' . $covid19_options[$doc->type] : ''; ?>
                <?php endif; ?>
            </td>
            <td>
                <?php echo date('d/m/Y', strtotime($doc->exam_date)); ?>
            </td>
            <td>
                <?php if ($doc->file_path != null): ?>
                    <a href="<?php echo file_url($doc->file_path); ?>" target="_blank">
                        <i class="glyphicon glyphicon-list-alt"></i>
                        Ver documento
                    </a>
                    <?php if ($doc->loaded_by == $this->session->userdata('user_id')): ?>
                        <a href="#" 
                           style="color: red;"
                           class="delete-result" 
                           data-result-id="<?php echo $doc->exam_result_id; ?>">
                            <i class="glyphicon glyphicon-remove"></i>
                        </a>            
                    <?php endif; ?> 
                    <br />
                    <span>
                        <?php echo 'R: ' . 
                            $this->Exam_request_result_type->find(
                                $doc->result_status
                            )
                            ->result_name; 
                        ?>        
                    </span> 
                <?php else: ?>
                    <button class="btn btn-primary btn-xs open-modal-upload-result" 
                          data-result-id="<?php echo $doc->exam_result_id; ?>">
                        Cargar
                    </button>  
                <?php endif;?>
            </td>
        </tr>
    <?php endforeach; ?>

    <?php if (empty($documents)): ?>
        <tr>
           <td colspan="5" align="center">
              Sin resultados
           </td> 
        </tr>
    <?php endif; ?>
</table>