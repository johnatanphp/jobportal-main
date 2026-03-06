<div>
  <div class="row">
    <?php foreach ($result_candidates as $candidate): ?>
        <div class="col-md-6">          
          <table width="100%">
            <tr>
              <td>
                <?php
                  $candidate_logo = $candidate->photo ? $candidate->photo : 'no_pic.jpg';
                  if (!file_exists(realpath(APPPATH . '../public/uploads/candidate/thumb/' . $candidate_logo))) {
                      $candidate_logo = 'no_pic.jpg';
                  }
                ?>
                <img src="<?php echo site_url('public/uploads/candidate/thumb/' . $candidate_logo);?>" alt="<?php echo $candidate->first_name;?>" style="max-width:90px;max-height:80px;" />
              </td>
              <td>
                <div>
                  <div>
                    <label>
                      <a href="#" class="show-rs-candidate" data-candidate-id="<?php echo $candidate->ID; ?>" data-job-id="<?php echo $job->ID; ?>">
                        <?php echo ellipsize(strip_tags(trim($candidate->first_name . ' ' . $candidate->last_name)), 25); ?>
                      </a>    
                    </label>
                  </div>
                  <div style="font-size: 12px;color:#666;">
                    <?php echo $candidate->email; ?>
                  </div>
                  <div>
                    <?php if ($candidate->discarded): ?>
                      <span>Descartado</span>
                    <?php endif; ?>
                  </div>
                </div>
              </td>
            </tr>
          </table>
        </div>
    <?php endforeach; ?>
  </div>
  <div style="text-align: center;">
    <?php if (count($result_candidates) == 0): ?>
      Sin resultados
    <?php endif; ?>
  </div>
</div>