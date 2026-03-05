<div class="col-md-12">  
  <div class="formwraper">
    <div class="formint">
      <div class="row">
        <div class="col-md-12" style="text-align:right;">
          <a href="<?php echo site_url('general/jobseeker/entry_forms/download/' . $entry_form->entry_form_id); ?>"
              target="_blank" 
              class="btn btn-sm btn-default">
            Descargar
          </a>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php $this->load->view('jobseeker/entry_form/ec/common/entry_form_view'); ?>
        </div>
      </div>
    </div>
  </div>
</div> 
    