<div class="container" style="width:100%;">
    <div class="panel-group" id="accordion">
        <?php foreach ($stages as $stage): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $stage->id; ?>">
                            <?php e($stage->name); ?>
                        </a>
                    </h4>
                </div>

                <div id="collapse-<?php echo $stage->id; ?>" class="panel-collapse collapse <?php echo $curent_stage_id == $stage->id ? 'in' : ''; ?>">
                    <div class="panel-body">
                        <?php echo form_open('employer/recruitment/process_document_stages/save', []); ?>
                            <input type="hidden" name="job_id" value="<?php echo $job_id; ?>"> 
                            <input type="hidden" name="stage_id" value="<?php echo $stage->id; ?>">
                            <ul style="list-style: none;">
                                <?php  $documents = $this->Recruitment_process_document_stage->get_documents($job_id, $stage->id); ?>
                                <?php foreach ($documents as $row_doc): ?>
                                    <li style="padding:7px 9px;">
                                        <input type="checkbox" 
                                            class="rys-document-config-check"
                                            name="document_ids[]"
                                            <?php echo $row_doc->document_id == $row_doc->id ? 'checked': ''; ?> 
                                            value="<?php echo $row_doc->id; ?>">
                                        <?php e($row_doc->name); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
</div>
