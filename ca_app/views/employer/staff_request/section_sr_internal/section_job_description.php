<div id="section-job-description">
  <div class="step-title">
    Descripción del puesto
  </div>
  <div class="step-inputs">
    
    <div class="input-group <?php echo (form_error('job_title'))?'has-error':'';?>">
      <label class="input-group-addon">Nombre del puesto <span></span></label>
      <label id="job_title"></label>
      <?php echo form_error('job_title'); ?>
    </div>

    <div class="input-group <?php echo (form_error('area'))?'has-error':'';?>">
      <label class="input-group-addon">Área perteneciente </label>
      <label id="belonging_area"></label>
      <?php echo form_error('industry'); ?>
    </div>

    <div class="input-group <?php echo (form_error('user_management'))?'has-error':'';?>">
      <label class="input-group-addon">Gerencia usuaria <span>*</span></label>
      <input name="user_management" type="text" class="form-control" id="user_management" value="<?php echo set_value('vacancies'); ?>">
      <?php echo form_error('user_management'); ?>
    </div>
    <div class="input-group <?php echo (form_error('applicant_headquarter'))?'has-error':'';?>">
      <label class="input-group-addon">Jefatura del solicitante <span>*</span></label>
       <input name="applicant_headquarter" type="text" class="form-control" id="applicant_headquarter" value="<?php echo set_value('applicant_headquarter'); ?>">
      <?php echo form_error('applicant_headquarter'); ?>
    </div>

    <?php echo form_error('vacancies', '<div class="info-error"><i class="glyphicon glyphicon-remove-circle" ></i>&nbsp;&nbsp;', '</div>'); ?>
    <div class="input-group <?php echo (form_error('vacancies'))?'has-error':'';?>">
      <label class="input-group-addon">Número de vacantes <span>*</span></label>
      <input name="vacancies" type="text" class="form-control" id="vacancies" value="<?php echo set_value('vacancies'); ?>" style="width: 40%;">
    </div>

    <div class="input-group <?php echo (form_error('name_immediate_boss'))?'has-error':'';?>">
      <label class="input-group-addon">Nombre del jefe inmediato <span>*</span></label>
      <input name="name_immediate_boss" type="text" class="form-control" id="name_immediate_boss" value="<?php echo set_value('name_immediate_boss'); ?>">
    </div>
  </div>
</div>