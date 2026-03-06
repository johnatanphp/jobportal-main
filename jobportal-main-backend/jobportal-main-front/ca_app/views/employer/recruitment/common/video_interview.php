<div class="candidate-section-content">
    <h4 class="candidate-section-content-title">
      Video entrevista  
    </h4>
	</div> 
<div class="row">
  <div class="col-md-12">     
    <?php if ($video_interview && $video_interview->video_path): ?>
      <div>
        <div class="row">
          <div class="col-md-12">
            <div class="attach-file-item">
              <a href="<?php echo file_url($video_interview->video_path); ?>" target="_blank">
                  <i class="glyphicon glyphicon-play-circle"></i>
                  Video subido
              </a>
            </div>
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6">
                  <label>Calificación</label>
                  <br />
                  <?php 
                    $qualification_status = [
                      1 => 'Recomendable',
                      2 => 'No recomendable',
                      3 => 'Alternativo'
                    ];
                  ?>
                  <?php 
                    echo isset($qualification_status[$video_interview->qualification]) ? $qualification_status[$video_interview->qualification] : 'Sin calificación';
                  ?>
              </div>
              <div class="col-md-6">
                  <label>Comentarios</label>
                  <br />
                  <?php if ($video_interview->comment != null): ?>
                    <?php echo nl2br($video_interview->comment); ?>
                  <?php else: ?>
                    Sin comentarios
                  <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!$video_interview || !$video_interview->video_path): ?>
      <div align="center">Sin resultados</div>
    <?php endif; ?>
  </div>
</div>