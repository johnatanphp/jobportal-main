<div id="section-mof">
    <div class="step-title">
      Seleccione Área y Plantilla
    </div>
    <div class="step-inputs">
      <div class="input-group <?php echo (form_error('internal_area'))?'has-error':'';?>">
        <label class="input-group-addon">Área perteneciente <span>*</span></label>
        <select name="internal_area" class="form-control" id="internal_area" style="width: 90%;">
          <option value="">Seleccione</option>
          <?php foreach ($internal_areas as $row_area): ?>
            <option value="<?php echo $row_area->ID; ?>"><?php echo $row_area->area_name; ?></option>
          <?php endforeach; ?>                  
        </select>
        <?php echo form_error('internal_area'); ?>
      </div>

      <div class="input-group">
        <label class="input-group-addon">Plantilla <span>*</span></label>
        <select name="request_template" type="text" class="form-control" id="request_template" style="width: 90%;">
          <option value="">Seleccione</option>
          
          <?php if ($this->config->item('mof_module_enabled')): ?>
            <option value="1">MOF</option>
          <?php endif; ?>

          <?php if ($this->config->item('job_layouts_module_enabled')): ?>
            <option value="2">Layout de puesto</option>
          <?php endif; ?>
        </select>
      </div>

      <div class="input-group" style="display:none;">
        <label class="input-group-addon">MOF <span>*</span></label>
        <select name="select_mof" type="text" class="form-control" id="select_mof" style="width: 90%;">
          <option value="">Seleccione</option>
        </select>
      </div>

      <div class="input-group" style="display:none;">
        <label class="input-group-addon">Layout de puesto <span>*</span></label>
        <select name="job_layout" type="text" class="form-control" id="job_layout" style="width: 90%;">
          <option value="">Seleccione</option>
        </select>
      </div>
    </div>
</div>