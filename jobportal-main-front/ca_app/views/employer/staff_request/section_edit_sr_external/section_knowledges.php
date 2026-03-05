<div id="section-knowledges">
  <div class="step-title">
    Conocimientos
  </div>
  <div class="step-inputs">
    <div class="input-group <?php echo (form_error('general_knowledges'))?'has-error':'';?>">
      <label class="input-group-addon">Conocimientos generales <span></span></label>
      <textarea id="general_knowledges" name="general_knowledges" class="form-control" rows="6"><?php echo set_value('general_knowledges') ? set_value('general_knowledges') : @$request->general_knowledges; ?></textarea>
      <?php echo form_error('general_knowledges'); ?>
    </div>

    <div class="input-group <?php echo (form_error('specific_knowledges'))?'has-error':'';?>">
      <label class="input-group-addon">Conocimientos específicos <span>*</span></label>
      <textarea id="specific_knowledges" name="specific_knowledges" class="form-control" rows="6"><?php echo set_value('specific_knowledges') ? set_value('specific_knowledges') : @$request->specific_knowledges; ?></textarea>
      <?php echo form_error('specific_knowledges'); ?>
    </div>
  </div>
</div>