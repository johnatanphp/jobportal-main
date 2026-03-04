<div>
  <div class="row">
    <?php foreach ($result_candidates as $candidate): ?>
        <div class="col-md-12">          
          <div style="border: 1px solid #ccc;border-radius: 10px;padding:10px 12px;margin: 5px 0;background: #ffffff;">
            <table width="100%">
              <tr>
                <td width="80">
                  <img src="<?php echo img_pic_candidate($candidate->photo); ?>" alt="<?php echo $candidate->first_name;?>" style="max-width:60px;max-height:70px;border-radius: 100%;" />
                </td>
                <td algn="left" style="vertical-align: top;">
                  <div>
                    <div>
                      <label>
                        <a href="#" 
                          class="show-rs-candidate" 
                          data-candidate-id="<?php echo $candidate->ID; ?>" 
                          data-process-id="<?php echo $process->id; ?>"
                          data-job-id="<?php echo $process->job_ID; ?>">
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
        </div>
    <?php endforeach; ?>
  </div>
  <div style="text-align: center;">
    <?php if (count($result_candidates) == 0): ?>
      Sin resultados
    <?php endif; ?>
  </div>
</div>