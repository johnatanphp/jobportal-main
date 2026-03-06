<div id="section-additional-comments">
  <div class="step-title">
    Comentarios adicionales del cliente
  </div>
  <div class="step-inputs">
    <div class="input-group <?php echo (form_error('additional_comments'))?'has-error':'';?>">
      <textarea name="additional_comments" class="form-control" rows="6"><?php echo set_value('additional_comments') ? set_value('additional_comments') : @$request->additional_comments; ?></textarea>
      <?php echo form_error('additional_comments'); ?>
    </div>
  </div>
</div>