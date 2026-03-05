<ul style="list-style: none;">
    <?php foreach ($documents as $row_doc): ?>
        <li style="padding:7px 9px;">
            <input type="checkbox" 
                class="recruitment-contract-document-config-check"
                <?php echo $row_doc->document_id == $row_doc->id ? 'checked': ''; ?> 
                value="<?php echo $row_doc->id; ?>"
                data-job-id="<?php echo $job_id; ?>">
            <?php e($row_doc->name); ?>
        </li>
    <?php endforeach; ?>
</ul>