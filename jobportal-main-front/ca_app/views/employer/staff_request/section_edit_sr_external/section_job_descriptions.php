<div id="section-job-description">
  <div class="step-title">
    Descripción del puesto
  </div>
  <div class="step-inputs">
    
    <div class="input-group <?php echo (form_error('job_title'))?'has-error':'';?>">
      <label class="input-group-addon">Nombre del puesto <span></span></label>
      <label id="job-title"><?php echo @$request->job_title; ?></label>
    </div>

    <?php echo form_error('vacancies', '<div class="info-error"><i class="glyphicon glyphicon-remove-circle" ></i>&nbsp;&nbsp;', '</div>'); ?>
    <div class="input-group <?php echo (form_error('vacancies'))?'has-error':'';?>">
      <label class="input-group-addon">Número de vacantes <span>*</span></label>
      <input name="vacancies" type="text" class="form-control" id="vacancies" value="<?php echo set_value('vacancies') ? set_value('vacancies') : @$request->vacancies; ?>" style="width: 40%;">
    </div>
 
    <div class="input-group <?php echo (form_error('occupational_group'))?'has-error':'';?>">
      <label class="input-group-addon">Grupo ocupacional <span>*</span></label>
      <select id="occupational_group" name="occupational_group" class="form-control">
        <option value="">Seleccione</option>
        <?php foreach ($job_charges as $charge): ?>
          <?php $occupational_group = set_value('occupational_group') ? set_value('occupational_group') : @$request->charge_ID; ?>
          
          <option value="<?php echo $charge->ID;?>" <?php echo $occupational_group == $charge->ID ? 'selected="selected"' : ''; ?>><?php echo $charge->charge_name;?></option>
        <?php endforeach; ?>
      </select>  
      <?php echo form_error('occupational_group'); ?>
    </div>
    <div class="input-group <?php echo (form_error('area'))?'has-error':'';?>">
      <label class="input-group-addon">Área / Departamento <span>*</span></label>
      
      <select id="industry" name="industry" class="form-control">
        <option value="">Seleccione</option>
        <?php foreach ($job_industries as $row_industry): ?>
        <?php  $industry_id = set_value('industry') ? set_value('industry') : @$request->industry_ID; ?>
        <option value="<?php echo $row_industry->ID; ?>" <?php echo $industry_id == $row_industry->ID ? 'selected="selected"' : ''; ?>><?php echo $row_industry->industry_name; ?></option>
        <?php endforeach; ?>
      </select>
      <?php echo form_error('industry'); ?>
    </div>                   
    <div class="input-group <?php echo (form_error('n_people_reporting'))?'has-error':'';?>">
      <label class="input-group-addon">N° de personas que reportan <span></span></label>
      <input name="n_people_reporting" type="text" class="form-control" id="n_people_reporting" value="<?php echo set_value('n_people_reporting') ? set_value('n_people_reporting') : @$request->n_people_reporting; ?>" style="width: 40%;">
      <?php echo form_error('n_people_reporting'); ?>
    </div>
    <div class="input-group <?php echo (form_error('name_immediate_boss'))?'has-error':'';?>">
      <label class="input-group-addon">Nombre del jefe inmediato <span>*</span></label>
      <input name="name_immediate_boss" type="text" class="form-control" id="name_immediate_boss" value="<?php echo set_value('name_immediate_boss') ? set_value('name_immediate_boss') : @$request->name_immediate_boss; ?>">
      <?php echo form_error('name_immediate_boss'); ?>
    </div>
    <div class="input-group <?php echo (form_error('charge_immediate_boss'))?'has-error':'';?>">
      <label class="input-group-addon">Cargo del jefe inmedito<span></span></label>
      <input name="charge_immediate_boss" type="text" class="form-control" id="charge_immediate_boss" value="<?php echo set_value('charge_immediate_boss') ? set_value('charge_immediate_boss') : @$request->charge_immediate_boss; ?>">
      <?php echo form_error('charge_immediate_boss'); ?>
    </div>
  </div>
</div>
