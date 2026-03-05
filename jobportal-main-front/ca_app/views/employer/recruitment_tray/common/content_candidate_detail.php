<style>
 .btn-show-options {
    background: #fff;
    border: 1px solid #ccc;
    padding: 4px 5px;
  }
</style>
<div class="formint">
  <div style="border: 1px solid #ccc;margin-bottom: 10px;">
      <div style="border-bottom: 1px solid #ccc;padding: 10px;">
          <div class="row">
              <div class="col-xs-2">
                  <div style="text-align: center;">
                      <a href="#" 
                      class="js-btn-load-content" 
                      data-url="<?php echo site_url('candidate/cv_data/' . $candidate->ID); ?>">
                          
                          <img width="100" src="<?php echo img_pic_candidate($candidate->photo); ?>" />
                      </a>
                  </div>
              </div>
              <div class="col-xs-10">
                  <div>
                      <h4 style="font-weight: bold;padding: 5px 0;">
                          <a href="#"
                              id="seeker-full-name"
                              style="color: #333;text-decoration: underline;" 
                              class="js-btn-load-content" 
                              data-url="<?php echo site_url('candidate/cv_data/' . $candidate->ID); ?>">
                                  <?php echo mb_strtoupper($candidate->first_name . ' ' . $candidate->last_name); ?>
                          </a>
                      </h4>
                  </div>
                  <div>
                      <ul class="list-options">
                          <li class="list-options__item">
                              <?php echo '<b>' . document_type_text($candidate->document_type) . ':</b> ' . $candidate->document_number; ?>
                          </li>
                          <li class="list-options__item">
                              <b>Email:</b> <?php e($candidate->email); ?>      
                          </li>
                          
                          <?php if ($candidate->employee_code): ?>
                              <li class="list-options__item">
                                  <b>Trabajador Código:</b> 
                                  <?php echo $candidate->employee_code; ?> 
                              </li>
                          <?php endif; ?>
                      </ul>
                  </div>
              </div>
          </div>
      </div>
      <div style="padding: 8px 5px; text-align: right; background: #eee;">
        <div class="dropdown dropdown-options" style="text-align: right;display: inline-block;">
            <button class="btn-xs btn-show-options dropdown-toggle" type="button" data-toggle="dropdown">
                Documentos del reclutamiento
            </button>
            <ul class="dropdown-menu dropdown-menu-right">

                <?php foreach ($rys_documents as $row_doc): ?>
                    <?php if ($row_doc->option_type_id == 1): ?>
                        <li>
                            <a class="menu-rs-option-more-detail js-btn-load-recruitment-documents"
                                data-process-id="<?php echo $tray_candidate->process_id; ?>"
                                data-candidate-id="<?php echo $candidate->ID; ?>"
                                data-document="<?php echo $row_doc->key; ?>">

                                <?php e($row_doc->name); ?>
                                <?php if (isset($rs_document_counter[$row_doc->key]) && $rs_document_counter[$row_doc->key] > 0): ?>
                                    <i class="glyphicon glyphicon-ok document-ok"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($row_doc->option_type_id == 2 && $row_doc->id == 3): ?>
                        <li>
                            <a class="menu-rs-option-more-detail js-btn-load-content"
                                data-url="<?php echo site_url('candidate/load_document_screening/' . $job->ID . '/' . $candidate->ID); ?>">
                                <?php e($row_doc->name); ?>
                                <?php if (isset($rs_document_counter[$row_doc->key]) && $rs_document_counter[$row_doc->key] > 0): ?>
                                    <i class="glyphicon glyphicon-ok document-ok"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($row_doc->option_type_id == 2 && $row_doc->id == 13): ?>
                        <li>
                            <a class="menu-rs-option-more-detail js-btn-load-recruitment-documents"
                                data-process-id="<?php echo $tray_candidate->process_id; ?>"
                                data-candidate-id="<?php echo $candidate->ID; ?>"
                                data-document="other_documents">
                                <?php e($row_doc->name); ?>
                                <?php if (isset($rs_document_counter[$row_doc->key]) && $rs_document_counter[$row_doc->key] > 0): ?>
                                    <i class="glyphicon glyphicon-ok document-ok"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($row_doc->option_type_id == 2 && in_array($row_doc->id, [1, 10])): ?>
                        <li>
                            <a class="menu-rs-option-more-detail btn-load-content-exam-requests"
                                data-process-id="<?php echo $tray_candidate->process_id; ?>"
                                data-candidate-id="<?php echo $candidate->ID; ?>"
                                data-document="<?php echo $row_doc->key; ?>">
                                <?php e($row_doc->name); ?>
                                <?php if (isset($rs_document_counter[$row_doc->key]) && $rs_document_counter[$row_doc->key] > 0): ?>
                                    <i class="glyphicon glyphicon-ok document-ok"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                <?php endforeach; ?>
            </ul>
        </div>
          <button class="btn-xs btn-show-options js-btn-load-content" 
                  type="button" 
                  id="btn-list-rd-seeker-documents" 
                  data-content-id="list-rd-seeker-documents"
                  data-url="<?php echo site_url('candidate/load_detail_requested_documents/' . $process->id . '/' . $candidate->ID); ?>">
              Documentos de contratación
          </button>

          <?php if ($job->company_ID == 1): ?>
              <div class="dropdowncontent_candidate_detail dropdown-options-more" style="display: inline-block;">
                  <button class="dropdown-toggle" type="button" data-toggle="dropdown">
                      <span class="glyphicon glyphicon-option-vertical"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right">
                      <li>
                          <a href="#" 
                          class="js-btn-load-content"
                          data-url="<?php echo site_url('candidate/search_experience_overall/' . $candidate->ID); ?>">
                              Experiencia en overall
                          </a>
                      </li>
                  </ul>
              </div>
          <?php endif; ?>
      </div>
  </div>
  <div class="content-main-detail">
  </div>
</div>

<script type="text/javascript">
  $(function(){
    $( "#seeker-full-name" ).click();
  });
</script>